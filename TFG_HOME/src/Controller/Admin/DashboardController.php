<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Entity\DisponibilidadVivienda;
use App\Entity\Localidad;
use App\Entity\Provincia;
use App\Entity\Reserva;
use App\Entity\Usuario;
use App\Entity\Vivienda;
use App\Entity\ViviendaFoto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    
    public function index(): Response
    {

        $this->configureMenuItems();

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FREETOUR ADMIN');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section("Entidades");
        yield MenuItem::linkToCrud('Usuario', 'fa fa-user-circle',Usuario::class);
        yield MenuItem::linkToCrud('Categoria', 'fa fa-list',Categoria::class);
        yield MenuItem::linkToCrud('Vivienda', 'fa fa-home ',Vivienda::class);
        yield MenuItem::linkToCrud('Reserva', 'fa fa-bookmark ',Reserva::class);
        yield MenuItem::linkToCrud('Disponibilidad', 'fa fa-bed ',DisponibilidadVivienda::class);
        yield MenuItem::linkToCrud('Foto vivienda', 'fa fa-photo ',ViviendaFoto::class);
         yield MenuItem::section("Mapa");
         yield MenuItem::linkToCrud('Provincia', 'fa fa-map-pin',Provincia::class);
         yield MenuItem::linkToCrud('Localidad', 'fa fa-map-signs',Localidad::class);
  
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


    
    #[Route('/', name:"home")]
    public function home(): Response
    {



        return $this->render('home.html.twig', [
           
        ]);
       
    }


    
}
