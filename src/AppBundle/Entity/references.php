<?php
namespace SudokuSolver\Model ;

use Exception;

class Grid
{
    /**
     * Constructor
     *
     * @param int $size Size of the grid - default = 9
     */
    public function __construct($size)
    {
        $this->size = $size ;
        $this->buildGrid() ;
    }

    /**
     * Build each cases of the grid
     *
     * @return array
     */
    protected function buildGrid()
    {
	$this->cases = array() ;
	for($row = 1; $row<=$this->size; $row++) { // row 
	    for($col = 1 ; $col<= $this->size; $col++) { // col
                $region = $this->getRegion($row, $col) ;
                $this->cases[$row . '.' . $col] = new GridCase($region, $row, $col, $this->size) ;
            }
	}
        return $this->cases ;
    }

    /**
     * Get a GridCase
     *
     * @param int $col Col number
     * @param int $row Row number
     *
     * @return GridCase
     */
    public function getCase($row, $col)
    {
        return $this->cases[$row . '.' . $col] ;
    }

    /**
     * Get the cases of the same row
     * 
     * @param int row number
     * 
     * return array
     */
    public function getRowCases($row)
    {
        $cases = array() ;
//        $i = 1 ;
        foreach($this->cases as $case) {
            if($case->getRow() == $row) {
//                $cases[$i] = $case ;
                $cases[] = $case ;
//                $i++ ;
            }
        }
        return $cases ;
    }

    /**
     * Get the cases of the same col
     * 
     * @param int col number
     * 
     * return array
     */
    public function getColCases($col)
    {
        $cases = array() ;
//        $i = 1 ;
        foreach($this->cases as $case) {
            if($case->getCol() == $col) {
//                $cases[$i] = $case ;
                $cases[] = $case ;
//                $i++ ;
            }
        }
        return $cases ;
    }

    /**
     * Get the cases of the same region
     * 
     * @param int region number
     * 
     * return array
     */
    public function getRegionCases($region)
    {
        $cases = array() ;
//        $i = 1 ;
        foreach($this->cases as $case) {
            if($case->getRegion() == $region) {
//                $cases[$i] = $case ;
                $cases[] = $case ;
//                $i++ ;
            }
        }
        return $cases ;
    }

     /**
     * Set a figure in a case
     *
     * @param int $col Col number
     * @param int $row Row number
     * @param int $figure Figure value
     *
     * @return void
     */
    public function setFigure($row, $col, $figure)
    {
        if($this->isAlreadySetInCol($row, $col, $figure)) {
            throw new Exception('Operation impossible - col : ' . $col . ' - figure : ' . $figure) ;
        } elseif($this->isAlreadySetInRow($row, $col, $figure)) {
            throw new Exception('Operation impossible - row : ' . $row . ' - figure : ' . $figure) ;
        } elseif($this->isAlreadySetInRegion($row, $col, $figure)) {
            throw new Exception('Operation impossible - region : ' . $region . ' - figure : ' . $figure) ;
        }
        $case = $this->getCase($row, $col) ;
        $case->figures->setFigure($figure) ;
    }

    /**
     * Discard a figure in a case
     *
     * @param int $col Col number
     * @param int $row Row number
     * @param int $figure Figure value
     *
     * @return void
     */
    public function discardFigure($row, $col, $figure)
    {
        $case = $this->getCase($row, $col) ;
        $case->figures->discardFigure($figure) ;
    }

    /**
     * Count figure in col
     * 
     * @param int $col
     * @param int $figure 
     * 
     * return int
     */
    protected function countFigureInCol($col, $figure)
    {
        $i = 0 ;
        foreach($this->getColCases($col) as $case) {
            if($case->figures->getFigureStatus($figure) == 1) {
                $i++ ;
            }
        }
        return $i ;
    }


    /**
     * Count figure in row
     * 
     * @param int $row
     * @param int $figure 
     * 
     * return int
     */
    protected function countFigureInRow($row, $figure)
    {
        $i = 0 ;
        foreach($this->getRowCases($row) as $case) {
            if($case->figures->getFigureStatus($figure) == 1) {
                $i++ ;
            }
        }
        return $i ;
    }

