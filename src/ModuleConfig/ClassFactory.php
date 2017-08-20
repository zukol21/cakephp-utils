<?php
namespace Qobo\Utils\ModuleConfig;

use Cake\Core\Configure;
use RuntimeException;

/**
 * ClassFactory Class
 *
 * This class provides a factory design
 * pattern for instantiating helper
 * classes like path finders and parsers.
 */
class ClassFactory
{
    /**
     * Create a new instance of a helper class
     *
     * @throws \RuntimeException when cannot create instance
     * @param string $configType Configuration type
     * @param string $classType Class type
     * @param array $options Options
     * @return object
     */
    public static function create($configType, $classType, array $options = [])
    {
        $configType = (string)$configType;
        $classType = (string)$classType;

        $classMap = empty($options['classMap']) ? [] : (array)$options['classMap'];
        $classMap = static::getClassMap($classMap);
        if (empty($classMap[$configType][$classType])) {
            throw new RuntimeException("No [$classType] found for configurationi type [$configType]");
        }

        $className = $classMap[$configType][$classType];
        $result = static::getInstance($className);

        return $result;
    }

    /**
     * Get class map
     *
     * If no class map given, a default one will be returned.  Otherwise,
     * a given class map will be returned as is.  In the future, this can
     * be extended to validate/filter/adjust the provided class map.
     *
     * @param array $classMap Class map
     * @return array
     */
    protected static function getClassMap(array $classMap = [])
    {
        if (empty($classMap)) {
            Configure::load('Qobo/Utils.module_config');
            $classMap = Configure::read('ModuleConfig.classMap');
        }

        return $classMap;
    }

    /**
     * Get an instance of a given class
     *
     * This method is public because it is useful in a variety of
     * situations, not just for the factory via class map.
     *
     * RuntimeException is used instead of InvalidArgumentException
     * purely for backward compatibiilty reasons.
     *
     * @throws \RuntimeException when cannot create instance
     * @param string $class Class name to instantiate
     * @return object
     */
    public static function getInstance($class)
    {
        if (!is_string($class)) {
            throw RuntimeException("Class name name must be string. [" . gettype($class) . "] given");
        }

        if (!class_exists($class)) {
            throw RuntimeException("Class [$class] does not exist");
        }

        return new $class;
    }
}
