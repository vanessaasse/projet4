<?php

namespace AppBundle\Controller;


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
     * page 2 choix des tickets - Initialisation de la visite
     *
     * @Route("/order")
     */
    public function orderAction(Request $request)
    {
        //
        $visit = $request->getSession()->get('visit');
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