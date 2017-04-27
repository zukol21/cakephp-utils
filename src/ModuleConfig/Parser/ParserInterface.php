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
     * @return object
     */
    public function parse($path, array $options = []);

    /**
     * Get parser errors
     *
     * @return array List of errors from last parsing
     */
    public function getErrors();

    /**
     * Get parser warnings
     *
     * @return array List of warnings from last parsing
     */
    public function getWarnings();
}
