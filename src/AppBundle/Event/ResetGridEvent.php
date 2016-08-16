<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Description of ReloadGridEvent
 *
 * @author haclong
 */
class ResetGridEvent extends Event {
    const NAME = 'grid.reset' ;
}
