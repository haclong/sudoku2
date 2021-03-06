<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidFigureCountException;

/**
 * Description of Values
 *
 * @author haclong
 */
class Values implements InitInterface, ResetInterface {
    protected $size ;
    protected $values = [] ;
    
    public function init($size)
    {
        $this->size = $size;
        $this->values = [] ;
    }
    public function reset()
    {
        $this->size = null ;
        $this->values = [] ;
    }

    public function add($value)
    {
        if(!in_array($value, $this->values))
        {
            if(count($this->values) >= $this->size)
            {
                throw new InvalidFigureCountException('Maximum allowed figure number reached : ' .$this->size) ;
            }
            $this->values[] = $value ;
        }    
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    
    public function getValues()
    {
        return $this->values ;
    }
    
    public function getValueByKey($key)
    {
        if(array_key_exists($key, $this->values)) {
            return $this->values[$key] ;
        }
        return null ;
    }
    
    public function getKeyByValue($value)
    {
        $array = array_flip($this->values) ;
        if(array_key_exists($value, $array))
        {
            return $array[$value] ;
        }
        return null ;
    }
}
