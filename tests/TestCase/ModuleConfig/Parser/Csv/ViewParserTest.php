<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Csv\ViewParser;

class ViewParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new ViewParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'views' . DS . 'view.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
        $this->assertTrue(is_array($result[0]), "Parser returned a non-array first element");
        $this->assertFalse(empty($result[0]), "Parser returned a non-array first element");
        $this->assertEquals(3, count($result[0]), "Parser returned incorrect number of items in first element");
        $this->assertEquals('Details', $result[0][0], "Parser missed panel name in first element");
        $this->assertEquals('status', $result[0][1], "Parser missed first field in first element");
        $this->assertEquals('type', $result[0][2], "Parser missed second field in first element");
    }
}
