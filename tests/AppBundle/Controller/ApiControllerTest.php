<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Event\TileSet;
use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\SetTileEvent;
use AppBundle\Utils\JsonMapper;
use AppBundle\Utils\TilesMapper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of ApiControllerTest
 *
 * @author haclong
 */
class ApiControllerTest extends WebTestCase
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
        $this->groupsService = $this->client->getContainer()->get('groupsService') ;
        $this->dispatcher = $this->client->getContainer()->get('event_dispatcher') ;
        $this->session->clear() ;
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
    public function testLoadGridRedirectToHomepage()
    {
        $this->client->request('GET', '/api/grid/load?size=9');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }    

    /**
     * @runInSeparateProcess
     */
    public function testLoadGrid()
    {
//        // initialise 
//        $this->grid->init(9) ;
//        $this->tiles->init(9) ;
//        $this->groups->init(9) ;
//        $this->values->init(9) ;
//        $this->gridsession->setGrid($this->grid) ;
//        $this->valuessession->setValues($this->values) ;
//        $this->tilessession->setTiles($this->tiles) ;
//        $this->groupssession->setGroups($this->groups) ;
//
//        // on vérifie que grid est rempli
//        $this->assertEquals(9, $this->gridsession->getGrid()->getSize()) ;
//        $this->assertEquals(81, $this->gridsession->getGrid()->getRemainingTiles()) ;
//        // on vérifie que values est rempli
//        $this->assertEquals(9, $this->valuessession->getValues()->getSize()) ;
//        $this->assertEquals(0, count($this->valuessession->getValues()->getValues())) ;
//        // on vérifie que tiles est rempli
//        $this->assertEquals(81, count($this->tilessession->getTiles()->getTileset())) ;
//        $this->assertEquals(9, $this->tilessession->getTiles()->getSize()) ;
//        // on vérifie que groups est rempli
//        $this->assertEquals(9, $this->groupssession->getGroups()->getSize()) ;

        // on initialise les objets en session
        $sudokuEntities = $this->client->getContainer()->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->dispatcher->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->dispatcher->dispatch(InitGameEvent::NAME, $event) ;

        $crawler = $this->client->request('GET', '/api/grid/load?size=4');
        
        // tests sur le retour en json
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;

        $json = json_decode($response->getContent()) ;
        $this->assertInstanceOf('stdClass', $json) ;
        $this->assertObjectHasAttribute('grid', $json) ;
        $this->assertObjectHasAttribute('size', $json->grid) ;
        $this->assertEquals(4, $json->grid->size) ;
        $this->assertObjectHasAttribute('tiles', $json->grid) ;
        $this->assertGreaterThan(4, count($json->grid->tiles)) ;

        // on vérifie que grid est rempli
        $this->assertEquals(4, $this->gridsession->getGrid()->getSize()) ;
        $this->assertEquals(10, $this->gridsession->getGrid()->getRemainingTiles()) ;
//        // on vérifie que values est rempli
//        $this->assertEquals(9, $this->session->getValues()->getSize()) ;
//        $this->assertEquals(9, count($this->session->getValues()->getValues())) ;
//        // on vérifie que tiles est rempli
//        $this->assertEquals(81, count($this->tilessession->getTiles()->getTileset())) ;
//        $this->assertEquals(9, $this->tilessession->getTiles()->getSize()) ;
        // on vérifie que la grille stockée dans l'objet $grid est la même qui est dans le json
        $mappedJson['grid'] = TilesMapper::toArray($this->tilessession->getTiles(), $this->valuessession->getValues()) ;
        $this->assertEquals($response->getContent(), json_encode($mappedJson)) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetTileRedirectToHomepage()
    {
        $this->client->request('GET', '/api/tile/set');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testSetTileReturnsError()
    {
        // créer une grille remplie
        $g4easy[0][1] = 2 ;
        $g4easy[1][0] = 3 ;
        $g4easy[2][2] = 2 ;
        $g4easy[3][1] = 3 ;
        $g4easy[3][2] = 4 ;
        $g4easy[3][3] = 1 ;
        
        // on initialise les objets en session
        $sudokuEntities = $this->client->getContainer()->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->dispatcher->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->dispatcher->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(4, $g4easy) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->dispatcher->dispatch(LoadGameEvent::NAME, $event) ;

        $crawler = $this->client->request(
                            'POST',
                            '/api/tile/set',
                            array(),
                            array(),
                            array('CONTENT_TYPE' => 'application/json'),
                            '{"tile":{"id":"t.0.0","value":"2"}}');
        
        // tests sur le retour en json
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $decodedJson = json_decode($response->getContent()) ;
        $this->assertTrue(isset($decodedJson->error)) ;
        $this->assertEquals('t.0.0', $decodedJson->error->id) ;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testSetTileReturnsOK()
    {
        // créer une grille remplie
        $g4easy[0][1] = 2 ;
        $g4easy[1][0] = 3 ;
        $g4easy[2][2] = 2 ;
        $g4easy[3][1] = 3 ;
        $g4easy[3][2] = 4 ;
        $g4easy[3][3] = 1 ;
        
        // on initialise les objets en session
        $sudokuEntities = $this->client->getContainer()->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->dispatcher->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->dispatcher->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(4, $g4easy) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->dispatcher->dispatch(LoadGameEvent::NAME, $event) ;

        $crawler = $this->client->request(
                            'POST',
                            '/api/tile/set',
                            array(),
                            array(),
                            array('CONTENT_TYPE' => 'application/json'),
                            '{"tile":{"id":"t.1.3","value":"2"}}');
        
        // tests sur le retour en json
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $decodedJson = json_decode($response->getContent()) ;
        $this->assertFalse(isset($decodedJson->error)) ;

        foreach($decodedJson->grid->tiles as $tile)
        {
            if($tile->id == 't.1.3') {
                $this->assertEquals(2, $tile->value) ;
            }
        }
        // on vérifie que grid est rempli
        $this->assertEquals(4, $this->gridsession->getGrid()->getSize()) ;
        $this->assertEquals(9, $this->gridsession->getGrid()->getRemainingTiles()) ;
//        var_dump($this->tilessession->getTiles()->getTileset()) ;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testSetTileReturnsSolved()
    {
        // créer une grille remplie
        $g4easy[0][1] = 2 ;
        $g4easy[0][2] = 3 ;
        $g4easy[0][3] = 4 ;
        $g4easy[1][0] = 3 ;
        $g4easy[1][1] = 4 ;
        $g4easy[1][2] = 1 ;
        $g4easy[1][3] = 2 ;
        $g4easy[2][0] = 4 ;
        $g4easy[2][1] = 1 ;
        $g4easy[2][2] = 2 ;
        $g4easy[2][3] = 3 ;
        $g4easy[3][0] = 2 ;
        $g4easy[3][1] = 3 ;
        $g4easy[3][2] = 4 ;
        $g4easy[3][3] = 1 ;
        
        // on initialise les objets en session
        $sudokuEntities = $this->client->getContainer()->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->dispatcher->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->dispatcher->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(4, $g4easy) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->dispatcher->dispatch(LoadGameEvent::NAME, $event) ;

        $crawler = $this->client->request(
                            'POST',
                            '/api/tile/set',
                            array(),
                            array(),
                            array('CONTENT_TYPE' => 'application/json'),
                            '{"tile":{"id":"t.0.0","value":"1"}}');
        
        // tests sur le retour en json
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $decodedJson = json_decode($response->getContent()) ;
        $this->assertTrue(isset($decodedJson->solved)) ;
        $this->assertEquals(1, $decodedJson->solved->status) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testReloadGridRedirectToHomepage()
    {
        $this->client->request('GET', '/api/grid/reload');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testReloadGrid()
    {
        // créer une grille remplie
        $g4easy[0][1] = 2 ;
        $g4easy[1][0] = 3 ;
        $g4easy[2][2] = 2 ;
        $g4easy[3][1] = 3 ;
        $g4easy[3][2] = 4 ;
        $g4easy[3][3] = 1 ;
        
        // on initialise les objets en session
        $sudokuEntities = $this->client->getContainer()->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->dispatcher->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->dispatcher->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(4, $g4easy) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->dispatcher->dispatch(LoadGameEvent::NAME, $event) ;

        $setTile = new TileSet() ;
        $setTile->set(0, 0, 1) ;
        $event = new SetTileEvent($setTile) ;
        $this->dispatcher->dispatch(SetTileEvent::NAME, $event) ;
        
        $crawler = $this->client->request('GET', '/api/grid/reload');
        
        // tests sur le retour en json
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $decodedJson = json_decode($response->getContent()) ;

        foreach($decodedJson->grid->tiles as $tile)
        {
            if($tile->id == 't.0.0') {
                $this->assertNull($tile->value) ;
            }
        }
        // TODO
        // on vérifie que grid est rempli
        $this->assertEquals(4, $this->gridsession->getGrid()->getSize()) ;
        $this->assertEquals(10, $this->gridsession->getGrid()->getRemainingTiles()) ;
//        var_dump($this->tilessession->getTiles()->getTileset()) ;
//        var_dump($this->tilessession->getTiles()->getTilesToSolve()) ;
        $this->assertEquals(10, count($this->tilessession->getTiles()->getTilesToSolve())) ;
    }
//
//    /**
//     * @runInSeparateProcess
//     */
//    public function testReloadGrid()
//    {
//        // Créer une grille remplie
//        $array = array() ;
//        $array[0][2] = 2 ;
//        $array[0][5] = 9 ;
//        $array[0][6] = 1 ;
//        $array[0][8] = 6 ;
//        $array[1][0] = 3 ;
//        $array[1][2] = 5 ;
//        $array[1][4] = 4 ;
//        $array[1][6] = 2 ;
//        $array[2][1] = 7 ;
//        $array[2][2] = 9 ;
//        $array[2][3] = 2 ;
//        $array[2][4] = 6 ;
//        $array[3][1] = 5 ;
//        $array[3][7] = 1 ;
//        $array[3][8] = 9 ;
//        $array[4][1] = 2 ;
//        $array[4][2] = 1 ;
//        $array[4][3] = 9 ;
//        $array[4][4] = 7 ;
//        $array[4][5] = 5 ;
//        $array[4][6] = 8 ;
//        $array[4][7] = 4 ;
//        $array[5][0] = 9 ;
//        $array[5][1] = 8 ;
//        $array[5][7] = 2 ;
//        $array[6][4] = 9 ;
//        $array[6][5] = 1 ;
//        $array[6][6] = 7 ;
//        $array[6][7] = 6 ;
//        $array[7][2] = 4 ;
//        $array[7][4] = 5 ;
//        $array[7][6] = 3 ;
//        $array[7][8] = 1 ;
//        $array[8][0] = 7 ;
//        $array[8][2] = 6 ;
//        $array[8][3] = 3 ;
//        $array[8][6] = 9 ;  
//        
//        // on initialize les objets en session
//        // initialise grid et tiles
//        $this->grid->init(9) ;
//        $this->tiles->init(9) ;
//        $this->values->init(9) ;
//        $this->groups->init(9) ;
//        $this->grid->setTiles($array) ;
//        foreach($array as $row => $cols) {
//            foreach($cols as $col => $value) {
//                $this->values->add($value) ;
//                $this->tiles->set($row, $col, $value) ;
//            }
//        }
//        $this->gridsession->setGrid($this->grid) ;
//        $this->valuessession->setValues($this->values) ;
//        $this->tilessession->setTiles($this->tiles) ;
//        $this->groupssession->setGroups($this->groups) ;
//        
//        // TODO
//        // on remplit $tiles avec les cases jouées
//
////var_dump($this->grid) ;
////var_dump($this->values) ;
////var_dump($this->session->getTiles()) ;
//        
//        // on recharge
//        $crawler = $this->client->request('GET', '/api/grid/reload');
//        
//        // tests sur le retour en json
//        $response = $this->client->getResponse();
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
//
//        $mappedJson['grid'] = TilesMapper::toArray($this->tilessession->getTiles(), $this->valuessession->getValues()) ;
//        $this->assertEquals($response->getContent(), json_encode($mappedJson)) ;
//
//        // TODO
//        // il faut vérifier que grid est revenu à l'initial
//        $this->assertEquals(9, $this->gridsession->getGrid()->getSize()) ;
//        $this->assertEquals(81, $this->gridsession->getGrid()->getRemainingTiles()) ;
//        $this->assertEquals($array, $this->gridsession->getGrid()->getTiles()) ;
//        // TODO
//        // il faut vérifier que $values n'a pas changé
//        $this->assertEquals(9, $this->valuessession->getValues()->getSize()) ;
//        $this->assertEquals(9, count($this->valuessession->getValues()->getValues())) ;
//        // TODO
//        // il faut vérifier que $tiles est revenu à l'initial = $grid
//        $this->assertEquals(81, count($this->tilessession->getTiles()->getTileset())) ;
//        $this->assertEquals(9, $this->tilessession->getTiles()->getSize()) ;
//    }

    /**
     * @runInSeparateProcess
     */
    public function testResetGridRedirectToHomepage()
    {
        $this->client->request('GET', '/api/grid/reset');
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }    

    /**
     * @runInSeparateProcess
     */
    public function testResetGrid()
    {
        // créer une grille remplie
        $g4easy[0][1] = 2 ;
        $g4easy[1][0] = 3 ;
        $g4easy[2][2] = 2 ;
        $g4easy[3][1] = 3 ;
        $g4easy[3][2] = 4 ;
        $g4easy[3][3] = 1 ;
        
        // on initialise les objets en session
        $sudokuEntities = $this->client->getContainer()->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->dispatcher->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->dispatcher->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(4, $g4easy) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->dispatcher->dispatch(LoadGameEvent::NAME, $event) ;

        $setTile = new TileSet() ;
        $setTile->set(0, 0, 1) ;
        $event = new SetTileEvent($setTile) ;
        $this->dispatcher->dispatch(SetTileEvent::NAME, $event) ;
        $setTile = new TileSet() ;
        $setTile->set(1, 3, 2) ;
        $event = new SetTileEvent($setTile) ;
        $this->dispatcher->dispatch(SetTileEvent::NAME, $event) ;
        
        $crawler = $this->client->request('GET', '/api/grid/reset');

        // tests sur le retour en json
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;

        $responseArray = JsonMapper::toArray($response->getContent()) ;
        $this->assertTrue(is_array($responseArray)) ;
        $this->assertEquals(4, $responseArray['size']) ;
        $this->assertEquals(array(), $responseArray['tiles']) ;
    }

//    /**
//     * @runInSeparateProcess
//     */
//    public function testResetGrid()
//    {
//        // Créer une grille remplie
//        $array = array() ;
//        $array[0][2] = 2 ;
//        $array[0][5] = 9 ;
//        $array[0][6] = 1 ;
//        $array[0][8] = 6 ;
//        $array[1][0] = 3 ;
//        $array[1][2] = 5 ;
//        $array[1][4] = 4 ;
//        $array[1][6] = 2 ;
//        $array[2][1] = 7 ;
//        $array[2][2] = 9 ;
//        $array[2][3] = 2 ;
//        $array[2][4] = 6 ;
//        $array[3][1] = 5 ;
//        $array[3][7] = 1 ;
//        $array[3][8] = 9 ;
//        $array[4][1] = 2 ;
//        $array[4][2] = 1 ;
//        $array[4][3] = 9 ;
//        $array[4][4] = 7 ;
//        $array[4][5] = 5 ;
//        $array[4][6] = 8 ;
//        $array[4][7] = 4 ;
//        $array[5][0] = 9 ;
//        $array[5][1] = 8 ;
//        $array[5][7] = 2 ;
//        $array[6][4] = 9 ;
//        $array[6][5] = 1 ;
//        $array[6][6] = 7 ;
//        $array[6][7] = 6 ;
//        $array[7][2] = 4 ;
//        $array[7][4] = 5 ;
//        $array[7][6] = 3 ;
//        $array[7][8] = 1 ;
//        $array[8][0] = 7 ;
//        $array[8][2] = 6 ;
//        $array[8][3] = 3 ;
//        $array[8][6] = 9 ;  
//        
//        // on initialize les objets en session
//        // initialise grid et tiles
//        $this->grid->init(9) ;
//        $this->tiles->init(9) ;
//        $this->values->init(9) ;
//        $this->groups->init(9) ;
//        $this->grid->setTiles($array) ;
//        foreach($array as $row => $cols) {
//            foreach($cols as $col => $value) {
//                $this->values->add($value) ;
//                $this->tiles->set($row, $col, $value) ;
//            }
//        }
//        $this->gridsession->setGrid($this->grid) ;
//        $this->valuessession->setValues($this->values) ;
//        $this->tilessession->setTiles($this->tiles) ;
//        $this->groupssession->setGroups($this->groups) ;
//
//        // on réinitialise
//        $crawler = $this->client->request('GET', '/api/grid/reset');
//        
//        // tests sur le retour en json
//        $response = $this->client->getResponse();
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
//        
//        $responseArray = JsonMapper::toArray($response->getContent()) ;
//        $this->assertTrue(is_array($responseArray)) ;
//        $this->assertEquals(9, $responseArray['size']) ;
//        $this->assertEquals(array(), $responseArray['tiles']) ;
//        
//        // on vérifie que grid est vide mais on garde size
//        $this->assertEquals(9, $this->gridsession->getGrid()->getSize()) ;
//        $this->assertEquals(81, $this->gridsession->getGrid()->getRemainingTiles()) ;
//        // on vérifie que values est vide
//        $this->assertNull($this->valuessession->getValues()->getSize()) ;
//        $this->assertEquals(0, count($this->valuessession->getValues()->getValues())) ;
//        // on vérifie que tiles est vide mais on garde size
//        $this->assertEquals(81, count($this->tilessession->getTiles()->getTileset())) ;
//        $this->assertEquals(9, $this->tilessession->getTiles()->getSize()) ;
//    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveGrid()
    {
        $client = static::createClient();
        
//        // Créer une grille remplie
//        $json = '{"grid":{"size":9,"tiles":[{"id":"t.0.0","value":""},{"id":"t.0.1","value":""},{"id":"t.0.2","value":"2"},{"id":"t.0.3","value":""},{"id":"t.0.4","value":""},{"id":"t.0.5","value":"9"},{"id":"t.0.6","value":"1"},{"id":"t.0.7","value":""},{"id":"t.0.8","value":"6"},{"id":"t.1.0","value":"3"},{"id":"t.1.1","value":""},{"id":"t.1.2","value":"5"},{"id":"t.1.3","value":""},{"id":"t.1.4","value":"4"},{"id":"t.1.5","value":""},{"id":"t.1.6","value":"2"},{"id":"t.1.7","value":""},{"id":"t.1.8","value":""},{"id":"t.2.0","value":""},{"id":"t.2.1","value":"7"},{"id":"t.2.2","value":"9"},{"id":"t.2.3","value":"2"},{"id":"t.2.4","value":"6"},{"id":"t.2.5","value":""},{"id":"t.2.6","value":""},{"id":"t.2.7","value":""},{"id":"t.2.8","value":""},{"id":"t.3.0","value":""},{"id":"t.3.1","value":"5"},{"id":"t.3.2","value":""},{"id":"t.3.3","value":""},{"id":"t.3.4","value":""},{"id":"t.3.5","value":""},{"id":"t.3.6","value":""},{"id":"t.3.7","value":"1"},{"id":"t.3.8","value":"9"},{"id":"t.4.0","value":""},{"id":"t.4.1","value":"2"},{"id":"t.4.2","value":"1"},{"id":"t.4.3","value":"9"},{"id":"t.4.4","value":"7"},{"id":"t.4.5","value":"5"},{"id":"t.4.6","value":"8"},{"id":"t.4.7","value":"4"},{"id":"t.4.8","value":""},{"id":"t.5.0","value":"9"},{"id":"t.5.1","value":"8"},{"id":"t.5.2","value":""},{"id":"t.5.3","value":""},{"id":"t.5.4","value":""},{"id":"t.5.5","value":""},{"id":"t.5.6","value":""},{"id":"t.5.7","value":"2"},{"id":"t.5.8","value":""},{"id":"t.6.0","value":""},{"id":"t.6.1","value":""},{"id":"t.6.2","value":""},{"id":"t.6.3","value":""},{"id":"t.6.4","value":"9"},{"id":"t.6.5","value":"1"},{"id":"t.6.6","value":"7"},{"id":"t.6.7","value":"6"},{"id":"t.6.8","value":""},{"id":"t.7.0","value":""},{"id":"t.7.1","value":""},{"id":"t.7.2","value":"4"},{"id":"t.7.3","value":""},{"id":"t.7.4","value":"5"},{"id":"t.7.5","value":""},{"id":"t.7.6","value":"3"},{"id":"t.7.7","value":""},{"id":"t.7.8","value":"1"},{"id":"t.8.0","value":"7"},{"id":"t.8.1","value":""},{"id":"t.8.2","value":"6"},{"id":"t.8.3","value":"3"},{"id":"t.8.4","value":""},{"id":"t.8.5","value":""},{"id":"t.8.6","value":"9"},{"id":"t.8.7","value":""},{"id":"t.8.8","value":""}]}}' ;
        
        $crawler = $client->request('GET', '/api/grid/save');
        
        $this->assertTrue(true) ;
    }
    
}
