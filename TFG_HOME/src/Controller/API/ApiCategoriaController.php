<?php

namespace App\Controller\API;

use App\Entity\Item;
use App\Repository\CategoriaRepository;
use App\Repository\LocalidadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiCategoriaController extends AbstractController
{


    #[Route('/categoria', name: 'getCategoria', methods:'GET')]
    public function getCatgoria(CategoriaRepository $categoriaRepository): Response
    {
      
        $categorias=$categoriaRepository->findAll();

        if (!$categorias) {
            return $this->json(['error' => 'categorias no encontradas'], 404);
        }

        foreach ($categorias as $categoria) {
            $json[] = [
                'id' => $categoria->getId(),
                'nombre' => $categoria->getNombre(),

            ];
        }

        return new JsonResponse($json, 200, [], false);
    }




}