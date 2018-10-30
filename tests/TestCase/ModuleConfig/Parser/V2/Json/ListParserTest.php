<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V2\Json;

use Cake\Core\Configure;
use PHPUnit\Framework\TestCase;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ListParser;
use Qobo\Utils\Utility;

class ListParserTest extends TestCase
{
    protected $parser;
    protected $dataDir;

    public function setUp()
    {
        $this->parser = new ListParser();
        $this->dataDir = TESTS . 'data' . DS . 'Modules';

        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V2');
    }

    public function tearDown()
    {
        unset($this->parser);
        unset($this->dataDir);
    }

    public function testParse(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'lists' . DS . 'local_genders.json';
        $result = $this->parser->parse($file);

        $resultArray = Utility::objectToArray($result);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('items', $resultArray);
        $this->assertEquals($resultArray['items']['m']['label'], 'M - Male');
    }

    public function testFilter(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'lists' . DS . 'local_genders.json';
        $result = $this->parser->parse($file, ['filter' => true]);

        $resultArray = Utility::objectToArray($result);
        $this->assertTrue(!in_array('foo', array_keys($resultArray['items'])));
    }

    public function testFlatten(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'lists' . DS . 'local_genders.json';
        $result = $this->parser->parse($file, ['filter' => true, 'flatten' => true]);

        $resultArray = Utility::objectToArray($result);

        $this->assertTrue(in_array('bar.bar_one', array_keys($resultArray['items'])));
        $this->assertTrue(in_array('bar.bar_two', array_keys($resultArray['items'])));
    }
}
