<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use League\Csv\Reader;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;

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

        $this->validatePath($path);

        $reader = Reader::createFromPath($path, $this->open_mode);
        $result = $reader->fetchOne();

        return $result;
    }
}
