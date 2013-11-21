<?php

/**
 * @name        TemperatureUnits
 * @package	BiberLtd\UnitConverterBundle
 *
 * @author	Said Imamoglu
 * @version     1.0.0
 * @date        20.11.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Converting temp units.
 *
 */

namespace BiberLtd\Bundles\UnitConverterBundle\Drivers\Units;

use BiberLtd\Bundles\UnitConverterBundle\Drivers,
    BiberLtd\Bundles\UnitConverterBundle\Exceptions;

class TemperatureUnits extends Drivers\UnitConverterDriver {

    public $units = array(
            'celcius' => array('from' => "\$result = \$result + 273.15;", 'to' => "\$result = \$result - 273.15;"),
            'fahrenheit' => array('from' => "\$result = 5/9 * (\$result + 459.67);", 'to' => "\$result = 9/5 * \$result - 459.67;"),
            'kelvin' => 1
    );

    /**
     * @name        __construct()
     * Constructor function.
     *
     * @since		1.0.0
     * @version         1.0.0
     * @author          Said Imamoglu
     *
     */
    public function __construct($kernel) {

        parent::__construct($kernel);
    }

    /*
     * @name        convert()
     * Converting given values
     * 
     * @since       1.0.0
     * @version     1.0.0
     * @author      Said Imamoglu
     * 
     * @use         $this->setValue()
     * @use         $this->setFrom()
     * @use         $this->setTo()
     * 
     * @params      $value      value to be converted
     * @params      $from       value converting from
     * @params      $to         value converting to
     * 
     * 
     * @throws      UnitValueInvalidException()
     * 
     * @return      Integer,decimal     $result
     */

    public function convert($from, $to, $value) { /*
     * Setting elements for calculating
     */
        $value = $this->setValue($value);
        $from = $this->setFrom($from);
        $to = $this->setTo($to);

        /*
         * Converting
         */
        $result = $value * ($from / $to);

        return $result;
    }

    /*
     * @name        setValue()
     * Setting value to be converted
     * 
     * @since       1.0.0
     * @version     1.0.0
     * @author      Said Imamoglu
     * 
     * @use         $this->createException()
     * 
     * @params      $value      value to be set
     * 
     * 
     * @throws      UnitValueInvalidException()
     * 
     * @return      Integer,decimal     $result
     */

    public function setValue($value) {
        return isset($value) ? $value : $this->createException('UnitValueInvalidException', 'Invalid value', 'err.density.invalid.value');
    }

    /*
     * @name        setFrom()
     * Setting From value to be converted
     * 
     * @since       1.0.0
     * @version     1.0.0
     * @author      Said Imamoglu
     * 
     * @use         $this->createException()
     * 
     * @params      $value      FROM value to be set
     * 
     * 
     * @throws      UnitValueInvalidException()
     * 
     * @return      Integer,decimal     $result
     */

    public function setFrom($from) {
        return array_key_exists($from, $this->units) ? $this->units[$from] : $this->createException('UnitValueInvalidException', 'Invalid FROM : ' . $from, 'err.invalid.from');
    }

    /*
     * @name        setTo()
     * Setting TO value to be converted
     * 
     * @since       1.0.0
     * @version     1.0.0
     * @author      Said Imamoglu
     * 
     * @use         $this->createException()
     * 
     * @params      $value      TO value to be set
     * 
     * 
     * @throws      UnitValueInvalidException()
     * 
     * @return      Integer,decimal     $result
     */

    public function setTo($to) {
        return array_key_exists($to, $this->units) ? $this->units[$to] : $this->createException('UnitValueInvalidException', 'Invalid TO : ' . $to, 'err.invalid.to');
    }

}

/**
 * Change Log:
 * **************************************
 * 20.11.2013
 * v1.0.0                      Said Imamoglu
 * **************************************
 * A __construct()
 * A convert()
 * A setValue()
 * A setFrom()
 * A setTo()
 *
 */