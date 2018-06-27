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

        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);
        dump($visit);

        if ($form->isSubmitted() && $form->isValid()) {
            $visitManager->generateTickets($visit);

            return $this->redirect($this->generateUrl('app_visit_identify'));
        }

        //On est en GET. On affiche le formulaire
        return $this->render('Visit/order.html.twig', array('form' => $form->createView()));
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
        $visit = $visitManager->getCurrentVisit();
        dump($visit);

        $form = $this->createForm(VisitTicketsType::class, $visit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $visitManager->computePrice($visit);

            return $this->redirect($this->generateUrl('app_visit_customer'));

        }
        //On est en GET. On affiche le formulaire
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
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function payAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit();
        dump($visit);

        if($request->getMethod() === "POST")
        {
            if($visitManager->getBookingCode($visit))
            {

              //TODO enregistrement dans la base
                /*
                 $em = $this->getDoctrine()->getManager();
                $em->persist($visit);
                $em->flush();
                */

             // TODO envoi du mail de rservation

                $token = $request->request->get('stripeToken');


                return $this->redirect($this->generateUrl('app_visit_confirmation'));
            }

            else{

                //TODO Comment savoir si le message flash est OK
                $this->get('session')->getFlashBag()->add('notice', 'Le paiement a échoué.');
                return $this->redirectToRoute('app_visit_pay');
            }


        }

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