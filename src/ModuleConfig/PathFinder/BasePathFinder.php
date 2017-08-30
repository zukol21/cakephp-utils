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
     * @var string $extension Default file extension
     */
    protected $extension = '';

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
        $this->validatePath($path);

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

    /**
     * Validate path string
     *
     * Check that the given path is a non-empty
     * string.
     *
     * NOTE: This method is very different from
     *       the Utility::validatePath(), which
     *       checks the filesystem path for
     *       existence.
     *
     * @param string $path Path string to check
     * @return void
     */
    protected function validatePath($path)
    {
        if (empty($path)) {
            $this->fail(new InvalidArgumentException("Path is not specified"));
        }

        if (!is_string($path)) {
            $this->fail(new InvalidArgumentException("Path is not a string"));
        }
    }

    /**
     * Add default extension if path doesn't have one
     *
     * Check if the given path has a file exntesion.
     * If not, add the default one and return the
     * modified path.
     *
     * @param string $path Path
     * @return string
     */
    protected function addDefaultExtension($path)
    {
        $result = $path;

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if (empty($extension)) {
            $path .= $this->extension;
        }

        return $path;
    }
}
