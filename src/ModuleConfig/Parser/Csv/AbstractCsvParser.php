<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use InvalidArgumentException;
use League\Csv\Reader;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;
use StdClass;

abstract class AbstractCsvParser extends AbstractParser
{
    /**
     * Mode to use for opening CSV files
     */
    protected $mode = 'r';

    /**
     * CSV file structure
     *
     * This is an optional list of column names, which will
     * be used as keys for the key-value parsing.
     *
     * @var array $structure List of column names
     */
    protected $structure = [];

    /**
     * Get empty result
     *
     * @return \StdClass
     */
    protected function getEmptyResult()
    {
        $result = parent::getEmptyResult();
        $result->items = [];

        return $result;
    }

    /**
     * Read and parse a given real path
     *
     * @throws \InvalidArgumentException when cannot read or decode path
     * @param string $path Path to file
     * @return object
     */
    protected function getDataFromRealPath($path)
    {
        $result = $this->getEmptyResult();

        // Fail with empty structure
        if (empty($this->structure)) {
            throw new InvalidArgumentException("No structure defined fro reading path: $path");
        }

        $reader = Reader::createFromPath($path, $this->mode);
        $rows = $reader->setOffset(1)->fetchAssoc($this->structure);
        foreach ($rows as $row) {
            $result->items[] = (object)$this->processRow($row, $path);
        }

        return $result;
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
        $row = json_encode($row);
        if ($row === false) {
            throw new InvalidArgumentException("Failed to encode row from path: $path");
        }
        $row = json_decode($row, true);
        if ($row === null) {
            throw new InvalidArgumentException("Failed to decode row from path: $path");
        }

        return $row;
    }
}
