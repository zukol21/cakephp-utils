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
        // [table] section
        if (!property_exists($data, 'table')) {
            $data->table = new StdClass();
        }

        // [table]icon
        if (!property_exists($data->table, 'icon') || empty($data->table->icon)) {
            $data->table->icon = 'cube';
        }

        // [table]searchable
        if (!property_exists($data->table, 'searchable')) {
            $data->table->searchable = true;
        }

        // [table]lookup_fields
        if (!property_exists($data->table, 'lookup_fields')) {
            $data->table->lookup_fields = [];
        }

        if (is_string($data->table->lookup_fields)) {
            $data->table->lookup_fields = explode(',', $data->table->lookup_fields);
        }

        // [table]typeahead_fields
        if (!property_exists($data->table, 'typeahead_fields')) {
            $data->table->typeahead_fields = [];
        }

        if (is_string($data->table->typeahead_fields)) {
            $data->table->typeahead_fields = explode(',', $data->table->typeahead_fields);
        }

        // [table]display_field
        if (!property_exists($data->table, 'display_field')) {
            $this->warnings = array_merge($this->warnings, ["'display_field' is not set in 'table' section"]);
        }

        // [virtualFields] section
        if (!property_exists($data, 'virtualFields')) {
            $data->virtualFields = new StdClass();
        }
        $virtualFields = json_decode(json_encode($data->virtualFields), true);
        foreach ($virtualFields as $virtualField => $realFields) {
            if (is_string($realFields)) {
                $data->virtualFields->$virtualField = explode(',', $realFields);
            }
        }

        // [associations] section
        if (!property_exists($data, 'associations')) {
            $data->associations = new StdClass();
        }

        // [associations]hide_associations
        if (!property_exists($data->associations, 'hide_associations')) {
            $data->associations->hide_associations = [];
        }

        if (is_string($data->associations->hide_associations)) {
            $data->associations->hide_associations = explode(',', $data->associations->hide_associations);
        }

        return $data;
    }
}
