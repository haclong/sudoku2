<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Event\ChooseGridEvent;
use AppBundle\Utils\GridMapper;
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
    public function testGetGrid()
    {
        $client = static::createClient();
        
        $grid = $client->getContainer()->get('gridEntity') ;
        $session = $client->getContainer()->get('session') ;
        $session->set('grid', $grid) ;
        $dispatcher = $client->getContainer()->get('event_dispatcher') ;

        $gridSize = new GridSize(9) ;
        $event = new ChooseGridEvent($gridSize) ;
        $dispatcher->dispatch('grid.choose', $event) ;
        
//        var_dump($service->getGrid()) ;

        $crawler = $client->request('GET', '/api/grid/get?size=9');
        
        // récupérer la grille en session
        
        // tests sur le retour en json
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $json = json_decode($response->getContent()) ;
        $this->assertInstanceOf('stdClass', $json) ;
        $this->assertObjectHasAttribute('getGrid', $json) ;
        $this->assertObjectHasAttribute('size', $json->getGrid) ;
        $this->assertEquals(9, $json->getGrid->size) ;
        $this->assertObjectHasAttribute('tiles', $json->getGrid) ;
        $this->assertGreaterThan(9, count($json->getGrid->tiles)) ;
        
        // tests sur les données de grid dans la session
        $mappedJson['getGrid'] = GridMapper::toArray($session->get('grid')) ;
//        var_dump($mappedJson) ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $session->get('grid')) ;
        $this->assertEquals(9, $session->get('grid')->getSize()) ;
        $this->assertFalse($session->get('grid')->isSolved()) ;
        $this->assertGreaterThan(0, count($session->get('grid')->getTiles())) ;
        $this->assertLessThanOrEqual(9, count($session->get('grid')->getTiles())) ;
        $this->assertEquals($response->getContent(), json_encode($mappedJson)) ;
    }

//    /**
//     * @runInSeparateProcess
//     */
//    public function testResetGrid()
//    {
//        $client = static::createClient();
////        var_dump($client) ;
//
//        $crawler = $client->request('GET', '/api/grid/reset');
//        $response = $client->getResponse();
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
//    }
}
