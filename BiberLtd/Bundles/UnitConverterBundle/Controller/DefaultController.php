<?php
/**
 * DefaultController
 *
 * Default controller of UnitConverterBundle
 *
 * @vendor      BiberLtd
 * @package		UnitConverterBundle
 * @subpackage	Controller
 * @name	    DefaultController
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        20.06.2013
 *
 */

namespace BiberLtd\Bundles\UnitConverterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception,
    Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function convertAction($value, $from, $to, $service)
    {
        echo 1; die;
        $from = strtoupper($from);
        $to = strtoupper($to);
        $service = strtoupper($service);

        $currency_converter = $this->get('biberltd_currency.currency_converter');
        $currency_converter->value = $value;
        $converted_value = $currency_converter->convert($value, $from, $to, $service)->output();

        return new Response($converted_value);
    }

    public function convertFormattedAction($value, $from, $to, $service, $code = 'on', $code_position = 'start', $symbol = 'off', $symbol_position = 'start', $precision = 2, $round = 'up', $decimal_symbol = '.', $thousands_symbol = ',', $show_original = 'off')
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        $service = strtoupper($service);

        $currency_converter = $this->get('biberltd_currency.currency_converter');
        $currency_converter->value = $value;
        if($thousands_symbol == 'comma'){
            $thousands_symbol = ',';
        }
        else if($thousands_symbol == 'dot'){
            $thousands_symbol = '.';
        }
        if($decimal_symbol == 'comma'){
            $decimal_symbol = ',';
        }
        else if($decimal_symbol == 'dot'){
            $decimal_symbol = '.';
        }
        $options = array(
            'code'              => $code,
            'code_position'     => $code_position,
            'symbol'            => $symbol,
            'symbol_position'   => $symbol_position,
            'precision'         => $precision,
            'rount'             => $round,
            'decimal_symbol'    => $decimal_symbol,
            'thousands_symbol'  => $thousands_symbol,
            'show_original'     => $show_original
        );

        $currency_converter->convert($value, $from, $to, $service)->format(array('to' => $to, 'from' => $from), $options);

        $formatted_value = $currency_converter->output();

        return new Response($formatted_value);
    }
}
