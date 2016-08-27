<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Grid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Choisir une taille de grille', $crawler->text());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGrid()
    {
        $this->client = static::createClient();
        $session = $this->mockSession() ;
//        echo get_class($this->client->getContainer()->get('sudokuSessionService')) ;

        $crawler = $this->client->request('GET', '/9');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Charger une grille', $crawler->filter('button#getGridButton')->text());
        $this->assertContains('t.8.8', $crawler->filter('td input')->last()->attr('id')) ;
    }

    protected function mockSession()
    {
        $session = $this->client->getContainer()->get('sudokuSessionService');
        $grid = new Grid() ;
        $session->saveGrid($grid) ;

        return $session ;
    }
}
