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
        foreach($tiles->getTileset() as $id => $value)
        {
            $tileset[] = array('id' => 't.'.$id, 'value' => $values->getValueByKey($value)) ;
        }
        $array['tiles'] = $tileset ;
        return $array ;
    }
}
        
