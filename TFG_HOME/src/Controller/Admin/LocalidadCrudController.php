<?php
namespace App\Controller\Admin;

use App\Entity\Localidad;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LocalidadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Localidad::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre', 'Nombre'),
            AssociationField::new('Provincia'),
            // Add other fields as needed
        ];
    }
}
