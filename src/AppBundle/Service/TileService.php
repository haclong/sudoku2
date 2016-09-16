<?php

namespace AppBundle\Service;

use AppBundle\Entity\Values;
use AppBundle\Event\DeduceTileEvent;
use AppBundle\Event\SetTileEvent;
use AppBundle\Exception\InvalidFigureCountException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Description of TileService
 *
 * @author haclong
 */
class TileService {
    /**
     * le gestionnaire d'événement
     * nécessaire parce qu'on déclenche un événement chaque fois 
     * que la case est trouvée
     * @var EventDispatcherInterface
     */
    protected $dispatcher ;
    
    /**
     * liste des values (index => valeur)
     * @var Values
     */
    protected $values ;
    
    protected $deduceTileEvent ;
    protected $setTileEvent ;
    
    /**
     * 
     * @param EventDispatcherInterface $eventDispatcher
     * @param SetTileEvent $setTileEvent
     * @param deduceTileEvent $deduceTileEvent
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, 
                                SetTileEvent $setTileEvent,
                                DeduceTileEvent $deduceTileEvent)
    {
        $this->dispatcher = $eventDispatcher ;
        $this->setTileEvent = $setTileEvent ;
        $this->deduceTileEvent = $deduceTileEvent ;
    }
    
    public function setValues(Values $values)
    {
        $this->values = $values ;
    }
    
    public function discard($tile, $figure) 
    {
        $value = $this->values->getKeyByValue($figure) ;
        if(in_array($value, $tile->getMaybeValues())) {
            $tile->discard($value) ;
        }
        
        $this->checkFiguresCount($tile) ;
        $this->checkOnePossibilityLast($tile) ;
    }
    
    public function set($tile, $figure) 
    {
        $value = $this->values->getKeyByValue($figure) ;
        $tile->set($value) ;

        $this->checkFiguresCount($tile) ;
        
        $this->setTileEvent->getTile()->set($tile->getRow(), $tile->getCol(), $tile->getRegion(), $tile->getValue()) ;

        $this->dispatcher->dispatch('tile.set', $this->setTileEvent) ;
    }

    protected function checkFiguresCount($tile) 
    {
        $i = 0 ;
        
        $i += count($tile->getMaybeValues()) ;
        
        $i += count($tile->getDiscardValues()) ;
        
        if(!is_null($tile->getValue()))
        {
            $i++ ;
        }
        
        if(!$i == $tile->getSize()) {
            throw new InvalidFigureCountException() ;
        }
    }
    
    protected function checkOnePossibilityLast($tile) 
    {
        if(count($tile->getMaybeValues()) == 1) 
        {
            $this->deduceTileEvent->getTile()->set($tile->getRow(), $tile->getCol(), $tile->getRegion(), current($tile->getMaybeValues())) ;

            $this->dispatcher->dispatch('tile.lastPossibility', $this->deduceTileEvent) ;
        }
    }
}

