<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Tiles;

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
    public static function toArray(Tiles $tiles)
    {
        $array = array() ;
        $array['size'] = $tiles->getSize() ;

        $tileset = array() ;
        foreach($tiles->getTileset() as $tile)
        {
            $tileset[] = array('id' => 't.'.$tile->getRow().'.'.$tile->getCol(), 'value' => $tile->getValue()) ;
        }
        $array['tiles'] = $tileset ;
        return $array ;
    }
}
        
