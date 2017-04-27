<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Csv\ListParser;

class ListParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new ListParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Common' . DS . 'lists' . DS . 'genders.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);
        $this->assertFalse(empty($result), "Parser returned empty result");
        $this->assertEquals(2, count($result), "Parser returned incorrect count of list values");
        $this->assertTrue(array_key_exists('value', $result[0]), "Parser missed 'value' key in first element of gender list");
        $this->assertEquals($result[0]['value'], 'm', "Parser missed 'm' as 'value' key in first element of gender list");
    }
}
