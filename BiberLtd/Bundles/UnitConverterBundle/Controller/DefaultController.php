<?php

/**
 * DefaultController
 *
 * Default controller of UnitConverterBundle
 *
 * @package		UnitConverterBundle
 * @subpackage	Controller
 * @name	    DefaultController
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 *
 */

namespace BiberLtd\Bundles\UnitConverterBundle\Controller;

use BiberLtd\Bundles\UnitConverterBundle\Exceptions as UnitException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception,
    Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {


    public function convertAction($type, $from, $to, $value) {

        $unit_converter = $this->get('biberltd_unit_converter.unit_converter');
        $converted_value = $unit_converter->convert($measure, $from, $to, $value);
        
        
        $format_options = array(
            'code' => 'on',
            'code_position' => 'end',
            'precision' => '10', //Max to 40
            'round' => 'up',
            'decimal_symbol' => '.',
            'thousands_symbol' => ',',
            'show_original' => 'off'
        );
        
        $formatted_value = $unit_converter->format($measure,$from,$to, $format_options);
        

        return new Response($formatted_value);
    }


}
