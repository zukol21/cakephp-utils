<?php
namespace Qobo\Utils\ModuleConfig\Parser;

use Exception;
use InvalidArgumentException;
use League\JsonGuard\Dereferencer;
use League\JsonGuard\Validator;
use Qobo\Utils\Utility;

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
     * @var array $options Parsing options
     */
    protected $options = [];

    /**
     * @var array $defaults Default configuration
     */
    protected $defaults = [];

    /**
     * Read and parse a given path
     *
     * @return array
     */
    abstract protected function getDataFromPath($path);

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
    public function parse($path, array $options = [])
    {
        $result = [];

        // Overwrite defaults
        if (!empty($options)) {
            $this->options = $options;
        }

        // If config file does not exist, use defaults
        $configFileExists = true;
        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            $result = $this->defaults;
            $configFileExists = false;
        }

        try {
            // Read and parse path
            if ($configFileExists) {
                $result = $this->getDataFromPath($path);
                $result = array_replace_recursive($this->defaults, $result);
            }
            // No need to validate empty data
            if (empty($result)) {
                return $result;
            }
            // Validate result
            $data = json_decode(json_encode($result));
            $schema = $this->getSchema();
            $this->validateData($data, $schema);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            throw new InvalidArgumentException($e->getMessage());
        }

        return $result;
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
     * Validate given data against schema
     *
     * @throws InvalidArgumentException when validation fails, and sets $errors
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
     * @return object
     */
    protected function getSchema()
    {
        if (empty($this->schema)) {
            throw new InvalidArgumentException("Schema not specified");
        }

        if (is_object($this->schema)) {
            return $this->schema;
        }

        if (is_string($this->schema)) {
            $deref = new Dereferencer();
            $this->schema = $deref->dereference($this->schema);

            return $this->schema;
        }

        throw new InvalidArgumentException("Schema is not a string or stdClass: " . print_r($this->schema, true));
    }
}
