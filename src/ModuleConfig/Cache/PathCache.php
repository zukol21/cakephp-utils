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
namespace Qobo\Utils\ModuleConfig\Cache;

use Exception;
use Qobo\Utils\Utility;

/**
 * PathCache Class
 *
 * This class extends the basic Cache with
 * additional checks based on the file path.
 * The cached data also contains the path to file
 * and its md5, which are checked during the reading
 * and if the file has been changed since, the
 * cache is invalidated and cleared.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class PathCache extends Cache
{
    /**
     * Required keys for the cached value
     *
     * Configuration for validating cached values.  An
     * associative array of keys to check for presence,
     * and the boolean for whether or not empty values
     * are allowed.
     *
     * @param array $requiredKeys Cached value validation
     */
    protected $requiredKeys = [
            'path' => true,
            'md5' => true,
            'data' => false,
        ];

    /**
     * Validate the value
     *
     * @param mixed $value Value to check
     * @return bool False if invalid, true if valid
     */
    protected function isValidCache($value)
    {
        $result = false;

        $parentResult = parent::isValidCache($value);
        if ($parentResult === false) {
            return $result;
        }

        $path = array_key_exists('path', $value) ? $value['path'] : '';
        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            $this->errors[] = "Path does not exist or is not readable: $path";

            return $result;
        }

        $md5 = array_key_exists('md5', $value) ? $value['md5'] : '';
        if (md5(file_get_contents($value['path'])) <> $md5) {
            $this->warnings[] = 'Stale cache found. Cleaning up and ignoring';

            return $result;
        }

        $result = true;

        return $result;
    }

    /**
     * Write a value to cache key
     *
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param array $params Additional parameters
     * @return bool False on failure, true on success
     */
    public function writeTo($key, $data, array $params = [])
    {
        $result = false;

        if (empty($params['path'])) {
            $this->errors[] = "Path parameter is required";

            return $result;
        }
        $path = $params['path'];

        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            $this->errors[] = "Path does not exist or is not readable: $path";

            return $result;
        }

        $cachedData = [
            'path' => $path,
            'md5' => md5(file_get_contents($path)),
            'data' => $data,
        ];

        $result = parent::writeTo($key, $cachedData, ['raw' => true]);

        return $result;
    }
}
