<?php

namespace App\Controller\API;

use App\Entity\DisponibilidadVivienda;
use App\Entity\Reserva;
use App\Entity\Usuario;
use App\Repository\DisponibilidadViviendaRepository;
use App\Repository\ReservaRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiReservaController extends AbstractController
{
    #[Route("/reservas/crear", name: "api_reservas_crear", methods: ["POST"])]
    public function crearReserva(Request $request, EntityManagerInterface $entityManager, UsuarioRepository $UsuarioRepository): JsonResponse
    {
        // Decodificar los datos JSON de la solicitud
        $data = json_decode($request->getContent(), true);
    
        // Extraer los datos de la solicitud
        $UsuarioId = $data['Usuarioid'] ?? null;
        $disponibilidades = $data['disponibilidades'] ?? [];
    
        // Verificar si se recibieron todos los campos necesarios
        if (!isset($UsuarioId, $disponibilidades) || empty($disponibilidades)) {
            return $this->json(['message' => 'Faltan campos obligatorios'], 400);
        }
    
        // Cargar la instancia de Usuario correspondiente al ID proporcionado
        $Usuario = $UsuarioRepository->find($UsuarioId);
    
        // Verificar si se encontró el Usuario
        if (!$Usuario) {
            return $this->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        // Calcular el total de los precios de las disponibilidades
        $totalPrecio = 0;
        foreach ($disponibilidades as $disponibilidad) {
            $disponibilidadId = $disponibilidad['id'] ?? null;
    
            if (!$disponibilidadId) {
                return $this->json(['message' => 'ID de disponibilidad no proporcionado'], 400);
            }
    
            $disponibilidadVivienda = $entityManager->getRepository(DisponibilidadVivienda::class)->find($disponibilidadId);
    
            if (!$disponibilidadVivienda) {
                return $this->json(['message' => 'Disponibilidad no encontrada'], 404);
            }
    
            $totalPrecio += $disponibilidadVivienda->getPrecio();
        }
    
        // Verificar si el Usuario tiene suficientes puntos
        if ($Usuario->getPuntos() < $totalPrecio) {
            return $this->json(['message' => 'Puntos insuficientes'], 400);
        }
    
        // Deducir los puntos del Usuario
        $Usuario->setPuntos($Usuario->getPuntos() - $totalPrecio);
    
        // Iterar sobre las disponibilidades y crear una reserva para cada una
        foreach ($disponibilidades as $disponibilidad) {
            $disponibilidadId = $disponibilidad['id'] ?? null;
    
            if (!$disponibilidadId) {
                return $this->json(['message' => 'ID de disponibilidad no proporcionado'], 400);
            }
    
            $disponibilidadVivienda = $entityManager->getRepository(DisponibilidadVivienda::class)->find($disponibilidadId);
    
            if (!$disponibilidadVivienda) {
                return $this->json(['message' => 'Disponibilidad no encontrada'], 404);
            }
    
            // Verificar si la disponibilidad ya está reservada
            $reservaExistente = $entityManager->getRepository(Reserva::class)->findOneBy(['disponibilidad_vivienda' => $disponibilidadVivienda]);
            if ($reservaExistente) {
                return $this->json(['message' => 'Una de las disponibilidades ya ha sido reservada, recargue la pagina'], 400);
            }
    
            // Crear una nueva instancia de Reserva y asignar los datos
            $reserva = new Reserva();
            $reserva->setUsuario($Usuario);
            $reserva->setDisponibilidadVivienda($disponibilidadVivienda);
            $reserva->setConfirmado(false); // Confirmado se establece en falso por defecto
    
            // Persistir la reserva en la base de datos
            $entityManager->persist($reserva);
        }
    
        // Guardar los cambios en la base de datos
        $entityManager->flush();
    
        // Devolver una respuesta JSON con un mensaje de éxito
        return $this->json(['message' => 'Reservas creadas correctamente'], 201);
    }


    #[Route("/intercambio/crear", name: "api_intercambio_crear", methods: ["POST"])]
    public function crearIntercambio(Request $request, EntityManagerInterface $entityManager, UsuarioRepository $UsuarioRepository, DisponibilidadViviendaRepository $disponibilidadViviendaRepository): JsonResponse
    {
      
        $data = json_decode($request->getContent(), true);

        // Extraer los datos de la solicitud
        $UsuarioId = $data['Usuarioid'] ?? null;
        $disponibilidades = $data['disponibilidades'] ?? [];
        $intercambiojson = $data['intercambiojson'] ?? null;

        // Verificar si se recibieron todos los campos necesarios
        if (!isset($UsuarioId, $disponibilidades) || empty($disponibilidades)) {
            return new JsonResponse(['message' => 'Faltan campos obligatorios'], 400);
        }

        // Cargar la instancia de Usuario correspondiente al ID proporcionado
        $Usuario = $UsuarioRepository->find($UsuarioId);

        // Verificar si se encontró el Usuario
        if (!$Usuario) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], 404);
        }

        // Iterar sobre las disponibilidades y crear una reserva para cada una
        foreach ($disponibilidades as $disponibilidad) {
            $disponibilidadId = $disponibilidad['id'] ?? null;

            if (!$disponibilidadId) {
                return new JsonResponse(['message' => 'ID de disponibilidad no proporcionado'], 400);
            }

            $disponibilidadVivienda = $disponibilidadViviendaRepository->find($disponibilidadId);

            if (!$disponibilidadVivienda) {
                return new JsonResponse(['message' => 'Disponibilidad no encontrada'], 404);
            }

            // Verificar si la disponibilidad ya está reservada
            $reservaExistente = $entityManager->getRepository(Reserva::class)->findOneBy(['disponibilidad_vivienda' => $disponibilidadVivienda]);
            if ($reservaExistente) {
                return new JsonResponse(['message' => 'Una de las disponibilidades ya ha sido reservada, recargue la página'], 400);
            }

            // Crear una nueva instancia de Reserva y asignar los datos
            $reserva = new Reserva();
            $reserva->setUsuario($Usuario);
            $reserva->setDisponibilidadVivienda($disponibilidadVivienda);
            $reserva->setConfirmado(false); // Confirmado se establece en falso por defecto
            $reserva->setIntercambiojson($intercambiojson);

            // Persistir la reserva en la base de datos
            $entityManager->persist($reserva);
        }

        // Guardar los cambios en la base de datos
        $entityManager->flush();

        // Devolver una respuesta JSON con un mensaje de éxito
        return new JsonResponse(['message' => 'Reservas creadas correctamente'], 201);
    }

   
