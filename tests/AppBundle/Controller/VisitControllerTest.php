<?php

namespace tests\AppBundle\Controller;

use AppBundle\Entity\Visit;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\DomCrawler\Link;

class VisitControllerTest extends WebTestCase
{

    /**
     * @param $url
     * @param $statusExpected
     *
     * @dataProvider urls
     */
    public function testUrls($url, $method, $statusExpected)
    {
        $client = static::createClient();
        $client->request($method, $url);

        $this->assertSame($statusExpected, $client->getResponse()->getStatusCode());
    }


    /**
     * @return array
     * Tableau d'URLs à tester via la méthode testUrls
     */
    public function urls(){
        return [
            ['/', 'GET', 301],
            ['/fr/', 'GET' , 200],
            ['/fr/', 'POST' , 405],
            ['/en/', 'GET' , 200 ],
            ['/en/', 'POST' , 405 ],
            ['/ru/', 'GET' , 404 ],
            ['/uiflaof', 'GET' , 404 ],
            ['/fr/contact', 'GET' , 200],
            ['/fr/contact', 'POST' , 200],
            ['/fr/order', 'GET' , 200],
            ['/fr/identification', 'GET' , 302],
            ['/fr/customer', 'GET' , 302],
            ['/fr/pay', 'GET' , 302],
            ['/fr/confirmation', 'GET' , 302 ]

        ];
    }


    /**
     * TEST
     * Verifie si le lien "Contact" en page d'accueil
     * redirige bien vers la page Contact
     */
    public function testLinkContactOnHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');

        $link = $crawler->filter('a:contains("Contact")')->eq(0)->link();
        $client->click($link);

        $this->assertEquals('AppBundle\Controller\VisitController::contactAction',
            $client->getRequest()->attributes->get('_controller'));
    }


    /**
     * TEST
     * Verifie si le lien "Achetez vos billets" en page d'accueil
     * redirige bien vers la page Order
     */
    public function testLinkBuyTicketsOnHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');

        $link = $crawler->filter('a:contains("Achetez vos billets")')->eq(0)->link();
        $client->click($link);

        $this->assertEquals('AppBundle\Controller\VisitController::orderAction',
            $client->getRequest()->attributes->get('_controller'));
    }


    /**
     * TEST
     * Test les formulaires du tunnel d'achat
     * jusqu'à la page PAY
     */
    public function testForm()
    {
        $client = static::createClient();

        // index.html.twig
        $crawler = $client->request('GET', '/fr/');

        $link = $crawler
            ->filter('a:contains("Achetez vos billets")')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertEquals('AppBundle\Controller\VisitController::orderAction',
            $client->getRequest()->attributes->get('_controller'));


        // order.html.twig
        $form = $crawler->selectButton('Validez')->form();

        $form['appbundle_visit[visitDate]'] = date('2019-08-30');
        $form['appbundle_visit[type]'] = 0;
        $form['appbundle_visit[nbTicket]'] = 1;

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals('AppBundle\Controller\VisitController::identifyAction',
            $client->getRequest()->attributes->get('_controller'));


        // identify.html.twig
        $form = $crawler->selectButton('Validez')->form();

        $values = $form->getPhpValues();

        $values['visit_tickets']['tickets'][0]['lastname'] = "Barelli";
        $values['visit_tickets']['tickets'][0]['firstname'] = "Claire";
        $values['visit_tickets']['tickets'][0]['country'] = "France";
        $values['visit_tickets']['tickets'][0]['birthday']['day'] = 20;
        $values['visit_tickets']['tickets'][0]['birthday']['month'] = 3;
        $values['visit_tickets']['tickets'][0]['birthday']['year'] = 1981;
        $values['visit_tickets']['tickets'][0]['discount'] = 0;


        $client->request('POST', $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());


        // customer.html.twig
        $form = $crawler->selectButton('Validez')->form();

        $values = $form->getPhpValues();

        $values['app_bundle_visit_customer_type']['customer']['lastname'] = "Barelli";
        $values['app_bundle_visit_customer_type']['customer']['firstname'] = "Claire";
        $values['app_bundle_visit_customer_type']['customer']['email'] = "claire.barelli@monadresse.fr";
        $values['app_bundle_visit_customer_type']['customer']['adress'] = "15 rue des Beaux-Arts";
        $values['app_bundle_visit_customer_type']['customer']['postCode'] = "75010";
        $values['app_bundle_visit_customer_type']['customer']['city'] = "Paris";
        $values['app_bundle_visit_customer_type']['customer']['country'] = "France";

        $client->request('POST', $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
    }


    /**
     * TEST
     * Je vérifie que la page Pay renvoie une redirection 302 lorsque je m'y rends sans suivre
     * le tunnel d'achat
     * TODO
    public function testPayPageIsUp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/order');

        $session = $client->getContainer()->get('session');


        $visit = new Visit;
        $date = new \DateTime("27-05-2019");
        $visit->setVisitDate($date);
        $type = Visit::TYPE_FULL_DAY;
        $visit->setType($type);
        $nbTickets = 2;
        $visit->addTicket($nbTickets);

        $session->set('visit',$visit);
        $client->request('GET', '/fr/identification');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }*/












}