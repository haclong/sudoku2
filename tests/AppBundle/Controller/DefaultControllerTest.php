<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->session = $this->client->getContainer()->get('session') ;
        $this->grid = $this->client->getContainer()->get('gridEntity') ;
        $this->session->set('grid', $this->grid) ;
    }
    
    protected function tearDown()
    {
        $this->client = null ;
        $this->session = null ;
        $this->grid = null ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testHomepage()
    {
        $crawler = $this->client->request('GET', '/');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Choisir une taille de grille', $crawler->text());
        $this->assertEquals($this->grid, $this->session->get('grid')) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testGrid()
    {
        $crawler = $this->client->request('GET', '/9');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Charger une grille', $crawler->filter('button#getGridButton')->text());
        $this->assertContains('t.8.8', $crawler->filter('td input')->last()->attr('id')) ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $this->session->get('grid')) ;
        $this->assertEquals(9, $this->session->get('grid')->getSize()) ;
        $this->assertFalse($this->session->get('grid')->isSolved()) ;
        $this->assertEquals(0, count($this->session->get('grid')->getTiles())) ;
    }
}
