<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Csv\MigrationParser;

class MigrationParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new MigrationParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
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

    public function testWrapFromPath()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }
}
