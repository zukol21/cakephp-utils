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
     * @param array $classMap Class map
     * @return object
     */
    public static function create($configType, $classType, array $classMap = [])
    {
        $configType = (string)$configType;
        $classType = (string)$classType;

        $classMap = static::getClassMap($classMap);
        if (empty($classMap[$configType][$classType])) {
            throw new RuntimeException("No [$classType] found for configurationi type [$configType]");
        }

        $className = $classMap[$configType][$classType];
        if (!is_string($className)) {
            throw new RuntimeException("Class name is not a string for [$configType] [$classType]");
        }

        if (!class_exists($className)) {
            throw new RuntimeException("Class [$className] does not exist");
        }

        return new $className;
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
}
