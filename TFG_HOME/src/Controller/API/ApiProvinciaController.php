<?php

namespace App\Controller\API;

use App\Repository\ProvinciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiProvinciaController extends AbstractController
{
    #[Route('/Provincia', name: 'getProvincia', methods:'GET')]
    public function getProvincia(ProvinciaRepository $ProvinciaRepository): Response
    {
        // $this->denyAccessUnlessGranted("ROLE_USER");
        $Provincias=$ProvinciaRepository->findAll();

        if (!$Provincias) {
            return $this->json(['error' => 'Provincias no encontradas'], 404);
        }

        foreach ($Provincias as $Provincia) {
            $json[] = [
                'id' => $Provincia->getId(),
                'nombre' => $Provincia->getNombre()

            ];
        }

        return new JsonResponse($json, 200, [], false);
    }



    
}