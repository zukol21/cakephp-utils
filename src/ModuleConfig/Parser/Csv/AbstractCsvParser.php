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
     * Get headers from path
     *
     * @param string $path Path to file
     * @return array
     */
    public function getHeadersFromPath($path)
    {
        $result = [];

        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            return $result;
        }

        $reader = Reader::createFromPath($path, $this->open_mode);
        $result = $reader->fetchOne();

        return $result;
    }

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

        // If no structure specified (default or param), then use headers
        if (empty($this->options['structure'])) {
            $this->options['structure'] = $this->getHeadersFromPath($path);
        }

        // Fail with empty structure
        if (empty($this->options['structure'])) {
            throw new InvalidArgumentException("Failed to read structure from path: $path");
        }

        $reader = Reader::createFromPath($path, $this->open_mode);
        $rows = $reader->setOffset(1)->fetchAssoc($this->options['structure']);
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
