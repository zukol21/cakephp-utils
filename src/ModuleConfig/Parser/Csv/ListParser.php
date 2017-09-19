<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use Exception;
use Qobo\Utils\Utility;

/**
 * List CSV Parser
 *
 * This parser is useful for parsing list CSV
 * files.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ListParser extends AbstractCsvParser
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
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'list.json';

    /**
     * CSV file structure
     *
     * This is an optional list of column names, which will
     * be used as keys for the key-value parsing.
     *
     * @var array $structure List of column names
     */
    protected $structure = ['value', 'label', 'inactive'];

    /**
     * @var bool $isPathRequired Is path required?
     */
    protected $isPathRequired = true;

    /**
     * Merge with default values
     *
     * @param object $data Data to merge with defaults
     * @return object
     */
    protected function mergeWithDefaults($data)
    {
        if (empty($data->items)) {
            return $data;
        }

        foreach ($data->items as $item) {
            if (!property_exists($item, 'children')) {
                $item->{'children'} = [];
            }
        }

        return $data;
    }

    /**
     * Process each row of data
     *
     * @param array $row Row data
     * @param string $path Path of the source
     * @return mixed
     */
    protected function processRow(array $row, $path)
    {
        $row = parent::processRow($row, $path);
        $row['children'] = $this->getChildren($row, $path);

        return $row;
    }

    /**
     * Get children for a given item
     *
     * @param array $row Item row
     * @param string $path Path of the source
     * @return array
     */
    protected function getChildren($row, $path)
    {
        $result = [];

        if (empty($row) || empty($row['value']) || empty($path)) {
            return $result;
        }

        // Remove .csv extension (ugly, but works)
        $childListPath = substr($path, 0, -4);
        $childListPath .= DIRECTORY_SEPARATOR . $row['value'] . '.csv';
        try {
            Utility::validatePath($childListPath);
        } catch (Exception $e) {
            // Child list does not exist, skip the rest
            return $result;
        }

        $parser = new ListParser();
        $children = $parser->parse($childListPath);
        $result = $children->items;

        return $result;
    }
}
