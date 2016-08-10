<?php

namespace AppBundle\Utils;

/**
 * Description of RegionGetter
 *
 * @author haclong
 */
class RegionGetter {
//    // taille de la grille
//    protected $gridSize ;
//
//    public function setGridSize($gridSize) {
//        $this->gridSize = $gridSize;
//    }
//
    /**
     * Set the region number using the size of the grid, the column number and the row number of the case
     *
     * @param int $col Col number
     * @param int $row Row number
     *
     * @return int Region number
     */
    public static function getRegion($row, $col, $gridSize)
    {
	$region = 0 ;
	$sqrt = sqrt($gridSize) ;
	
	// Identify which part of the grid the row belong to
        $row_region = floor(($row / $gridSize) * $sqrt) ;
        
        // Identify which part of the grid the column belongs to
	$col_region = floor(($col / $gridSize) * $sqrt) ;

        // Identify region number
        $region = ($row_region * $sqrt) + $col_region ;
	return (int) $region ;
    }
}
