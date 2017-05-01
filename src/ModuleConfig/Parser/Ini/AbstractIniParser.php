<?php
namespace Qobo\Utils\ModuleConfig\Parser\Ini;

use Exception;
use InvalidArgumentException;
use Piwik\Ini\IniReader;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;
use Qobo\Utils\Utility;
use StdClass;

abstract class AbstractIniParser extends AbstractParser
{
    /**
     * Read and parse a given path
     *
     * @throws \InvalidArgumentException when cannot read or decode path
     * @param string $path Path to read and parse
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $result = new StdClass();

        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            // If path is required, child class should check for it.
            $this->warnings[] = "Path does not exist: $path";
            $result = $this->mergeWithDefaults($result);
            return $result;
        }

        try {
            $reader = new IniReader();
            $data = $reader->readFile($path);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Failed to read path: $path");
        }

        $data = json_encode($data);
        if ($data === false) {
            throw new InvalidArgumentException("Failed to encode data from path: $path");
        }

        $data = json_decode($data);
        if ($data === null) {
            throw new InvalidArgumentException("Failed to parse path: $path");
        }

        $result = (object)$data;
        $result = $this->mergeWithDefaults($result);

        return $result;
    }
}
