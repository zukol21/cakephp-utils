<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use Exception;
use InvalidArgumentException;
use League\Csv\Reader;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;
use Qobo\Utils\Utility;
use StdClass;

abstract class AbstractCsvParser extends AbstractParser
{
    /**
     * Mode to use for opening CSV files
     */
    protected $open_mode = 'r';

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
     * Read and parse a given path
     *
     * @throws \InvalidArgumentException when cannot read or decode path
     * @param string $path Path to file
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $result = new StdClass();
        $result->items = [];

        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            // If path is required, child class should check for it.
            $this->warnings[] = "Path does not exist: $path";
            $result = $this->mergeWithDefaults($result);

            return $result;
        }

        // Fail with empty structure
        if (empty($this->structure)) {
            throw new InvalidArgumentException("No structure defined fro reading path: $path");
        }

        $reader = Reader::createFromPath($path, $this->open_mode);
        $rows = $reader->setOffset(1)->fetchAssoc($this->structure);
        foreach ($rows as $row) {
            $row = json_encode($row);
            if ($row === false) {
                throw new InvalidArgumentException("Failed to encode row from path: $path");
            }
            $row = json_decode($row, true);
            if ($row === null) {
                throw new InvalidArgumentException("Failed to decode row from path: $path");
            }
            $result->items[] = (object)$row;
        }
        $result = $this->mergeWithDefaults($result);

        return $result;
    }
}
