<?php

namespace AppBundle\Utils;

use AppBundle\Persistence\SudokuSession;
use Symfony\Bridge\Monolog\Logger;

/**
 * Description of SessionMarker
 *
 * @author haclong
 */
class SessionMarker {
    protected $session ;
    protected $logger ;
    
    public function __construct(SudokuSession $session, Logger $logger) {
        $this->session = $session;
        $this->logger = $logger;
    }

    public function logSession($mark)
    {
        $grid = $this->session->getGrid() ;
        $values = $this->session->getValues() ;
        $tiles = $this->session->getTiles() ;
        $array = [
            "size" => $grid->getSize(),
            "solved" => $grid->isSolved(),
            "remain" => $grid->getRemainingTiles(),
            "tiles" => json_encode($grid->getTiles()),
            "values" => json_encode($values->getValues()),
            "tileset" => json_encode($tiles->getTileset())
        ] ;
        $this->logger->debug($mark, $array) ;
    }
}
