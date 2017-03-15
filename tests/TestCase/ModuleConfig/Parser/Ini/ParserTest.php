<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Ini;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Ini\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new Parser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseFromPathException()
    {
        $result = $this->parser->parseFromPath('some-non-existing-file');
    }

    public function testParseFromPath()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'config.ini';
        $result = $this->parser->parseFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");

        $this->assertFalse(empty($result['table']), "Parser missed 'table' section");
        $this->assertFalse(empty($result['table']['display_field']), "Parser missed 'display_field' key");
        $this->assertEquals('name', $result['table']['display_field'], "Parser misinterpreted 'display_field' value");
    }

    public function testGetFieldsIniParamsDefault()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams($file, 'cost', 'default');

        $this->assertEquals('EUR', $result);
    }

    public function testGetFieldsIniParams()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams($file, 'cost');

        $this->assertTrue(is_array($result));
        $this->assertTrue(in_array('default', array_keys($result)));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetFieldsIniParamsPathException()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams(123, 'cost');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetFieldsIniParamsFieldException()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams($file, null);
    }

    public function testGetFieldsIniParamsNonReturned()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams($file, 'birthdate', 'default');
        $this->assertEquals(null, $result);
    }

    public function testGetFieldsIniParamsFileDoesntExist()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields_from_outer_space.ini';
        $result = $this->parser->getFieldsIniParams($file, 'birthdate', 'default');
        $this->assertEquals(null, $result);
    }

    public function testGetFieldsIniArrayParams()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams($file, 'cost', ['default', 'foo', 'baz']);
        $this->assertTrue((['default', 'foo'] == array_keys($result)));
    }

    public function testGetFieldsIniOneArrayElement()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->getFieldsIniParams($file, 'cost', ['default']);
        $this->assertEquals('EUR', $result);
    }

    public function testParseFromPathTestingArrays()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'array_in_config.ini';
        $result = $this->parser->parseFromPath($file);

        $this->assertTrue(is_array($result), 'Return data from parser isn\'t array type');
        $this->assertArrayHasKey('associations', $result, "No associations found in the table config");
        $this->assertArrayHasKey('association_labels', $result['associations'], "No associations found in the table config");
        $this->assertTrue(is_array($result['associations']['association_labels']), "Associations label is not an array");
    }
}
