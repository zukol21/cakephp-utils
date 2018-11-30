<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V2\Json;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ConfigParser;
use Qobo\Utils\Utility\Convert;

class ConfigParserTest extends TestCase
{
    /**
     * Config parser
     * @var \Qobo\Utils\ModuleConfig\Parser\V2\Json\ConfigParser
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
        $this->parser = new ConfigParser();
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
     * Parse a valid config json file.
     *
     * @return void
     */
    public function testParse(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'config' . DS . 'config.json';

        $result = $this->parser->parse($file);
        $this->assertNotEmpty($result);
        $this->assertEmpty($this->parser->getErrors());

        // var_dump($result);
        // exit;
    }

    /**
     * Parse a valid config json file with ommited default values
     *
     * @return void
     */
    public function testParseWithoutDefaults(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'config' . DS . 'config_no_defaults.json';

        $result = $this->parser->parse($file);
        $this->assertNotEmpty($result);
        $this->assertEmpty($this->parser->getErrors());

        $defaults = [
            'table' => [
                'type' => 'module',
                'display_field' => 'id',
                'icon' => 'cube',
                'searchable' => true,
                'lookup_fields' => [],
                'typeahead_fields' => [],
                'basic_search_fields' => [],
                'allow_reminders' => [],
                'translatable' => false,
                'permissions_parent_modules' => [],
            ],
            'virtualFields' => [],
            'associations' => [
                'hide_associations' => [],
            ],
            'associationLabels' => [],
            'notifications' => [
                'enable' => false,
                'ignored_fields' => [],
            ],
            'manyToMany' => [
                'modules' => [],
            ],
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
     * Parse an invalid config json file.
     *
     * @return void
     */
    public function testParseInvalid(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'config' . DS . 'config_invalid.json';

        try {
            $this->parser->parse($file);

            $this->fail('JSON Validation was supposed to fail.');
        } catch (InvalidArgumentException $e) {
            $this->assertCount(1, $this->parser->getErrors(), 'Invalid error count.');
            $this->assertContains('The property invalidAlias is not defined', $this->parser->getErrors()[0], 'Invalid error occured.');
        }
    }
}
