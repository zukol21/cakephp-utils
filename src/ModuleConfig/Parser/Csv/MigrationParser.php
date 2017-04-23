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
class MigrationParser extends AbstractCsvParser
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
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'migration.json';

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
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Parsing options
     * @return array
     */
    public function parse($path, array $options = [])
    {
        $fields = parent::parse($path, $options);
        if (empty($fields)) {
            return $fields;
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
