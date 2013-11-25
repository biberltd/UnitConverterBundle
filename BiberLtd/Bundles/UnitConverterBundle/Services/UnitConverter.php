<?php

/**
 * CurrencyConverter Class
 *
 * This class provides unit conversion mechanisms.
 * 
 * @package         Core
 * @subpackage      Services
 * @name	    Encryption
 *
 * @author          Said İmamoğlu
 *
 * @copyright       Biber Ltd. (www.biberltd.com)
 *
 * @version         1.2.2
 *
 */

namespace BiberLtd\Bundles\UnitConverterBundle\Services;

use BiberLtd\Bundles\UnitConverterBundle\Exceptions as UnitException,
    BiberLtd\Bundles\UnitConverterBundle\Drivers\Units;

class UnitConverter {

    public $measure;

    /** @var $value value to be converted */
    public $value;

    /** @var $value_formatted formatted value */
    protected $value_formatted;

    /** @var $value_converted converted value */
    protected $value_converted;

    /** @var $units collection of currencies */
    public $units;

    /** @var $kernel Application kernel */
    private $kernel;

    /** @var $measures stores which unit drivers installed */
    protected $measures = array();

    /**
     * @name 		__construct()
     *  Constructor function.
     *
     * @since		1.0.0
     * @version         1.2.2
     * @author          Said İmamoğlu
     *
     */
    public function __construct($kernel) {
        $this->getFiles();
        $this->kernel = $kernel;
    }

