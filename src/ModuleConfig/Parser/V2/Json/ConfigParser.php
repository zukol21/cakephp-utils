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
namespace Qobo\Utils\ModuleConfig\Parser\V2\Json;

use stdClass;

/**
 * Config JSON Parser
 *
 * This parser is useful for module config JSON processing.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ConfigParser extends AbstractJsonParser
{
    /**
     * JSON schema
     *
     * This can either be a string, pointing to the file
     * or an stdClass with an instance of an already parsed
     * schema
     *
     * @var string|\stdClass $schema JSON schema
     */
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'config.json';

    /**
     * Default sections and configuration values
     *
     * @var array
     */
    protected $defaults = [
        'table' => [
            'display_field' => 'id',
            'icon' => 'cube',
            'searchable' => true,
            'lookup_fields' => [],
            'typeahead_fields' => [],
            'basic_search_fields' => [],
            'allow_reminders' => [],
            'translatable' => false,
            'permissions_parent_modules' => [],
        ],
        'virtualFields' => [],
        'associations' => [
            'hide_associations' => [],
        ],
        'associationLabels' => [],
        'notifications' => [
            'enable' => false,
            'ignored_fields' => [],
        ],
        'manyToMany' => [
            'modules' => [],
        ],
    ];

    /**
     * Merge with default values
     *
     * @param object $data Data to merge with defaults
     * @return object
     */
    protected function mergeWithDefaults($data = null)
    {
        // Set defaults
        foreach ($this->defaults as $section => $options) {
            // Make sure the section exists
            if (!property_exists($data, $section)) {
                $data->$section = new stdClass();
            }
            // Make sure default values are set
            foreach ($options as $option => $value) {
                if (!property_exists($data->$section, $option)) {
                    $data->$section->$option = $value;
                }
            }
        }

        return $data;
    }
}
