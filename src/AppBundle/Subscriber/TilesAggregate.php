<?php

namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of TilesAggregate
 *
 * @author haclong
 */
class TilesAggregate implements EventSubscriberInterface{
    protected $session ;
    
    public function __construct(Session $session)
    {
        $this->session = $session ;
    }
    
    public static function getSubscribedEvents() {
        return array(
//            LoadGameEvent::NAME => 'onLoadGame',
        ) ;
    }
    
    protected function getTilesFromSession() {
        return $this->session->get('tiles') ;
    }
    protected function storeTiles(Tiles $tiles) {
        $this->session->set('tiles', $tiles) ;
    }
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
