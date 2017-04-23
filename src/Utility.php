<?php
namespace Qobo\Utils;

use Cake\Core\App;
use Cake\Core\Plugin;
use DirectoryIterator;
use Exception;
use InvalidArgumentException;

class Utility
{
    /**
     * Check validity of the given path
     *
     * @throws \InvalidArgumentException when path does not exist or is not readable
     * @param string $path Path to validate
     * @return void
     */
    public static function validatePath($path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("Path does not exist [$path]");
        }
        if (!is_readable($path)) {
            throw new InvalidArgumentException("Path is not readable [$path]");
        }
    }

    /**
     * Method that returns all controller names.
     *
     * @param bool $includePlugins Flag for including plugin controllers
     * @return array
     */
    public static function getControllers($includePlugins = true)
    {
        // get application controllers
        $result = static::getDirControllers(APP . 'Controller' . DS);

        if (!(bool)$includePlugins) {
            return $result;
        }

        $plugins = Plugin::loaded();
        // get plugins controllers
        foreach ($plugins as $plugin) {
            $path = Plugin::path($plugin) . 'src' . DS . 'Controller' . DS;
            $result = array_merge($result, static::getDirControllers($path, $plugin));
        }

        return $result;
    }

    /**
     * Method that retrieves controller names found on the provided directory path.
     *
     * @param string $path Directory path
     * @param string $plugin Plugin name
     * @param bool $fqcn Flag for using fqcn
     * @return array
     */
    public static function getDirControllers($path, $plugin = null, $fqcn = true)
    {
        $result = [];

        try {
            static::validatePath($path);
            $dir = new DirectoryIterator($path);
        } catch (Exception $e) {
            return $result;
        }

        foreach ($dir as $fileinfo) {
            // skip directories
            if (!$fileinfo->isFile()) {
                continue;
            }

            $className = $fileinfo->getBasename('.php');

            // skip AppController
            if ('AppController' === $className) {
                continue;
            }

            if (!empty($plugin)) {
                $className = $plugin . '.' . $className;
            }

            if ((bool)$fqcn) {
                $className = App::className($className, 'Controller');
            }

            if ($className) {
                $result[] = $className;
            }
        }

        return $result;
    }
}
