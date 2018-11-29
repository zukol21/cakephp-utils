<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Schema;

use Cake\Filesystem\Folder;
use PHPUnit\Framework\TestCase;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;

class SchemaTest extends TestCase
{
    /**
     * Path to schema files
     * @var string
     */
    protected $schemaPath;

    /**
     * List of json schema files
     * @var \Seld\JsonLint\JsonParser
     */
    protected $linter;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        // All your dir are belong to us!
        $this->schemaPath = implode(DIRECTORY_SEPARATOR, [
            dirname(dirname(dirname(dirname(dirname(__DIR__))))),
            'src', 'ModuleConfig', 'Parser', 'Schema'
        ]);

        $this->linter = new JsonParser;
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown(): void
    {
        unset($this->schemaPath);
        unset($this->linter);

        parent::tearDown();
    }

    /**
     * Check the folder with json schemas.
     *
     * This unit test also serves as a data provider for `testLintJsonSchemas`
     * method
     *
     * @return string[] List of json schema files
     */
    public function testSchemaFolder(): array
    {
        $this->assertTrue(file_exists($this->schemaPath), "Path does not exist: $this->schemaPath");
        $this->assertTrue(is_dir($this->schemaPath), "Path is not a directory: $this->schemaPath");
        $this->assertTrue(is_readable($this->schemaPath), "Path is not readable: $this->schemaPath");

        $dir = new Folder($this->schemaPath);

        return $dir->read(false, true, true)[1];
    }

    /**
     * Lint an array of json schemas
     *
     * @depends testSchemaFolder
     * @param string[] $schemaFiles Array of paths to json schema files.
     * @return void
     */
    public function testLintJsonSchemas(array $schemaFiles): void
    {
        foreach ($schemaFiles as $file) {
            $contents = '';
            if (($contents = file_get_contents($file)) === false) {
                $this->fail("Could not read: `$file`");
            }

            $result = $this->linter->lint((string)$contents, JsonParser::DETECT_KEY_CONFLICTS);

            $this->assertNotInstanceOf(ParsingException::class, $result, $this->formatLinterError($file, $result));
            $this->assertNull($result);
        }
    }

    /**
     * Helper function which formats a `ParsingException` message if one is
     * given.
     *
     * @param string $file File location
     * @param ParsingException|null $result Parsing exception
     * @return string Formatted error message if `$result` not null, empty
     * string otherwise
     */
    protected function formatLinterError(string $file, ?ParsingException $result): string
    {
        return ($result instanceof ParsingException) ? $file . "\n" . $result->getMessage() : '';
    }
}
