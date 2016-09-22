<?php

namespace AppBundle\Entity;

use AppBundle\Utils\RegionGetter;

/**
 * Description of Groups
 *
 * @author haclong
 */
class Groups {
    protected $valuesByGroup = array() ;
    protected $tilesByGroup = array() ;
    protected $size ;
    
    public function init($size)
    {
        $this->size = $size ;
        // construit les colonnes / lignes / regions
        // répartit les id des cases par colonne / ligne / region
        $this->sortTilesByGroup($size) ;
        // par colonne / ligne / region, répartit les cases contenant les valeurs
        $this->buildValuesByGroup($size) ;
    }
    
    public function reset()
    {
        $this->size = null ;
        $this->valuesByGroup = array() ;
        $this->tilesByGroup = array() ;
    }
    
    public function reload()
    {
        $this->buildValuesByGroup($this->size) ;
    }
    
    public function setTile($row, $col, $value) 
    {
        
    }
    
    public function getCol($index)
    {
        return $this->valuesByGroup['col'][$index] ;
    }
    public function getRow($index)
    {
        return $this->valuesByGroup['row'][$index] ;
    }
    public function getRegion($index)
    {
        return $this->valuesByGroup['region'][$index] ;
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
    protected function buildValuesByGroup($size)
    {
        $this->valuesByGroup['col'] = $this->buildValuesByCols($size) ;
        $this->valuesByGroup['row'] = $this->buildValuesByRows($size) ;
        $this->valuesByGroup['region'] = $this->buildValuesByRegions($size) ;
    }
    /**
     * retourne la liste des id de cases par valeurs par colonne
     * @param int $size
     * @return array
     */
    protected function buildValuesByCols($size)
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
    protected function buildValuesByRows($size)
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
    protected function buildValuesByRegions($size)
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
    protected function sortTilesByGroup($size)
    {
        $this->buildGroup($size) ;
        $this->sortTiles($size) ;
    }
    protected function buildGroup($size)
    {
        for($i=0; $i<$size; $i++)
        {
            // initialize les 3 types de groupe : col, row et region
            // nb de col = taille de la grille
            // nb de row = taille de la grille
            // nb de région = taille de la grille
            $this->tilesByGroup['col'][$i] = [] ;
            $this->tilesByGroup['row'][$i] = [] ;
            $this->tilesByGroup['region'][$i] = [] ;
        }
    }
    protected function sortTiles($size)
    {
        for($row=0; $row<$size; $row++)
        {
            for($col=0; $col<$size; $col++)
            {
                $region = RegionGetter::getRegion($row, $col, $size) ;
                $this->tilesByGroup['col'][$row][] = $row.'.'.$col ;
                $this->tilesByGroup['row'][$col][] = $row.'.'.$col ;
                $this->tilesByGroup['region'][$region][] = $row.'.'.$col ;
            }
        }
    }
}
