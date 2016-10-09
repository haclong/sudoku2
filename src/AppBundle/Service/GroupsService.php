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
        
        // écarter le numéro de toutes les cases de la grille
        $this->discard($groups->getValuesByGroup(), $index, array_unique($impactedTiles)) ;
        
        // vérifier qu'il n'y a pas de dernières valeurs dans le groupe
        $this->checkLastValueInGroup($groups) ;
        
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
        if(!array_key_exists($index, $array))
        {
            throw new AlreadySetTileException($index . " is already set in " . $id) ;
        }
    }
    
    // pouvoir écarter un chiffre d'une case
//    public function discard($groups, $value, $row, $col)
//    {
//        
//    }
    protected function discard(&$groups, $index, $impactedTiles)
    {
        // col, row, region
        foreach($groups as $type => &$group)
        {
            foreach($group as $groupid => &$figure)
            {
                foreach($figure as $key => &$tiles)
                {
                    if($key == $index)
                    {
//                        echo $type . "::" . $groupid . "::" . $key . "::";
                        foreach($impactedTiles as $impactedTile)
                        {
                            $flippedTiles = array_flip($tiles) ;
                            unset($flippedTiles[$impactedTile]) ;
                            $tiles = array_flip($flippedTiles) ;
                        }
                    }
                }
            }
        }
    }
    
    protected function checkLastValueInGroup($groups)
    {
        foreach($groups->getValuesByGroup() as $type => $group)
        {
            foreach($group as $groupid => $figure)
            {
                foreach($figure as $index => $tileId)
                {
                    if(count($tileId) == 1)
                    {
                        // dispatch lastvalueingroup ;
                        //$array[$type][$groupid][$value] = count($tileId) ;
                        //$tileId = id de la case
                        //$index = index values
                        $tile = explode('.', current($tileId)) ;
                        $this->deduceTileEvent->getTile()->set($tile[0], $tile[1], $index) ;
                        $this->dispatcher->dispatch(DeduceTileEvent::NAME, $this->deduceTileEvent) ;
                    }
                }
            }
        }
    }
    protected function checkLastValueInTile($groups)
    {
        foreach($groups->getValuesByTile() as $tileId => $datas)
        {
            if((count($datas['col']) != count($datas['row'])) && (count($datas['col']) != count($datas['region'])))
            {
                throw new Exception() ;
            }
            if(count($datas['col']) == 1)
            {
                // $tileId = id de la case
                // $datas['col'][0] = dernière valeur de la case
                // dispatch last value in tile ;
                $tile = explode('.', $tileId) ;
                $this->deduceTileEvent->getTile()->set($tile[0], $tile[1], $datas['col'][0]) ;
                $this->dispatcher->dispatch('tile.deduce', $this->deduceTileEvent) ;
            }
        }
    }
}