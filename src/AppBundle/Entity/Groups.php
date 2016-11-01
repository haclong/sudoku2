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
    protected $valuesByGridObject ;
    protected $size ;

    public function __construct(ArrayObject $arrayObject, ValuesByGrid $valuesByGrid)
    {
        $this->arrayObject = $arrayObject ;
        $this->valuesByGridObject = $valuesByGrid ;
    }
    
    public function init($size)
    {
        $this->size = $size ;
        // construit les colonnes / lignes / regions
        // répartit les id des cases par colonne / ligne / region
        $this->tilesByGroup = $this->setTilesByGroup($size) ;
        // par colonne / ligne / region, répartit les cases contenant les valeurs
        $this->valuesByGrid = $this->setValuesByGrid($size) ;
    }
    
    public function reset()
    {
        $this->size = null ;
        $this->tilesByGroup = array() ;
        $this->valuesByGrid = $this->valuesByGridObject ;
    }
    
    public function reload(Grid $grid)
    {
        $this->valuesByGrid = $this->setValuesByGrid($grid->getSize()) ;
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    public function getCol($index)
    {
        return $this->filterValuesByGrid($this->getTilesByCol($index)) ;
    }
    public function getRow($index)
    {
        return $this->filterValuesByGrid($this->getTilesByRow($index)) ;
    }
    public function getRegion($index)
    {
        return $this->filterValuesByGrid($this->getTilesByRegion($index)) ;
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
    public function getValuesByGrid()
    {
        return $this->valuesByGrid ;
    }
    public function getValuesByTile()
    {
        $valuesByTile = [] ;
        foreach($this->getValuesByGrid() as $index => $tiles)
        {
            foreach($tiles as $tile)
            {
                $valuesByTile[$tile][] = $index ;
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
    protected function filterValuesByGrid($tilesByGroup)
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
