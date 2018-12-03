<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Qobo\Utils\ModuleConfig\Parser;

use Cake\Core\InstanceConfigTrait;
use InvalidArgumentException;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\Exception\ValidationException;
use JsonSchema\Validator;
use Qobo\Utils\ErrorTrait;
use Qobo\Utils\ModuleConfig\Parser\SchemaInterface;
use Qobo\Utils\Utility;
use Qobo\Utils\Utility\Convert;
use Seld\JsonLint\ParsingException;
use stdClass;

class Parser implements ParserInterface
{
    use ErrorTrait;
    use InstanceConfigTrait;
    use JsonReaderTrait;

    /**
     * Default configuration.
     * @var array
     */
    protected $_defaultConfig = [
        'allowEmptyData' => true,
        'allowEmptySchema' => true,
        'lint' => false,
        'validationMode' => Constraint::CHECK_MODE_APPLY_DEFAULTS
    ];

    /**
     * JSON schema
     *
     * @var \Qobo\Utils\ModuleConfig\Parser\SchemaInterface $schema JSON schema
     */
    protected $schema;

    /**
     * @var array $options Parsing options
     */
    protected $options = [];

    /**
     * Class constructor.
     *
     * Options:
     * - allowEmptyData: Throw an exception when the data is empty.
     * - allowEmptySchema: Throw an exception when the data is empty.
     * - lint: Whether to lint the json file or not.
     * - validationMode: Validation mode which will be passed to Json validation.
     * See \JsonSchema\Constraints\Constraint::class for more on those.
     *
     * @param \Qobo\Utils\ModuleConfig\Parser\SchemaInterface $schema Schema object.
     * @param mixed[] $options Options.
     */
    public function __construct(SchemaInterface $schema, array $options = [])
    {
        $this->setSchema($schema);
        $this->setConfig($options);
    }

    /**
     * {@inheritDoc}
     */
    public function setSchema(SchemaInterface $schema): void
    {
        $this->schema = $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \InvalidArgumentException When data cannot be read from file.
     * @throws \Qobo\Utils\ModuleConfig\Parser\JsonValidationException When json validation fails.
     */
    public function parse(string $path, array $options = []): object
    {
        if (!empty($options)) {
            $this->setConfig($options);
        }

        $data = $this->getEmptyResult();

        try {
            Utility::validatePath($path);
            $result = (string)file_get_contents($path);
            $data = $this->dataToJson($result, $this->getConfig('lint'));
        } catch (ParsingException $e) {
            $this->errors[] = $e->getMessage();

            throw new InvalidArgumentException($e->getMessage(), 0, $e);
        } catch (InvalidArgumentException $e) {
            $this->errors[] = $e->getMessage();

            throw $e;
        }

        try {
            $this->validate($data);
        } catch (JsonValidationException $e) {
            throw new InvalidArgumentException($e->getMessage(), 0, $e);
        }

        return $data;
    }

    /**
     * Validate the JSON object against the schema.
     *
     * @param object $data JSON object.
     * @throws \Qobo\Utils\ModuleConfig\Parser\JsonValidationException When json validation fails.
     * @return void
     */
    protected function validate(object $data): void
    {
        $config = $this->getConfig();
        $schema = $this->getEmptyResult();

        try {
            $schema = $this->getSchema()->read();
        } catch (InvalidArgumentException $e) {
            $this->errors[] = $e->getMessage();

            throw new JsonValidationException("Schema file `{$this->schema->getSchemaPath()}` cannot be read", 0, $e);
        }

        // No need to validate empty data (empty() does not work on objects)
        $dataArray = Convert::objectToArray($data);
        if (empty($dataArray)) {
            if ($config['allowEmptyData'] === false) {
                throw new JsonValidationException('Empty data is not allowed.');
            }
            $this->warnings[] = "Skipping validation of empty data";

            return;
        }

        // No need to validate with empty schema (empty() does not work on objects)
        $schemaArray = Convert::objectToArray($schema);
        if (empty($schemaArray)) {
            if ($config['allowEmptySchema'] === false) {
                throw new JsonValidationException('Empty schema is not allowed.');
            }
            $this->warnings[] = "Skipping validation with empty schema";

            return;
        }

        $validator = new Validator;
        $validator->validate($data, $schema, $config['validationMode']);

        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $this->errors[] = sprintf('[%s]: %s', $error['pointer'], $error['message']);
            }

            throw new JsonValidationException('Failed to validate json data against the schema.');
        }
    }

    /**
     * Get empty result
     *
     * @return \stdClass
     */
    protected function getEmptyResult(): \stdClass
    {
        return new stdClass();
    }
}
