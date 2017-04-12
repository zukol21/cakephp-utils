<?php
namespace Qobo\Utils\ModuleConfig\Parser;

use InvalidArgumentException;

abstract class AbstractParser implements ParserInterface
{
    /**
     * @var array $errors List of errors from last parsing
     */
    protected $errors = [];

    /**
     * @var array $options Parsing options
     */
    protected $options = [];

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
    abstract public function parse($path, array $options = []);

    /**
     * Get parser errors
     *
     * @return array List of errors from last parsing
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Validate path
     *
     * @throws InvalidArgumentException If $path does not exist or is not readable
     * @param string $path Path to validate
     * @return void
     */
    protected function validatePath($path)
    {
        $path = (string)$path;
        $path = trim($path);

        if (empty($path)) {
            $error = 'Path cannot be empty';
            $this->errors[] = $error;
            throw new InvalidArgumentException($error);
        }

        if (!is_readable($path)) {
            $error = "Path does not exist or is not readable: $path";
            $this->errors[] = $error;
            throw new InvalidArgumentException($error);
        }
    }
}
