<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V1\Json;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\V1\Json\MenusParser;

class MenusParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new MenusParser();
        $this->dataDir = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V1');
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'menus.json';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");
        $this->assertTrue(property_exists($result, 'main_menu'), "Parser missed 'main_menu' item");
        $this->assertTrue(is_array($result->main_menu), "Parser returned non-array for main menu items");
    }

    public function testParseMissing()
    {
        $file = $this->dataDir . 'MissingModule' . DS . 'config' . DS . 'menus.json';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testParseEmpty()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'empty.json';
        $result = $this->parser->parse($file);
    }
}
