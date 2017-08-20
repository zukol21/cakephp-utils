<?php
namespace Qobo\Utils\ModuleConfig\Cache;

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

        if (md5($value['path']) <> $value['md5']) {
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

        $cachedData = [
            'path' => $path,
            'md5' => md5($path),
            'data' => $data,
        ];

        $result = parent::writeTo($key, $cachedData, ['raw' => true]);

        return $result;
    }
}
