<?php
namespace Qobo\Utils\Test\TestCase\Parser\Csv;

use PHPUnit_Framework_TestCase;
use Qobo\Utils\Parser\Csv\MigrationParser;

class MigrationParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseFromPath()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $parser = new MigrationParser();
        $result = $parser->parseFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrapFromPathException()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'CsvMigrations' . DS . 'migrations' . DS . 'Foo' . DS . 'migration.csv';
        $parser = new MigrationParser();
        $result = $parser->wrapFromPath($file, [], 'foobar');
    }

    public function testWrapFromPath()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Foo' . DS . 'db' . DS . 'migration.csv';
        $parser = new MigrationParser();
        $result = $parser->wrapFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }
}
