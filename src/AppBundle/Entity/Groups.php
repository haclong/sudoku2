<?php

namespace AppBundle\Entity;

use AppBundle\Utils\RegionGetter;
use RuntimeException;

/**
 * Description of Groups
 *
 * @author haclong
 */
class Groups implements InitInterface, ReloadInterface, ResetInterface {
//    public $valuesByGroup = array() ;
    protected $valuesByGrid = array() ;
    protected $tilesByGroup = array() ;
    protected $valuesByTile = array() ;
    protected $size ;

    public function init($size)
    {
        $this->size = $size ;
        // construit les colonnes / lignes / regions
        // répartit les id des cases par colonne / ligne / region
        $this->tilesByGroup = $this->setTilesByGroup($size) ;
        // par colonne / ligne / region, répartit les cases contenant les valeurs
//        $this->valuesByGroup = $this->setValuesByGroup($size) ;
        $this->valuesByGrid = $this->setValuesByGrid($size) ;
        $this->valuesByTile = $this->setValuesByTile($size) ;
    }
    
    public function reset()
    {
        $this->size = null ;
//        $this->valuesByGroup = array() ;
        $this->tilesByGroup = array() ;
        $this->valuesByGrid = array() ;
        $this->valuesByTile = array() ;
    }
    
    public function reload(Grid $grid)
    {
//        $this->valuesByGroup = $this->setValuesByGroup($grid->getSize()) ;
        $this->valuesByGrid = $this->setValuesByGrid($grid->getSize()) ;
        $this->valuesByTile = $this->setValuesByTile($grid->getSize()) ;
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
    public function getValuesByTile()
    {
        $valuesByTile = [] ;
        foreach($this->valuesByGrid as $index => $tiles)
        {
            foreach($tiles as $tile)
            {
                $valuesByTile[$tile][] = $index ;
            }
        }
        return $valuesByTile ;
    }
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
        return $this->tilesByGroup['region'][$index] ;
    }
    /**
     * Retourne la liste de tous les id des cases de la ligne numéro $index
     * @param int $index
     * @return array
     */
    protected function getTilesByRow($index)
    {
        return $this->tilesByGroup['row'][$index] ;
    }
    
    protected function setValuesByGrid($size)
    {
        $array = [] ;
        for($index = 0; $index < $size ; $index++)
        {
            for($row = 0; $row < $size ; $row++)
            {
                for($col = 0; $col < $size ; $col++)
                {
                    $array[$index][] = $row . "." . $col ;
                }
            }
        }
        return $array ;
    }
    // filtrer valuesByGrid par groupe
    protected function &filterValuesByGrid($tilesByGroup)
    {
        $array = [] ;
        if(is_null($tilesByGroup))
        {
            throw new RuntimeException("Tiles By Group undefined") ;
        }
        foreach($tilesByGroup as $tileByGroup)
        {
            foreach($this->valuesByGrid as $index => $tiles)
            {
                foreach($tiles as $tile)
                {
                    if($tile == $tileByGroup) 
                    {
                        $array[$index][] = $tile ;
                    }
                }
            }
        }
        return $array ;
    }
    protected function setValuesByTile($size)
    {
        $array = [] ;
        for($row=0; $row<$size; $row++)
        {
            for($col=0; $col<$size; $col++)
            {
                for($index=0; $index<$size; $index++)
                {
                    $array[$row.'.'.$col][] = $index ;
                }
            }
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
