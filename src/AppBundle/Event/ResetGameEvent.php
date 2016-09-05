<?php

namespace AppBundle\Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of ResetGameEvent
 *
 * @author haclong
 */
class ResetGameEvent extends Event {
    const NAME = 'game.reset' ;
}
