<?php

namespace Externalshop\System\Utils;

class ArrayUtils {
    
    function noDifferences(array $array):string {
        $string = '';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $string .= ';' . $this->noDifferences($value);
            } elseif ($value != 'values_equals') {
                return $string .= ';' . $key . ' x ' . $value;
            }
        }
        return trim($string, ';');
    }

    function arrayDiff( array $oldArray, array $newArray, array $skipkeys = [] ) {
        $responsearray = array();

        $keys1 = array_keys($oldArray);
        $keys2 = array_keys($newArray);

        foreach( $keys1 as $key ) {
            if (!in_array($key, $skipkeys)) {
                $responsearray[$key] = $this->compareArrayvalues($key, $oldArray, $newArray, $skipkeys);
            }
        }
        foreach( $keys2 as $key ) {
            if (!in_array($key, $skipkeys)) {
                $val = $this->checkExistence($key, $keys1);
                if ($val) {
                    $responsearray['field_' . $key] = $val;
                }
            }
        }
        return $responsearray;
    }

    function hashArray(array $array, array $fieldstohash, array $fieldsnottohash, int $minlength = 0):array {
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $jsonarray = json_decode($value, true);
                if (!empty($jsonarray) && is_array($jsonarray) && in_array($key, $fieldsnottohash)) {
                    $array[$key] = $value;
                } elseif (!empty($jsonarray) && is_array($jsonarray)) {
                    $array[$key] = json_encode($this->hashArray($jsonarray, $fieldstohash, $fieldsnottohash, $minlength));
                } elseif (in_array($value, [ '[]', '0', '1' ])) {
                    $array[$key] = $value;
                } else {
                    $array[$key] = $this->hashValue($key, $value, $fieldstohash, $fieldsnottohash, $minlength);
                }
            } elseif (is_array($value)) {
                $array[$key] = $this->hashArray($value, $fieldstohash, $fieldsnottohash, $minlength);
            }
        }
        return $array;
    }

    private function hashValue($key, $value, array $fieldstohash, array $fieldsnottohash, int $minlength) {
        foreach ($fieldstohash as $fkey) {
            if (
                strpos(strtolower($key), $fkey) !== false && 
                !in_array($key, $fieldsnottohash) && 
                strlen($value) > $minlength
               ) {
                return hash('md5', $value);
            }
        }
        return $value;
    }

    private function compareArrayvalues($key, $oldArray, $newArray, $skipkeys) {
        if ( array_key_exists( $key, $newArray ) ) {
            $val1 = $oldArray[ $key ];
            $val2 = $newArray[ $key ];
            if ( is_array( $val1 ) || is_array( $val2 ) ) {
                if ( is_array( $val1 ) && is_array( $val2 ) ) {
                    return $this->arrayDiff( $val1, $val2, $skipkeys );
                } else {
                    return 'type_inequal';
                }
            } elseif( 
                      hash('md5', $val1) == $val2 || $val1 == hash('md5', $val1)
                    ) {
                return 'values_equals';
            } elseif( $val1 == $val2 ) {
                return 'values_equals';
            } else {
                return 'values_unequalxxx strings ' . json_encode($val1) . 'xxx' . json_encode($val2);
            }
        } else {
            return 'value_unavailable_new';
        }
    }

    private function checkExistence($key, $keys1) {
        $keypresent = false;
        foreach ($keys1 as $key1) {
            if ($key == $key1) {
                $keypresent = true;
            }
        }
        if (!$keypresent) {
            return 'value_unavailable_old';
        }
        return '';
    }

}
