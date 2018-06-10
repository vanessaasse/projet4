<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Ticket;
use AppBundle\Form\CustomerType;
use AppBundle\Form\TicketType;
use AppBundle\Form\VisitTicketsType;
use AppBundle\Form\VisitType;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\VisitManager;
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
     * Initialisation de la visite
     * page 2 choix des tickets
     *
     * @Route("/order")
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
     * page 3 identification des visiteurs
     *
     * @Route("/identification")
     * @throws \Exception
     */
    public function identifyAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit();
        dump($visit);

        $form = $this->createForm(VisitTicketsType::class, $visit);

        $form->handleRequest($request);
        dump($visit);

        if($form->isSubmitted() && $form->isValid()) {

            return $this->redirect($this->generateUrl('app_visit_customer'));

        }
        //On est en GET. On affiche le formulaire
        return $this->render('Visit/identify.html.twig', array('form'=>$form->createView()));
    }


    /**
     * page 4 coordonnées de l'acheteur
     *
     * @Route("/customer")
     *
     * @throws \AppBundle\Exception\InvalidVisitSessionException
     */
    public function customerAction(Request $request, VisitManager $visitManager, CustomerManager $customerManager)
    {
        // on récupère la session en cours
        $visit = $visitManager->getCurrentVisit();
        dump($visit);

        //On initialise un nouveau client
        $customer = $customerManager->initCustomer();
        dump($customer);

        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);
        dump($customer);

        if($form->isSubmitted() && $form->isValid()) {

            return $this->redirect($this->generateUrl('app_visit_pay'));

        }

        return $this->render('Visit/customer.html.twig', array('form'=>$form->createView(), 'visit'=> $visit, 'customer' => $customer));

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