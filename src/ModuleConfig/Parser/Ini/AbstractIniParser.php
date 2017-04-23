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
     * @return array
     */
    protected function getDataFromPath($path)
    {
        $reader = new IniReader();
        $result = $reader->readFile($path);

        return $result;
    }
}
