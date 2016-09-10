<?php

namespace AppBundle\Utils;

/**
 * Description of SessionMarker
 *
 * @author haclong
 */
class SessionMarker {
    protected $session ;
    protected $logger ;
    
    public function __construct($session, $logger) {
        $this->session = $session;
        $this->logger = $logger;
    }

    public function logSession($mark)
    {
        $grid = $this->session->get('grid') ;
        $values = $this->session->get('values') ;
        $array = [
            "size" => $grid->getSize(),
            "solved" => $grid->isSolved(),
            "remain" => $grid->getRemainingTiles(),
            "tiles" => json_encode($grid->getTiles()),
            "values" => json_encode($values->getValues())
        ] ;
        $this->logger->debug($mark, $array) ;
    }
}
