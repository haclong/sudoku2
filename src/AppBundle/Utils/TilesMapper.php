<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Tiles;
use AppBundle\Entity\Values;

/**
 * Description of TilesMapper
 *
 * @author haclong
 */
class TilesMapper {
    /**
     * @param Tiles
     * @return array 
     */
    public static function toArray(Tiles $tiles, Values $values)
    {
        $array = array() ;
        $array['size'] = $tiles->getSize() ;

        $tileset = array() ;
        foreach($tiles->getTileset() as $tile)
        {
            $tileset[] = array('id' => 't.'.$tile->getRow().'.'.$tile->getCol(), 'value' => $values->getValueByKey($tile->getValue())) ;
        }
        $array['tiles'] = $tileset ;
        return $array ;
    }
}
        
