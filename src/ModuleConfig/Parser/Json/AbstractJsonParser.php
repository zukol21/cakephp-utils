<?php
namespace Qobo\Utils\ModuleConfig\Parser\Json;

use Qobo\Utils\ModuleConfig\Parser\AbstractParser;

abstract class AbstractJsonParser extends AbstractParser
{
    /**
     * Read and parse a given path
     *
     * @param string $path Path to read and parse
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $result = (object)json_decode(file_get_contents($path));

        return $result;
    }
}
