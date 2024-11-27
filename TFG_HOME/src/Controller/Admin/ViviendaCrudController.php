<?php

namespace App\Controller\Admin;

use App\Entity\Vivienda;
use App\Repository\DisponibilidadViviendaRepository;
use App\Repository\ReservaRepository;
use App\Repository\ViviendaRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ViviendaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vivienda::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    #[Route('/creavivienda', name:"creavivienda")]
    public function creavivienda(): Response
    {



        return $this->render('creavivienda.html.twig', [
           
        ]);
       
    }

    #[Route('/viviendas', name:"viviendas")]
    public function viviendas(): Response
    {



        return $this->render('viviendas.html.twig', [
           
        ]);
       
    }

    #[Route('/misviviendas', name: "misviviendas")]
    public function misViviendas(ViviendaRepository $viviendaRepository, DisponibilidadViviendaRepository $disponibilidadViviendaRepository, ReservaRepository $reservaRepository): Response
    {
        // Obtener el Usuario autenticado
        $Usuario = $this->getUser();
    
        // Verificar si el Usuario está autenticado
        if (!$Usuario) {
            throw $this->createAccessDeniedException('No has iniciado sesión.');
        }

        $premiumExpiryDate = $Usuario->getPremium();
        $currentDate = new \DateTime();
    
        if ($premiumExpiryDate && $premiumExpiryDate < $currentDate or $premiumExpiryDate==null) {
            $this->addFlash('error', 'Tu suscripción premium ha expirado.');
            return $this->redirectToRoute('app_login');
        }
    
    
        // Obtener todas las viviendas del Usuario
        $viviendas = $viviendaRepository->findBy(['Usuario' => $Usuario]);
    
        // Array para almacenar los datos de las viviendas del Usuario
        $viviendasData = [];
    
        // Iterar sobre cada vivienda del Usuario
        foreach ($viviendas as $vivienda) {
            // Obtener la primera foto de la vivienda
            $firstFoto = $vivienda->getViviendaFotos()->first();
            $fotoUrl = $firstFoto ? $firstFoto->getFotoUrl() : null;
    
            // Obtener todas las disponibilidades asociadas a esta vivienda
            $disponibilidades = $disponibilidadViviendaRepository->findBy(['vivienda' => $vivienda]);
    
            // Array para almacenar los datos de las disponibilidades de esta vivienda
            $disponibilidadesData = [];
    
            // Iterar sobre cada disponibilidad
            foreach ($disponibilidades as $disponibilidad) {
                // Obtener todas las reservas asociadas a esta disponibilidad
                $reservas = $reservaRepository->findBy(['disponibilidad_vivienda' => $disponibilidad]);
    
                // Almacenar los datos de esta disponibilidad y sus reservas
                $disponibilidadData = [
                    'fecha' => $disponibilidad->getFecha()->format('Y-m-d'),
                    'reservas' => [],
                ];
    
                // Iterar sobre cada reserva
                foreach ($reservas as $reserva) {
                    // Almacenar los datos de esta reserva
                    $disponibilidadData['reservas'][] = [
                        'id' => $reserva->getId(), // Agregar el ID de la reserva
                        'confirmado' => $reserva->isConfirmado() ? 'Sí' : 'No',
                        'intercambiojson' => $reserva->getIntercambiojson(),
                    ];
                }
    
                // Agregar los datos de esta disponibilidad al array de disponibilidades de la vivienda
                $disponibilidadesData[] = $disponibilidadData;
            }
    
            // Agregar los datos de esta vivienda y sus disponibilidades al array de datos de viviendas del Usuario
            $viviendasData[] = [
                'titulo' => $vivienda->getTITULO(),
                'descripcion' => $vivienda->getDESCRIPCION(),
                'fotoUrl' => $fotoUrl,
                'disponibilidades' => $disponibilidadesData,
            ];
        }
    
        // Renderizar la plantilla con los datos de las viviendas del Usuario y sus disponibilidades y reservas
        return $this->render('misviviendas.html.twig', [
            'viviendasData' => $viviendasData,
        ]);
    }
    
    
    #[Route('/viviendas/{id}', name: 'viviendaporid')]
    public function vivienda(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Obtén la vivienda correspondiente según el ID proporcionado
        $vivienda = $entityManager->getRepository(Vivienda::class)->find($id);

        // Verifica si la vivienda existe
        if (!$vivienda) {
            $this->addFlash('error', 'No se encontró la vivienda con el ID ' . $id);
            return $this->redirectToRoute('home');
        }

        // Renderiza la plantilla Twig y pasa la vivienda como variable
        return $this->render('viviendasindv.html.twig', [
            'vivienda' => $vivienda,
        ]);
    }



    #[Route('/ejemplo', name:"ejemplo")]
    public function ejemplo(): Response
    {



        return $this->render('ejemplo.html.twig', [
           
        ]);
       
    }
    #[Route('/misreservas', name: "misreservas")]
    public function misreservas(ReservaRepository $reservaRepository): Response
    {
        // Obtiene el Usuario autenticado
        $Usuario = $this->getUser();

        // Verifica si el Usuario está autenticado
        if (!$Usuario instanceof UserInterface) {
            throw $this->createAccessDeniedException('No has iniciado sesión.');
        }

        // Obtiene las reservas del Usuario
        $reservas = $reservaRepository->findBy(['Usuario' => $Usuario]);

        // Agrupa las reservas por vivienda
        $viviendasReservas = [];
        foreach ($reservas as $reserva) {
            $vivienda = $reserva->getDisponibilidadVivienda()->getVivienda();
            if (!isset($viviendasReservas[$vivienda->getId()])) {
                $viviendasReservas[$vivienda->getId()] = [
                    'vivienda' => $vivienda,
                    'reservas' => []
                ];
            }
            $viviendasReservas[$vivienda->getId()]['reservas'][] = $reserva;
        }

        // Renderiza la plantilla con la información del Usuario y sus reservas agrupadas por vivienda
        return $this->render('misreservas.html.twig', [
            'Usuario' => $Usuario,
            'viviendasReservas' => $viviendasReservas,
        ]);
    }

}
