<?php
namespace Qobo\Utils\ModuleConfig\Parser\Ini;

use Piwik\Ini\IniReader;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;

abstract class AbstractIniParser extends AbstractParser
{
    /**
     * Read and parse a given path
     *
     * @param string $path Path to read and parse
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $reader = new IniReader();
        $result = $reader->readFile($path);
        $result = (object)json_decode(json_encode($result));

        return $result;
    }
}
