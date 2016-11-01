<?php

namespace Tests\AppBundle\Persistence;

use AppBundle\Persistence\GroupsSession;

/**
 * Description of GroupsSessionTest
 *
 * @author haclong
 */
class GroupsSessionTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected function setUp() 
    {
        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                        ->getMock() ;
    }

    public function testGetGroupsCallsSessionGet()
    {
        $this->session->expects($this->once())
                ->method('get')
                ->with($this->equalTo('groups')) ;
        $groupsSession = new GroupsSession($this->session) ;
        $groupsSession->getGroups() ;
    }
        
    public function testGetGroupsReturnsGroups()
    {
        $groups = $this->getMockBuilder('AppBundle\Entity\Groups')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($groups));
        $groupsSession = new GroupsSession($this->session) ;
        $groupsSession->setGroups($groups) ;
        $this->assertEquals($groups, $groupsSession->getGroups()) ;
    }

    public function testSetGroupsCallsSessionSet()
    {
        $groups = $this->getMockBuilder('AppBundle\Entity\Groups')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('set')
                ->with($this->equalTo('groups'), $groups) ;
        $groupsSession = new GroupsSession($this->session) ;
        $groupsSession->setGroups($groups) ;
    }
    
    public function testGroupsStoredReturnTrue()
    {
        $groups = $this->getMockBuilder('AppBundle\Entity\Groups')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->session->method('get')
                      ->with('groups')
                      ->willReturn($groups) ;
        $groupsSession = new GroupsSession($this->session) ;
        $this->assertTrue($groupsSession->isReady()) ;
    }
    
    public function testGroupsNotStoredReturnFalse()
    {
        $this->session->method('get')
                      ->with('groups')
                      ->willReturn(false) ;
        $groupsSession = new GroupsSession($this->session) ;
        $this->assertFalse($groupsSession->isReady()) ;
    }
}
