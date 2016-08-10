<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Exception\InvalidGridSizeException;
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

    public function testGrid()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/9');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Charger une grille', $crawler->filter('button#getGridButton')->text());
        $this->assertContains('t.8.8', $crawler->filter('td input')->last()->attr('id')) ;
    }
    
    public function testGridSizeException()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/7');
        $this->assertContains('Error', $crawler->filter('h1')->text());
    }
}
