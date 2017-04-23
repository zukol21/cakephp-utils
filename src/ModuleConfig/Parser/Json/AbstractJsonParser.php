<?php
namespace Qobo\Utils\ModuleConfig\Parser\Json;

use Qobo\Utils\ModuleConfig\Parser\AbstractParser;

abstract class AbstractJsonParser extends AbstractParser
{
    /**
     * Read and parse a given path
     *
     * @param string $path Path to read and parse
     * @return array
     */
    protected function getDataFromPath($path)
    {
        $result = json_decode(file_get_contents($path), true);

        return $result;
    }
}
