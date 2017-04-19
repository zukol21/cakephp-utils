<?php
namespace Qobo\Utils\ModuleConfig\Parser\Json;

use Exception;
use InvalidArgumentException;
use League\JsonGuard\Dereferencer;
use League\JsonGuard\Validator;
use Qobo\Utils\ModuleConfig\Parser\AbstractParser;

abstract class AbstractJsonParser extends AbstractParser
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
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Parsing options
     * @return array
     */
    public function parse($path, array $options = [])
    {
        $result = [];

        // Overwrite defaults
        if (!empty($options)) {
            $this->options = $options;
        }

        try {
            // Check if path is usable
            $this->validatePath($path);
            // Read and parse path
            $result = $this->getData($path);
            // No need to validate empty data
            if (empty($result)) {
                return $result;
            }
            // Validate result
            $data = $result;
            $schema = $this->getSchema();
            $this->validateData($data, $schema);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            throw new InvalidArgumentException($e->getMessage());
        }

        return $result;
    }

    /**
     * Read and parse a given path
     *
     * @param string $path Path to read and parse
     * @return array
     */
    protected function getData($path)
    {
        $result = json_decode(file_get_contents($path));

        return $result;
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
