<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Utils\GridMapper;
use AppBundle\Utils\JsonMapper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of ApiControllerTest
 *
 * @author haclong
 */
class ApiControllerTest extends WebTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLoadGrid()
    {
        $client = static::createClient();
        
        $grid = $client->getContainer()->get('gridEntity') ;
        $grid->init(9) ;
        $values = $client->getContainer()->get('valuesEntity') ;
        
        $session = $client->getContainer()->get('session') ;
        $session->set('grid', $grid) ;
        $session->set('values', $values) ;

        $crawler = $client->request('GET', '/api/grid/load?size=9');
        
        // tests sur le retour en json
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $json = json_decode($response->getContent()) ;
        $this->assertInstanceOf('stdClass', $json) ;
        $this->assertObjectHasAttribute('grid', $json) ;
        $this->assertObjectHasAttribute('size', $json->grid) ;
        $this->assertEquals(9, $json->grid->size) ;
        $this->assertObjectHasAttribute('tiles', $json->grid) ;
        $this->assertGreaterThan(9, count($json->grid->tiles)) ;
        
        // tests sur les données de grid dans la session
        $mappedJson['grid'] = GridMapper::toArray($session->get('grid')) ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $session->get('grid')) ;
        $this->assertEquals(9, $session->get('grid')->getSize()) ;
        $this->assertFalse($session->get('grid')->isSolved()) ;
        $this->assertGreaterThan(0, count($session->get('grid')->getTiles())) ;
        $this->assertLessThanOrEqual(9, count($session->get('grid')->getTiles())) ;
        $this->assertEquals($response->getContent(), json_encode($mappedJson)) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testReloadGrid()
    {
        $client = static::createClient();
        
        // Créer une grille remplie
        $array = array() ;
        $array[0][2] = 2 ;
        $array[0][5] = 9 ;
        $array[0][6] = 1 ;
        $array[0][8] = 6 ;
        $array[1][0] = 3 ;
        $array[1][2] = 5 ;
        $array[1][4] = 4 ;
        $array[1][6] = 2 ;
        $array[2][1] = 7 ;
        $array[2][2] = 9 ;
        $array[2][3] = 2 ;
        $array[2][4] = 6 ;
        $array[3][1] = 5 ;
        $array[3][7] = 1 ;
        $array[3][8] = 9 ;
        $array[4][1] = 2 ;
        $array[4][2] = 1 ;
        $array[4][3] = 9 ;
        $array[4][4] = 7 ;
        $array[4][5] = 5 ;
        $array[4][6] = 8 ;
        $array[4][7] = 4 ;
        $array[5][0] = 9 ;
        $array[5][1] = 8 ;
        $array[5][7] = 2 ;
        $array[6][4] = 9 ;
        $array[6][5] = 1 ;
        $array[6][6] = 7 ;
        $array[6][7] = 6 ;
        $array[7][2] = 4 ;
        $array[7][4] = 5 ;
        $array[7][6] = 3 ;
        $array[7][8] = 1 ;
        $array[8][0] = 7 ;
        $array[8][2] = 6 ;
        $array[8][3] = 3 ;
        $array[8][6] = 9 ;  
        
        $grid = $client->getContainer()->get('gridEntity') ;
        $grid->init(9) ;
        $grid->setTiles($array) ;
        $values = $client->getContainer()->get('valuesEntity') ;
        
//        $gridToJson = GridMapper::toArray($grid) ;
//        $expectedTiles = $gridToJson['tiles'] ;

        // Remplir la session avec la grille existante
        $session = $client->getContainer()->get('session') ;
        $session->set('grid', $grid) ;
        $session->set('values', $values) ;

        $crawler = $client->request('GET', '/api/grid/reload');
        
        // tests sur le retour en json
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $responseArray = JsonMapper::toArray($response->getContent()) ;
        
        $this->assertTrue(is_array($responseArray)) ;
        $this->assertEquals(9, $responseArray['size']) ;
        $this->assertEquals($array, $responseArray['tiles']) ;
        
        // tests sur les données de grid dans la session
        $this->assertInstanceOf('AppBundle\Entity\Grid', $session->get('grid')) ;
        $this->assertEquals(9, $session->get('grid')->getSize()) ;
        $this->assertFalse($session->get('grid')->isSolved()) ;
        $this->assertEquals($array, $session->get('grid')->getTiles()) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testResetGrid()
    {
        $client = static::createClient();
        
        // Créer une grille remplie
        $array = array() ;
        $array[0][2] = 2 ;
        $array[0][5] = 9 ;
        $array[0][6] = 1 ;
        $array[0][8] = 6 ;
        $array[1][0] = 3 ;
        $array[1][2] = 5 ;
        $array[1][4] = 4 ;
        $array[1][6] = 2 ;
        $array[2][1] = 7 ;
        $array[2][2] = 9 ;
        $array[2][3] = 2 ;
        $array[2][4] = 6 ;
        $array[3][1] = 5 ;
        $array[3][7] = 1 ;
        $array[3][8] = 9 ;
        $array[4][1] = 2 ;
        $array[4][2] = 1 ;
        $array[4][3] = 9 ;
        $array[4][4] = 7 ;
        $array[4][5] = 5 ;
        $array[4][6] = 8 ;
        $array[4][7] = 4 ;
        $array[5][0] = 9 ;
        $array[5][1] = 8 ;
        $array[5][7] = 2 ;
        $array[6][4] = 9 ;
        $array[6][5] = 1 ;
        $array[6][6] = 7 ;
        $array[6][7] = 6 ;
        $array[7][2] = 4 ;
        $array[7][4] = 5 ;
        $array[7][6] = 3 ;
        $array[7][8] = 1 ;
        $array[8][0] = 7 ;
        $array[8][2] = 6 ;
        $array[8][3] = 3 ;
        $array[8][6] = 9 ;  
        
        $grid = $client->getContainer()->get('gridEntity') ;
        $grid->init(9) ;
        $grid->setTiles($array) ;
        $values = $client->getContainer()->get('valuesEntity') ;
        
        // Remplir la session avec la grille existante
        $session = $client->getContainer()->get('session') ;
        $session->set('grid', $grid) ;
        $session->set('values', $values) ;

        $crawler = $client->request('GET', '/api/grid/reset');
        
        // tests sur le retour en json
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        
        $responseArray = JsonMapper::toArray($response->getContent()) ;
        $this->assertTrue(is_array($responseArray)) ;
        $this->assertEquals(9, $responseArray['size']) ;
        $this->assertEquals(array(), $responseArray['tiles']) ;
        
        // tests sur les données de grid dans la session
        $this->assertInstanceOf('AppBundle\Entity\Grid', $session->get('grid')) ;
        $this->assertEquals(9, $session->get('grid')->getSize()) ;
        $this->assertFalse($session->get('grid')->isSolved()) ;
        $this->assertEquals(array(), $session->get('grid')->getTiles()) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveGrid()
    {
        $client = static::createClient();
        
//        // Créer une grille remplie
//        $json = '{"grid":{"size":9,"tiles":[{"id":"t.0.0","value":""},{"id":"t.0.1","value":""},{"id":"t.0.2","value":"2"},{"id":"t.0.3","value":""},{"id":"t.0.4","value":""},{"id":"t.0.5","value":"9"},{"id":"t.0.6","value":"1"},{"id":"t.0.7","value":""},{"id":"t.0.8","value":"6"},{"id":"t.1.0","value":"3"},{"id":"t.1.1","value":""},{"id":"t.1.2","value":"5"},{"id":"t.1.3","value":""},{"id":"t.1.4","value":"4"},{"id":"t.1.5","value":""},{"id":"t.1.6","value":"2"},{"id":"t.1.7","value":""},{"id":"t.1.8","value":""},{"id":"t.2.0","value":""},{"id":"t.2.1","value":"7"},{"id":"t.2.2","value":"9"},{"id":"t.2.3","value":"2"},{"id":"t.2.4","value":"6"},{"id":"t.2.5","value":""},{"id":"t.2.6","value":""},{"id":"t.2.7","value":""},{"id":"t.2.8","value":""},{"id":"t.3.0","value":""},{"id":"t.3.1","value":"5"},{"id":"t.3.2","value":""},{"id":"t.3.3","value":""},{"id":"t.3.4","value":""},{"id":"t.3.5","value":""},{"id":"t.3.6","value":""},{"id":"t.3.7","value":"1"},{"id":"t.3.8","value":"9"},{"id":"t.4.0","value":""},{"id":"t.4.1","value":"2"},{"id":"t.4.2","value":"1"},{"id":"t.4.3","value":"9"},{"id":"t.4.4","value":"7"},{"id":"t.4.5","value":"5"},{"id":"t.4.6","value":"8"},{"id":"t.4.7","value":"4"},{"id":"t.4.8","value":""},{"id":"t.5.0","value":"9"},{"id":"t.5.1","value":"8"},{"id":"t.5.2","value":""},{"id":"t.5.3","value":""},{"id":"t.5.4","value":""},{"id":"t.5.5","value":""},{"id":"t.5.6","value":""},{"id":"t.5.7","value":"2"},{"id":"t.5.8","value":""},{"id":"t.6.0","value":""},{"id":"t.6.1","value":""},{"id":"t.6.2","value":""},{"id":"t.6.3","value":""},{"id":"t.6.4","value":"9"},{"id":"t.6.5","value":"1"},{"id":"t.6.6","value":"7"},{"id":"t.6.7","value":"6"},{"id":"t.6.8","value":""},{"id":"t.7.0","value":""},{"id":"t.7.1","value":""},{"id":"t.7.2","value":"4"},{"id":"t.7.3","value":""},{"id":"t.7.4","value":"5"},{"id":"t.7.5","value":""},{"id":"t.7.6","value":"3"},{"id":"t.7.7","value":""},{"id":"t.7.8","value":"1"},{"id":"t.8.0","value":"7"},{"id":"t.8.1","value":""},{"id":"t.8.2","value":"6"},{"id":"t.8.3","value":"3"},{"id":"t.8.4","value":""},{"id":"t.8.5","value":""},{"id":"t.8.6","value":"9"},{"id":"t.8.7","value":""},{"id":"t.8.8","value":""}]}}' ;
        
        $crawler = $client->request('GET', '/api/grid/save');
        
        
    }
}
