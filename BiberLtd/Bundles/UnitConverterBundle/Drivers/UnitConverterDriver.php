<?php

/**
 * @name        CurrencyDriver
 * @package		BiberLtd\UnitConverterBundle
 *
 * @author		Can Berkol
 * @version     1.0.1
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The base driver class for each currency.
 *
 */

namespace BiberLtd\Bundles\UnitConverterBundle\Drivers;

use BiberLtd\Bundles\UnitConverterBundle\Exceptions;

class UnitConverterDriver {

    protected $code;
    protected $name;
    protected $symbol;
    protected $kernel;

    /** @var integer The allowed length of currency code */
    private $codeLength = 3;

    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param 			array 	$config 	Accepted keys: code, name, symbol, codeLength
     *
     */
    
    
    public function __construct($kernel,array $config = array()) {
        $this->kernel = $kernel;
        foreach ($config as $key => $value) {
            $setFn = 'set' . ucfirst($key);
            if (method_exists($this, $setFn)) {
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
    function __destruct() {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

    /**
     * @name 			getCode()
     *  				Gets the currency code.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->code
     *
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @name 			getName()
     *  				Gets the currency name.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->name
     *
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @name 			getSymbol()
     *  				Gets the currency symbol.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->symbol
     *
     */
    public function getSymbol() {
        return $this->symbol;
    }

    /**
     * @name 			setCode()
     *  				Sets the currency code.
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
    public function setCode($value) {
        if (is_string($value) && strlen($value) == $this->codeLength) {
            $this->code = $value;
        } else {
            throw new CurrencyCodeException('Currency code must be exactly ' . $this->codeLength . ' characters long.');
        }
        return $this;
    }

    /**
     * @name 			setName()
     *  				Sets the currency name.
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
    public function setName($value) {
        if (is_string($value)) {
            $this->name = $value;
        }
        return $this;
    }

    /**
     * @name 			setSymbol()
     *  				Sets the currency symbol.
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
    public function setSymbol($value) {
        if (is_string($value)) {
            $this->symbol = $value;
        }
        return $this;
    }

    /**
     * @name            createException()
     *                  Handles exception creation in a centralized manner. The function updates response code and
     *                  returns updated response object.
     *
     * @author          Said İmamoğlu
     *
     * @since           1.1.1
     * @version         1.1.1
     *
     * @param           string              $exception              Name of exception
     * @param           string              $msg                    Custom part of message
     * @param           string              $code                   error code
     *
     * @return          array               $this->response
     */
    public function createException($exception, $msg, $code) {
        $exception = 'BiberLtd\\Bundles\\UnitConverterBundle\\Exceptions\\' . $exception;
        new $exception($this->kernel, $msg);
        $this->response['code'] = $code;
        return $this->response;
    }
}

    /**
     * Change Log:
     * **************************************
     * v1.0.1                      Can Berkol
     * 21.06.2013
     * **************************************
     * U _construct() Comment fixed.
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
    