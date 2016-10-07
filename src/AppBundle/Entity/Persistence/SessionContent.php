<?php

namespace AppBundle\Entity\Persistence;

use AppBundle\Persistence\IsReadyInterface;
use ArrayObject;

/**
 * Description of SessionContent
 *
 * @author haclong
 */
class SessionContent extends ArrayObject {
    public function add(IsReadyInterface $class)
    {
        $this->offsetSet(null, $class) ;
    }
}
