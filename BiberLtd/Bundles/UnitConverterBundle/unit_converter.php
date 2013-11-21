<?php

/**
 * Developed by Biber Ltd. (http://www.biberltd.com)
 * 
 * version:         1.0.3
 * last update:     17 October 2011
 * author:          Selimcan Sakar, Oğuz Ülgen
 * copyright:       Biber Ltd. (http://biberltd.com)
 * description:     This field provides a new field type where users will have a text box to 
 *                  to store currency related information.
 * 
 * 
 * license:
 * 
 * This license is a legal agreement between you and Biber Bilisim Teknolojileri ve Dis Tic. Ltd. Sti
 * (Biber Ltd.) for the use of our add-ons and other software (the “Software”). 
 * By downloading any Biber Ltd. software you agree to be bound by the terms and conditions of this 
 * license. Biber Ltd. reserves the right to alter this agreement at any time, for any reason, without 
 * notice.
 * 
 * PERMITTED USE 
 * 
 * One license grants the right to perform one installation of the Software. Each additional 
 * installation of the Software requires an additional purchased license. Development systems 
 * are not included in these restrictions, you may install the Software on development systems 
 * as needed.
 * 
 * RESTRICTIONS
 * 
 * Unless you have been granted prior, written consent from Biber Ltd., you may not:
 * 
 *      Reproduce, distribute, or transfer the Software, or portions thereof, to any third party.
 *      Sell, rent, lease, assign, or sublet the Software or portions thereof.
 *      Grant rights to any other person.
 *      Use the Software in violation of any laws of the Republic of Turkiye or international 
 *      law or regulation.
 * 
 * DISPLAY OF COPYRIGHT NOTICES
 * 
 * All copyright and proprietary notices and logos in the Control Panel and within the Software 
 * files must remain intact.
 * 
 * MAKING COPIES
 * 
 * You may make copies of the Software for back-up purposes, provided that you reproduce the 
 * Software in its original form and with all proprietary notices on the back-up copy.
 * 
 * SOFTWARED MODIFICIATIONS
 * 
 * You may alter, modify, or extend the Software for your own use, or commission a third-party 
 * to perform modifications for you, but you may not resell, redistribute or transfer 
 * the modified or derivative version without prior written consent from Biber Ltd. Components 
 * from the Software may not be extracted and used in other programs without prior written consent 
 * from Biber Ltd.
 * 
 * TECHNICAL SUPPORT
 * 
 * Technical support is available through emailing Software Support at support@biberltd.com. 
 * Biber Ltd. does not provide direct phone support. No representations or guarantees are made 
 * regarding the response time in which support questions are answered.
 * 
 * REFUNDS
 * 
 * Biber Ltd. offers refunds on software within 15 days of purchase. All transaction fees will 
 * be deducted before refund is applied. Send a refund request for assistance.
 * 
 * INDEMNITY
 * 
 * You agree to indemnify and hold harmless Biber Ltd. for any third-party claims, actions or suits, 
 * as well as any related expenses, liabilities, damages, settlements or fees arising from your use 
 * or misuse of the Software, or a violation of any terms of this license.
 * 
 * DISCLAIMER OF WARRANTY
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, 
 * BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR 
 * FITNESS FOR A PARTICULAR PURPOSE.  FURTHER, BIBER LTD DOES NOT WARRANT THAT THE SOFTWARE OR 
 * ANY RELATED SERVICE WILL ALWAYS BE AVAILABLE.
 * 
 * LIMITIATIONS OF LIBRARY
 * 
 * YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY
 *  ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE 
 * FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING 
 * BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE 
 * PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.
 * 
 * 
 * It is hereby understood that the downloading of any Biber Ltd.software automatically binds you to 
 * all the terms of this Software License Agreement.
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$plugin_info = array(
    'pi_name' => 'Biber Unit Converter',
    'pi_version' => '1.0.2',
    'pi_author' => 'Biber Ltd.',
    'pi_author_url' => 'http://biberltd.com/wiki/English:bbr_unit_converter/'
);

/**
 * Biber Unit Converter Class
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Selimcan Sakar
 * @copyright		Copyright (c) 2010, Biber Ltd.
 * @link			http://biberltd.com/wiki/
 */
class Bbr_unit_converter {

    var $return_data = "";

