<?php

namespace AppBundle\Utils;

/**
 * Description of SudokuGridMapper
 *
 * @author haclong
 */
class SudokuFileMapper 
{
    public static function mapToString($array)
    {
        $string = '' ;
        $string .= '<?php'."\r\n" ;
        foreach($array as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $string .= '$array['.$row.']['.$col.'] = '.$value.' ;'."\r\n" ;
            }
        }
        $string .= 'return $array ;' ;
        return $string ;
    }
}
