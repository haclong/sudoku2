<?php

namespace AppBundle\Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of ClearGridEvent
 *
 * @author haclong
 */
class ClearGridEvent extends Event {
    const NAME = 'grid.clear' ;
}
