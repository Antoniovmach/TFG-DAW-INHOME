<?php

namespace App\Controller\API;

use App\Entity\Vivienda;
use App\Entity\ViviendaFoto;
use App\Service\FileUploaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiViviendaFotoController extends AbstractController
{
    private $fileUploaderService;

    public function __construct(FileUploaderService $fileUploader)
    {
        $this->fileUploaderService = $fileUploader;
    }

    #[Route("/ViviendaFoto/crear", name: "postViviendaFoto", methods: ["POST"])]
    public function createViviendaFoto(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $foto = $request->files->get('foto');
        $viviendaId = $request->request->get('vivienda_id');

        if (!$foto) {
            return $this->json(['message' => 'Falta la imagen'], 400);
        }

        if (!$viviendaId) {
            return $this->json(['message' => 'Falta el ID de la vivienda'], 400);
        }

    
        $nombreFoto = $this->fileUploaderService->upload($foto);

        $viviendaFoto = new ViviendaFoto();
        $viviendaFoto->setFotoUrl($nombreFoto);

        // Asignar la vivienda con el ID proporcionado
        $vivienda = $em->getRepository(Vivienda::class)->find($viviendaId);
        if (!$vivienda) {
            return $this->json(['message' => 'Vivienda no encontrada'], 404);
        }
        $viviendaFoto->setVivienda($vivienda);

  
        $em->persist($viviendaFoto);
        $em->flush();

     
        $viviendaFotoId = $viviendaFoto->getId();

        return $this->json(['message' => $viviendaFotoId], 201);
    }
}