    /**
     * Functions to call unit lists
     * 
     */
    private function length_units() {
        return array(
            'mil' => 0.0000254,
            'km' => 1000,
            'm' => 1,
            'dm' => 0.1,
            'cm' => 0.01,
            'mm' => 0.001,
            'ft' => 0.3048,
            'inch' => 0.0254,
            'yards' => 0.9144
        );
    }

    private function weight_units() {
        return array(
            'kg' => 1,
            'g' => 0.001,
            'dg' => 0.1,
            'cg' => 0.01,
            'mg' => 0.001,
            'lbs' => 0.45359237,
            'tonne' => 1000,
            'carat' => 0.0002,
            'oz' => 0.028349523125
        );
    }

    private function time_units() {
        return array(
            'y' => 31536000,
            'w' => 604800,
            'd' => 86400,
            'h' => 3600,
            'min' => 60,
            'sec' => 1,
            'msec' => 0.000000001,
            'nsec' => 0.000000000001,
        );
    }

    private function time_true_units() {
        return array(
            'y' => 31536000,
            'w' => 604800,
            'd' => 86400,
            'h' => 3600,
            'min' => 60,
            'sec' => 1,
            'msec' => 0.000000001,
            'nsec' => 0.000000000001,
        );
    }

    private function speed_units() {
        return array(
            "cm/min" => 0.00016666666666666666,
            "cm/sec" => 0.01,
            "ft/h" => 0.00008466683600033866,
            "ft/min" => 0.00508,
            "ft/sec" => 0.3048,
            "inch/min" => 0.0004233341800016934,
            "inch/sec" => 0.0254,
            "km/h" => 0.2777777777777778,
            'knot' => 0.5144444444444444444,
            'mach' => 340.2933,
            "m/h" => 0.0002777777777777778,
            "m/min" => 0.016666666666666666,
            "m/s" => 1,
            "yard/h" => 0.000254000508001016,
            "yard/min" => 0.01524,
            "yard/s" => 0.9144,
            'c' => 2.9979e8
        );
    }

    private function angle_units() {
        return array(
            'arcminute' => array('from' => "\$result = \$result / (360 * 60);", 'to' => "\$result = \$result * (360 * 60);"),
            'arcsecond' => array('from' => "\$result = \$result / (360 * 3600);", 'to' => "\$result = \$result * (360 * 3600);"),
            'circle' => 1,
            'turn' => 1,
            'degree' => array('from' => "\$result = \$result / 360;", 'to' => "\$result = \$result * 360;"),
            'grad' => array('from' => "\$result = \$result / 400;", 'to' => "\$result = \$result * 400;"),
            'octant' => 0.125,
            'quadrant' => 0.25,
            'radian' => array('from' => "\$result = \$result / (2 * M_PI);", 'to' => "\$result = \$result * (2 * M_PI);"),
            'sextant' => array('from' => "\$result = \$result / 6;", 'to' => "\$result = \$result * 6;"),
            'sign' => array('from' => "\$result = \$result / 12;", 'to' => "\$result = \$result * 12;")
        );
    }

    private function electric_currency_units() {
        return array(
            'aba' => 10,
            'a' => 1,
            "c/s" => 1,
            "v/omega" => 1,
            "w/v" => 1,
            'bi' => 0.01,
            'emu' => 10,
            'esu' => 3.335641e-10,
            'g' => 3.335641e-10,
            'ga' => 1000000000,
            'gi' => 0.79577472,
            'ka' => 1000,
            'ma' => 1000000,
            'ma' => 0.001,
            'mua' => 0.000001,
            'na' => 0.000000001
        );
    }

    private function energy_units() {
        return array(
            'j' => 1,
            'nm' => 1,
            'ws' => 1,
            'kJ' => 1000,
            'wh' => 3600,
            'cal' => 4186,
            'mj' => 1000000,
            'hph' => 1000000000,
        );
    }

    private function force_units() {
        return array(
            'dyn' => 0.00001,
            'kgf' => 9.80665,
            'kn' => 1000,
            'mn' => 1000000,
            'n' => 1,
            'lbf' => 4.4482216152605,
            't' => 1000000,
        );
    }

    private function power_units() {
        return array(
            'th/h' => 0.001162222222222222,
            'th/min' => 0.069733333333333333,
            'cal/h' => 1.162222222222222,
            'cal/min' => 69.733333333333333,
            'th/s' => 4.184,
            'gw' => 1000000000,
            'n' => 1,
            'lbf' => 4.4482216152605,
            'hp' => 745.69987158227022,
            'w' => 1,
            'j/h' => 1,
            'j/min' => 1,
            'j/s' => 1,
            'kw' => 1000,
            'mw' => 1000000,
        );
    }

