<?php

// src/Service/MessageGenerator.php
namespace App\Service;

use Psr\Log\LoggerInterface;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;



class MessageGenerator
{
    public function __construct(
        private LoggerInterface $logger,
       
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,


    ) {
    }

    public function getHappyMessage(): string
    {
        $this->logger->info('About to find a happy message!');
        // ... your logic to generate a happy message

        // Return a string value here
        return 'A happy message!';
    }

    public function getMensaje(): string
    {
        $this->logger->info('Importante');
        // ... your logic to generate a happy message

        // Return a string value here
        return 'Hola esto funciona!';
    }

    public function sendEmail($correo, $subject, $text): bool
    {
        $email = (new Email())
        ->from("freetour@gmail.com")
        ->to($correo)
        ->subject($subject)
        ->text($text);

        $this->mailer->send($email);

        return false;
    }
}
