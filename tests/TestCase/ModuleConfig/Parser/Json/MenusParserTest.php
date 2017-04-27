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

        $this->assertTrue(is_object($result), "Parser returned a non-object");
    }

    public function testParseMissing()
    {
        $file = $this->dataDir . 'MissingModule' . DS . 'config' . DS . 'menus.json';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");
    }
}
