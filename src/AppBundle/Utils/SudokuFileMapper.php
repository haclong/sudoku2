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
    
    public static function prepareArrayForJson($fileContent)
    {
        $array = array() ;
        foreach($fileContent as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $array[] = array('id' => 't.' .$row.'.'.$col, 'value'=> $value) ;
            }
        }
        return $array ;
    }
}