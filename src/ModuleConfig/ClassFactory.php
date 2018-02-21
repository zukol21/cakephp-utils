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
     * @param \Qobo\Utils\ModuleConfig\ClassType $classType Class type
     * @param array $options Options
     * @return object
     */
    public static function create($configType, ClassType $classType, array $options = [])
    {
        $configType = (string)$configType;
        $classType = (string)$classType;

        $classMapVersion = empty($options['classMapVersion']) ? Configure::read('ModuleConfig.classMapVersion') : (string)$options['classMapVersion'];
        $classMap = empty($options['classMap'][$classMapVersion]) ? Configure::read('ModuleConfig.classMap.' . $classMapVersion) : (array)$options['classMap'][$classMapVersion];
        if (empty($classMap[$configType][$classType])) {
            throw new RuntimeException("No [$classType] found for configuration type [$configType] in class map version [$classMapVersion]");
        }

        $className = $classMap[$configType][$classType];
        $result = static::getInstance($className);

        return $result;
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
            throw new RuntimeException("Class name name must be string. [" . gettype($class) . "] given");
        }

        if (!class_exists($class)) {
            throw new RuntimeException("Class [$class] does not exist");
        }

        return new $class;
    }
}
