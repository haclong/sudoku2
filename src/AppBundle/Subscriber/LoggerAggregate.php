<?php

namespace AppBundle\Subscriber;

use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\ValidateTileSetEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of LoggerAggregate
 *
 * @author haclong
 */
class LoggerAggregate implements EventSubscriberInterface {
    protected $logger ;
    
    public function __construct(Logger $logger) {
        $this->logger = $logger ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            SetGameEvent::NAME => 'onSetGame',
            InitGameEvent::NAME => 'onInitGame',
            LoadGameEvent::NAME => 'onLoadGame',
            ReloadGameEvent::NAME => array('onReloadGame', -10),
            ResetGameEvent::NAME => 'onResetGame',
            ValidateTileSetEvent::NAME => 'onValidatedTile',
        ) ;
    }
}
