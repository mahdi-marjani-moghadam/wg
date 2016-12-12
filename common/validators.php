<?php
/**
 * PHP Validator doc
 *
 * User: mahm0ud22
 * Date: 10/31/14
 * Time: 10:58 AM
 *
 * @Auther Mahmoud Masih Tehrani
 * @Email mahmud.tehrani@gmail.com
 * @URL http://masihtehrani.ir
 * @version 0.0.1
 */
class Validator
{
    /**
     * check require string
     *
     * @param $value
     * @return int
     */
    public static function required($value)
    {
        $x = (isset($value) && $value != '') ? 1 : 0;
        return $x;
    }
    /**
     * check reqire array
     *
     * @param $value
     * @return int
     */
    public static function requiredArray($value)
    {
        $x = (is_array($value) && count($value) > 0) ? 1 : 0;
        return $x;
    }
    /**
     * handle data
     *
     * @param $value
     * @return string
     */
    public static function handleData($value)
    {
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = strip_tags($value);
        $value = stripslashes($value);
        $value = htmlentities($value);
        return $value;
    }
    /**
     * validator Boolean
     *
     * @param $value |Boolean
     * @return bool | 1 validator is true | 0 validator is false
     * @version 1.0.3
     * @access public
     */
    public static function Boolean($value)
    {
        $acceptable = array(true, false, 0, 1, '0', '1');
        $y = (in_array($value, $acceptable, true)) ? 1 : 0;
        return $y;
    }
    /**
     * validator Email
     *
     * @param $value |Email
     * @param $require |is required!? 0,1
     * @return int|string | -1 is not required | 1 validator is true | 0 validator is false
     * @version 1.0.2
     * @access public
     */
    public static function Email($value, $require)
    {
        $x = ($require) ? ((self::required($value)) ?: -1) : '';
        $y = (filter_var($value, FILTER_VALIDATE_EMAIL) == true) ? 1 : 0;
        $z = ($x == -1)?$x:$y;
        return $z;
    }
    /**
     * validator IP
     *
     * @param $value |ip
     * @param $require |is required!? 0,1
     * @return int|string | -1 is not required | 1 validator is true | 0 validator is false
     * @version 1.0.2
     * @access public
     */
    public static function IP($value, $require)
    {
        $x = ($require) ? ((self::required($value)) ?: -1) : '';
        $y = (filter_var($value, FILTER_VALIDATE_IP) == true) ? 1 : 0;
        $z = ($x == -1)?$x:$y;
        return $z;
    }
    /**
     * validator URL
     *
     * @param $value |URL
     * @param $require |is required!? 0,1
     * @return int|string | -1 is not required | 1 validator is true | 0 validator is false
     * @version 1.1.2
     * @access public
     */
    public static function URL($value, $require)
    {
        $x = ($require) ? ((self::required($value)) ?: -1) : '';
        $y = (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value)) ? 1 : 0;
        $z = ($x == -1)?$x:$y;
        return $z;
    }
    /**
     * validator Number
     *
     * @param $value |Number
     * @return bool | 1 validator is true | 0 validator is false
     * @version 1.0.3
     * @access public
     */
    public static function Numeric($value)
    {
        $y = (is_numeric($value)) ? 1 : 0;
        return $y;
    }
    /**
     * validator String
     *
     * @param $value |String
     * @param $require |is required!? 0,1
     * @return int|string | -1 is not required | 1 validator is true | 0 validator is false
     * @version 1.0.2
     * @access public
     */
    public static function String($value, $require)
    {
        $x = ($require) ? ((self::required($value)) ?: -1) : '';
        $y = (is_string($value)) ? 1 : 0;
        $z = ($x == -1)?$x:$y;
        return $z;
    }
    /**
     * validator just alphabet english
     *
     * @param $value |String
     * @param $require |is required!? 0,1
     * @return int|string | -1 is not required | 1 validator is true | 0 validator is false
     * @version 1.0.2
     * @access public
     */
    public static function alphabetEnglish($value, $require)
    {
        $x = ($require) ? ((self::required($value)) ?: -1) : '';
        $y = (ctype_alpha($value)) ? 1 : 0;
        $z = ($x == -1)?$x:$y;
        return $z;
    }
    /**
     * validator cellnumber
     *
     * @param $value |String
     * @return int|string | 1 validator is true | 0 validator is false
     * @version 1.0.1
     * @access public
     */
    public static function isPhone($input,$areaCode='98')
    {
        if($areaCode=='98')
        {
            if (preg_match("/^(989([0-3]{1}[0-9]{1})([0-9]{7}))*$/", $input) )
                return 1;
            else
                return 0;
        }
        else
        {
            return 1;
        }


    }
}
