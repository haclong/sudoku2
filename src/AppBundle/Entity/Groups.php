<?php

namespace AppBundle\Entity;

use ArrayObject;
use AppBundle\Entity\Groups\ValuesByGrid;
use AppBundle\Entity\Groups\ValuesByTile;
use AppBundle\Utils\RegionGetter;
use RuntimeException;

/**
 * Description of Groups
 *
 * @author haclong
 */
class Groups implements InitInterface, ReloadInterface, ResetInterface {
//    public $valuesByGroup = array() ;
    protected $valuesByGrid ;
    protected $arrayObject ;
    
    /**
     * liste des cases rangées par colonne / lignes / region
     * $tilesByGroup[##typeGroup##][##indexGroup##] = [##list<tileId>##]
     * $tilesByGroup['col'][0] = ['0.0', '1.0', '2.0', '3.0']
     * $tilesByGroup['row'][0] = ['0.0', '0.1', '0.2', '0.3']
     * $tilesByGroup['region'][0] = ['0.0', '0.1', '1.0', '1.1']
     * @var array
     */
    protected $tilesByGroup = array() ;
//    protected $valuesByTile ;
    protected $valuesByGridObject ;
    protected $valuesByTileObject ;
    protected $size ;

    public function __construct(ArrayObject $arrayObject, ValuesByGrid $valuesByGrid, ValuesByTile $valuesByTile)
    {
        $this->arrayObject = $arrayObject ;
        $this->valuesByGridObject = $valuesByGrid ;
        $this->valuesByTileObject = $valuesByTile ;
    }
    
    public function init($size)
    {
        $this->size = $size ;
        // construit les colonnes / lignes / regions
        // répartit les id des cases par colonne / ligne / region
        $this->tilesByGroup = $this->setTilesByGroup($size) ;
        // par colonne / ligne / region, répartit les cases contenant les valeurs
//        $this->valuesByGroup = $this->setValuesByGroup($size) ;
        $this->valuesByGrid = $this->setValuesByGrid($size) ;
//        $this->valuesByTile = $this->setValuesByTile($size) ;
    }
    
    public function reset()
    {
        $this->size = null ;
//        $this->valuesByGroup = array() ;
        $this->tilesByGroup = array() ;
        $this->valuesByGrid = $this->valuesByGridObject ;
//        $this->valuesByTile->exchangeArray(array()) ;
    }
    
