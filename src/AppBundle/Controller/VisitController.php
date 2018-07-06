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
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class VisitController extends Controller
{

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

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
     *
     * @param Request $request
     * @param VisitManager $visitManager
     * @param PublicHolidaysService $publicHolidaysService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function orderAction(Request $request, VisitManager $visitManager, PublicHolidaysService $publicHolidaysService)
    {
        $visit = $visitManager->initVisit();

        $publicHolidays = $publicHolidaysService->getPublicHolidaysOnTheseTwoYears();

        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($visit);
        dump($errors);

        if (count($errors) == 0) {

            if($form->isSubmitted() && $form->isValid()) {
                $visitManager->generateTickets($visit);

                return $this->redirect($this->generateUrl('app_visit_identify'));
            }

            else {

                return $this->render('Visit/order.html.twig', array('form' => $form->createView(), 'publicHolidays' => $publicHolidays,
                    'errors' => $errors));
            }

        }

        return $this->render('Visit/order.html.twig', array('form' => $form->createView(), 'publicHolidays' => $publicHolidays));

    }


    /**
     * page 3 - Identification des visiteurs - création des billets
     * @Route("/identification", name="app_visit_identify")
     *
     * @param Request $request
     * @param VisitManager $visitManager
     *
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function identifyAction(Request $request, VisitManager $visitManager)
    {
        //$visit = $visitManager->getCurrentVisit(Visit::IS_VALID_INIT);
        $visit = $visitManager->getCurrentVisit();
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
     *
     * @param Request $request
     * @param VisitManager $visitManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     *
     */
    public function customerAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit();
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
     *
     * @Route("/pay", name="app_visit_pay")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function payAction(Request $request, VisitManager $visitManager)
    {
        //$visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_TICKET);
        $visit = $visitManager->getCurrentVisit();

        // Création du booking code
        $visitManager->getBookingCode($visit);

        dump($visit);


        if($request->getMethod() === "POST") {
            //Création de la charge - Stripe
            $token = $request->request->get('stripeToken');

            $secretkey = $this->getParameter('stripe_secret_key');


            \Stripe\Stripe::setApiKey($secretkey);
            \Stripe\Charge::create(array(
                "amount" => $visitManager->computePrice($visit) * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "Réservation sur la billeterie du Musée du Louvre"));

            //TODO enregistrement dans la base
            /*
             $em = $this->getDoctrine()->getManager();
            $em->persist($visit);
            $em->flush();
            */

            // TODO envoi du mail de rservation

            $this->get('session')->getFlashBag()->add('notice', 'Votre paiement a bien été pris en compte.');
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
     *
     * @Route("/confirmation")
     */
    public function confirmationAction()
    {
        return $this->render('Visit/confirmation.html.twig');
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