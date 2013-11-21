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

    public $measures = array(
        'density' => 'Density',
        'digital' => 'DigitalStorage',
        'electric' => 'ElectricCurrency',
        'energy' => 'Energy',
        'force' => 'Force',
        'length' => 'Length',
        'power' => 'Power',
        'pressure' => 'Pressure',
        'speed' => 'Speed',
        'temperature' => 'Temperature',
        'timeConversion' => 'TimeConversion',
        'timeTrue' => 'TimeTrue',
        'time' => 'Time',
        'volume' => 'Volume',
        'weight' => 'Weight',
    );

    public function checkMeasureExist($type) {
        if (!is_string($type)) {
            new UnitException\InvalidUnitException($this->get('kernel'), 'Unit name must be string.');
            exit;
        }
        if (!array_key_exists($type, $this->measures)) {
            new UnitException\InvalidUnitException($this->get('kernel'), 'Specified unit name can not found : ' . $type);
            exit;
        }
        
        return $this->measures[$type].'Units';
    }

    public function convertAction($type, $from, $to, $value) {
        
        $measure = $this->checkMeasureExist($type);

        $unit_converter = $this->get('biberltd_unit_converter.unit_converter');
        $converted_value = $unit_converter->convert($measure,$from, $to,$value);

        return new Response($converted_value);
    }

    public function convertFormattedAction($value, $from, $to, $code = 'on', $code_position = 'start', $symbol = 'off', $symbol_position = 'start', $precision = 2, $round = 'up', $decimal_symbol = '.', $thousands_symbol = ',', $show_original = 'off') {
        $from = strtoupper($from);
        $to = strtoupper($to);

        $currency_converter = $this->get('biberltd_unit_converter.unit_converter');
        $currency_converter->value = $value;
        if ($thousands_symbol == 'comma') {
            $thousands_symbol = ',';
        } else if ($thousands_symbol == 'dot') {
            $thousands_symbol = '.';
        }
        if ($decimal_symbol == 'comma') {
            $decimal_symbol = ',';
        } else if ($decimal_symbol == 'dot') {
            $decimal_symbol = '.';
        }
        $options = array(
            'code' => $code,
            'code_position' => $code_position,
            'symbol' => $symbol,
            'symbol_position' => $symbol_position,
            'precision' => $precision,
            'rount' => $round,
            'decimal_symbol' => $decimal_symbol,
            'thousands_symbol' => $thousands_symbol,
            'show_original' => $show_original
        );

        $currency_converter->convert($value, $from, $to)->format(array('to' => $to, 'from' => $from), $options);

        $formatted_value = $currency_converter->output();

        return new Response($formatted_value);
    }

}