    public function reload(Grid $grid)
    {
//        $this->valuesByGroup = $this->setValuesByGroup($grid->getSize()) ;
        $this->valuesByGrid = $this->setValuesByGrid($grid->getSize()) ;
//        $this->valuesByTile = $this->setValuesByTile($grid->getSize()) ;
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    public function &getCol($index)
    {
        return $this->filterValuesByGrid($this->getTilesByCol($index)) ;
//        return $this->valuesByGroup['col'][$index] ;
    }
    public function &getRow($index)
    {
        return $this->filterValuesByGrid($this->getTilesByRow($index)) ;
//        return $this->valuesByGroup['row'][$index] ;
    }
    public function &getRegion($index)
    {
        return $this->filterValuesByGrid($this->getTilesByRegion($index)) ;
//        return $this->valuesByGroup['region'][$index] ;
    }
    public function getImpactedTiles($row, $col)
    {
        $region = RegionGetter::getRegion($row, $col, $this->size) ;
        $tilesByCol = $this->getTilesByCol($col) ;
        $tilesByRow = $this->getTilesByRow($row) ;
        $tilesByRegion = $this->getTilesByRegion($region) ;
        $tiles = array_merge($tilesByCol, $tilesByRow, $tilesByRegion) ;
        return $tiles ;
    }
//    public function &getValuesByGroup()
//    {
//        return $this->valuesByGroup ;
//    }
    public function &getValuesByGrid()
    {
        return $this->valuesByGrid ;
    }
//    protected function setValuesByTile($size)
//    {
//        $array = clone $this->valuesByTileObject ;
//        for($row=0; $row<$size; $row++)
//        {
//            for($col=0; $col<$size; $col++)
//            {
//                $values = clone $this->arrayObject ;
//                for($index=0; $index<$size; $index++)
//                {
//                    $values->offsetSet(NULL, $index) ;
//                }
//                $array->offsetSet($row.'.'.$col, $values) ;
//            }
//        }
//        return $array ;
//    }
    public function getValuesByTile()
    {
//        echo "start : " ;
        $valuesByTile = [] ;
//        var_dump($valuesByTile) ;
        foreach($this->getValuesByGrid() as $index => $tiles)
        {
//            var_dump("index:" .$index) ;
//            echo "tiles:" ;
//            var_dump($tiles) ;
            foreach($tiles as $tile)
            {
//        var_dump("tile : ". $tile) ;
                $valuesByTile[$tile][] = $index ;
            }
            
        }
//        var_dump($valuesByTile) ;
        return $valuesByTile ;
//        echo "end : " ;
//        
//        foreach($valuesByTile as $tile => $indexes)
//        {
//            $values = clone $this->arrayObject ;
//            foreach($indexes as $index)
//            {
//                $values->offsetSet(NULL, $index) ;
//            }
//            $this->valuesByTileObject->offsetSet($tile, $values) ;
//        }
//        return $this->valuesByTileObject ;
    }
//    public function getValuesByTile()
//    {
//        return $this->valuesByTile ;
//    }
//    public function getValuesByTile()
//    {
//        $valuesByTile = [] ;
//        foreach($this->valuesByGroup as $type => $grouptype)
//        {
//            foreach($grouptype as $index => $group)
//            {
//                foreach($group as $value => $figure)
//                {
//                    foreach($figure as $key => $tileId)
//                    {
//                        $valuesByTile[$tileId][$type][] = $value ;
//                    }
//                }
//            }
//        }
//        return $valuesByTile ;
//    }

    // getTilesByGroup
    /**
     * Retourne la liste de tous les id des cases de la colonne numéro $index
     * @param int $index
     * @return array
     */
    protected function getTilesByCol($index)
    {
        if(isset($this->tilesByGroup['col'][$index]))
        {
            return $this->tilesByGroup['col'][$index] ;
        } else {
            return NULL ;
        }
    }
    /**
     * Retourne la liste de tous les id des cases de la region numéro $index
     * @param int $index
     * @return array
     */
    protected function getTilesByRegion($index)
    {
        if(isset($this->tilesByGroup['region'][$index]))
        {
            return $this->tilesByGroup['region'][$index] ;
        } else {
            return NULL ;
        }
    }
    /**
     * Retourne la liste de tous les id des cases de la ligne numéro $index
     * @param int $index
     * @return array
     */
    protected function getTilesByRow($index)
    {
        if(isset($this->tilesByGroup['row'][$index]))
        {
            return $this->tilesByGroup['row'][$index] ;
        } else {
            return NULL ;
        }
    }
    
    protected function setValuesByGrid($size)
    {
        $array = clone $this->valuesByGridObject ;
        for($index = 0; $index < $size ; $index++)
        {
            $tiles = clone $this->arrayObject ;
            for($row = 0; $row < $size ; $row++)
            {
                for($col = 0; $col < $size ; $col++)
                {
                    $tiles->offsetSet(NULL, $row . "." . $col) ;
                }
            }
            $array->offsetSet($index, $tiles) ;
        }
        return $array ;
    }
    // filtrer valuesByGrid par groupe
    protected function &filterValuesByGrid($tilesByGroup)
    {
        $array = clone $this->valuesByGridObject ;
        if(is_null($tilesByGroup))
        {
            throw new RuntimeException("Tiles By Group undefined") ;
        }
        
        foreach($this->valuesByGrid as $index => $tiles)
        {
            $tilesList = clone $this->arrayObject ;
            foreach($tiles as $tile)
            {
                if(in_array($tile, $tilesByGroup))
                {
                    $tilesList->offsetSet(NULL, $tile) ;
                }
            }
            $array->offsetSet($index, $tilesList) ;
        }
        return $array ;
    }
    
//    // set $valuesByGroup
//    protected function setValuesByGroup($size)
//    {
//        $array = [] ;
//        $array['col'] = $this->sortValuesByCols($size) ;
//        $array['row'] = $this->sortValuesByRows($size) ;
//        $array['region'] = $this->sortValuesByRegions($size) ;
//        
//        return $array ;
//    }
//    /**
//     * retourne la liste des id de cases par valeurs par colonne
//     * @param int $size
//     * @return array
//     */
//    protected function sortValuesByCols($size)
//    {
//        $array = [] ;
//        for($groupindex=0; $groupindex<$size; $groupindex++)
//        {
//            for($value=0; $value<$size; $value++)
//            {
//                $array[$groupindex][$value] = $this->getTilesByCol($groupindex) ;
//            }
//        }
//        return $array ;
//    }
//    /**
//     * retourne la liste des id de cases par valeurs par ligne
//     * @param int $size
//     * @return array
//     */
//    protected function sortValuesByRows($size)
//    {
//        $array = [] ;
//        for($groupindex=0; $groupindex<$size; $groupindex++)
//        {
//            for($value=0; $value<$size; $value++)
//            {
//                $array[$groupindex][$value] = $this->getTilesByRow($groupindex) ;
//            }
//        }
//        return $array ;
//    }
//    /**
//     * retourne la liste des id de cases par valeurs par region
//     * @param int $size
//     * @return array
//     */
//    protected function sortValuesByRegions($size)
//    {
//        $array = [] ;
//        for($groupindex=0; $groupindex<$size; $groupindex++)
//        {
//            for($value=0; $value<$size; $value++)
//            {
//                $array[$groupindex][$value] = $this->getTilesByRegion($groupindex) ;
//            }
//        }
//        return $array ;
//    }
    
    // set $tilesByGroup
    protected function setTilesByGroup($size)
    {
        $array = [] ;
        for($i=0; $i<$size; $i++)
        {
            // initialize les 3 types de groupe : col, row et region
            // nb de col = taille de la grille
            // nb de row = taille de la grille
            // nb de région = taille de la grille
            $array['col'][$i] = [] ;
            $array['row'][$i] = [] ;
            $array['region'][$i] = [] ;
        }

        for($row=0; $row<$size; $row++)
        {
            for($col=0; $col<$size; $col++)
            {
                $region = RegionGetter::getRegion($row, $col, $size) ;
                $array['col'][$col][] = $row.'.'.$col ;
                $array['row'][$row][] = $row.'.'.$col ;
                $array['region'][$region][] = $row.'.'.$col ;
            }
        }
        return $array ;
    }
}
