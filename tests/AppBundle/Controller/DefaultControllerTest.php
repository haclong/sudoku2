<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->session = $this->client->getContainer()->get('sudokuSession') ;
        $this->gridsession = $this->client->getContainer()->get('gridSession') ;
        $this->valuessession = $this->client->getContainer()->get('valuesSession') ;
        $this->tilessession = $this->client->getContainer()->get('tilesSession') ;
        $this->groupssession = $this->client->getContainer()->get('groupsSession') ;
        $this->grid = $this->client->getContainer()->get('gridEntity') ;
        $this->values = $this->client->getContainer()->get('valuesEntity') ;
        $this->tiles = $this->client->getContainer()->get('tilesEntity') ;
        $this->groups = $this->client->getContainer()->get('groupsEntity') ;
        $this->gridsession->setGrid($this->grid) ;
        $this->valuessession->setValues($this->values) ;
        $this->tilessession->setTiles($this->tiles) ;
        $this->groupssession->setGroups($this->groups) ;
     }
    
    protected function tearDown()
    {
        $this->client = null ;
        $this->session = null ;
        $this->gridsession = null ;
        $this->valuessession = null ;
        $this->tilessession = null ;
        $this->groupssession = null ;
        $this->grid = null ;
        $this->values = null ;
        $this->tiles = null ;
        $this->groups = null ;
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
    public function testGetGridRedirectToHomepage()
    {
        $this->session->clear() ;
        $this->client->request('GET', '/9');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }    

    /**
     * @runInSeparateProcess
     */
    public function testGrid()
    {
        $this->session->clear() ;
        // on vérifie que grid est vide
        $this->assertNull($this->gridsession->getGrid()) ;
        // on vérifie que values est vide
        $this->assertNull($this->valuessession->getValues()) ;
        // on vérifie que tiles est vide
        $this->assertNull($this->tilessession->getTiles()) ;
        $this->assertNull($this->groupssession->getGroups()) ;
        $this->grid->reset() ;
        $this->values->reset() ;
        $this->tiles->reset() ;
        $this->groups->reset() ;
        $this->gridsession->setGrid($this->grid) ;
        $this->valuessession->setValues($this->values) ;
        $this->tilessession->setTiles($this->tiles) ;
        $this->groupssession->setGroups($this->groups) ;

        $crawler = $this->client->request('GET', '/9');

        // on vérifie ce qu'il se passe à l'écran
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Charger une grille', $crawler->filter('button#loadGridButton')->text());
        $this->assertContains('t.8.8', $crawler->filter('td input')->last()->attr('id')) ;

        // on vérifie que grid est rempli
        $this->assertEquals(9, $this->gridsession->getGrid()->getSize()) ;
        $this->assertEquals(81, $this->gridsession->getGrid()->getRemainingTiles()) ;
        // on vérifie que values est rempli
        $this->assertEquals(9, $this->valuessession->getValues()->getSize()) ;
        $this->assertEquals(0, count($this->valuessession->getValues()->getValues())) ;
        // on vérifie que tiles est rempli
        $this->assertEquals(81, count($this->tilessession->getTiles()->getTileset())) ;
        $this->assertEquals(9, $this->tilessession->getTiles()->getSize()) ;
    }
}
