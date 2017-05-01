<?php
namespace Qobo\Utils\ModuleConfig\Parser\Json;

use Exception;
use InvalidArgumentException;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;
use Qobo\Utils\Utility;
use StdClass;

abstract class AbstractJsonParser extends AbstractParser
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

        $data = file_get_contents($path);
        if ($data === false) {
            throw new InvalidArgumentException("Failed to read path: $path");
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
