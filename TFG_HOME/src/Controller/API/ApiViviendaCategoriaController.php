<?php


namespace App\Controller\API;

use App\Entity\Categoria;
use App\Entity\Vivienda;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiViviendaCategoriaController extends AbstractController
{
    #[Route("/viviendacategorias/crear", name: "api_categorias", methods: ["POST"])]
    public function index(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Obtener los datos de la solicitud
        $data = json_decode($request->getContent(), true);

        // Verificar si se recibieron datos válidos
        if (!isset($data) || empty($data)) {
            return $this->json(['message' => 'No se proporcionaron datos válidos'], 400);
        }

        // Obtener el ID de la vivienda
        $viviendaId = $data[0]['viviendaId'];

        // Obtener la instancia de la vivienda por su ID
        $vivienda = $em->getRepository(Vivienda::class)->find($viviendaId);

        // Verificar si se encontró la vivienda
        if (!$vivienda) {
            return $this->json(['message' => 'Vivienda no encontrada'], 404);
        }

        // Iterar sobre las categorías y asociarlas a la vivienda
        foreach ($data as $categoriaData) {
            // Obtener el ID de la categoría y el ID de la vivienda
            $categoriaId = $categoriaData['id'];

            // Obtener la instancia de la categoría por su ID
            $categoria = $em->getRepository(Categoria::class)->find($categoriaId);

            // Verificar si se encontró la categoría
            if (!$categoria) {
                return $this->json(['message' => 'Categoría no encontrada'], 404);
            }

            // Asociar la categoría a la vivienda
            $vivienda->addCategorium($categoria);
        }

        // Persistir los cambios en la base de datos
        $em->flush();

        // Devolver una respuesta JSON indicando que las categorías se asociaron correctamente a la vivienda
        return $this->json(['message' => 'Categorías asociadas correctamente a la vivienda'], 201);
    }
}
