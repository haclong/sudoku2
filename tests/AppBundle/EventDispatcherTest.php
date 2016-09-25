<?php

namespace Tests\AppBundle;

use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetTileEvent;
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
    public function testInitGameSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(InitGameEvent::NAME, 3) ;
    }

    /**
     * @runInSeparateProcess
     */
    public function testChooseGameSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(ChooseGameEvent::NAME, 2) ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testLoadGameSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(LoadGameEvent::NAME, 3) ;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testReloadGameSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(ReloadGameEvent::NAME, 2) ;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testResetGameSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(ResetGameEvent::NAME, 3) ;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testSetTileSubscribersAdded()
    {
        $this->AreSubscriberAddedByEvent(SetTileEvent::NAME, 1) ;
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
