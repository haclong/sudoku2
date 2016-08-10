<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidFigureCountException;

/**
 * Description of Value
 *
 * @author haclong
 */
class Value {
    protected $gridSize ;
    protected $values = array() ;
    
    public function setGridSize($gridSize) {
        $this->gridSize = $gridSize;
    }

    public function add($value)
    {
        if(count($this->values) >= $this->gridSize)
        {
            throw new InvalidFigureCountException('Maximum allowed figure number reached : ' .$this->gridSize) ;
        }
        if(!in_array($value, $this->values))
        {
            $this->values[] = $value ;
        }    
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
