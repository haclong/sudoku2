<?php

namespace AppBundle\Service;

use AppBundle\Event\DeduceTileEvent;
use AppBundle\Event\ValidateTileSetEvent;
use AppBundle\Exception\AlreadySetTileException;
use AppBundle\Utils\RegionGetter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use \Exception ;

/**
 * Description of GroupsService
 *
 * @author haclong
 */
class GroupsService {
    /**
     * le gestionnaire d'événement
     * nécessaire parce qu'on déclenche un événement chaque fois 
     * que la case est trouvée
     * @var EventDispatcherInterface
     */
    protected $dispatcher ;

    /**
     *
     * @var ValidateTileSetEvent
     */
    protected $validateTileSetEvent ;
    
    /**
     *
     * @var DeduceTileEvent
     */
    protected $deduceTileEvent ;

    /**
     * 
     * @param EventDispatcherInterface $eventDispatcher
     * @param ValidateTileSetEvent $validateTileSetEvent
     * @param DeduceTileEvent $deduceTileEvent
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, 
                                ValidateTileSetEvent $validateTileSetEvent,
                                DeduceTileEvent $deduceTileEvent)
    {
        $this->dispatcher = $eventDispatcher ;
        $this->validateTileSetEvent = $validateTileSetEvent ;
        $this->deduceTileEvent = $deduceTileEvent ;
    }

    // pouvoir écarter un chiffre de toutes les cases
    public function set($groups, $index, $row, $col)
    {
        // vérifier que la valeur n'apparaît pas déjà sur une autre case dans le même groupe
        $this->checkAlreadySetTile($groups, $index, $row, $col) ;
        
        // envoyer un événement pour valider le choix du numéro
        $this->validateTileSetEvent->getTile()->set($row, $col, $index) ;
        $this->dispatcher->dispatch(ValidateTileSetEvent::NAME, $this->validateTileSetEvent) ;

        // trouver toutes les cases impactées par le choix d'une valeur dans une case
        $impactedTiles = $groups->getImpactedTiles($row, $col) ;
     
        // écarter tous les numéros de la case
        $this->discardValuesInTile($groups, $row.'.'.$col) ;
        
        // écarter le numéro de toutes les cases de la grille
        $this->discard($groups->getValuesByGrid()->offsetGet($index), array_unique($impactedTiles)) ;
        
        // vérifier qu'il n'y a pas de dernières valeurs dans le groupe
        $this->checkLastValueInGroups($groups) ;
        
        // vérifier qu'il n'y a pas de dernière valeur dans la case
        $this->checkLastValueInTile($groups) ;
    }
    
    protected function checkAlreadySetTile($groups, $index, $row, $col)
    {
        $region = RegionGetter::getRegion($row, $col, $groups->getSize()) ;
        // vérifier que la valeur n'a pas déjà été assignée
        $this->checkValue($groups->getRow($row), $index, "row.".$row) ;
        $this->checkValue($groups->getCol($col), $index, "col.".$col) ;
        $this->checkValue($groups->getRegion($region), $index, "region.".$region) ;
    }
    protected function checkValue($array, $index, $id)
    {
        if($array->offsetExists($index) && count($array->offsetGet($index)) == 0)
        {
            throw new AlreadySetTileException($index . " is already set in " . $id) ;
        }
    }
    
    protected function discardValuesInTile($groups, $tileId)
    {
        foreach($groups->getValuesByGrid() as $index => $tiles)
        {
            $key = $this->getKey($tiles, $tileId) ;
            if(!is_null($key))
            {
                $groups->getValuesByGrid()->offsetGet($index)->offsetUnset($key) ;
            }
        }
    } 
    
    // pouvoir écarter un chiffre d'une case
//    protected function discard($tilesForIndex, $impactedTiles)
    protected function discard($tilesForIndex, $impactedTiles)
    {
        $keyToRemove = [] ;
        foreach($tilesForIndex as $key => $tile)
        {
            if(in_array($tile, $impactedTiles))
            {
                $keyToRemove[] = $key ;
            }
        }
        foreach($keyToRemove as $key)
        {
            $tilesForIndex->offsetUnset($key) ;
        }
    }
    
    protected function checkLastValueInGroups($groups)
    {
        for($index = 0; $index < $groups->getSize(); $index++)
        {
            $this->checkLastValueInGroup($groups->getCol($index)) ;
            $this->checkLastValueInGroup($groups->getRow($index)) ;
            $this->checkLastValueInGroup($groups->getRegion($index)) ;
        }
    }

    protected function checkLastValueInGroup($valuesByGrid)
    {
        foreach($valuesByGrid as $index => $tiles)
        {
            if(count($tiles) == 1)
            {
                $tile = explode('.', $tiles->getIterator()->current()) ;
                $this->deduceTileEvent->getTile()->set($tile[0], $tile[1], $index) ;
                $this->dispatcher->dispatch(DeduceTileEvent::NAME, $this->deduceTileEvent) ;
            }
        }
    }

    protected function checkLastValueInTile($groups)
    {
        foreach($groups->getValuesByTile() as $tileId => $datas)
        {
            if(count($datas) == 1)
            {
                // $tileId = id de la case
                // dispatch value in tile ;
                $tile = explode('.', $tileId) ;
//                $index = $datas->getIterator()->current() ;
                $index = current($datas) ;
                $this->deduceTileEvent->getTile()->set($tile[0], $tile[1], $index) ;
                $this->dispatcher->dispatch(DeduceTileEvent::NAME, $this->deduceTileEvent) ;
            }
        }
    }
    protected function getKey($arrayObject, $target)
    {
        foreach($arrayObject as $key => $value)
        {
            if($target == $value)
            {
                return $key ;
            }
        }
    }
}