    private function pressure_units() {
        return array(
            'atm' => 101325,
            'bar' => 10000,
            'cmhg' => 1333.22,
            "cmh<sub>2</sub>O" => 1333.22,
            "fh<sub>2</sub>O" => 2989.06692,
            "inh<sub>2</sub>O" => 249.08891,
            'inhg' => 249.08891,
            'mbar' => 100,
            'pa' => 1,
        );
    }

    private function volume_units() {
        return array(
            "dm<sup>3</sup>" => 1,
            "barrel(s)" => 35.23907016688,
            "gallon(s)" => 4.54609,
            'lt' => 1,
            "pint(s)" => 0.56826125,
            "tablespoon(s)" => 0.01478676478125,
            "teaspoon(s)" => 0.00492892159375
        );
    }

    private function digital_storage_units() {
        return array(
            'bit' => 0.125,
            'byte' => 1,
            'kbit' => 128,
            'kbyte' => 1024,
            'mbit' => 131072,
            'mbyte' => 1048576,
            'gbit' => 134217728,
            'gbyte' => 1073741824,
            'tbit' => 137438953472,
            'tbyte' => 1099511627776,
            'pbit' => 140737488355328,
            'pbyte' => 1125899906842624,
            'ebit' => 144115188075855872,
            'ebyte' => 1152921504606846976,
        );
    }

    private function density_units() {
        return array(
            'g/cm<sup>3</sup>' => 1,
            'g/l' => 0.001,
            'g/ml' => 1,
            'kg/m<sup>3</sup>' => 0.001,
            'kg/l' => 1,
            'mg/ml' => 0.001,
            'mg/l' => 0.000001,
            'ounce/inch<sup>3</sup>' => 27.679904,
            'pound/foot<sup>3</sup>' => 0.016018463,
            'tonne/m<sup>3</sup>' => 140737488355328,
        );
    }

    private function temperature_units() {
        return array(
            'celcius' => array('from' => "\$result = \$result + 273.15;", 'to' => "\$result = \$result - 273.15;"),
            'fahrenheit' => array('from' => "\$result = 5/9 * (\$result + 459.67);", 'to' => "\$result = 9/5 * \$result - 459.67;"),
            'kelvin' => 1
        );
    }

    /**
     * Constructor fix for PHP 4.x
     */
    function Bbr_unit_converter() {
        $this->__construct();
    }

