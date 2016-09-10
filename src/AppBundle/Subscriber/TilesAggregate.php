<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Tiles;
use AppBundle\Utils\SudokuSession;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of TilesAggregate
 *
 * @author haclong
 */
class TilesAggregate implements EventSubscriberInterface{
    protected $session ;
    
    public function __construct(SudokuSession $session)
    {
        $this->session = $session ;
    }
    
    public static function getSubscribedEvents() {
        return array(
//            LoadGameEvent::NAME => 'onLoadGame',
        ) ;
    }
    
    protected function getTilesFromSession() {
        return $this->session->getTiles() ;
    }
    protected function storeTiles(Tiles $tiles) {
        $this->session->setTiles($tiles) ;
    }
    
//    public function onLoadGame(LoadGameEvent $event) {
//        $grid = $this->getGridFromSession() ;
//
//        if($grid->getSize() != $event->getTiles()->getSize())
//        {
//            throw new RuntimeException('event grid size differs from session grid size') ;
//        }
//        $grid->setTiles($event->getTiles()->getTiles()) ;
//        $this->storeGrid($grid) ;
//    }
}
//<?php
//
//namespace AppBundle\Subscriber;
//
//use AppBundle\Entity\Tile;
//use AppBundle\Entity\Tiles;
//use AppBundle\Entity\Values;
//    
//    
//    public function onResetGrid() {
////        $this->session->resetValues() ;
//    }
//    
//    public function onGetGrid(GetGridEvent $event) {
//        $tiles = $this->getTilesFromSession() ;
//        $grid = $event->getGrid() ;
//        $size = $grid->getSize(); 
//        
//        for($row=0; $row<$size; $row++) 
//        {
//            for($col=0; $col<$size; $col++)
//            {
//                $tile = clone $this->tile ;
//                $tile->initialize($row, $col, $size) ;
//                $tiles->offsetSet($tile->getId(), $tile) ;
//            }
//        }
//        echo "coucou  " . __LINE__ ;
////        foreach($grid->getTiles() as $row=>$rowset) 
////        {
////            foreach($rowset as $col=> $value)
////            {
////                
////                echo $row.".".$col." = " .$value." - ".Values::getKeyByValues($value)."</br>" ; 
////            }
////            
////        }
////        var_dump($this->session->getGrid()) ;
////        echo get_class($this->session) ;
////        $this->storeTiles($tiles) ;
//    }
//}
