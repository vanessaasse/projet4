<?php

namespace AppBundle\Service;

use AppBundle\Entity\Visit;
use AppBundle\Form\ContactType;

/**
 * Class EmailService
 * @package AppBundle\Service
 */
class EmailService
{
    // Ce service prend trois arguments : l'envoi de mail, le template, et l'adresse d'envoi
    // Ces trois arguments sont repris dans services.yml
    // Dans services.yml, j'indique quelle adresse mail il y a derrière $emailfrom

    protected $mailer;
    protected $templating;
    private $emailfrom;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, $emailfrom)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailfrom = $emailfrom;

    }
    /**
     * @param Visit $visit
     * @return $this
     */
    public function sendMailConfirmation(Visit $visit)
    {
        $email = $visit->getCustomer()->getEmail();

        $message = (new \Swift_Message('Confirmation de votre réservation sur le site du Musée du Louvre'))
            ->setFrom($this->emailfrom) // je récupère l'adresse que j'ai enregistré dans parameters.yml grâce à cet argument
            ->setTo($email)
            ->setBody($this->templating->render('Emails/registration.html.twig', ['visit' => $visit]))
            ->setContentType('text/html');

        $this->mailer->send($message);

    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMailContact($data)
    {
        $message = (new \Swift_Message('Site du Musée du Louvre - Page Contact'))
            ->setFrom($data['email']) // je récupère l'adresse donnée par l'internaute dans le formulaire.
                                        // Dans le controller, j'ai appelé les datas par  $emailService->sendMailContact($form->getData());
            ->setTo($this->emailfrom) // je récupère l'adresse que j'ai enregistré dans parameters.yml grâce à cet argument
            ->setBody($this->templating->render('Emails/contact.html.twig',
                array('data' => $data)))
            ->setContentType('text/html');

        $this->mailer->send($message);
    }
}