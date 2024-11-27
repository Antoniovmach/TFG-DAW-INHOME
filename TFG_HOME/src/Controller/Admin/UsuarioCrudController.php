<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioCrudController extends AbstractCrudController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ["ROLE_ADMIN", "ROLE_USER", "ROLE_GUIDE"];

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            TextField::new('apellidos'),
            TextField::new('email'),
            TextField::new('contrasena'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices(),
            ImageField::new('foto')
                ->setBasePath('uploads/images/Usuario')
                ->setUploadDir('public/uploads/images/Usuario')
                ->setUploadedFileNamePattern('[uuid].[extension]'),
            BooleanField::new('is_verified'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashPassword($user): void
    {
        if ($user instanceof Usuario && $plainPassword = $user->getPassword()) {
            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setContrasena($hashedPassword);
        }
    }
}
