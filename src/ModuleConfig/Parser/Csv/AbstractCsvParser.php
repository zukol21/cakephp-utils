<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use Exception;
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
     * @param string $path Path to file
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $result = new StdClass();
        $result->items = [];

        // If no structure specified (default or param), then use headers
        if (empty($this->options['structure'])) {
            $this->options['structure'] = $this->getHeadersFromPath($path);
        }

        $reader = Reader::createFromPath($path, $this->open_mode);
        $rows = $reader->setOffset(1)->fetchAssoc($this->options['structure']);
        foreach ($rows as $row) {
            $result->items[] = (object)json_decode(json_encode($row), true);
        }

        return $result;
    }
}
