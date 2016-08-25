<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Grid;

/**
 * Description of GridMapper
 *
 * @author haclong
 */
class GridMapper {
    /**
     * 
     * @param Grid
     * @return array
     */
    public static function toArray(Grid $grid)
    {
        $array = array() ;
        $array['size'] = $grid->getSize() ;
        
        $tiles = array() ;
        foreach($grid->getTiles() as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $tiles[] = array('id' => 't.'.$row.'.'.$col, 'value' => $value) ;
            }
        }
        $array['tiles'] = $tiles ;
        return $array ;
    }
}