<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidFigureCountException;

/**
 * Description of Values
 *
 * @author haclong
 */
class Values {
    protected $gridSize ;
    protected $values = array() ;
    
    public function setGridSize($gridSize) {
        $this->gridSize = $gridSize;
    }

    public function add($value)
    {
        if(!in_array($value, $this->values))
        {
            if(count($this->values) >= $this->gridSize)
            {
                throw new InvalidFigureCountException('Maximum allowed figure number reached : ' .$this->gridSize) ;
            }
            $this->values[] = $value ;
        }    
//        if(!in_array($value, $this->values))
//        {
//            $this->values[] = $value ;
//        }    
    }
    
    public function getGridSize()
    {
        return $this->gridSize ;
    }
    
    public function getValues()
    {
        return $this->values ;
    }
    
    public function reset()
    {
        $this->values = array() ;
        //$this->gridSize = null ;
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
