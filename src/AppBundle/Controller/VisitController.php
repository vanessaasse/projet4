<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\InvalidVisitSessionException;
use AppBundle\Form\CustomerType;
use AppBundle\Form\TicketType;
use AppBundle\Form\VisitCustomerType;
use AppBundle\Form\VisitTicketsType;
use AppBundle\Form\VisitType;
use AppBundle\Manager\VisitManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\PublicHolidaysService;


class VisitController extends Controller
{
    /**
     * Page 1 - Page d'accueil
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('Visit/index.html.twig');
    }


    /**
     * Page 2 - Initialisation de la visite - choix de la date / du type de billet / du nb de billets
     * @Route("/order")
     * @param Request $request
     * @param VisitManager $visitManager
     * @param PublicHolidaysService $publicHolidaysService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function orderAction(Request $request, VisitManager $visitManager, PublicHolidaysService $publicHolidaysService)
    {
        $visit = $visitManager->initVisit();

        $publicHolidays = $publicHolidaysService->getPublicHolidaysOnTheseTwoYears();

        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
                $visitManager->generateTickets($visit);

                return $this->redirect($this->generateUrl('app_visit_identify'));
        }

        return $this->render('Visit/order.html.twig', array('form' => $form->createView(), 'publicHolidays' => $publicHolidays));

    }


    /**
     * page 3 - Identification des visiteurs - création des billets
     * @Route("/identification", name="app_visit_identify")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function identifyAction(Request $request, VisitManager $visitManager)
    {

        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_INIT);
        dump($visit);

        $form = $this->createForm(VisitTicketsType::class, $visit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $visitManager->computePrice($visit);

            return $this->redirect($this->generateUrl('app_visit_customer'));

        }

        return $this->render('Visit/identify.html.twig', array('form'=>$form->createView(), 'visit' => $visit,));
    }


    /**
     * page 4 - Coordonnées de l'acheteur - création du customer
     * @Route("/customer", name="app_visit_customer")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     *
     */
    public function customerAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_TICKET);
        dump($visit);

        $form = $this->createForm(VisitCustomerType::class, $visit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            return $this->redirect($this->generateUrl('app_visit_pay'));

        }

        return $this->render('Visit/customer.html.twig', array('form'=>$form->createView(), 'visit' => $visit));

    }

    /**
     * page 5 paiement
     * @Route("/pay", name="app_visit_pay")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function payAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_CUSTOMER);

        // Création du booking code
        $visitManager->getBookingCode($visit);

        dump($visit);


        if($request->getMethod() === "POST") {
            //Création de la charge - Stripe
            $token = $request->request->get('stripeToken');

            // chargement de la clé secrète de Stripe
            $secretkey = $this->getParameter('stripe_secret_key');

            // paiement
            \Stripe\Stripe::setApiKey($secretkey);
            \Stripe\Charge::create(array(
                "amount" => $visitManager->computePrice($visit) * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "Réservation sur la billeterie du Musée du Louvre"));

            // enregistrement dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($visit);
            $em->flush();


            // TODO envoi du mail de rservation

            $this->addFlash('notice', 'Votre paiement a bien été pris en compte.');
            return $this->redirect($this->generateUrl('app_visit_confirmation'));
        }

        /*else{
            // Message flash si le paiement a échoué
            $this->get('session')->getFlashBag()->add('notice', 'Le paiement a échoué.');
            return $this->redirectToRoute('app_visit_pay');
        }*/

        return $this->render('Visit/pay.html.twig', array('visit' => $visit));

    }


    /**
     * page 6 confirmation
     * @Route("/confirmation")
     * @throws InvalidVisitSessionException
     */
    public function confirmationAction(VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_BOOKINGCODE);

        return $this->render('Visit/confirmation.html.twig', array('visit' => $visit));
    }


    /**
     * page contact
     *
     * @Route("/contact")
     */
    public function contactAction()
    {
        return $this->render('Visit/contact.html.twig');
    }

}