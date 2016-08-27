<?php

namespace Tests\AppBundle;

use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of EventDispatcherTest
 * on teste si - dans l'application - il y a bien des subscribers pour chaque événement
 * @author haclong
 */
class EventDispatcherTest extends WebTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testGetGridSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(GetGridEvent::NAME, 1) ;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testResetGridSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(ResetGridEvent::NAME, 1) ;
    }

    protected function AreSubscriberAddedByEvent($event, $expected)
    {
        $client = static::createClient();
        $container = $client->getContainer() ;
        $dispatcher = $container->get('event_dispatcher') ;
        $this->assertTrue($dispatcher->hasListeners($event)) ;
        $this->assertEquals($expected, count($dispatcher->getListeners($event))) ;
        
    }
}
