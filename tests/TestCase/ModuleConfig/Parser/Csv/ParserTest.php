<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Csv\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new Parser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseException()
    {
        $result = $this->parser->parse('some-non-existing-file');
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetheadersFromPathException()
    {
        $result = $this->parser->getHeadersFromPath('some-non-existing-file');
    }

    public function testGetHeadersFromPath()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->getHeadersFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array for headers");
        $this->assertFalse(empty($result), "Parser returned empty result for headers");
    }
}
