<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use Exception;
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
        // add, edit, view
        $file = $this->dataDir . 'Foo' . DS . 'views' . DS . 'view.csv';
        $result = null;
        try {
            $result = $this->parser->parse($file);
        } catch (Exception $e) {
            print_r($this->parser->getErrors());
        }

        $this->assertTrue(is_object($result), "Parser returned a non-object");
        $this->assertFalse(empty($result->items), "Parser returned empty items");
        $this->assertTrue(is_array($result->items), "Parser returned non-array items");

        // Convert object to array recursively
        $result = $result->items;

        $this->assertTrue(is_array($result[0]), "Parser returned a non-array first element");
        $this->assertFalse(empty($result[0]), "Parser returned a non-array first element");
        $this->assertEquals(3, count($result[0]), "Parser returned incorrect number of items in first element");
        $this->assertEquals('Details', $result[0][0], "Parser missed panel name in first element");
        $this->assertEquals('status', $result[0][1], "Parser missed first field in first element");
        $this->assertEquals('type', $result[0][2], "Parser missed second field in first element");

        // index
        $file = $this->dataDir . 'Foo' . DS . 'views' . DS . 'index.csv';
        $result = null;
        try {
            $result = $this->parser->parse($file);
        } catch (Exception $e) {
            print_r($this->parser->getErrors());
        }

        $this->assertTrue(is_object($result), "Parser returned a non-object");
        $this->assertFalse(empty($result->items), "Parser returned empty items");
        $this->assertTrue(is_array($result->items), "Parser returned non-array items");

        // Convert object to array recursively
        $result = $result->items;

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertTrue(is_array($result[0]), "Parser returned a non-array first element");
        $this->assertFalse(empty($result[0]), "Parser returned a non-array first element");
        $this->assertEquals(1, count($result[0]), "Parser returned incorrect number of items in first element");
        $this->assertEquals('status', $result[0][0], "Parser missed first field in first element");
        $this->assertEquals('type', $result[1][0], "Parser missed first field in second element");
    }
}
