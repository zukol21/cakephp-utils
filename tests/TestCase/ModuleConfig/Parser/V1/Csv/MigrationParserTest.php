<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V1\Csv;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\V1\Csv\MigrationParser;

class MigrationParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new MigrationParser();
        $this->dataDir = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V1');
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertFalse(empty($result), "Parser returned empty result");
        $this->assertTrue(array_key_exists('id', $result), "Parser missed 'id' field");
        $this->assertTrue(array_key_exists('name', $result['id']), "Parser missed 'name' key in 'id' field");
        $this->assertEquals('id', $result['id']['name'], "Parser missed 'name' value in 'id' field");
        $this->assertTrue(array_key_exists('type', $result['id']), "Parser missed 'type' key in 'id' field");
        $this->assertEquals('uuid', $result['id']['type'], "Parser missed 'type' value in 'id' field");
        $this->assertTrue(array_key_exists('required', $result['id']), "Parser missed 'required' key in 'id' field");
        $this->assertTrue(array_key_exists('non-searchable', $result['id']), "Parser missed 'non-searchable' key in 'id' field");
        $this->assertTrue(array_key_exists('unique', $result['id']), "Parser missed 'unique' key in 'id' field");
    }

    public function testParseNonExisting()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'missing_migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");
        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertTrue(empty($result), "Parser returned empty result");
    }

    public function testParseEmpty()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'empty_migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertTrue(empty($result), "Parser returned non-empty result");
    }

    public function testWrapFromPath()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");
        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertFalse(empty($result), "Parser returned empty result");
    }
}
