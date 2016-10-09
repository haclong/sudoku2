<?php

namespace AppBundle\Subscriber;

use AppBundle\Event\SetTileEvent;
use AppBundle\Persistence\GroupsSession;
use AppBundle\Persistence\ValuesSession;
use AppBundle\Service\GroupsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of SetTileSubscriber
 *
 * @author haclong
 */
class SetTileSubscriber implements EventSubscriberInterface {
    protected $groupsSession ;
    protected $service ;
    protected $valuesSession ;
    
    public function __construct(GroupsSession $session, ValuesSession $values, GroupsService $service)
    {
        $this->groupsSession = $session ;
        $this->valuesSession = $values ;
        $this->service = $service ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            SetTileEvent::NAME => 'onSetTile'
        ) ;
    }
    
    public function onSetTile(SetTileEvent $event)
    {
        $groups = $this->groupsSession->getGroups() ;
        $values = $this->valuesSession->getValues() ;
        $tile = $event->getTile() ;
        $value = $tile->getValue() ;

        // add value in values entity
        if(is_null($values->getKeyByValue($value)))
        {
            $values->add($value) ;
        }
        
        // get values index
        $index = $values->getKeyByValue($value) ;
        
        $this->service->set($groups, $index, $tile->getRow(), $tile->getCol()) ;
        
        // save values
        $this->valuesSession->setValues($values) ;
        $this->groupsSession->setGroups($groups) ;
    }
}