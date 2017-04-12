<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Ini;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Ini\FieldsParser;

class FieldsParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new FieldsParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseExceptionNonExisting()
    {
        $result = $this->parser->parse('some-non-existing-file');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseExceptionEmptyPath()
    {
        $result = $this->parser->parse('');
    }

    public function testGetErrors()
    {
        $caughtException = false;
        try {
            $result = $this->parser->parse('');
        } catch (\Exception $e) {
            $caughtException = true;
            $errors = $this->parser->getErrors();
            $this->assertTrue(is_array($errors), "Errors are not an array");
            $this->assertFalse(empty($errors), "Errors are empty");
            $this->assertEquals($e->getMessage(), $errors[0], "Error message is different from exception message");
        }
        $this->assertTrue($caughtException, "Exception was not caught");
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");

        $this->assertFalse(empty($result['cost']), "Parser missed 'cost' section");
        $this->assertFalse(empty($result['cost']['default']), "Parser missed 'default' key");
        $this->assertEquals('EUR', $result['cost']['default'], "Parser misinterpreted 'display_field' value");
    }
}
