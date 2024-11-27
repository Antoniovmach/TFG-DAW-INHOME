<?php

namespace App\Controller\API;

use App\Entity\Item;
use App\Repository\LocalidadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiLocalidadController extends AbstractController
{


    #[Route('/Localidad', name: 'getLocalidad', methods:'GET')]
    public function getLocalidad(LocalidadRepository $LocalidadRepository): Response
    {
        // $this->denyAccessUnlessGranted("ROLE_USER");
        $Localidades=$LocalidadRepository->findAll();

        if (!$Localidades) {
            return $this->json(['error' => 'Localidades no encontradas'], 404);
        }

        foreach ($Localidades as $Localidad) {
            $json[] = [
                'id' => $Localidad->getId(),
                'nombre' => $Localidad->getNombre(),
                'Provincia_id' => $Localidad->getProvincia()->getId(),

            ];
        }

        return new JsonResponse($json, 200, [], false);
    }

    #[Route('/Localidad/{ProvinciaId}', name: 'getLocalidadByProvincia', methods:'GET')]
    public function getLocalidadByProvincia(LocalidadRepository $LocalidadRepository, $ProvinciaId): Response
    {
        $Localidades = $LocalidadRepository->findBy(['Provincia' => $ProvinciaId]);

        if (!$Localidades) {
            return $this->json(['error' => 'Localidades no encontradas para la Provincia seleccionada'], 404);
        }

 
        foreach ($Localidades as $Localidad) {
            $json[] = [
                'id' => $Localidad->getId(),
                'nombre' => $Localidad->getNombre(),
                'Provincia_id' => $Localidad->getProvincia()->getId(),
            ];
        }

        return new JsonResponse($json, 200, [], false);
    }







}