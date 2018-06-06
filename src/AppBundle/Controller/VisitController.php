<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Ticket;
use AppBundle\Form\CustomerType;
use AppBundle\Form\TicketType;
use AppBundle\Form\VisitType;
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
    public function orderAction(Request $request)
    {
        //On crée un nouvel objet Visit
        $visit = new Visit();
        $visit->setInvoiceDate(new \DateTime());

        //On crée le formulaire VisitType
        $form = $this->createForm(VisitType::class, $visit);

        // On hydrate les données à partir de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // On récupère l'Entity Manager
            $em = $this->getDoctrine()->getManager();

            // On persiste l'entité
            $em->persist($visit);

            $visit->setVisitDate(new \DateTime())->setType($type)->setNbTicket($nbTicket);


            //$visit->getNbTicket() => $visit->addTicket(new Ticket());
            $request->getSession()->set('visit', $visit);
            return $this->redirect($this->generateUrl('app_visit_identify'));

        }

        //On est en GET. On affiche le formulaire
        return $this->render('Visit/order.html.twig', array('form'=>$form->createView()));

        //
        //$visit = $request->getSession()->get('visit');
        //

    }


    /**
     * page 3 identification des visiteurs
     *
     * @Route("/identification")
     */
    public function identifyAction(Request $request)
    {
        $visit = $request->getSession()->get('visit');


        //On appelle le formulaire TicketType
        $formBuilder = $this->get('form.factory')->createBuilder(VisitTicketsType::class, $visit);

        //A partir du formulaire, on le génère
        $formTicket = $formBuilder->getForm();

        //Si la requête est en POST
        if($request->isMethod('POST'))
        {
            //On fait le lien requête->formulaire
            // Désormais, la variable $ticket contient les valeurs entrées par le visiteur
            $formTicket->handleRequest($request);

            //On vérifie que les données entrées sont valides
            if($formTicket->isValid())
            {
                //$em = $this->getDoctrine()->getManager();
                //$em->persist();
                //$em->flush();

                $request->getSession()->get('ticket');

                //On redirige l'acheteur vers la page 4 - coordonnées de l'acheteur
                return $this->redirectToRoute('app_visit_customer');
            }
        }

        //On est en GET. On affiche le formulaire
        return $this->render('Visit/identify.html.twig', array('form'=>$formTicket->createView()));

    }


    /**
     * page 4 coordonnees de l'acheteur
     *
     * @Route("/customer")
     */
    public function customerAction(Request $request)
    {
        //On crée un nouvel objet Customer
        $customer = new Customer;

        //On appelle le formulaire CustomerType
        $formBuilder = $this->get('form.factory')->createBuilder(CustomerType::class, $customer);

        //A partir du formulaire, on le génère
        $formCustomer = $formBuilder->getForm();

        //Si la requête est en POST
        if($request->isMethod('POST'))
        {
            //On fait le lien requête->formulaire
            // Désormais, la variable $ticket contient les valeurs entrées par le visiteur
            $formCustomer->handleRequest($request);

            //On vérifie que les données entrées sont valides
            if($formCustomer->isValid())
            {
                //$em = $this->getDoctrine()->getManager();
                //$em->persist();
                //$em->flush();

                $request->getSession()->get('customer');

                //On redirige l'acheteur vers la page 5 - paiement
                return $this->redirectToRoute('app_visit_pay');
            }
        }

        //On est en GET. On affiche le formulaire
        return $this->render('Visit/customer.html.twig', array('form'=>$formCustomer->createView()));
    }


    /**
     * page 5 paiement
     *
     * @Route("/pay")
     */
    public function payAction()
    {
        //
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