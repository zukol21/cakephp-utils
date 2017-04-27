<?php
namespace Qobo\Utils\ModuleConfig\Parser\Ini;

use StdClass;

/**
 * Config INI Parser
 *
 * This parser is useful for module config INI processing.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ConfigParser extends AbstractIniParser
{
    /**
     * JSON schema
     *
     * This can either be a string, pointing to the file
     * or an StdClass with an instance of an already parsed
     * schema
     *
     * @var string|StdClass $schema JSON schema
     */
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'config.json';

    /**
     * Merge with default values
     *
     * @param object $data Data to merge with defaults
     * @return object
     */
    protected function mergeWithDefaults($data = null)
    {
        if (!property_exists($data, 'table')) {
            $data->table = new StdClass();
        }

        if (!property_exists($data->table, 'icon') || empty($data->table->icon)) {
            $data->table->icon = 'cube';
        }

        if (!property_exists($data->table, 'searchable')) {
            $data->table->searchable = true;
        }

        return $data;
    }
}
