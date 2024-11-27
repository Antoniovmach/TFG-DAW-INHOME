<?php

// src/Service/SiteUpdateManager.php
namespace App\Service;

use App\Entity\Usuario;
use App\Service\MessageGenerator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SiteUpdateManager
{
    public function __construct(
        private MessageGenerator $messageGenerator,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function notifyOfSiteUpdate(): bool
    {
        $happyMessage = $this->messageGenerator->getHappyMessage();


        $lastId = $this->entityManager->getRepository(Usuario::class)->findOneBy([], ['id' => 'DESC']);
        $Usuario = $this->entityManager->getRepository(Usuario::class)->findById($lastId->getId());
        // $Usuario=$this->entityManager->getRepository(Usuario::class)->findById(46);

        $email = (new Email())
            ->from('admin@example.com')
            ->to($Usuario[0]->getEmail())
            ->subject('Reserva realizada!')
            ->text('La reserva '.$happyMessage.' se ha realizado correctamente');

        $this->mailer->send($email);



        return true;
    }
}
