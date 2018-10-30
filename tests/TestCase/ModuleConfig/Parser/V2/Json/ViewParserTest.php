<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V2\Json;

use Cake\Core\Configure;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ViewParser;
use Qobo\Utils\Utility;

class ViewParserTest extends TestCase
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

    public function testParse(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'views' . DS . 'add.json';

        $result = $this->parser->parse($file);
        $result = Utility::objectToArray($result);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('items', $result);
        foreach ($result['items'] as $rowFields) {
            $this->assertGreaterThan(0, count($rowFields));
            $this->assertLessThan(14, count($rowFields));
        }
    }

    public function testParseInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $file = $this->dataDir . DS . 'Foo' . DS . 'views' . DS . 'invalid.json';

        $this->parser->parse($file);
    }
}
