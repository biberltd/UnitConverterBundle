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

use BiberLtd\Bundles\UnitConverterBundle\Exceptions,
    BiberLtd\Bundles\UnitConverterBundle\Drivers\Currencies,
    BiberLtd\Bundles\UnitConverterBundle\Drivers\ServiceProviders;

class UnitConverter {
    /** @var $value value to be converted */
    public      $value;
    /** @var $value_formatted formatted value */
    protected   $value_formatted;
    /** @var $value_converted converted value */
    protected   $value_converted;
    /** @var $units collection of currencies */
    protected   $units;
    /** @var $kernel Application kernel */
    private   $kernel;
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
        $this->register_units();
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
    public function convert($value, $from = 'm', $to = 'km'){
        
        /**
         * Check if selected conversion service is registered.
         */
        if(!isset($value)){
            new \BiberLtd\Bundles\UnitConverterBundle\Exceptions\UnitValueInvalidException($this->kernel, $value);
            exit;
        }
        $this->value = $value;
        
        if(!isset($this->units[$from])){
            new \BiberLtd\Bundles\UnitConverterBundle\Exceptions\UnitValueInvalidException($this->kernel, $from);
            exit;
        }
        $from = $this->units[$from];
        if(!isset($this->units[$to])){
            new \BiberLtd\Bundles\UnitConverterBundle\Exceptions\UnitValueInvalidException($this->kernel, $to);
            exit;
        }
        $to = $this->units[$to];
        
        $result = $value *($from / $to);

        

        /** Sets the value converted and the value formatted to the converted value. */
        $this->value_converted = $this->value_formatted = $conversion_rate * $value;
        return $this;
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
     * @name 			load_xml()
     *                  Loads the XML file into an object using SimpleXML and cURL libraries.
     * @author          Can Berkol
     * @since			1.0.0
     * @param           string          $url            URL of service
     * @param           integer         $timeout        Seconds to timeout the connection.
     * @param           string          $agent          Agent / Browser to behave like.
     * @return          string          $conversions    Content of XML
     *
     */
    private function load_xml($url, $timeout = 0, $agent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)'){
        /**
         * Open remote connection.
         */
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($connection,  CURLOPT_USERAGENT , $agent);
        curl_setopt($connection, CURLOPT_CONNECTTIMEOUT, $timeout);
        $xml_string = curl_exec($connection);
        if(!$xml_string){
            new \BiberLtd\Bundles\UnitConverterBundle\Exceptions\ConnectionException($this->kernel);
        }
        curl_close($connection);
        /** Remote connection ends */
        $conversions = new \SimpleXMLElement($xml_string);

        return $conversions;
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
    private function register_units(){
        $files = glob(__DIR__.'\\..\\Drivers\\Units\\*Currency.php');
        foreach($files as $file){
            $unit_class = str_replace('.php', '', $file);
            $unit_class = str_replace(__DIR__.'\\..\\Drivers\\Units\\', '', $unit_class);
            $unit_code = str_replace('Unit', '', $unit_class);
            $unit_class = '\\BiberLtd\\Bundles\\UnitConverterBundle\\Drivers\\Units\\'.$unit_class;
            $unit = new $unit_class();

            $this->units[$unit_code] = $unit;
        }
        
        return $this;
    }
    /**
     * @name 			register_services()
     *  				Registers all available service provides.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          object      $this
     *
     */
    private function register_services(){
        $files = glob(__DIR__.'\\..\\Drivers\\ServiceProviders\\*Service.php');
        foreach($files as $file){
            $service_class = str_replace('.php', '', $file);
            $service_class = str_replace(__DIR__.'\\..\\Drivers\\ServiceProviders\\', '', $service_class);
            $service_code = str_replace('Service', '', $service_class);
            $service_class = '\\BiberLtd\\Bundles\\UnitConverterBundle\\Drivers\\ServiceProviders\\'.$service_class;
            $service = new $service_class();

            $this->services[$service_code] = $service;
        }

        return $this;
    }
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