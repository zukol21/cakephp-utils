<?php
namespace Qobo\Utils\ModuleConfig\Parser;

interface ParserInterface
{
    /**
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Options for parsing
     * @return array
     */
    public function parse($path, array $options = []);
}
