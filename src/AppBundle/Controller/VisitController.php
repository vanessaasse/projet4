<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Visit;
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
        $visit = new visit;

        //On appelle le formulaire VisitType
        $formBuilder = $this->get('form.factory')->createBuilder(VisitType::class, $visit);

        //A partir du formulaire, on le génère
        $form = $formBuilder->getForm();

        //Si la requête est en POST
        if($request->isMethod('POST'))
        {
            //On fait le lien requête->formulaire
            // Désormais, la variable $visit contient les valeurs entrées par le visiteur
            $form->handleRequest($request);

            //On vérifie que les données entrées sont valides
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist();
                $em->flush();

                $request->getSession();

                //On redirige l'acheteur vers la page 3 - identification des visiteurs
                return $this->redirectToRoute('app_visit_identify');
            }
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
    public function identifyAction()
    {
        //
    }


    /**
     * page 4 coordonnees de l'acheteur
     *
     * @Route("/customer")
     */
    public function customerAction()
    {
        //
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


    /*
     * page contact
     *
     * @Route("/contact")
     */
    public function contactAction()
    {
        //
    }

}