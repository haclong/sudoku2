<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of DebugControllerTest
 *
 * @author haclong
 */
class DebugControllerTest extends WebTestCase {
    
    public function testDebugPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/debug');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Debug', $crawler->filter('h1')->text());
    }
}
