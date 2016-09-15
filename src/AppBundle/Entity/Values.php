<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidFigureCountException;

/**
 * Description of Values
 *
 * @author haclong
 */
class Values {
    protected $size ;
    protected $values = array() ;
    
    public function setSize($size) {
        $this->size = $size;
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
//        if(!in_array($value, $this->values))
//        {
//            $this->values[] = $value ;
//        }    
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    
    public function getValues()
    {
        return $this->values ;
    }
    
    public function reset()
    {
        $this->values = array() ;
        //$this->size = null ;
    }
    
    public function getValueByKey($key)
    {
        return $this->values[$key] ;
    }
    
    public function getKeyByValue($value)
    {
        $array = array_flip($this->values) ;
        return $array[$value] ;
    }
}
