<?php

namespace Toolbox;

/**
 * Class ArrayTools
 * @package Toolbox
 */

class ArrayTools
{
    /**
     * Verifies and returns an array containing parts matching $request.
     * @param array $array
     * @param string $request
     * @param string $delimiter
     * @return array
     */
    private static $request;
    private static $matches = array();
    private static $delimiter = '';
    /**
     * Splits all arrays, return string(s).
     * @var array
     * return string
     */
    private static $imploded = array();
    /**
     * Splits all arrays by a delimiter.
     * @var array
     * @return array
     */
    private static $exploded = array();
    /**
     * Returns the first array in a multidimensional one.
     * @param array $array
     * @return mixed
     */
    private static $call;
    private static $type;

    // Methods for arrayMatch

    /**
     * @param   array $array An array
     * @param   string $field The field to get values from
     * @param   bool $preserve_keys Whether or not to preserve the
     *                                     array keys
     * @param   bool $remove_nomatches If the field doesn't appear to
     *                                     be set, remove it from the array
     * @return  array
     *
     * @link    http://codex.wordpress.org/static function_Reference/wp_list_pluck
     **/
    public static function array_pluck(array $array, $field, $preserve_keys = TRUE, $remove_nomatches = TRUE)
    {
        $new_list = array();

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                if (isset($value->{$field})) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value->{$field};
                    } else {
                        $new_list[] = $value->{$field};
                    }
                } else if (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            } else {
                if (isset($value[$field])) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value[$field];
                    } else {
                        $new_list[] = $value[$field];
                    }
                } else if (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            }
        }

        return $new_list;
    }

    /**
     * Returns boolean or images in an array.
     * @param array $array
     * @param bool $asBoolean
     * @return array|bool
     */
    public static function getImages(array $array = array(), $asBoolean = false)
    {
        $matches = array();
        $supported_image = array(
            'gif',
            'jpg',
            'jpeg',
            'ico',
            'png'
        );

        foreach ($array as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
            if (in_array($ext, $supported_image))
                $matches[] = $file;
        }
        return $asBoolean ? !empty($matches) ? true : false : $matches;
    }
    // @end

    /**
     * Matches two arrays against each other.
     * @param array $array0
     * @param array $array1
     * @param bool $assoc
     * @param bool $flip
     * @return array
     */
    public static function arrayMatch(array $array0, array $array1, $assoc = false, $flip = false)
    {
        return $assoc ? static::array_match_assoc($array0, $array1, $flip) : static::array_match($array0, $array1, $flip);
    }

    private static function array_match_assoc(array $array0, array $array1, $flip)
    {
        return $flip ? array_intersect_assoc($array0, array_flip($array1)) : array_intersect_assoc($array0, $array1);
    }

    private static function array_match(array $array0, array $array1, $flip)
    {
        return $flip ? array_intersect($array0, array_flip($array1)) : array_intersect($array0, $array1);
    }

    public static function implodeMultidimensional(array $array = array(), $delimiter = '')
    {
        if (!empty($delimiter)) static::$delimiter = $delimiter;
        array_walk_recursive($array, function ($value, $key) {
            if (is_array($value)) {
                static::$imploded[] = implode(static::$delimiter, $value);
            } else {
                static::$imploded[] = implode(static::$delimiter, array($key => $value));
            }
        });
        return static::$imploded;
    }

    /**
     * array_unique, on steroids.
     * @param array $array
     * @param bool $preserveKeys
     * @return array
     */
    public static function arrayUnique(array $array = array(), $preserveKeys = false)
    {
        // Unique Array for return
        $arrayRewrite = array();
        // Array with the md5 hashes
        $arrayHashes = array();
        foreach ($array as $key => $item) {
            // Serialize the current element and create a md5 hash
            $hash = md5(serialize($item));
            // If the md5 didn't come up yet, add the element to
            // to arrayRewrite, otherwise drop it
            if (!isset($arrayHashes[$hash])) {
                // Save the current element hash
                $arrayHashes[$hash] = $hash;
                // Add element to the unique Array
                if ($preserveKeys) {
                    $arrayRewrite[$key] = $item;
                } else {
                    $arrayRewrite[] = $item;
                }
            }
        }
        return $arrayRewrite;
    }

    /**
     * in_array, on steroids.
     * @param array $haystack
     * @param $needle
     * @param string $delimiter
     * @return bool
     */
    public static function inArray(array $haystack = array(), $needle, $delimiter = '')
    {
        if (in_array($needle, $haystack)) {
            return true;
        }
        if (static::arrayContains($haystack, $needle, $delimiter)) {
            return true;
        }
        return false;
    }

    public static function arrayContains(array $array = array(), $request = '', $delimiter = '')
    {
        if (isset($delimiter)) {
            static::$delimiter = $delimiter;
            $array = array_unique(static::explodeMultidimensional($array));
        }
        static::$request = $request;
        array_walk_recursive($array, function ($v, $k) {
            if ($k OR $v == static::$request) {
                static::$matches[$k] = $v;
            }
        });
        return static::$matches;
    }

    public static function explodeMultidimensional(array $array = array(), $delimiter = '')
    {
        if (!empty($delimiter)) static::$delimiter = $delimiter;
        array_walk_recursive($array, function ($value, $key) {
            if (!is_array($value)) {
                static::$exploded[] = explode(static::$delimiter, $value);
            } else {
                static::$exploded[] = static::explodeMultidimensional($value);
            }
        });
        return static::$exploded;
    }

    /**
     * Converts an array to a string.
     * @param $array
     * @return string
     */
    public static function array2string($array, $delimiter)
    {
        $str = "";
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $str = implode($delimiter, $v);
            } else {
                $str .= $v . $delimiter;
            }
        }
        return $str;
    }

    /**
     * @param $type |  Value or Key
     * @return static
     */
    public static function getFirst($type)
    {
        static::$type = $type;
        static::$call = __FUNCTION__;
        return new static;
    }

    /**
     * @param $type |  Value or Key
     * @return static
     */
    public static function getLast($type)
    {
        static::$type = $type;
        static::$call = __FUNCTION__;
        return new static;
    }

    public function In(array $array = array())
    {
        switch (static::$call) {
            case 'getFirst':
                if (static::$type == 'value') {
                    if (phpversion() >= '5.6.0') {
                        return array_values($array)[0];
                    }
                    return array_shift(array_values($array));
                } else {
                    if (phpversion() >= '5.6.0') {
                        return array_keys($array)[0];
                    }
                    return array_shift(array_keys($array));
                }
                break;

            case 'getLast':
                if (static::$type == 'value') {
                    return array_pop(array_values($array));
                } else {
                    return array_pop(array_keys($array));
                }
                break;
        }
    }

    /**
     * array_values, for objects.
     * @param $object
     * @param $target
     * @return string
     */
    public static function getObjectValues($object, $target)
    {
        $request = '';
        $array = array();
        foreach ($object as $key => $value) {
            $array[] .= $value->$target;
            $request = implode("\n", $array);
        }
        return $request;
    }

    /**
     * array_keys, for objects.
     * @param $object
     * @param $target
     * @return string
     */
    public static function getObjectKeys($object, $target)
    {
        $request = '';
        $array = array();
        foreach ($object as $key => $value) {
            $array[] .= $key->$target;
            $request = implode("\n", $array);
        }
        return $request;
    }

    /**
     * Verifies an array is multidimensional.
     * @param $array
     * @return bool
     */
    public static function isMultidimensional(array $array = array())
    {
        if (count($array) == count($array, COUNT_RECURSIVE)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * An improved version of array_merge.
     * @param array $array
     * @return array|mixed
     */
    public static function merge_array(array $array = array())
    {
        return call_user_func_array('array_merge', $array);
    }

    /**
     * array_merge_recursive, on steroids.
     * @param array $arrays
     * @return array
     */
    public static function array_merge_recursive(array $arrays = array())
    {
        $merged = array();
        $mapNum = array_map("count", $arrays);
        $getNum = array_sum($mapNum);
        for ($i = 0; $i < $getNum; $i++) {
            $merged[] = array_merge_recursive($arrays[$i], $arrays[$i]);
        }
        return $merged;
    }

    /**
     * Merges a multidimensional array or string.
     * @param $ArraysOrObjects
     * @return string
     */
    public static function mergeMultidimensional($ArraysOrObjects)
    {
        $imploded = '';
        foreach ($ArraysOrObjects as $array) {
            if (is_array($array)) {
                $imploded .= implode("\n", $array);
            } else {
                $imploded = implode("\n", get_object_vars($array));
            }
        }
        return $imploded;
    }

    /**
     * Count total number of arrays in multiDimensional Array
     * @param $arr
     * @return int
     */
    static function getTotalMultiArrayNum($arr)
    {
        $count = 0;
        foreach ($arr as $type) {
            $count += count($type, COUNT_RECURSIVE);
        }
        return $count;
    }

    /**
     * Count max number of keys available in multidimensional Array.
     * eg. use getTotalMultiArrayNum as $parents.
     * @param $arr
     * @param $parents
     * @return mixed|string
     */
    static function getMaxKeysInMultiArray(array $arr = array(), $parents)
    {
        $num = '';
        for ($i = min($arr, COUNT_RECURSIVE); $i <= $parents; $i++) {
            foreach ($arr as $q) {
                if (!empty($q[$i])) {
                    $num = $i;
                }
            }
        }
        return $num + 1;
    }

    /**
     * Filters an array, return parts matching.
     * @param $callable
     * @param $key
     * @param $needle
     * @return mixed
     */
    public static function filterArrayByCall($callable, $key, $needle)
    {
        $result = key(
            array_filter(
                $callable(),
                function ($entry) use ($key, $needle) {
                    return $entry[$key] == $needle;
                }
            )
        );
        return $result;
    }

    /**
     * Filters an object by a string.
     * similar to in_array but returns values instead of boolean.
     * @param $string
     * @param $obj
     * @return array
     */
    public static function filterObjectByString($string, $obj)
    {
        $array = array();
        foreach ($obj as $arr) {
            if (empty($arr->$string)) {
                $array = $arr;
            }
        }
        return $array;
    }

    /**
     * Returns keys of a multidimensional array.
     * @param $ar
     * @return array
     */
    public static function getMultiArrayKeys(array $ar = array())
    {
        $keys[] = array();
        foreach ($ar as $k => $v) {
            $keys[] = $k;
            if (is_array($ar[$k]))
                $keys = array_merge($keys, static::getMultiArrayKeys($ar[$k]));
        }
        return $keys;
    }

    /**
     * Returns a random value from an Array.
     * @param array $array
     * @param int $num
     * @return array
     */
    public static function getRandom(array $array, $num = 1)
    {
        shuffle($array);

        $r = array();
        for ($i = 0; $i < $num; $i++) {
            $r[] = $array[$i];
        }
        return $num == 1 ? $r[0] : $r;
    }

    /**
     * in_array for object arrays.
     * @param $obj
     * @param $haystack
     * @return bool
     */
    public static function existInObjectArray($obj, $haystack)
    {
        return count(array_map('unserialize', array_intersect(array_map('serialize', (array)$obj), array_map('serialize', (array)$haystack)))) > 0;
    }

    /**
     * Sanitize an array.
     * @param array $array
     * @return mixed
     */
    public static function sanitize(array $array = array())
    {
        return filter_var_array($array, FILTER_SANITIZE_STRING);
    }

    /**
     * Capitalizes all fields in an array.
     * @param array $fields
     * @return array
     */
    public static function Capitalize(array $fields = array())
    {
        $Capitalized = array();
        foreach ($fields as $key => $value) {
            $Capitalized[$key] = ucwords(implode("\n", $value));
        }
        return array_merge_recursive($fields, $Capitalized);
    }

    /**
     * Capitalizes only the first letter in field in array.
     * @param array $fields
     * @return array
     */
    public static function CapitalizeFirst(array $fields = array())
    {
        $Capitalized = array();
        foreach ($fields as $key => $value) {
            $Capitalized[$key] = ucfirst(implode("\n", $value));
        }
        return array_merge_recursive($fields, $Capitalized);
    }

    /**
     * A convenient way of removing multiple keys from an array.
     * @param array $remove
     * @param array $array
     * @return array
     */
    public static function removeMultiple(array $remove = array(), array $array = array())
    {
        return array_diff_key($array, array_flip($remove));
    }
}