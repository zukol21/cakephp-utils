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
        $data->table->lookup_fields = $this->csv2array($data->table->lookup_fields);

        // [table]typeahead_fields
        if (!property_exists($data->table, 'typeahead_fields')) {
            $data->table->typeahead_fields = [];
        }
        $data->table->typeahead_fields = $this->csv2array($data->table->typeahead_fields);

        // [table]basic_search_fields
        if (!property_exists($data->table, 'basic_search_fields')) {
            $data->table->basic_search_fields = [];
        }
        $data->table->basic_search_fields = $this->csv2array($data->table->basic_search_fields);

        // [table]allow_reminders
        if (!property_exists($data->table, 'allow_reminders')) {
            $data->table->allow_reminders = [];
        }
        $data->table->allow_reminders = $this->csv2array($data->table->allow_reminders);

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
            $data->virtualFields->$virtualField = $this->csv2array($realFields);
        }

        // [associations] section
        if (!property_exists($data, 'associations')) {
            $data->associations = new StdClass();
        }

        // [associations]hide_associations
        if (!property_exists($data->associations, 'hide_associations')) {
            $data->associations->hide_associations = [];
        }
        $data->associations->hide_associations = $this->csv2array($data->associations->hide_associations);

        // [associationLabels] section
        if (!property_exists($data, 'associationLabels')) {
            $data->associationLabels = new StdClass();
        }

        // [notifications] section
        if (!property_exists($data, 'notifications')) {
            $data->notifications = new StdClass();
        }

        // [notifications]enable
        if (!property_exists($data->notifications, 'enable')) {
            $data->notifications->enable = false;
        }

        // [notifications]ignored_fields
        if (!property_exists($data->notifications, 'ignored_fields')) {
            $data->notifications->ignored_fields = [];
        }
        $data->notifications->ignored_fields = $this->csv2array($data->notifications->ignored_fields);

        // [manyToMany] section
        if (!property_exists($data, 'manyToMany')) {
            $data->manyToMany = new StdClass();
        }

        // [manyToMany]modules
        if (!property_exists($data->manyToMany, 'modules')) {
            $data->manyToMany->modules = [];
        }
        $data->manyToMany->modules = $this->csv2array($data->manyToMany->modules);

        return $data;
    }

    /**
     * Convert a comma-separated string to array
     *
     * If provided $csv is not a string, return as is.
     *
     * @param string $csv String to convert
     * @return array
     */
    protected function csv2array($csv)
    {
        if (!is_string($csv)) {
            return $csv;
        }

        $result = explode(',', $csv);

        return $result;
    }
}
