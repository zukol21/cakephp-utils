<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Csv\MigrationParser;

class MigrationParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new MigrationParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParseFromPath()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->parseFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrapFromPathException()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->wrapFromPath($file, [], 'foobar');
    }

    public function testWrapFromPath()
    {
        $file = $this->dataDir . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $result = $this->parser->wrapFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }
}
