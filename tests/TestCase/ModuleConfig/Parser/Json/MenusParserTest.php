<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Json;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Json\MenusParser;

class MenusParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new MenusParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'menus.json';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");

        $this->assertFalse(empty($result['main']), "Parser missed 'main' section");
        $this->assertFalse(empty($result['main']['enable']), "Parser missed 'enable' key");
        $this->assertEquals(true, $result['main']['enable'], "Parser misinterpreted 'enable' value");
    }
}
