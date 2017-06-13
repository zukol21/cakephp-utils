<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

use Cake\Core\Configure;
use InvalidArgumentException;
use Qobo\Utils\ErrorTrait;
use Qobo\Utils\Utility;

abstract class BasePathFinder implements PathFinderInterface
{
    use ErrorTrait;

    /**
     * CakePHP configuration key with base path
     *
     * @var string $pathConfigKey
     */
    protected $pathConfigKey = 'CsvMigrations.modules.path';

    /**
     * @var string $prefix Path prefix
     */
    protected $prefix;

    /**
     * @var string $fileName File name
     */
    protected $fileName;

    /**
     * Find path
     *
     * @param string $module Module to look for files in
     * @param string $path     Path to look for
     * @param bool   $validate Validate existence of the result
     * @return null|string|array Null for not found, string for single path, array for multiple paths
     */
    public function find($module, $path = null, $validate = true)
    {
        if (empty($module)) {
            $this->fail(new InvalidArgumentException("Module is not specified"));
        }

        if (!is_string($module)) {
            $this->fail(new InvalidArgumentException("Module name is not a string"));
        }

        if (empty($path)) {
            $path = $this->fileName;
        }

        if (!is_string($path)) {
            $this->fail(new InvalidArgumentException("Path is not a string"));
        }

        $result = '';
        if (!empty($this->pathConfigKey)) {
            $result = Configure::readOrFail($this->pathConfigKey);
        }
        $result .= $module . DIRECTORY_SEPARATOR;

        if (!empty($this->prefix)) {
            $path = $this->prefix . DIRECTORY_SEPARATOR . $path;
        }

        $result .= $path;

        if ($validate) {
            Utility::validatePath($result);
        }

        return $result;
    }
}
