<?php

namespace AppBundle\Entity\Persistence;

/**
 * Description of SessionContent
 *
 * @author haclong
 */
class SessionContent extends \ArrayObject {
    public function add($class)
    {
        $this->offsetSet(null, $class) ;
    }
}
