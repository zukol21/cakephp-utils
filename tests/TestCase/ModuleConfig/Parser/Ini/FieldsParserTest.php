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

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'fields.ini';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertFalse(empty($result), "Parser returned empty result");
        $this->assertFalse(empty($result['cost']), "Parser missed 'cost' section");
        $this->assertFalse(empty($result['cost']['default']), "Parser missed 'default' key");
        $this->assertEquals('EUR', $result['cost']['default'], "Parser misinterpreted 'display_field' value");
    }
}
