<?php
/**
 * @name        ConnectionException
 * @package		BiberLtd\UnitConverterBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        05.07.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Exception to handle cURL connection problems.
 *
 */
namespace BiberLtd\Bundles\UnitConverterBundle\Exceptions;

use BiberLtd\Bundles\ExceptionBundle\Services;

class ConnectionException extends Services\ExceptionAdapter {
    public function __construct($kernel, $message = "", $code = 101100, Exception $previous = null) {
        parent::__construct(
            $kernel,
            'It seems like your server is not connected to Internet. Please check your server\'s Internet connection and try again.',
            $code,
            $previous);
     }
}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Can Berkol
 * 05.07.2013
 * **************************************
 * A __destruct()
 *
 */