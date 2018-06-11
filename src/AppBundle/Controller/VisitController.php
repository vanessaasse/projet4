<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Ticket;
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

class VisitController extends Controller
{

    /**
     * @Route("/", name="homepage")
     *
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
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function orderAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->initVisit();
        dump($visit);

        $form = $this->createForm(VisitType::class,$visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $visitManager->generateTickets($visit);

            return $this->redirect($this->generateUrl('app_visit_identify'));
        }

        //On est en GET. On affiche le formulaire
        return $this->render('Visit/order.html.twig', array('form'=>$form->createView()));
    }


    /**
     * page 3 - Identification des visiteurs - création des billets
     * @Route("/identification")
     *
     * @param Request $request
     * @param VisitManager $visitManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InvalidVisitSessionException
     */
    public function identifyAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit();

        $form = $this->createForm(VisitTicketsType::class, $visit);

        $form->handleRequest($request);
        dump($visit);

        if($form->isSubmitted() && $form->isValid()) {

            $visitManager->createTickets($visit);

            return $this->redirect($this->generateUrl('app_visit_customer'));

        }
        //On est en GET. On affiche le formulaire
        return $this->render('Visit/identify.html.twig', array('form'=>$form->createView()));
    }


    /**
     * page 4 - Coordonnées de l'acheteur - création du customer
     * @Route("/customer")
     *
     * @param Request $request
     * @param VisitManager $visitManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InvalidVisitSessionException
     *
     */
    public function customerAction(Request $request, VisitManager $visitManager)
    {
        // on récupère la session en cours
        $visit = $visitManager->getCurrentVisit();

        $form = $this->createForm(VisitCustomerType::class, $visit);

        $form->handleRequest($request);
        dump($visit);

        if($form->isSubmitted() && $form->isValid()) {

            $visitManager->createCustomer($visit);

            return $this->redirect($this->generateUrl('app_visit_pay'));

        }

        return $this->render('Visit/customer.html.twig', array('form'=>$form->createView()));

    }

    /**
     * page 5 paiement
     *
     * @Route("/pay")
     * @throws \AppBundle\Exception\InvalidVisitSessionException
     *
     */
    public function payAction(/*Request $request,*/ VisitManager $visitManager)
    {
        // on récupère la session en cours
        $visit = $visitManager->getCurrentVisit();
        dump($visit);



        return $this->render('Visit/pay.html.twig') ;
    }


    /**
     * page 6 confirmation
     *
     * @Route("/confirmation")
     */
    public function confirmationAction()
    {
        //
    }


    /**
     * page contact
     *
     * @Route("/contact")
     */
    public function contactAction()
    {
        //
    }

}