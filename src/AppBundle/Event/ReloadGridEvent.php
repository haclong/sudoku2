<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Description of ReloadGridEvent
 *
 * @author haclong
 */
class ReloadGridEvent extends Event {
    const NAME = 'grid.reload' ;
}
