<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use Qobo\Utils\Utility;
use StdClass;

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
     * Parsing options
     */
    protected $options = [
        // Structure of the some_list.csv file
        'structure' => [
            'value',
            'label',
            'inactive',
        ],
    ];

    /**
     * Read and parse a given path
     *
     * @param string $path Path to file
     * @return object
     */
    protected function getDataFromPath($path)
    {
        // List files are required
        Utility::validatePath($path);
        return parent::getDataFromPath($path);
    }
}
