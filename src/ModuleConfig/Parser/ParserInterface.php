<?php
namespace Qobo\Utils\ModuleConfig\Parser;

interface ParserInterface
{
    /**
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @throws InvalidArgumentException when file is not readable or not valid
     * @param string $path    Path to file
     * @param array  $options Options for parsing
     * @return array
     */
    public function parse($path, array $options = []);

    /**
     * Get parser errors
     *
     * @return array List of errors from last parsing
     */
    public function getErrors();
}
