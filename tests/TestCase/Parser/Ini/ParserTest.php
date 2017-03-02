<?php
namespace Qobo\Utils\Test\TestCase\Parser\Ini;

use PHPUnit_Framework_TestCase;
use Qobo\Utils\Parser\Ini\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseFromPathException()
    {
        $parser = new Parser();
        $result = $parser->parseFromPath('some-non-existing-file');
    }

    public function testParseFromPath()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'config.ini';
        $parser = new Parser();
        $result = $parser->parseFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");

        $this->assertFalse(empty($result['table']), "Parser missed 'table' section");
        $this->assertFalse(empty($result['table']['display_field']), "Parser missed 'display_field' key");
        $this->assertEquals('name', $result['table']['display_field'], "Parser misinterpreted 'display_field' value");
    }

    public function testGetFieldsIniParamsDefault()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, 'cost', 'default');

        $this->assertEquals($result, 'EUR');
    }

    public function testGetFieldsIniParams()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, 'cost');

        $this->assertTrue(is_array($result));
        $this->assertTrue(in_array('default', array_keys($result)));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetFieldsIniParamsPathException()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams(123, 'cost');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetFieldsIniParamsFieldException()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, null);
    }

    public function testGetFieldsIniParamsNonReturned()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, 'birthdate', 'default');
        $this->assertEquals($result, null);
    }

    public function testGetFieldsIniParamsFileDoesntExist()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields_from_outer_space.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, 'birthdate', 'default');
        $this->assertEquals($result, null);
    }

    public function testGetFieldsIniArrayParams()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, 'cost', ['default', 'foo', 'baz']);
        $this->assertTrue((['default', 'foo'] == array_keys($result)));
    }

    public function testGetFieldsIniOneArrayElement()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $parser = new Parser();
        $result = $parser->getFieldsIniParams($file, 'cost', ['default']);
        $this->assertEquals($result, 'EUR');
    }

    public function testParseFromPathTestingArrays()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'config' . DS . 'array_in_config.ini';
        $parser = new Parser();
        $result = $parser->parseFromPath($file);

        $this->assertTrue(is_array($result), 'Return data from parser isn\'t array type');
        $this->assertArrayHasKey('associations', $result, "No associations found in the table config");
        $this->assertArrayHasKey('association_labels', $result['associations'], "No associations found in the table config");
        $this->assertTrue(is_array($result['associations']['association_labels']), "Associations label is not an array");
    }
}
