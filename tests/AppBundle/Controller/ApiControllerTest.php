<?php

namespace Tests\AppBundle\Controller;

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

        $crawler = $client->request('GET', '/api/grid/get?size=test');
        $response = $client->getResponse();
//var_dump($crawler) ;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json')) ;
        $this->assertContains('{"getGrid":{"tiles":[{"id":"t.0.0","value":2},{"id":"t.2.5","value":8},{"id":"t.5.3","value":5}]}}', $response->getContent());
    }

}