    /**
     * Count figure in region
     * 
     * @param int $region
     * @param int $figure 
     * 
     * return int
     */
    protected function countFigureInRegion($region, $figure)
    {
        $i = 0 ;
        foreach($this->getRegionCases($region) as $case) {
            if($case->figures->getFigureStatus($figure) == 1) {
                $i++ ;
            }
        }
        return $i ;
    }

    /**
     * Check if figure is already set in col
     * 
     * @param int $row
     * @param int $col
     * @param int $figure 
     * 
     * return bool
     */
    protected function isAlreadySetInCol($row, $col, $figure)
    {
        foreach($this->getColCases($col) as $case) {
            if($case->figures->getFigureStatus($figure) == 1 && $case->getRow() != $row) {
                return true ;
            }
        }
        return false ;
    }

    /**
     * Check if figure is already set in row
     * 
     * @param int $row
     * @param int $col
     * @param int $figure 
     * 
     * return bool
     */
    protected function isAlreadySetInRow($row, $col, $figure)
    {
        foreach($this->getRowCases($row) as $case) {
            if($case->figures->getFigureStatus($figure) == 1 && $case->getCol() != $col) {
                return true ;
            }
        }
        return false ;
    }

    /**
     * Check if figure is already set in region
     * 
     * @param int $row
     * @param int $col
     * @param int $figure 
     * 
     * return bool
     */
    protected function isAlreadySetInRegion($row, $col, $figure)
    {
        $region = $this->getRegion($row, $col) ;
        foreach($this->getRegionCases($region) as $case) {
            if($case->figures->getFigureStatus($figure) == 1 && $case->getCol() != $col && $case->getRow() != $row) {
                return true ;
            }
        }
        return false ;
    }

    /**
     * Check if figure is allowed
     * 
     * @param array $cases
     * 
     * return bool
     */
    protected function isFigureValid($figure)
    {
        if($figure <= $this->size && $figure > 0) {
            return true ;
        }
        return false ;
    }

    /**
     * Load an array into a grid
     * 
     * @param array $cases 
     * 
     * return void
     */
    public function loadGrid($cases)
    {
        foreach($cases as $row => $ligne) {
            foreach($ligne as $col => $figure) {
                if(!empty($figure)) {
                    if(!$this->isFigureValid($figure)) {
                        throw new Exception('Le numéro n\'est pas valide') ;
                    }
                    $this->setFigure($row, $col, $figure) ;
                }
            }
        }
    }

    /**
     * New grid - emptying cases initial grid figures reset
     *
     * @return array GridCase
     */
    public function newGrid()
    {
	foreach($this->cases as $case) {
            $case->figures->unsetAll() ;
        }
        return $this->cases ;
    }

    /**
     * Prepare the grid to the view : change the array unique key to multiple keys (rows / cols) and choose value to display on screen
     *
     * @param int $figure If we need to display the grid for the same figure only (all possible options)
     * @param array $gridcases Grid cases - if none, $this->cases used
     *
     * @return array int
     */
    public function prepare($figure=null, $gridcases=null)
    {
        // if $gridcases == null, take $this->cases
        if($gridcases == null) {
            $gridcases = $this->cases ;
        }

        // transform the initial $this->cases unique key array to a new array with two keys and an int as value for each keys
        foreach($gridcases as $cases) {
	    $row = $cases->getRow() ;
            $col = $cases->getCol() ;
            $grid_values[$row][$col] = $cases->figures->getFigure($figure) ;
        }
        return $grid_values ;
    }

    /**
     * Validate grid
     *
     * @return bool
     */
    public function isValid()
    {
        foreach($this->cases as $case) {
            $row = $case->getRow() ;
            $col = $case->getCol() ;
            $region = $case->getRegion($row, $col) ;
            for($figure=1; $figure<=$this->size; $figure++)
            {
                if($this->countFigureInCol($col, $figure) > 1) {
//                    throw new \Exception('Operation impossible - col : ' . $col . ' - figure : ' . $figure) ;
                    return false ;
                } elseif($this->countFigureInRow($row, $figure) > 1) {
//                    throw new \Exception('Operation impossible - row : ' . $row . ' - figure : ' . $figure) ;
                    return false ;
                } elseif($this->countFigureInRegion($region, $figure) > 1) {
//                    throw new \Exception('Operation impossible - region : ' . $region . ' - figure : ' . $figure) ;
                    return false ;
                }
            }
        }

        return true ;
    }

