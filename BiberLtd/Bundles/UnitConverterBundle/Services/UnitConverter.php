<?php
/**
 * CurrencyConverter Class
 *
 * This class provides forex (foreign exchange) currency conversion mechanisms.
 *
 * @vendor      BiberLtd
 * @package		Core
 * @subpackage	Services
 * @name	    Encryption
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.2.2
 * @date        05.07.2013
 *
 */

namespace BiberLtd\Bundles\UnitConverterBundle\Services;

use BiberLtd\Bundles\UnitConverterBundle\Exceptions as UnitException,
    BiberLtd\Bundles\UnitConverterBundle\Drivers\Units;

class UnitConverter {
    public      $measure;
    /** @var $value value to be converted */
    public      $value;
    /** @var $value_formatted formatted value */
    protected   $value_formatted;
    /** @var $value_converted converted value */
    protected   $value_converted;
    /** @var $units collection of currencies */
    protected   $units;
    /** @var $kernel Application kernel */
    private     $kernel;
    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.2.2
     * @author          Can Berkol
     *
     */
    public function __construct($kernel){
        $this->kernel = $kernel;
    }
    /**
     * @name 			__destruct()
     *  				Destructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     */
    public function __destruct(){
        foreach($this as $key => $value){
            $this->$key = null;
        }
    }
    /**
     * @name            convert()
     *  				Converts the value from one currency to another using the provided service provider.
     * @author          Can Berkol
     * @since			1.0.0
     * @version         1.2.2
     *
     * @throws          \BiberLtd\Bundles\UnitConverterBundle\Exceptions\CurrencyServiceProviderException
     * @throws          \BiberLtd\Bundles\UnitConverterBundle\Exceptions\CurrencyCodeException
     *
     * @param           numeric         $value
     * @param           string          $from
     * @param           string          $to
     * @param           string          $service
     *
     * @return          object          $this
     */
    public function convert($measure, $from , $to ,$value){
        $measure = 'BiberLtd\Bundles\UnitConverterBundle\Drivers\Units\\'.$measure;
        $unit = new $measure($this->kernel);
        
        $result = $unit->convert($from,$to,$value);
        
        return $result;
    }
    /**
     * @name 			format()
     *  				Formats number of the value_converted and saves into value_formatted.
     * @author          Can Berkol
     * @since		    1.0.0
     * @version         1.2.2
     *
     * @throws          \BiberLtd\Bundles\UnitConverterBundle\Exceptions\CurrencyCodeException
     *
     * @param           array          $unit            Keys: from, to
     * @param           array          $formatting_options
     *
     *                                 code             => on, off (show currency code - default: on)
     *                                 code_position    => start, end (default: end)
     *                                 symbol           => on, off (show currency symbol - default: off)
     *                                 symbol_position  => start, end (default: start)
     *                                 round            => any integer (default: 2)
     *                                 direction        => up, down (round direction, default: up)
     *                                 decimal_symbol   => any string (default: .)
     *                                 thousands_symbol => any string (default: ,)
     *                                 show_original    => on, off (default: off, shows the original value in paranthesis)
     *
     * @return         object         $this
     */
    public final function format($unit, array $formatting_options = array()){
        if(!isset($this->units[$unit['to']])){
            new \BiberLtd\Bundles\UnitConverterBundle\Exceptions\CurrencyCodeException($this->kernel, $unit['to']);
        }
        if(!isset($this->units[$unit['from']])){
            new \BiberLtd\Bundles\UnitConverterBundle\Exceptions\CurrencyCodeException($this->kernel, $unit['from']);
        }
        $unit_to = $this->units[$unit['to']];
        $unit_from = $this->units[$unit['from']];
        /**
         * initialize options
         */
        $default_options = array(
            'code'              => 'on',
            'code_position'     => 'end',
            'symbol'            => 'off',
            'symbol_position'   => 'start',
            'precision'         => '2',
            'round'             => 'up',
            'decimal_symbol'    => '.',
            'thousands_symbol'  => ',',
            'show_original'     => 'off'
        );
        /**
         * Override defaults.
         */
        $options = array_merge($default_options, $formatting_options);
        /**
         * Read values
         */
        $value = $this->value_converted;
        /**
         * Rounding
         */
        $exploded_value = explode('.', $value);
        $precision = $options['precision'] + 1;
        if(!isset($exploded_value[1])){
            $exploded_value[1] = '00';
        }
        $exploded_value[1] = substr($exploded_value[1], 0, $precision);
        $value = (double) $exploded_value[0].'.'.$exploded_value[1];
        switch($options['round']){
            case 'down':
                $value = round($value, $options['precision'], PHP_ROUND_HALF_DOWN);
                break;
            case 'up':
            default:
                $value = round($value, $options['precision'], PHP_ROUND_HALF_UP);
                break;
        }
        /**
         *  Code & Symbol display
         */
        switch($options['code']){
            case 'off':
                break;
            case 'on':
            default:
                switch($options['code_position']){
                    case 'start':
                        $value = $unit_to->getCode().' '.$value;
                        break;
                    case 'end':
                    default:
                        $value .= ' '.$unit_to->getCode();
                        break;
                }
                break;
        }
        switch($options['symbol']){
            case 'off':
                break;
            case 'on':
            default:
                $symbol = $unit_to->getSymbol();
                switch($options['symbol_position']){
                    case 'start':
                        $value = $symbol.' '.$value;
                        break;
                    case 'end':
                    default:
                        $value .= ' '.$symbol;
                        break;
                }
                break;
        }
        /**
         * Decimal and thousands separator
         */
        $value = str_replace(array('.', ','), array($options['decimal_symbol'], $options['thousands_symbol']), $value);
        /**
         * Show original
         */
        if($options['show_original'] == 'on'){
            $value .= ' '.'('.$unit_from->getCode().' '.round($this->value, 2).')';
        }
        $this->value_formatted = $value;
        return $this;
    }
    /**
     *  @name 			output()
     *  				Outputs the converted value.
     *  @author         Can Berkol
     * 	@since			1.0.0
     *
     *  @param          bool            $print          true|false, false is default. If set to true it does print the value otherwise returns it.
     *
     *  @return         string          $this->value_formatted
     *
     */
    public final function output($print = false){
        if(!$print){
            return $this->value_formatted;
        }
        echo $this->value_formatted;
    }
    /**
     * @name 			register_units()
     *  				Registers all available currencies.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          object      $this
     *
     */

}
/**
 * Change Log:
 * **************************************
 * v1.2.2                      Can Berkol
 * 05.07.2013
 * **************************************
 * ExceptionBundle dependency added.
 * A $kernel
 * U __construct()
 * U convert() Exception namespace fixed.
 * U format()
 *
 * **************************************
 * v1.0.0                      Can Berkol
 * 21.06.2013
 * **************************************
 * A $code
 * A $units
 * A $services
 * A $value
 * A $value_converted
 * A $value_formatted
 * A __construct()
 * A __destruct()
 * A convert()
 * A format()
 * A load_xml()
 * A output()
 * A register_units()
 * A register_services()
 *
 */