<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 26-03-15
 * Time: 19:42
 */

namespace Toolbox;

/**
 * Class StringTools
 * @package Toolbox
 */
class StringTools
{


    /**
     * Returns a string by position given by chained method.
     * @var array
     */
    private static $string = array();
    private static $delimiter = '';
    private $method = '';

    public static function isAlphabetic($string)
    {
        return ctype_alpha($string);
    }

    public static function isAlphanumeric($string)
    {
        return ctype_alnum($string);
    }

    public static function isNumeric($string)
    {
        return ctype_digit($string);
    }

    public static function isHex($string)
    {
        return ctype_xdigit($string);
    }

    /**
     * Converts strings containing underscore(s) to CamelCased strings.
     * @param $string
     * @return mixed
     */
    public static function underscoredToUpperCamelCased($string)
    {
        return preg_replace('/(?:^|_)(.?)/e', "strtoupper('$1')", $string);
    }

    /**
     * Converts strings containing underscore(s) to CamelCased strings where the first is NOT with CamelCase.
     * @param $string
     * @return mixed
     */
    public static function underscoredTolowerCamelCased($string)
    {
        return preg_replace('/_(.?)/e', "strtoupper('$1')", $string);
    }

    /**
     * Converts strings containing CamelCases to underscores strings.
     * @param $string
     * @return string
     */
    public static function CamelCaseToUnderscored($string)
    {
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $string));
    }

    /**
     * @param $string
     * @return string
     */
    public static function CapitalizeFirst($string)
    {
        return ucfirst($string);
    }

    /**
     * @param $string
     * @return string
     */
    public static function Capitalize($string)
    {
        return ucwords($string);
    }

    /**
     * Returns lowercase string.
     * @param $string
     * @return string
     */
    public static function Lowercase($string)
    {
        return strtolower($string);
    }

    // Methods for chaining with GetStringBy.

    public static function getStringBy($delimiter, $string)
    {
        static::$delimiter = $delimiter;
        static::$string = explode($delimiter, $string);
        return new static;
    }

    /**
     * Verifies a string contains an image.
     * @param $string
     * @param bool $asBoolean
     * @return bool|string
     */
    public static function isImage($string, $asBoolean = false)
    {
        $matches = '';
        $supported_image = array(
            'gif',
            'jpg',
            'ico',
            'jpeg',
            'png'
        );

        $ext = strtolower(pathinfo($string, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
        if (in_array($ext, $supported_image))
            $matches = $string;

        return $asBoolean ? !empty($matches) ? true : false : $matches;
    }

    /**
     * Create an array from a string.
     * @param $string
     * @param $delimiter
     * @return array
     */
    public static function stringToArray($string, $delimiter)
    {
        return explode($delimiter, $string);
    }

    /**
     * Creates an array of Tokens.
     * @param $string
     * @param string $quotationMarks
     * @return array
     */
    public static function tokenize($string, $quotationMarks = '"\'')
    {
        $tokens = array(array(), array());
        for ($nextToken = strtok($string, ' '); $nextToken !== false; $nextToken = strtok(' ')) {
            if (strpos($quotationMarks, $nextToken[0]) !== false) {
                if (strpos($quotationMarks, $nextToken[strlen($nextToken) - 1]) !== false) {
                    $tokens[0][] = substr($nextToken, 1, -1);
                } else {
                    $tokens[0][] = substr($nextToken, 1) . ' ' . strtok($nextToken[0]);
                }
            } else {
                $tokens[1][] = $nextToken;
            }
        }
        return $tokens;
    }

    public function Last()
    {
        return array_pop(static::$string);
    }

    public function First()
    {
        return array_shift(static::$string);
    }

    public function Specific($num)
    {
        return static::$string[$num];
    }

    public function Entire()
    {
        $this->method = 'Entire';
        return $this;
    }

    public function asString()
    {
        return implode(static::$delimiter, static::$string);
    }

    public function asArray()
    {
        return static::$string;
    }

}