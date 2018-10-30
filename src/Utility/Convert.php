<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Qobo\Utils\Utility;

use InvalidArgumentException;
use stdClass;

/**
 * Convert Class
 *
 * This class provides a variety of helper methods
 * to convert data between different formats, structures,
 * and the like.
 */
class Convert
{
    /**
     * Convert value to bytes
     *
     * Convert sizes from PHP settings like post_max_size
     * for example 8M to integer number of bytes.
     *
     * If number is integer return as is.
     *
     * NOTE: This is a modified copy from qobo/cakephp-utils/config/bootstrap.php
     *
     * @throws \InvalidArgumentException when cannot convert
     * @param string|int $value Value to convert
     * @return int
     */
    public static function valueToBytes($value): int
    {
        if (is_int($value)) {
            return $value;
        }

        $value = trim($value);

        // Native PHP check for digits in string
        if (ctype_digit(ltrim($value, '-'))) {
            return (int)$value;
        }

        $signed = (substr($value, 0, 1) === '-') ? -1 : 1;

        // Kilobytes
        if (preg_match('/(\d+)K$/i', $value, $matches)) {
            return (int)($matches[1] * $signed * 1024);
        }

        // Megabytes
        if (preg_match('/(\d+)M$/i', $value, $matches)) {
            return (int)($matches[1] * $signed * 1024 * 1024);
        }

        // Gigabytes
        if (preg_match('/(\d+)G$/i', $value, $matches)) {
            return (int)($matches[1] * $signed * 1024 * 1024 * 1024);
        }

        throw new InvalidArgumentException("Failed to find K, M, or G in a non-integer value [$value]");
    }

    /**
     * Convert an object to associative array
     *
     * NOTE: in case of any issues during the conversion, this
     * method will return an empty array and NOT throw any
     * exceptions.
     *
     * @param mixed $source Object to convert
     * @return mixed[]
     */
    public static function objectToArray($source): array
    {
        $result = [];

        if (is_array($source)) {
            return $source;
        }

        if (!is_object($source)) {
            return $result;
        }

        $json = json_encode($source);
        if ($json === false) {
            return $result;
        }

        $array = json_decode($json, true);
        if ($array === null) {
            return $result;
        }
        $result = $array;

        return $result;
    }

    /**
     * Convert an array to object
     *
     * NOTE: in case of any issues during the conversion, this
     * method will return an empty \stdClass instance and NOT throw
     * any exceptions.
     *
     * @param mixed[] $source Array to convert
     * @return \stdClass
     */
    public static function arrayToObject(array $source): \stdClass
    {
        $result = new stdClass();

        $json = json_encode($source);
        if ($json === false) {
            return $result;
        }

        $object = json_decode($json);
        if ($object === null) {
            return $result;
        }
        $result = $object;

        return $result;
    }
}
