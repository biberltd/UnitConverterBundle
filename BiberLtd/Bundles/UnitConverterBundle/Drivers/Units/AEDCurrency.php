<?php
/**
 * @name        AEDCurrency
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Initializes United Arab Emirates Dirhams.
 *
 */
namespace BiberLtd\Bundles\UnitConverterBundle\Drivers\Currencies;

use BiberLtd\Bundles\UnitConverterBundle\Drivers;

class AEDCurrency extends Drivers\CurrencyDriver {
    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     */
    public function __construct(){
        $currency_config = array(
            'code'      => substr(str_replace(__NAMESPACE__, '', __CLASS__), 1, 3),
            'name'      => 'United Arab Emirates Dirhams',
            'symbol'    => '',
        );
        parent::__construct($currency_config);
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Can Berkol
 * **************************************
 * A __construct()
 *
 */