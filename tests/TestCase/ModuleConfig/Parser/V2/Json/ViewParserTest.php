<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V2\Json;

use Cake\Core\Configure;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ViewParser;

class ViewParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    public function setUp()
    {
        $this->parser = new ViewParser();
        $this->dataDir = TESTS . 'data' . DS . 'Modules';

        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V2');
    }

    public function tearDown()
    {
        unset($this->dataDir);
        unset($this->parser);
    }

    public function testParse()
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'views' . DS . 'add.json';

        $result = json_decode(json_encode($this->parser->parse($file)), true);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('items', $result);
        foreach ($result['items'] as $rowFields) {
            $this->assertGreaterThan(0, count($rowFields));
            $this->assertLessThan(14, count($rowFields));
        }
    }

    public function testParseInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $file = $this->dataDir . DS . 'Foo' . DS . 'views' . DS . 'invalid.json';

        $this->parser->parse($file);
    }
}
