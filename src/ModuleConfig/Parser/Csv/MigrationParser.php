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
     * @var string|\StdClass $schema JSON schema
     */
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'migration.json';

    /**
     * CSV file structure
     *
     * This is an optional list of column names, which will
     * be used as keys for the key-value parsing.
     *
     * @var array $structure List of column names
     */
    protected $structure = ['name', 'type', 'required', 'non-searchable', 'unique'];

    /**
     * Field to use for wrapping into associative array
     */
    protected $wrapField = 'name';

    /**
     * Read and parse a given path
     *
     * @param string $path Path to file
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $result = parent::getDataFromPath($path);

        if (empty($result->items)) {
            unset($result->items);

            return $result;
        }

        // Convert indexed array to associative one,
        // using wrapField as a key for the record.
        $fields = $result->items;
        unset($result->items);
        foreach ($fields as $field) {
            $result->{$field->{$this->wrapField}} = $field;
        }

        return $result;
    }
}
