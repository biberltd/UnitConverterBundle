<?php
/**
 * @name        ServiceProviderDriver
 * @package		BiberLtd\UnitConverterBundle
 *
 * @author		Can Berkol
 * @version     1.0.1
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The base driver class for each currency conversion service provider.
 *
 */
namespace BiberLtd\Bundles\UnitConverterBundle\Drivers;

use BiberLtd\Bundles\UnitConverterBundle\Exceptions;

class ServiceProviderDriver {
    protected $code;
    protected $name;
    protected $url;
    protected $from;
    protected $to;

    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param 			array 	$config 	Accepted keys: code, name, url, from, to
     *
     */
    public function __construct(array $config = array()){
        foreach($config as $key => $value){
            $setFn = 'set'.ucfirst($key);
            if(method_exists($this, $setFn)){
                $this->$setFn($value);
            }
        }
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
    function __destruct(){
        foreach($this as $key => $value){
            $this->$key = null;
        }
    }
    /**
     * @name 			getCode()
     *  				Gets the conversion service code.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->code
     *
     */
    public function getCode(){
        return $this->code;
    }
    /**
     * @name 			getFrom()
     *  				Gets the base currency of the convesion service.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->symbol
     *
     */
    public function getFrom(){
        return $this->from;
    }
    /**
     * @name 			getName()
     *  				Gets the conversion service name.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->name
     *
     */
    public function getName(){
        return $this->name;
    }
    /**
     * @name 			getTo()
     *  				Gets the supported currencies of the conversion service.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->symbol
     *
     */
    public function getTo(){
        return $this->to;
    }
    /**
     * @name 			getUrl()
     *  				Gets the conversion service URL.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->symbol
     *
     */
    public function getUrl(){
        return $this->url;
    }
    /**
     * @name 			setCode()
     *  				Sets the conversion service code.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @throws          CurrencyCodeException
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setCode($value){
        if(is_string($value)){
            $this->code = $value;
        }
        return $this;
    }
    /**
     * @name 			setFrom()
     *  				Sets the conversion service supported currencies.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @throws          Exceptions\CurrencyNotFoundException
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setFrom($value){
        if(is_string($value)){
            if(!file_exists(__DIR__.'/Currencies/'.$value.'Currency.php')){
                throw new Exceptions\CurrencyNotFoundException($value);
            }
            $this->from = $value;
        }
        return $this;
    }
    /**
     * @name 			setName()
     *  				Sets the conversion service name.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setName($value){
        if(is_string($value)){
            $this->code = $value;
        }
        return $this;
    }
    /**
     * @name 			setTo()
     *  				Sets the base currency of the converison service.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @throws          Exceptions\CurrencyNotFoundException
     * @throws          Exceptions\CurrencyServiceToException
     *
     * @param           array           $currencies
     *
     * @return          object          $this
     *
     */
    public function setTo($currencies){
        if(is_array($currencies)){
            foreach($currencies as $currency_code){
                if(!file_exists(__DIR__.'/Currencies/'.$currency_code.'Currency.php')){
                    throw new Exceptions\CurrencyNotFoundException($currency_code);
                }
            }
            $this->to = $currencies;
        }
        else{
            throw new Exceptions\CurrencyServiceToException('You need to provide all available currencies in an array.');
        }
        return $this;
    }
    /**
     * @name 			setUrl()
     *  				Sets the conversion service URL.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setUrl($value){
        if(is_string($value)){
            $this->url = $value;
        }
        return $this;
    }
    /**
     * @name 			isConvertible()
     *  				Throws exception if from or to currency is not available and returns true otherwise..
     *
     * @since			1.0.1
     * @version         1.0.1
     * @author          Can Berkol
     * @access          protected
     *
     * @throws          Exceptions\CurrencyServiceToException
     * @throws          Exceptions\CurrencyServiceFromException
     *
     * @param           Drivers\Currencies          $to
     * @param           Drivers\Currencies          $from
     *
     * @return          bool                                       true
     *
     */
    protected function isConvertible($to, $from){
        $convertible_to = false;
        $convertible_from = false;
        foreach($this->to as $currency){
            if($to->getCode() == $currency){
                $convertible_to = true;
            }
            if($from->getCode() == $currency){
                $convertible_from = true;
            }
        }
        if(!$convertible_to && $to->getCode() == $this->from){
            $convertible_to = true;
        }
        if(!$convertible_from && $from->getCode() == $this->from){
            $convertible_from = true;
        }
        if(!$convertible_to){
            throw new Exceptions\CurrencyServiceToException($from->getCode());
        }
        if(!$convertible_from){
            throw new Exceptions\CurrencyServiceFromException($from->getCode());
        }
        return true;
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.1                      Can Berkol
 * 21.06.2013
 * **************************************
 * A isConvertible()
 *
 * **************************************
 * v1.0.0                      Can Berkol
 * 21.06.2013
 * **************************************
 * A $code
 * A $codeLength
 * A $name
 * A $symbol
 * A __construct()
 * A __ destruct()
 * A getCode()
 * A getName()
 * A getSymbol()
 * A setCode()
 * A setName()
 * A setSymbol()
 */