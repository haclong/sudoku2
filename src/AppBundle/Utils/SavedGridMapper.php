<?php

namespace AppBundle\Utils;

use AppBundle\Entity\View\SavedGrid;

/**
 * Description of SavedGridMapper
 *
 * @author haclong
 */
class SavedGridMapper {
    /**
     * 
     * @param json $json {"grid":{"size":9,"tiles":[{"id":"t.0.0","value":""},{"id":"t.0.1","value":""},{"id":"t.0.2","value":"2"},{"id":"t.0.3","value":""},{"id":"t.0.4","value":""},{"id":"t.0.5","value":"9"},{"id":"t.0.6","value":"1"},{"id":"t.0.7","value":""},{"id":"t.0.8","value":"6"},{"id":"t.1.0","value":"3"},{"id":"t.1.1","value":""},{"id":"t.1.2","value":"5"},{"id":"t.1.3","value":""},{"id":"t.1.4","value":"4"},{"id":"t.1.5","value":""},{"id":"t.1.6","value":"2"},{"id":"t.1.7","value":""},{"id":"t.1.8","value":""},{"id":"t.2.0","value":""},{"id":"t.2.1","value":"7"},{"id":"t.2.2","value":"9"},{"id":"t.2.3","value":"2"},{"id":"t.2.4","value":"6"},{"id":"t.2.5","value":""},{"id":"t.2.6","value":""},{"id":"t.2.7","value":""},{"id":"t.2.8","value":""},{"id":"t.3.0","value":""},{"id":"t.3.1","value":"5"},{"id":"t.3.2","value":""},{"id":"t.3.3","value":""},{"id":"t.3.4","value":""},{"id":"t.3.5","value":""},{"id":"t.3.6","value":""},{"id":"t.3.7","value":"1"},{"id":"t.3.8","value":"9"},{"id":"t.4.0","value":""},{"id":"t.4.1","value":"2"},{"id":"t.4.2","value":"1"},{"id":"t.4.3","value":"9"},{"id":"t.4.4","value":"7"},{"id":"t.4.5","value":"5"},{"id":"t.4.6","value":"8"},{"id":"t.4.7","value":"4"},{"id":"t.4.8","value":""},{"id":"t.5.0","value":"9"},{"id":"t.5.1","value":"8"},{"id":"t.5.2","value":""},{"id":"t.5.3","value":""},{"id":"t.5.4","value":""},{"id":"t.5.5","value":""},{"id":"t.5.6","value":""},{"id":"t.5.7","value":"2"},{"id":"t.5.8","value":""},{"id":"t.6.0","value":""},{"id":"t.6.1","value":""},{"id":"t.6.2","value":""},{"id":"t.6.3","value":""},{"id":"t.6.4","value":"9"},{"id":"t.6.5","value":"1"},{"id":"t.6.6","value":"7"},{"id":"t.6.7","value":"6"},{"id":"t.6.8","value":""},{"id":"t.7.0","value":""},{"id":"t.7.1","value":""},{"id":"t.7.2","value":"4"},{"id":"t.7.3","value":""},{"id":"t.7.4","value":"5"},{"id":"t.7.5","value":""},{"id":"t.7.6","value":"3"},{"id":"t.7.7","value":""},{"id":"t.7.8","value":"1"},{"id":"t.8.0","value":"7"},{"id":"t.8.1","value":""},{"id":"t.8.2","value":"6"},{"id":"t.8.3","value":"3"},{"id":"t.8.4","value":""},{"id":"t.8.5","value":""},{"id":"t.8.6","value":"9"},{"id":"t.8.7","value":""},{"id":"t.8.8","value":""}]}}
     * @return SavedGrid
     */
    public static function fromJson($json)
    {
        $jsonContent = json_decode ($json) ;

        $tiles = array();
        foreach($jsonContent->grid->tiles as $tile)
        {
            $id = explode('.', $tile->id) ;
            $value = $tile->value ;
            $tiles[$id[1]][$id[2]] = $value ;
        }
        
        $grid = new SavedGrid() ;
        $grid->setSize($jsonContent->grid->size) ;
        $grid->setTiles($tiles) ;
        return $grid ;
    }
}