    /**
     * @name        __destruct()
     *  Destructor function.
     *
     * @since		1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     *
     */
    public function __destruct() {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

    /**
     * @name            convert()
     *Converts the value from one unit to another by the convert method of sub class.
     * @author          Said İmamoğlu
     * @since           1.0.0
     * @version         1.2.2
     *
     * @use             $measure->convert()
     * @throws          UnitException\InvalidUnitException
     *
     * @param           string          $measure
     * @param           string          $from
     * @param           string          $to
     * @param           numeric         $value
     *
     * @return          object          $result
     */
    public function convert($measure, $from, $to, $value) {
        if (!$this->checkMeasureExist($measure)) {
            new UnitException\InvalidUnitException($this->get('kernel'), 'Specified unit name can not found : ' . $measure);
            exit;
        }
        $measure = 'BiberLtd\Bundles\UnitConverterBundle\Drivers\Units\\' . $this->measures[$measure];
        $unitMeasure = new $measure($this->kernel);
        $result = $unitMeasure->convert((string) $from, (string) $to, $value);
        $this->value_converted = $result;
        return $result;
    }

    /**
     * @name 		format()
     * Formats number of the value_converted and saves into value_formatted.
     * @author          Said İmamoğlu
     * @since		1.0.0
     * @version         1.2.2
     *
     * @throws          UnitException\InvalidUnitException
     *
     * @param           array          $unit            Keys: from, to
     * @param           array          $formatting_options
     *
     *                                 code             => on, off (show currency code - default: on)
     *                                 code_position    => start, end (default: end)
     *                                 round            => any integer (default: 2)
     *                                 direction        => up, down (round direction, default: up)
     *                                 decimal_symbol   => any string (default: .)
     *                                 thousands_symbol => any string (default: ,)
     *                                 show_original    => on, off (default: off, shows the original value in paranthesis)
     *
     * @return         object         $this
     */
    public final function format($measure, $from, $to, array $formatting_options = array()) {
        $measure = 'BiberLtd\\Bundles\\UnitConverterBundle\\Drivers\\Units\\' . $this->measures[$measure];
        $unit = new $measure($this->kernel);

        if (!isset($unit->units[$to])) {
            new UnitException\InvalidUnitException($this->kernel, $to);
        }
        if (!isset($unit->units[$from])) {
            new UnitException\InvalidUnitException($this->kernel, $from);
        }
        $unit_to = $unit->units[$to];
        $unit_from = $unit->units[$from];
        /**
         * initialize options
         */
        $default_options = array(
            'code' => 'on',
            'code_position' => 'end',
            'precision' => '7', //MAX 40
            'round' => 'up',
            'decimal_symbol' => '.',
            'thousands_symbol' => ',',
            'show_original' => 'off'
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
        switch ($options['round']) {
            case 'down':
                $value = round($value, $options['precision'], PHP_ROUND_HALF_DOWN);
                break;
            case 'up':
            default:
                $value = round($value, $options['precision'], PHP_ROUND_HALF_UP);
                break;
        }

        /* -------------------------------
         * Setting precision
         * -------------------------------
         * By default php converts numbers to scientific notation (0.00001 to 1.0E-5) which is higher than 10^-4
         * This is not an issue but i am defining this as an issue :)
         * 
         * To fix this issue 
         * 
         */
        if (strpos($value, 'E')) {
            $explode = explode('E', $value);
            if ($explode[1] >= 5 || $explode[1] <= -5) {
                $precision = "%." . $options['precision'] . "f";
                $value = rtrim(sprintf($precision, $value), '0');
                // If there are only zeroes after delimiter(.) this will trim all zeroes and dot at the right of value.
                if (strpos($value, '.')) {
                    $exploded_value = explode('.', $value);

                    if ($exploded_value[1] == '' || empty($exploded_value[1]) || is_null($exploded_value)) {
                        $value = $exploded_value[0];
                    }
                }
            }
        }

        /**
         *  Code & Symbol display
         */
        switch ($options['code']) {

            case 'off':
                break;
            case 'on':
                $to = str_replace('3', '&sup3;', str_replace('2', '&sup2;', str_replace('|', '/', $to)));

            default:
                switch ($options['code_position']) {
                    case 'start':
                        $value = $to . ' ' . $value;
                        break;
                    case 'end':
                    default:
                        $value .= ' ' . $to;
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
        if ($options['show_original'] == 'on') {
            $from = str_replace('3', '&sup3;', str_replace('2', '&sup2;', str_replace('|', '/', $from)));
            $value .= ' ' . '(' . $from . ' ' . round($this->value, 2) . ')';
        }
        $this->value_formatted = $value;

        return $value;
    }

    /**
     * @name 		checkMeasureExist()
     * Checks if specified measure exist.
     * @author          Said İmamoğlu
     * @since		1.0.0
     * @version         1.2.2
     *
     * @throws          UnitException\InvalidUnitException
     *
     * @param           array          $measure            Keys: digitalstorage,density,length
     *
     * @return         object         $this
     */
    public function checkMeasureExist($measure) {
        if (!is_string($measure)) {
            new UnitException\InvalidUnitException($this->kernel, 'Unit name must be string.');
            exit;
        }

        if (!array_key_exists($measure, $this->measures)) {
            new UnitException\InvalidUnitException($this->kernel, 'Specified unit name can not found : ' . $measure);
            exit;
        }

        return $this->measures[$measure];
    }

    /**
     * @name 		getFiles()
     * Retrieves files from directory to array.
     * 
     * @author          Said İmamoğlu
     * @since		1.0.0
     * @version         1.2.2
     *
     * @return         void
     */
    public function getFiles() {

        $files = glob(__DIR__ . '/../Drivers/Units/*Units.php');
        $measureClass = array();
        foreach ($files as $file) {
            $class = str_replace(__DIR__ . '/../Drivers/Units/', '', str_replace('.php', '', $file));
            $measureClass[str_replace('units', '', strtolower($class))] = $class;
        }
        $this->measures = $measureClass;
    }

}

/**
 * Change Log:
 * **************************************
 * v1.2.2                      Said İmamoğlu
 * 05.07.2013
 * **************************************
 * ExceptionBundle dependency added.
 * A $kernel
 * U __construct()
 * U convert() Exception namespace fixed.
 * U format()
 *
 * **************************************
 * v1.0.0                      Said İmamoğlu
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