#[Route("/reservas/confirmar", name: "api_reservas_confirmar", methods: ["POST"])]
public function confirmarReserva(Request $request, EntityManagerInterface $entityManager, ReservaRepository $reservaRepository): JsonResponse
{
    // Decodificar los datos JSON de la solicitud
    $data = json_decode($request->getContent(), true);

    // Extraer el ID de la reserva del cuerpo de la solicitud
    $reservaId = $data['reservaId'] ?? null;

    // Verificar si se recibió el ID de la reserva
    if (!$reservaId) {
        return $this->json(['message' => 'ID de reserva no proporcionado'], 400);
    }

    // Buscar la reserva por su ID
    $reserva = $reservaRepository->find($reservaId);

    // Verificar si se encontró la reserva
    if (!$reserva) {
        return $this->json(['message' => 'Reserva no encontrada'], 404);
    }

    // Obtener la disponibilidad de vivienda asociada a la reserva
    $disponibilidadVivienda = $reserva->getDisponibilidadVivienda();

    // Verificar si la disponibilidad de vivienda existe
    if (!$disponibilidadVivienda) {
        return $this->json(['message' => 'Disponibilidad de vivienda no encontrada'], 404);
    }

    // Obtener el precio de la disponibilidad de vivienda
    $precio = $disponibilidadVivienda->getPrecio();

    // Obtener la vivienda asociada a la disponibilidad de vivienda
    $vivienda = $disponibilidadVivienda->getVivienda();

    // Verificar si la vivienda existe
    if (!$vivienda) {
        return $this->json(['message' => 'Vivienda no encontrada'], 404);
    }

    // Obtener el usuario asociado a la vivienda
    $usuario = $vivienda->getUsuario();

    // Verificar si el usuario existe
    if (!$usuario) {
        return $this->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Sumar el precio de la disponibilidad de vivienda a los puntos del usuario
    $puntosActuales = $usuario->getPuntos();
    $nuevosPuntos = $puntosActuales + $precio;
    $usuario->setPuntos($nuevosPuntos);

    // Cambiar el estado de confirmación de la reserva a 1
    $reserva->setConfirmado(true);

    // Guardar los cambios en la base de datos
    $entityManager->flush();

    // Devolver una respuesta JSON con un mensaje de éxito
    return $this->json(['message' => 'Reserva confirmada correctamente'], 200);
}

#[Route("/reservas/rechazar", name: "api_reservas_rechazar", methods: ["POST"])]
public function rechazarReserva(Request $request, EntityManagerInterface $entityManager, ReservaRepository $reservaRepository): JsonResponse
{
    // Decodificar los datos JSON de la solicitud
    $data = json_decode($request->getContent(), true);

    // Extraer el ID de la reserva del cuerpo de la solicitud
    $reservaId = $data['reservaId'] ?? null;

    // Verificar si se recibió el ID de la reserva
    if (!$reservaId) {
        return $this->json(['message' => 'ID de reserva no proporcionado'], Response::HTTP_BAD_REQUEST);
    }

    // Buscar la reserva por su ID
    $reserva = $reservaRepository->find($reservaId);

    // Verificar si se encontró la reserva
    if (!$reserva) {
        return $this->json(['message' => 'Reserva no encontrada'], Response::HTTP_NOT_FOUND);
    }

    // Obtener el usuario asociado a la reserva
    $usuario = $reserva->getUsuario();

    // Verificar si el usuario existe
    if (!$usuario) {
        return $this->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
    }

    // Obtener la disponibilidad de vivienda asociada a la reserva
    $disponibilidadVivienda = $reserva->getDisponibilidadVivienda();

    // Verificar si la disponibilidad de vivienda existe
    if (!$disponibilidadVivienda) {
        return $this->json(['message' => 'Disponibilidad de vivienda no encontrada'], Response::HTTP_NOT_FOUND);
    }

    // Obtener el precio de la disponibilidad de vivienda
    $precio = $disponibilidadVivienda->getPrecio();

    // Sumar el precio de la disponibilidad de vivienda a los puntos del usuario
    $puntosActuales = $usuario->getPuntos();
    $nuevosPuntos = $puntosActuales + $precio;
    $usuario->setPuntos($nuevosPuntos);

    // Eliminar la reserva de la base de datos
    $entityManager->remove($reserva);

    // Guardar los cambios en la base de datos
    $entityManager->flush();

    // Devolver una respuesta JSON con un mensaje de éxito
    return $this->json(['message' => 'Reserva rechazada correctamente. Los puntos han sido sumados al usuario'], Response::HTTP_OK);
}
}