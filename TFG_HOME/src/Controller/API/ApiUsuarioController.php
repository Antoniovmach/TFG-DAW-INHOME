<?php

namespace App\Controller\API;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class ApiUsuarioController extends AbstractController
{
    #[Route('/api/Usuario/comprar-premium', name: 'api_comprar_premium', methods: ['POST'])]
    public function comprarPremium(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'] ?? null;

        if (!$userId) {
            return new JsonResponse(['error' => 'ID de Usuario no proporcionado'], Response::HTTP_BAD_REQUEST);
        }

        $Usuario = $entityManager->getRepository(Usuario::class)->find($userId);
        if (!$Usuario) {
            return new JsonResponse(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $currentDate = new DateTime();

        // Verificar si la fecha premium del Usuario no ha vencido
        if ($Usuario->getPremium() && $Usuario->getPremium() > $currentDate) {
            return new JsonResponse(['error' => 'El Usuario ya es premium hasta ' . $Usuario->getPremium()->format('Y-m-d')], Response::HTTP_BAD_REQUEST);
        }

        $year = (int) $currentDate->format('Y') + 1;
        $month = (int) $currentDate->format('m');
        $day = (int) $currentDate->format('d');

        if ($month === 2 && $day === 29) {
            $month = 3;
            $day = 1;
        }

        $premiumDate = new DateTime("$year-$month-$day");
        $Usuario->setPremium($premiumDate);

        // Sumar 100 puntos al Usuario
        $currentPoints = $Usuario->getPuntos() ?? 0;
        $Usuario->setPuntos($currentPoints + 1000);

        $entityManager->persist($Usuario);
        $entityManager->flush();

        return new JsonResponse([
            'success' => 'Usuario ahora es premium hasta ' . $premiumDate->format('Y-m-d'),
            'newPoints' => $Usuario->getPuntos()
        ]);
    }
}