    /**
     * Check if all cases are solved
     *
     * @return bool
     */
    public function isSolved()
    {
        foreach($this->cases as $case) {
            if($case->figures->isFigureEmpty()) {
                return false ;
            }            
        }

        return true ;
    }
}





<?php
namespace SudokuSolver\Model ;

class GridCase
{
    /**
     * available number values altered by hypothesis or hypothesis number values
     *
     * @var Figures
     */
    public $figures ;

    /**
     * available number values or final number value
     *
     * @var Figures 
     */
    protected $final_figures ;

    /**
     * status of the case : is already set or newly set
     *
     * @var $status bool
     */
    protected $status ;

    /**
     * saved status of the case
     *
     * @var $final_status bool
     */
    protected $final_status ;

    /**
     * Constructor
     *
     * @param int $region Region number
     * @param int $col Column number
     * @param int $row Row number
     * @param int $size Sudoku grid size (useful to set the figures array)
     */
    public function __construct($region, $row, $col, $size)
    {
        $this->col = $col ;
        $this->row = $row ;
	$this->region = $region ;
        $this->id = $row . "." . $col ;
        $this->status = false ;
        $this->figures = new Figure($size) ;
    }
    
    /**
     * get the case status
     */
    public function getStatus()
    {
        if($this->figures->isFigureEmpty())
        {
            $this->status = false ;
        }
        return $this->status ;
    }

    /**
     * Save grid state - set a save point to the figures information
     *
     * @return Figure
     */
    public function saveFigure()
    {
        $this->final_figures = clone $this->figures ;
        $this->final_status = $this->status ;
    }

    /**
     * Restore grid state - recover the figures informations from the last save point
     *
     * @return Figure
     */
    public function restoreFigure()
    {
        $this->figures = clone $this->final_figures ;
        $this->status = $this->final_status ;
    }
}







<?php
namespace SudokuSolver\Model ;

class Figure
{
    /**
     * Is figure empty ?
     *
     * return bool
     */
    public function isFigureEmpty()
    {
        foreach($this->figures as $figure)
        {
            if($figure == self::VALID)
            {
                return false ;
            }
        }
        return true ;
    }

    /**
     * Is figure valid ?
     *
     * return bool
     */
    public function isFigureSet()
    {
        foreach($this->figures as $figure)
        {
            if($figure == self::VALID)
            {
                return true ;
            }
        }
        return false ;
    }

    /**
     * Return figure status : if not discarded, figure still valid
     *
     * @param int $figure
     * 
     * return string
     */
    protected function getFigureMaybe($figure)
    {
        if($this->figures[$figure] != self::DISCARD)
        {
            return $figure ;
        }
        return '' ;
    }

    /**
     * Get final figure
     *
     * @param int $figure
     * 
     * return string
     */
    public function getFigure($figure=null)
    {
        if($figure==null)
        {
            return $this->getFigureSet() ;
        }
        else
        {
            return $this->getFigureMaybe($figure) ;
        }
    }

    /**
     * Get figures by key
     *
     * @param int $index
     * 
     * return int
     */
    public function getFigureStatus($index)
    {
        return $this->figures[$index] ;
    }
}





<?php
namespace SudokuSolver\Model ;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Solver implements EventManagerAwareInterface
{
    /**
     * Events
     *
     * @var Event $events
     */
    protected $events;

    /**
     * Grid 
     *
     * @var Grid
     */
    protected $grid ;
    
    /**
     * Number of iteration before declaring infinite loop
     *
     * @var int
     */
    protected $infiniteLimit = 1 ;

    /**
     * Number of iteration to keep
     *
     * @var int
     */
    protected $keepIteration = 4 ;

