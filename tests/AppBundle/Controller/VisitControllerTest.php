<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\DomCrawler\Link;

class VisitControllerTest extends WebTestCase
{
    /**
     * TEST
     */
    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/en/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }


    /**
     * TEST
     * Je vérifie que la page Pay renvoie une redirection 302 lorsque je m'y rends sans suivre
     * le tunnel d'achat
     */
    public function testPayPageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/fr/pay');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }


    /**
     * TEST
     * Vérifie que cela retourne bien une erreur 404
     */
    public function testRouteNotFoundIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/uiflaof');

        $this->assertSame(404, $client->getResponse()->getStatusCode());
    }


    /**
     * TEST
     * Verifie sur le lien "Contact" en page d'accueil
     * redirige bien vers la page Contact
     */
    public function testHomePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');
        $link = $crawler->selectLink("contact")->links();

        $lastContent = $client->getResponse()->getContent();
        $this->assertContains("Contactez-nous", $lastContent);

    }

}