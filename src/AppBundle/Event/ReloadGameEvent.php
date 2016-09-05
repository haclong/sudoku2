<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Description of ReloadGameEvent
 *
 * @author haclong
 */
class ReloadGameEvent extends Event {
    const NAME = 'game.reload' ;
}
