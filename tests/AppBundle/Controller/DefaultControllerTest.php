<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->session = $this->client->getContainer()->get('sudokuSession') ;
        $this->grid = $this->client->getContainer()->get('gridEntity') ;
        $this->values = $this->client->getContainer()->get('valuesEntity') ;
        $this->tiles = $this->client->getContainer()->get('tilesEntity') ;
        $this->session->setGrid($this->grid) ;
        $this->session->setValues($this->values) ;
        $this->session->setTiles($this->tiles) ;
    }
    
    protected function tearDown()
    {
        $this->client = null ;
        $this->session = null ;
        $this->grid = null ;
        $this->values = null ;
        $this->tiles = null ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testHomepage()
    {
        // on remplit grid et tiles
        $this->session->getGrid()->init(9) ;
        $this->session->getTiles()->init(9) ;
        
        // on vérifie que grid est rempli
        $this->assertEquals(9, $this->session->getGrid()->getSize()) ;
        $this->assertEquals(81, $this->session->getGrid()->getRemainingTiles()) ;
        // on vérifie que values est vide
        $this->assertNull($this->session->getValues()->getSize()) ;
        $this->assertEquals(0, count($this->session->getValues()->getValues())) ;
        // on vérifie que tiles est rempli
        $this->assertEquals(81, count($this->session->getTiles()->getTileset())) ;
        $this->assertEquals(9, $this->session->getTiles()->getSize()) ;
        
        $crawler = $this->client->request('GET', '/');
       
        // on vérifie ce qu'il se passe à l'écran
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Choisir une taille de grille', $crawler->text());

        // on vérifie que grid est vide
        $this->assertNull($this->session->getGrid()->getSize()) ;
        $this->assertEquals(-1, $this->session->getGrid()->getRemainingTiles()) ;
        // on vérifie que tiles est vide
        $this->assertEquals(0, count($this->session->getTiles()->getTileset())) ;
        $this->assertNull($this->session->getTiles()->getSize()) ;
        // on vérifie que values est vide
        $this->assertNull($this->session->getValues()->getSize()) ;
        $this->assertEquals(0, count($this->session->getValues()->getValues())) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testGrid()
    {
        $this->session->clear() ;
        // on vérifie que grid est vide
        $this->assertNull($this->session->getGrid()) ;
        // on vérifie que values est vide
        $this->assertNull($this->session->getValues()) ;
        // on vérifie que tiles est vide
        $this->assertNull($this->session->getTiles()) ;
        $this->grid->reset() ;
        $this->values->reset() ;
        $this->tiles->reset() ;
        $this->session->setGrid($this->grid) ;
        $this->session->setValues($this->values) ;
        $this->session->setTiles($this->tiles) ;

        $crawler = $this->client->request('GET', '/9');

        // on vérifie ce qu'il se passe à l'écran
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Charger une grille', $crawler->filter('button#loadGridButton')->text());
        $this->assertContains('t.8.8', $crawler->filter('td input')->last()->attr('id')) ;

        // on vérifie que grid est rempli
        $this->assertEquals(9, $this->session->getGrid()->getSize()) ;
        $this->assertEquals(81, $this->session->getGrid()->getRemainingTiles()) ;
        // on vérifie que values est rempli
        $this->assertNull($this->session->getValues()->getSize()) ;
        $this->assertEquals(0, count($this->session->getValues()->getValues())) ;
        // on vérifie que tiles est rempli
        $this->assertEquals(81, count($this->session->getTiles()->getTileset())) ;
        $this->assertEquals(9, $this->session->getTiles()->getSize()) ;
    }
}
