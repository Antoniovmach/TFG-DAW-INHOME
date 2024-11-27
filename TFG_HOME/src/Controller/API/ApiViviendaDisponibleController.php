<?php

namespace App\Controller\API;

use App\Entity\DisponibilidadVivienda;
use App\Entity\Vivienda;
use App\Repository\DisponibilidadViviendaRepository;
use App\Repository\ReservaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiViviendaDisponibleController extends AbstractController
{
    #[Route('/viviendadisponibilidad/crear', name: 'postTour', methods: ['POST'])]
    public function createTour(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $viviendaId = $data['vivienda_id'];
        $tours = $data['jsonArrayProgramacion'];

        // Validar vivienda_id
        if (!$viviendaId) {
            return $this->json(['message' => 'Falta el ID de la vivienda'], 400);
        }

        $vivienda = $em->getRepository(Vivienda::class)->find($viviendaId);
        if (!$vivienda) {
            return $this->json(['message' => 'Vivienda no encontrada'], 404);
        }

        // Mapa de días de la semana
        $dayMap = [
            'L' => 'Mon',
            'M' => 'Tue',
            'X' => 'Wed',
            'J' => 'Thu',
            'V' => 'Fri',
            'S' => 'Sat',
            'D' => 'Sun',
        ];

        foreach ($tours as $tourData) {
            $fecha_inicio = \DateTime::createFromFormat('d/m/Y', $tourData['temporadaIni']);
            $fecha_fin = \DateTime::createFromFormat('d/m/Y', $tourData['temporadaFin']);
            $dias = array_map(function ($dia) use ($dayMap) {
                return $dayMap[$dia];
            }, explode(',', $tourData['diasSemana']));

            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($fecha_inicio, $interval, $fecha_fin->modify('+1 day'));

            foreach ($period as $date) {
                $dayOfWeek = $date->format('D');
                if (in_array($dayOfWeek, $dias)) {
                    $disponibilidad = new DisponibilidadVivienda();
                    $disponibilidad->setFecha($date);
                    $disponibilidad->setPrecio($tourData['precio']);
                    $disponibilidad->setVivienda($vivienda);

                    $em->persist($disponibilidad);
                }
            }
        }

        $em->flush();

        return $this->json(['message' => 'Disponibilidad creada con éxito'], 201);
    }

    #[Route('/api/viviendasdisponible/{id}', name: 'api_viviendadisponible_by_id', methods: ['GET'])]
    public function getViviendadisponibleById(DisponibilidadViviendaRepository $disponibilidadViviendaRepository, ReservaRepository $reservaRepository, $id): JsonResponse
    {
        // Busca la disponibilidad de la vivienda por su ID
        $disponibilidades = $disponibilidadViviendaRepository->findBy(['vivienda' => $id]);
    
        // Verifica si se encontró la disponibilidad
        if (!$disponibilidades) {
            // Retorna una respuesta JSON con un mensaje de error si no se encuentra la disponibilidad
            return new JsonResponse(['message' => 'No se encontró la disponibilidad para la vivienda con el ID proporcionado'], 404);
        }
    
        // Crea un array para almacenar los datos de disponibilidad
        $disponibilidadData = [];
    
        // Itera sobre las disponibilidades encontradas y agrega los datos al array
        foreach ($disponibilidades as $disponibilidadItem) {
            // Verifica si la disponibilidad está reservada
            $reserva = $reservaRepository->findOneBy(['disponibilidad_vivienda' => $disponibilidadItem]);
    
            // Solo incluir disponibilidades que no están reservadas
            if (!$reserva) {
                $disponibilidadData[] = [
                    'id_disponibilidad_vivienda' => $disponibilidadItem->getId(),
                    'fecha' => $disponibilidadItem->getFecha()->format('Y-m-d'),
                    'precio' => $disponibilidadItem->getPrecio(),
                ];
            }
        }
    
        // Retorna la respuesta JSON con los datos de disponibilidad
        return new JsonResponse($disponibilidadData);
    }
    
}
