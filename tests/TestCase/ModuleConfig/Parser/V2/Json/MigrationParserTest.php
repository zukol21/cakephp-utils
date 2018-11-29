<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V2\Json;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\MigrationParser;
use Qobo\Utils\Utility\Convert;

class MigrationParserTest extends TestCase
{
    /**
     * Migration parser
     * @var \Qobo\Utils\ModuleConfig\Parser\V2\Json\MigrationParser
     */
    protected $parser;

    /**
     * Path to modules
     * @var string
     */
    protected $dataDir;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->parser = new MigrationParser();
        $this->dataDir = TESTS . 'data' . DS . 'Modules';

        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V2');
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        unset($this->dataDir);
        unset($this->parser);
    }

    /**
     * Parse a valid migration json file.
     *
     * @return void
     */
    public function testParse(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'db' . DS . 'migration.json';

        $result = $this->parser->parse($file);
        $this->assertNotEmpty($result);
        $this->assertEmpty($this->parser->getErrors());

        $defaults = [
            'id' => [
                'required' => false,
                'non-searchable' => false,
                'unique' => false,
            ]
        ];

        $defaults = Hash::flatten($defaults);
        $result = Convert::objectToArray($result);
        $result = Hash::flatten($result);

        foreach ($defaults as $key => $value) {
            $this->assertArrayHasKey($key, $result, "Element `{$key}` is missing, should be defaulted by the schema.");
            $this->assertEquals($value, $result[$key], "Default value of `${key}` doesn't match");
        }
    }

    /**
     * Parse an invalid migration json file.
     *
     * @return void
     */
    public function testParseInvalid(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'db' . DS . 'migration_invalid.json';

        try {
            $this->parser->parse($file);
        } catch (InvalidArgumentException $e) {
            $this->assertContains('Error validating /id/required', $e->getMessage());
        }
    }
}