    /**
     * Attempts
     *
     * @var array
     */
    private $attempt = array() ;

    /**
     * Hypothesis
     *
     * @var array
     */
    private $hypothesis = array() ;

    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(__CLASS__, get_called_class())) ;
        $this->events = $events ;
        return $this ;
    }
    
    public function getEventManager()
    {
        if(null === $this->events) {
            $this->setEventManager(new EventManager()) ;
        }
        return $this->events ;
    }

    /**
     * Constructor
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid ;
    }

    /**
     * Start solving
     *
     * @return void
     */
    public function run()
    {
        $i = 0 ;
        do {
            $this->unsetAttempt($i) ;
            $this->attempt[$i] = $this->getSnapshot($this->grid) ;
            if(!$this->iterate($i)) {
                break ;
            }
            $i++ ;
        } while(!$this->grid->isSolved()) ;
    }

    /**
     * Solver algorythm
     *
     * @param array $iteration
     * 
     * @return bool false if stuck in infinite loop
     */
    protected function iterate($iteration)
    {
        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'tour ' . $iteration)) ;

        if($this->isInfinite($iteration)) {
            $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'vérification des possibilités par colonne')) ;
            $this->setFigureInCols() ;
            $this->attempt[$iteration] = array() ;
            $this->attempt[$iteration] = $this->getSnapshot($this->grid) ;

            if($this->isInfinite($iteration)) {
                $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'vérification des possibilités par ligne')) ;
                $this->setFigureInRows() ;
                $this->attempt[$iteration] = array() ;
                $this->attempt[$iteration] = $this->getSnapshot($this->grid) ;
                
                if($this->isInfinite($iteration)) {
                    $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'vérification des possibilités par region')) ;
                    $this->setFigureInRegions() ;
                    $this->attempt[$iteration] = array() ;
                    $this->attempt[$iteration] = $this->getSnapshot($this->grid) ;
                    
                    if($this->isInfinite($iteration)) {
                        $this->assume() ;
                        $this->attempt[$iteration] = array() ;
                        $this->attempt[$iteration] = $this->getSnapshot($this->grid) ;
                        
                        if($this->isInfinite($iteration)) {
                            return false ;
                        }
                    }
                }
            }
        }

        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'suppression des possibilités sur les cases')) ;
        $this->discardValues() ;
        if(!$this->validateGrid()) {
           $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'hypothèse ratée')) ;
           $this->revert() ;
        }

        return true ;
    }

    /**
     * Take a snapshot of the grid, including values
     *
     * @param $grid Grid
     * 
     * @return array
     */
    protected function getSnapshot(Grid $grid)
    {
        $snapshot = array() ;
        foreach($grid->getCases() as $k => $case)
        {
            for($i=1; $i<= $grid->getSize(); $i++)
            {
                $snapshot[$k][$i] = $case->figures->getFigureStatus($i) ;
            }
        }
        return $snapshot ;
    }

    /**
     * Check if we are stuck in infinite loop
     * 
     * @param $key int - index de l'itération
     *
     * @return bool
     */
    protected function isInfinite($key)
    {
        if($key >= $this->infiniteLimit && $this->attempt[$key] == $this->attempt[$key - $this->infiniteLimit]) {
            return true ;
        }
        return false ;
    }
   
    /**
     * Unset previous attempt
     * 
     * @param $iteration int - index de l'itération
     *
     * @return bool
     */
    protected function unsetAttempt($iteration)
    {
        if($iteration >= $this->keepIteration) {
            unset($this->attempt[$iteration - $this->keepIteration]) ;
        }
    }

    /**
     * Check figures to discard after set or init values
     *
     * @return void
     */
    protected function discardValues()
    {
        foreach($this->grid->getCases() as $case) {
            if($case->figures->isFigureSet() && !$case->getStatus()) {
                $col = $case->getCol() ;
                $row = $case->getRow() ;
                $region = $case->getRegion() ;
                $status = $case->validateCase() ;
                $figure = $case->figures->getFigure() ;
                $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$row. '.' .$col. ' : chiffre confirmé ' .$figure)) ;

//                foreach($this->grid->getColCases($col) as $c) {
//                    if($c != $case && !$c->figures->isFigureSet()) {
//                        $c->figures->discardFigure($figure) ;
//                    }
//                }
//                foreach($this->grid->getRowCases($row) as $c) {
//                    if($c != $case && !$c->figures->isFigureSet()) {
//                        $c->figures->discardFigure($figure) ;
//                    }
//                }
//                foreach($this->grid->getRegionCases($region) as $c) {
//                    if($c != $case && !$c->figures->isFigureSet()) {
//                        $c->figures->discardFigure($figure) ;
//                    }
//                }
                foreach($this->grid->getCases() as $c) {
                    if($c->getCol() == $col && !$c->figures->isFigureSet()) {
                        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$c->getRow(). '.' .$c->getCol(). ' : discard ' .$figure)) ;
                        $c->figures->discardFigure($figure) ;
                    } elseif ($c->getRow() == $row && !$c->figures->isFigureSet()) {
                        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$c->getRow(). '.' .$c->getCol(). ' : discard ' .$figure)) ;
                        $c->figures->discardFigure($figure) ;
                    } elseif ($c->getRegion() == $region && !$c->figures->isFigureSet()) {
                        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$c->getRow(). '.' .$c->getCol(). ' : discard ' .$figure)) ;
                        $c->figures->discardFigure($figure) ;
                    }
                }
            }
        }
    }
    
    /**
     * Validate grid based on sudoku rules
     *
     * @return array
     */
    protected function validateGrid() 
    {
        if(!$this->grid->isValid()) {
            return false ;
        }
        return true ;
    }

    /**
     * Get every positions of a figure on the same col
     *
     * @param int $col Number of the col
     * @param int $figure Figure checked
     *
     * @return array
     */
    protected function getFigureInCol($col, $figure)
    {
        $cases = array() ;
        foreach($this->grid->getColCases($col) as $case) {
            if($case->figures->getFigureStatus($figure) == 2) {
                $cases[] = $case ;
            }
        }
        return $cases ;
    }

    /**
     * Set figure if it's last in cols
     *
     * @return void
     */
    public function setFigureInCols()
    {
        $grid_size = $this->grid->getSize() ;
        

        for($col=1; $col<=$grid_size; $col++) {
            $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'recherche dans colonne ' . $col)) ;
            for($figure=1; $figure<=$grid_size; $figure++) {
                $array = $this->getFigureInCol($col, $figure) ;
                if($this->isLastFigureInGroup($array)) {
                    $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$array[0]->getRow(). '.' .$col. ' : dernière option (col) ' .$figure)) ;
                    $this->grid->setFigure($array[0]->getRow(), $col, $figure) ;
                }
            } 
        }
    }

    /**
     * Get every positions of a figure on the same row
     *
     * @param int $row Number of the row
     * @param int $figure Figure checked
     *
     * @return array
     */
    protected function getFigureInRow($row, $figure)
    {
        $cases = array() ;
        foreach($this->grid->getRowCases($row) as $case) {
            if($case->figures->getFigureStatus($figure) == 2) {
                $cases[] = $case ;
            }
        }
        return $cases ;
    }

    /**
     * Set figure if it's last in rows
     *
     * @return void
     */
    public function setFigureInRows()
    {
        $grid_size = $this->grid->getSize() ;

        for($row=1; $row<=$grid_size; $row++) {
            $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'recherche dans ligne ' . $row)) ;
            for($figure=1; $figure<=$grid_size; $figure++) {
                $array = $this->getFigureInRow($row, $figure) ;
                if($this->isLastFigureInGroup($array)) {
                    $this->grid->setFigure($row, $array[0]->getCol(), $figure) ;
                    $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$row. '.' .$array[0]->getCol(). ' : dernière option (ligne) ' .$figure)) ;
                }
            } 
        }
    }

    /**
     * Get every positions of a figure on the same region
     *
     * @param int $region Number of the region
     * @param int $figure Figure checked
     *
     * @return array
     */
    protected function getFigureInRegion($region, $figure)
    {
        $cases = array() ;
        foreach($this->grid->getRegionCases($region) as $case) {
            if($case->figures->getFigureStatus($figure) == 2) {
                $cases[] = $case ;
            }
        }
        return $cases ;
    }

    /**
     * Set Figure if it's last in regions
     *
     * @return void
     */
    public function setFigureInRegions()
    {
        $grid_size = $this->grid->getSize() ;

        for($region=1; $region<=$grid_size; $region++) {
           $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'recherche dans la region ' . $region)) ;
             for($figure=1; $figure<=$grid_size; $figure++) {
                $array = $this->getFigureInRegion($region, $figure) ;
                if($this->isLastFigureInGroup($array)) {
                    $this->grid->setFigure($array[0]->getRow(), $array[0]->getCol(), $figure) ;
                    $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$array[0]->getRow(). '.' .$array[0]->getCol(). ' : dernière option (reg) ' .$figure)) ;
                }
            } 
        }
    }

    /**
     * Check if figure is last in group
     *
     * @param array $array array of cases of the same group 
     *
     * @return bool
     */
    protected function isLastFigureInGroup($array)
    {
        if (count($array) == 1) {
            return true ;
        }
        return false ;
    }
    
    /**
     * Save grid - copying grid into saved grid
     *
     * @return void
     */
    public function saveGrid()
    {
	foreach($this->grid->getCases() as $case) {
            $case->saveFigure() ;
        }
    }

    /**
     * Restore grid - revert to saved grid
     *
     * @return array GridCase
     */
    public function restoreGrid()
    {
	foreach($this->grid->getCases() as $case) {
            $case->restoreFigure() ;
        }
    }

    /**
     * Make hypothesis on first available case 
     * 
     * @return bool
     */
    protected function assume()
    {
        if(count($this->hypothesis) == 0) {
            $this->saveGrid() ;
            $index = 0 ;
        } else {
            $this->restoreGrid() ;
            $this->saveGrid() ;
            $index = $this->hypothesis['index'] + 1 ;
        }

        $caseSet = $this->getEmptyCaseByIndex($index) ;
        $figureSet = $this->getFirstMaybeFigure($caseSet) ;
        $this->hypothesis = array('row' => $caseSet->getRow(), 'col' => $caseSet->getCol(), 'figure' => $figureSet, 'index' => $index) ;

        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$caseSet->getRow(). '.' .$caseSet->getCol(). ' : tente de valider ' . $figureSet)) ;
        $this->grid->setFigure($caseSet->getRow(), $caseSet->getCol(), $figureSet) ;
    }

    /**
     * Discard last hypothesis
     * 
     * @return void
     */
    protected function revert()
    {
        $this->restoreGrid() ;
        
        $this->getEventManager()->trigger('log', $this, array('priority' => 'debug', 'message' => 'case ' .$this->hypothesis['row']. '.' .$this->hypothesis['col']. ' : ' . $this->hypothesis['figure'] . ' discard by hypothesis.')) ;
        $this->grid->discardFigure($this->hypothesis['row'], $this->hypothesis['col'], $this->hypothesis['figure']) ;
        $this->grid->getCase($this->hypothesis['row'], $this->hypothesis['col'])->unvalidateCase() ;
        $this->hypothesis = array() ;
    }

    /**
     * Find first empty case
     * 
     * @param $index
     * 
     * @return array $case | false
     */
    protected function getEmptyCaseByIndex($index = 0)
    {
        $cases = array() ;
        foreach($this->grid->getCases() as $case) {
            if($case->figures->isFigureEmpty()) {
                $cases[] = $case ;
            } 
        }
        return $cases[$index] ;
    }

    /**
     * Find maybe figure
     * 
     * @param $case Case
     * 
     * @return int $figure
     */
    protected function getFirstMaybeFigure($case)
    {
        for($figure = 1; $figure <= $this->grid->getSize(); $figure++) {
            if($case->figures->getFigureStatus($figure) == 2) {
                return $figure ;
            }
        }
        return false ;
    }
}