    public function __construct() {

        $this->EE = & get_instance();
        $units = array();
        $round = '';
        $round_by = 0;
        $power = 1;

        $value = $this->EE->TMPL->tagdata;
        $unit_type = strtolower($this->EE->TMPL->fetch_param('unit_type'));
        $unit_from = strtolower($this->EE->TMPL->fetch_param('unit_from'));
        $unit_to = strtolower($this->EE->TMPL->fetch_param('unit_to'));
        $replace_unit = $this->EE->TMPL->fetch_param('replace_unit');
        $unit_position = strtolower($this->EE->TMPL->fetch_param('unit_position'));
        $round = $this->EE->TMPL->fetch_param('round');

        if (is_numeric($this->EE->TMPL->fetch_param('round_by'))) {
            $round_by = $this->EE->TMPL->fetch_param('round_by');
        }
        if (is_numeric($this->EE->TMPL->fetch_param('power'))) {
            $power = $this->EE->TMPL->fetch_param('power');
        }
        $show_unit = $this->EE->TMPL->fetch_param('show_unit');
        $function_name = $unit_type . '_units';
        if (method_exists($this, $function_name)) {
            $units = $this->$function_name();
        }

        /**
         * Changing the $round variable in order to be able to use it with the PHP function round()
         */
        /**  REMOVED DUE TO INCOMPATIBILITY WITH EARLIER VERSIONS OF PHP THAN 5.3
          switch ($round){
          case "up":
          $round = PHP_ROUND_HALF_UP;
          break;
          case "down":
          $round = PHP_ROUND_HALF_DOWN;
          break;
          }
         */
        $result = $value;

        /**
         * if it's a value, than calculate the result, if not, it's an array of two calculation. 
         * a from-calculation and a to-calculation. then do the one calculation you need.
         */
        if (!isset($units[$unit_to])) {
            $this->return_data = $result;
            return;
        }
        if (!isset($units[$unit_from])) {
            $this->return_data = $result;
            return;
        }
        if (isset($units[$unit_from]) && is_numeric($units[$unit_from])) {
            $result = $result * (pow(($units[$unit_from]), $power));
        } else {
            if (isset($units[$unit_from])) {
                eval($units[$unit_from]['from']);
            }
        }

        if (isset($units[$unit_to]) && is_numeric($units[$unit_to])) {
            $result = $result / (pow(($units[$unit_to]), $power));
        } else {
            if (isset($units[$unit_to])) {
                eval($units[$unit_to]['to']);
            }
        }

        /**
         * using the PHP function round()
         */
        if ($round_by >= 0 || $round != "") {
            if ($round_by != "" && $round != "") {
                $result = round($result, $round_by);
                switch ($round) {
                    case 'up':
                        $result = ceil($result);
                        break;
                    case 'down':
                        $result = floor($result);
                        break;
                }
            } else if ($round_by == "" && $round != "") {
                switch ($round) {
                    case 'up':
                        $result = ceil($result);
                        break;
                    case 'down':
                        $result = floor($result);
                        break;
                }
            } else {
                $result = round($result, $round_by);
            }
        }
        $this->return_data = $result;
        /**
         * Rounding fix for time
         */
        if ($unit_type == 'time_true') {
            $this->return_data = $this->time_conversion($value, $unit_to, $unit_from);
        }

        /**
         * show the unit, if needed
         */
        $show_unit = strtoupper($show_unit);
        if ($show_unit == 'TRUE') {
            if (!empty($replace_unit)) {
                $unit_to = $replace_unit;
            }
            switch ($unit_position) {
                case 'before':
                    $this->return_data = $unit_to . ' ' . $this->return_data;
                    break;
                case 'after':
                default:
                    $this->return_data .= ' ' . $unit_to;
                    break;
            }

            if ($power > 1) {
                $this->return_data .= "<sup>" . $power . "</sup>";
            }
        }
    }

    /**
     * function time_conversion($value, $unit_to, $unit_from)
     * 
     * @author: Oguz Ulgen
     *
     * Works to change the time in true conversion standarts
     */
    private function time_conversion($value, $unit_to, $unit_from) {

        if ($unit_from == 'sec') {
            if ($unit_to == 'min') {
                $result1 = (int) ($value / 60);
                $result2 = $value / 60 - $result1;
                $result2 *= 60;
                $result = $result1 . ':' . $result2;
            }
            if ($unit_to == 'h') {
                $result1 = (int) ($value / 3600);
                $result2 = $value / 3600 - $result1;
                $result2 = (int) ($result2 * 60);
                $result3 = ($value % 3600) % 60;
                $result = $result1 . ':' . $result2 . ':' . $result3;
            }
            if ($unit_to == 'd') {
                $result1 = (int) ($value / 86400);
                $result2 = (int) (($value % 86400) / 3600);
                $result3 = (int) ((($value % 86400) % 3600) / 60);
                $result4 = ($value % 86400) % 3600 % 60;
                $result = $result1 . ':' . $result2 . ':' . $result3 . ':' . $result4;
            }
        }
        if ($unit_from == 'min') {
            if ($unit_to == 'h') {
                $result1 = (int) ($value / 60);
                $result2 = $value / 60 - $result1;
                $result2 *= 60;
                $result = $result1 . ':' . $result2;
            }
            if ($unit_to == 'd') {
                $result1 = (int) ($value / 1440);
                $result2 = (int) (($value % 1440) / 60);
                $result3 = (int) (($value % 1440) % 60);
                $result = $result1 . ':' . $result2 . ':' . $result3;
            }
        }
        if ($unit_from == 'h') {
            if ($unit_to == 'd') {
                $result1 = (int) ($value / 24);
                $result2 = $value / 24 - $result1;
                $result2 *= 24;
                $result = $result1 . ':' . $result2;
            }
        }
        return $result;
    }

}

/**
 * Change Log:
 * 
 *  v 1.0.3
 * 
 * - A new unit type called time_true added. This way user gets non-fractal real time output.
 * 
 * v 1.0.2
 * 
 * - A bug in weight conversions is fixed.
 * 
 * v 1.0.1
 * 
 * - A bug in rounding of numbers has been fixed.
 * 
 */ 