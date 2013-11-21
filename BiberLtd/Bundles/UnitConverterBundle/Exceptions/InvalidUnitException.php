<?php
/**
 * @name        InvalidUnitException
 * @package		BiberLtd\UnitConverterBundle
 *
 * @author		Said Imamoglu
 * @version     1.1.0
 * @date        26.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Custom Exception.
 *
 */
namespace BiberLtd\Bundles\UnitConverterBundle\Exceptions;

use BiberLtd\Bundles\ExceptionBundle\Services;

class InvalidUnitException extends Services\ExceptionAdapter {
    /**
     * @name 			__construct()
     *  				Constructor.
     *
     * @since			1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @param       object      $kernel
     * @param       string      $message
     * @param       integer     $code
     * @param       \Exception  $previous
     *
     * @see         \BiberLtd\Bundles\ExceptionBundle\
     */
    public function __construct($kernel, $message, $code = 101105, \Exception $previous = null) {
        parent::__construct(
            $kernel,
            'Unit can not found. '.$message,
            $code,
            $previous
        );
    }
}
/**
 * Change Log:
 * **************************************
 * v1.1.1                      Can Berkol
 * 05.07.2013
 * **************************************
 * U __contruct() $kernel parameter added.
 *
 * **************************************
 * v1.1.0                      Can Berkol
 * 26.06.2013
 * **************************************
 * U extends BBRExceptionAdapter
 *
 * **************************************
 * v1.0.0                      Can Berkol
 * 21.06.2013
 * **************************************
 * A __destruct()
 *
 */