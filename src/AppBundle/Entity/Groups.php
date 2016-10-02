<?php

namespace AppBundle\Entity;
use AppBundle\Utils\RegionGetter;

/**
 * Description of Groups
 *
 * @author haclong
 */
class Groups implements InitInterface, ReloadInterface, ResetInterface {
    protected $valuesByGroup = array() ;
    protected $tilesByGroup = array() ;
    protected $size ;

    public function init($size)
    {
        $this->size = $size ;
        // construit les colonnes / lignes / regions
        // répartit les id des cases par colonne / ligne / region
        $this->tilesByGroup = $this->setTilesByGroup($size) ;
        // par colonne / ligne / region, répartit les cases contenant les valeurs
        $this->valuesByGroup = $this->setValuesByGroup($size) ;
    }
    
    public function reset()
    {
        $this->size = null ;
        $this->valuesByGroup = array() ;
        $this->tilesByGroup = array() ;
    }
    
    public function reload(Grid $grid)
    {
        $this->valuesByGroup = $this->setValuesByGroup($grid->getSize()) ;
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    public function &getCol($index)
    {
        return $this->valuesByGroup['col'][$index] ;
    }
    public function &getRow($index)
    {
        return $this->valuesByGroup['row'][$index] ;
    }
    public function &getRegion($index)
    {
        return $this->valuesByGroup['region'][$index] ;
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
    public function &getValuesByGroup()
    {
        return $this->valuesByGroup ;
    }
    public function getValuesByTile()
    {
        $valuesByTile = [] ;
        foreach($this->valuesByGroup as $type => $grouptype)
        {
            foreach($grouptype as $index => $group)
            {
                foreach($group as $value => $figure)
                {
                    foreach($figure as $key => $tileId)
                    {
                        $valuesByTile[$tileId][$type][] = $value ;
                    }
                }
            }
        }
        return $valuesByTile ;
    }

    // getTilesByGroup
    /**
     * Retourne la liste de tous les id des cases de la colonne numéro $index
     * @param int $index
     * @return array
     */
    protected function getTilesByCol($index)
    {
        return $this->tilesByGroup['col'][$index] ;
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
    
    // set $valuesByGroup
    protected function setValuesByGroup($size)
    {
        $array = [] ;
        $array['col'] = $this->sortValuesByCols($size) ;
        $array['row'] = $this->sortValuesByRows($size) ;
        $array['region'] = $this->sortValuesByRegions($size) ;
        
        return $array ;
    }
    /**
     * retourne la liste des id de cases par valeurs par colonne
     * @param int $size
     * @return array
     */
    protected function sortValuesByCols($size)
    {
        $array = [] ;
        for($groupindex=0; $groupindex<$size; $groupindex++)
        {
            for($value=0; $value<$size; $value++)
            {
                $array[$groupindex][$value] = $this->getTilesByCol($groupindex) ;
            }
        }
        return $array ;
    }
    /**
     * retourne la liste des id de cases par valeurs par ligne
     * @param int $size
     * @return array
     */
    protected function sortValuesByRows($size)
    {
        $array = [] ;
        for($groupindex=0; $groupindex<$size; $groupindex++)
        {
            for($value=0; $value<$size; $value++)
            {
                $array[$groupindex][$value] = $this->getTilesByRow($groupindex) ;
            }
        }
        return $array ;
    }
    /**
     * retourne la liste des id de cases par valeurs par region
     * @param int $size
     * @return array
     */
    protected function sortValuesByRegions($size)
    {
        $array = [] ;
        for($groupindex=0; $groupindex<$size; $groupindex++)
        {
            for($value=0; $value<$size; $value++)
            {
                $array[$groupindex][$value] = $this->getTilesByRegion($groupindex) ;
            }
        }
        return $array ;
    }
    
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
