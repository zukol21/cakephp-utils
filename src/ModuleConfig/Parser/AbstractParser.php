<?php
namespace Qobo\Utils\ModuleConfig\Parser;

use Exception;
use InvalidArgumentException;
use League\JsonGuard\Dereferencer;
use League\JsonGuard\Validator;
use Qobo\Utils\Utility;
use StdClass;

abstract class AbstractParser implements ParserInterface
{
    /**
     * JSON schema
     *
     * This can either be a string, pointing to the file
     * or an StdClass with an instance of an already parsed
     * schema
     *
     * @var string|StdClass $schema JSON schema
     */
    protected $schema;

    /**
     * @var array $errors List of errors from last parsing
     */
    protected $errors = [];

    /**
     * @var array $errors List of warnings from last parsing
     */
    protected $warnings = [];

    /**
     * @var array $options Parsing options
     */
    protected $options = [];

    /**
     * Read and parse a given path
     *
     * @param string $path Path to file
     * @return object
     */
    abstract protected function getDataFromPath($path);

    /**
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Options for parsing
     * @return object
     */
    public function parse($path, array $options = [])
    {
        $result = new StdClass();

        // Overwrite default options
        if (!empty($options)) {
            $this->options = $options;
        }

        // If config file does not exist, use defaults
        $configFileExists = true;
        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            // Merge empty data with defaults
            $result = $this->mergeWithDefaults($result);
            $configFileExists = false;
            $this->warnings[] = "Path does not exist, relying on defaults: $path";
        }

        try {
            // Read and parse path
            if ($configFileExists) {
                $result = $this->getDataFromPath($path);
                $result = $this->mergeWithDefaults($result);
            }

            // No need to validate empty data (empty() does not work on objects)
            if (empty((array)$result)) {
                $this->warnings[] = "Skipping validation of empty result";

                return $result;
            }

            $schema = $this->getSchema();
            // No need to validate with empty schema (empty() does not work on objects)
            if (empty((array)$schema)) {
                $this->warnings[] = "Skipping validation with empty schema";

                return $result;
            }

            // Validate result
            $this->validateData($result, $schema);
        } catch (Exception $e) {
            $this->fail("[" . basename($path) . "] : " . $e->getMessage());
        }

        return $result;
    }

    /**
     * Fail execution with a given error
     *
     * * Adds error to the list of errors
     * * Throws an exception with the error message
     *
     * @throws \InvalidArgumentException
     * @param string $message Error message
     * @return void
     */
    protected function fail($message)
    {
        $this->errors[] = $message;
        throw new InvalidArgumentException($message);
    }

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
     * Get parser warnings
     *
     * @return array List of warnings from last parsing
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Validate given data against schema
     *
     * @param object $data Data to validate
     * @param object $schema Schema to validate against
     * @return void
     */
    protected function validateData($data, $schema)
    {
        $validator = new Validator($data, $schema);
        if ($validator->fails()) {
            foreach ($validator->errors() as $error) {
                $this->errors[] = $error->getMessage();
            }
            throw new InvalidArgumentException("Validation failed");
        }
    }

    /**
     * Get schema
     *
     * @return object|null
     */
    protected function getSchema()
    {
        $result = null;
        if (empty($this->schema)) {
            return $result;
        }

        if (is_object($this->schema)) {
            return $this->schema;
        }

        if (is_string($this->schema)) {
            $deref = new Dereferencer();
            $this->schema = $deref->dereference($this->schema);

            return $this->schema;
        }

        $this->fail("Schema is not a string or object: " . gettype($this->schema));
    }

    /**
     * Merge with default values
     *
     * @param object $data Data to merge with defaults
     * @return object
     */
    protected function mergeWithDefaults($data)
    {
        return $data;
    }
}
