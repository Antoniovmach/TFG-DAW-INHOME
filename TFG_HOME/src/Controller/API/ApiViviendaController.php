<?php

namespace App\Controller\API;

use App\Entity\Localidad;
use App\Entity\Provincia;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vivienda;
use App\Repository\DisponibilidadViviendaRepository;
use App\Repository\ReservaRepository;
use App\Repository\ViviendaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiViviendaController extends AbstractController
{

    #[Route("/vivienda/crear", name: "postVivienda", methods: ["POST"])]
    public function createVivienda(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Obtener los datos de la solicitud
        $data = json_decode($request->getContent(), true);
    
        // Extraer los datos de la solicitud
        $titulo = $data['titulo'] ?? null;
        $descripcion = $data['descripcion'] ?? null;
        $npersonas = $data['npersonas'] ?? null;
        $punto_inicio = $data['punto_inicio'] ?? null;
        $localidad_id = $data['localidad_id'] ?? null;
        $Usuario_id = $data['Usuario_id'] ?? null;
    
        // Verificar si se recibieron todos los campos necesarios
        if (!isset($titulo, $descripcion, $npersonas, $punto_inicio, $localidad_id)) {
            return $this->json(['message' => 'Faltan campos obligatorios'], 400);
        }
    
   
        $localidad = $em->getRepository(Localidad::class)->find($localidad_id);
    
        // Verificar si se encontró la localidad
        if (!$localidad) {
            return $this->json(['message' => 'Localidad no encontrada'], 404);
        }
    
        // Crear una nueva instancia de Vivienda y asignar los datos
        $vivienda = new Vivienda();
        $vivienda->setTitulo($titulo);
        $vivienda->setDescripcion($descripcion);
        $vivienda->setNpersonas($npersonas);
        $vivienda->setLocalidad($localidad);
    
        if ($Usuario_id) {
            // Cargar la instancia de Usuario correspondiente al ID proporcionado
            $Usuario = $em->getRepository(Usuario::class)->find($Usuario_id);
            // Verificar si se encontró el Usuario
            if (!$Usuario) {
                return $this->json(['message' => 'Usuario no encontrado'], 404);
            }
            $vivienda->setUsuario($Usuario);
        }
        $vivienda->setLocalizacion($punto_inicio);
    
        // Persistir la vivienda en la base de datos
        $em->persist($vivienda);
        $em->flush();
    
        // Obtener el ID de la vivienda creada
        $viviendaId = $vivienda->getId();
    
        // Devolver una respuesta con el ID de la vivienda creada
        return $this->json(['message' => $viviendaId], 201);
    }

    #[Route('/api/viviendasporusuario', name: 'api_viviendas_usuario', methods: ['POST'])]
    public function getViviendasusuario(
        Request $request,
        ViviendaRepository $viviendaRepository,
        DisponibilidadViviendaRepository $disponibilidadRepository,
        ReservaRepository $reservaRepository
    ): JsonResponse {
        // Obtener el contenido de la solicitud y decodificarlo
        $data = json_decode($request->getContent(), true);
        $usuarioId = $data['usuarioId'] ?? null;
        $fechas = $data['fechas'] ?? [];
    
        if (!$usuarioId) {
            return new JsonResponse(['error' => 'No se ha proporcionado el ID del usuario'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        if (empty($fechas)) {
            return new JsonResponse(['error' => 'No se han proporcionado fechas'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        // Obtener las viviendas para el usuario específico
        $viviendas = $viviendaRepository->findBy(['Usuario' => $usuarioId]);
    
        if (!$viviendas) {
            return new JsonResponse(['error' => 'No se han encontrado viviendas para el usuario proporcionado'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $responseData = [];
    
        foreach ($viviendas as $vivienda) {
            $disponibilidadesIds = [];
            $todasFechasDisponibles = true;
    
            foreach ($fechas as $fecha) {
                // Crear objeto DateTime desde el formato d/m/Y
                $fechaObj = \DateTime::createFromFormat('d/m/Y', $fecha);
    
                if (!$fechaObj) {
                    return new JsonResponse(['error' => 'Una de las fechas proporcionadas es inválida'], JsonResponse::HTTP_BAD_REQUEST);
                }
    
                // Convertir fecha a objeto DateTime para la consulta
                $fechaConsulta = $fechaObj->format('Y-m-d');
    
                // Buscar la disponibilidad de la vivienda para la fecha actual
                $disponibilidades = $disponibilidadRepository->findBy([
                    'vivienda' => $vivienda,
                    'fecha' => new \DateTime($fechaConsulta),
                ]);
    
                $fechaDisponible = false;
    
                foreach ($disponibilidades as $disponibilidad) {
                    // Verificar si hay reserva asociada a esta disponibilidad
                    $reserva = $reservaRepository->findOneBy(['disponibilidad_vivienda' => $disponibilidad]);
    
                    if (!$reserva) {
                        $fechaDisponible = true;
                        $disponibilidadesIds[] = $disponibilidad->getId();
                    }
                }
    
                if (!$fechaDisponible) {
                    $todasFechasDisponibles = false;
                    break; // Salir del bucle si alguna fecha no está disponible
                }
            }
    
            if ($todasFechasDisponibles) {
                $responseData[] = [
                    'id' => $vivienda->getId(),
                    'titulo' => $vivienda->getTitulo(),
                    'disponibilidades' => $disponibilidadesIds
                ];
            }
        }
    
        return new JsonResponse($responseData);
    }





    #[Route('/api/viviendas', name: 'api_viviendas', methods: ['GET'])]
    public function getViviendas(ViviendaRepository $viviendaRepository): JsonResponse
    {
        if ($this->espremium()){
        $viviendas = $viviendaRepository->findAll();
    
        $data = [];
        foreach ($viviendas as $vivienda) {
            $localidad = $vivienda->getLocalidad();
            $provincia = $localidad ? $localidad->getProvincia() : null;
    
            $categorias = [];
            foreach ($vivienda->getCategoria() as $categoria) {
                $categorias[] = [
                    'id' => $categoria->getId(),
                    'nombre' => $categoria->getNombre(),
                ];
            }
    
            $viviendaFotos = [];
            foreach ($vivienda->getViviendaFotos() as $foto) {
                $viviendaFotos[] = [
                    'id' => $foto->getId(),
                    'foto_url' => $foto->getFotoUrl(),
                ];
            }
    
            $data[] = [
                'id' => $vivienda->getId(),
                'titulo' => $vivienda->getTITULO(),
                'descripcion' => $vivienda->getDESCRIPCION(),
                'npersonas' => $vivienda->getnpersonas(),
                'localidad' => [
                    'id' => $localidad ? $localidad->getId() : null,
                    'nombre' => $localidad ? $localidad->getNOMBRE() : null,
                ],
                'provincia' => [
                    'id' => $provincia ? $provincia->getId() : null,
                    'nombre' => $provincia ? $provincia->getNOMBRE() : null,
                ],
                'categorias' => $categorias,
                'vivienda_fotos' => $viviendaFotos,
            ];
        }
        }else{
            return $this->json(['error' => 'Debe registrarse como usuario premium'], 401);
        }
        return new JsonResponse($data);
    }

    #[Route('/api/viviendas/filtro', name: 'api_viviendasfiltradas', methods: ['GET'])]
    public function getViviendasfiltradas(
        ViviendaRepository $viviendaRepository,
        DisponibilidadViviendaRepository $disponibilidadRepository,
        Request $request
    ): JsonResponse {
        $localidadId = $request->query->get('localidadId');
        $provinciaId = $request->query->get('provinciaId');
        $fechaInicio = $request->query->get('fechaInicio');
        $fechaFin = $request->query->get('fechaFin');
    
        // Obtener las viviendas disponibles entre las fechas dadas si están proporcionadas
        $viviendasDisponibles = [];
        if ($fechaInicio && $fechaFin) {
            try {
                // Convertir las fechas de formato DD/MM/YYYY a DateTime
                $fechaInicioObj = \DateTime::createFromFormat('d/m/Y', $fechaInicio);
                $fechaFinObj = \DateTime::createFromFormat('d/m/Y', $fechaFin);
    
                if (!$fechaInicioObj || !$fechaFinObj) {
                    return new JsonResponse(['error' => 'Las fechas proporcionadas son inválidas'], JsonResponse::HTTP_BAD_REQUEST);
                }
    
                // Ajustar las fechas al formato deseado para la consulta
                $fechaInicio = $fechaInicioObj->format('Y-m-d');
                $fechaFin = $fechaFinObj->format('Y-m-d');
    
                $viviendasDisponibles = $disponibilidadRepository->findViviendasDisponiblesEntreFechas($fechaInicioObj, $fechaFinObj);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Las fechas proporcionadas son inválidas'], JsonResponse::HTTP_BAD_REQUEST);
            }
        } else {
            // Si no se proporcionan fechaInicio y fechaFin, obtener todas las disponibilidades
            $viviendasDisponibles = $disponibilidadRepository->findAll();
        }
    
        // Filtrar por localidad si se proporciona
        if ($localidadId) {
            $viviendasDisponibles = array_filter($viviendasDisponibles, function ($disponibilidad) use ($localidadId) {
                $vivienda = $disponibilidad->getVivienda();
                return $vivienda && $vivienda->getLocalidad()->getId() == $localidadId;
            });
        }
    
        // Filtrar por provincia si se proporciona
        if ($provinciaId) {
            $viviendasDisponibles = array_filter($viviendasDisponibles, function ($disponibilidad) use ($provinciaId) {
                $vivienda = $disponibilidad->getVivienda();
                return $vivienda && $vivienda->getLocalidad()->getProvincia()->getId() == $provinciaId;
            });
        }
    
        // Obtener viviendas únicas
        $viviendasUnicas = [];
        foreach ($viviendasDisponibles as $disponibilidad) {
            $vivienda = $disponibilidad->getVivienda();
            $viviendasUnicas[$vivienda->getId()] = $vivienda;
        }
    
        // Preparar el array de datos para la respuesta JSON
        $data = [];
        foreach ($viviendasUnicas as $vivienda) {
            $localidad = $vivienda->getLocalidad();
            $provincia = $localidad ? $localidad->getProvincia() : null;
    
            $categorias = [];
            foreach ($vivienda->getCategoria() as $categoria) {
                $categorias[] = [
                    'id' => $categoria->getId(),
                    'nombre' => $categoria->getNombre(),
                ];
            }
    
            $viviendaFotos = [];
            foreach ($vivienda->getViviendaFotos() as $foto) {
                $viviendaFotos[] = [
                    'id' => $foto->getId(),
                    'foto_url' => $foto->getFotoUrl(),
                ];
            }
    
            $data[] = [
                'id' => $vivienda->getId(),
                'titulo' => $vivienda->getTitulo(),
                'descripcion' => $vivienda->getDescripcion(),
                'npersonas' => $vivienda->getNpersonas(),
                'localidad' => [
                    'id' => $localidad ? $localidad->getId() : null,
                    'nombre' => $localidad ? $localidad->getNombre() : null,
                ],
                'provincia' => [
                    'id' => $provincia ? $provincia->getId() : null,
                    'nombre' => $provincia ? $provincia->getNombre() : null,
                ],
                'categorias' => $categorias,
                'vivienda_fotos' => $viviendaFotos,
            ];
        }
    
        return new JsonResponse($data);
    }

    #[Route('/api/viviendas/{id}', name: 'api_vivienda_by_id', methods: ['GET'])]
    public function getViviendaById(ViviendaRepository $viviendaRepository, $id): JsonResponse
    {
        // Busca la vivienda por su ID en el repositorio
        $vivienda = $viviendaRepository->find($id);
    
        // Verifica si la vivienda existe
        if (!$vivienda) {
  
            return new JsonResponse(['message' => 'No se encontró la vivienda con el ID proporcionado'], Response::HTTP_NOT_FOUND);
        }
    
        // Decodificar la ubicación desde la representación de cadena JSON
        $ubicacion = json_decode((string) $vivienda, true);
    
        if ($ubicacion === null) {
          
            return new JsonResponse(['message' => 'Error con la ubicación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        $data = [
            'id' => $vivienda->getId(),
            'titulo' => $vivienda->getTITULO(),
            'descripcion' => $vivienda->getDESCRIPCION(),
            'npersonas' => $vivienda->getnpersonas(),
            'latitud' => $ubicacion['latitud'], 
            'longitud' => $ubicacion['longitud'],
        ];
    
        // Retorna la respuesta JSON con los datos de la vivienda
        return new JsonResponse($data);
    }


    

    private function estalogeado():bool
    {
        if ($this->getUser()){
        
        return true;
    }
    return false;
    }
    
    private function espremium(): bool
{
    $user = $this->getUser();
    
    if ($user) {
        $currentDate = new \DateTime();
        $premiumExpiryDate = $user->getPremium();

        if ($premiumExpiryDate && $premiumExpiryDate >= $currentDate) {
            return true;
        }
    }
    
    return false;
}

    

    // else{
    //     return $this->json(['error' => 'Debe iniciar sesion'], 401);
    // }
}