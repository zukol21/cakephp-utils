<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

/**
 * Migration CSV Parser
 *
 * This parser is useful for parsing migration CSV
 * files.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class MigrationParser extends Parser
{
    /**
     * Parsing options
     */
    protected $options = [
        // Structure of the migration.csv file
        'structure' => [
            'name',
            'type',
            'required',
            'non-searchable',
            'unique',
        ],
    ];

    /**
     * Field to use for wrapping into associative array
     */
    protected $wrapField = 'name';

    /**
     * Parse from path
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Parsing options
     * @return array
     */
    public function parseFromPath($path, array $options = [])
    {
        $fields = parent::parseFromPath($path, $options);
        if (empty($fields)) {
            return $fields;
        }

        // Overwrite defaults
        if (!empty($options)) {
            $this->options = $options;
        }

        // Convert indexed array to associative one,
        // using wrapField as a key for the record.
        $result = [];
        foreach ($fields as $field) {
            $result[$field[$this->wrapField]] = $field;
        }

        return $result;
    }
}
