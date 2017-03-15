<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig;

use Cake\Core\Configure;
use Qobo\Utils\ModuleConfig\ModuleConfig;
use Qobo\Utils\Parser\Ini\Parser;
use Qobo\Utils\PathFinder\ConfigPathFinder;
use PHPUnit_Framework_TestCase;

class ModuleConfigTest extends PHPUnit_Framework_TestCase
{
    protected $pf;

    protected function setUp()
    {
        $dir = dirname(dirname(__DIR__)) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $dir);
    }

    public function testSetFinder()
    {
        $expected = new ConfigPathFinder();
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');
        $mc->setFinder($expected);
        $result = $mc->getFinder();
        $this->assertFalse(empty($result), "Path finder is empty");
        $this->assertEquals($expected, $result, "Setting path finder is broken");
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetFinderException()
    {
        $mc = new ModuleConfig('unsupportedType', 'Foo');
        $mc->getFinder();
    }

    public function testSetParser()
    {
        $expected = new Parser();
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');
        $mc->setParser($expected);
        $result = $mc->getParser();
        $this->assertFalse(empty($result), "Parser is empty");
        $this->assertEquals($expected, $result, "Setting parser is broken");
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetParserException()
    {
        $mc = new ModuleConfig('unsupportedType', 'Foo');
        $mc->getParser();
    }


    public function testFind()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');
        $path = $mc->find();
        $this->assertFalse(empty($path), "Path is empty [$path]");
        $this->assertTrue(is_string($path), "Path is not a string [$path]");
        $this->assertTrue(file_exists($path), "Path does not exist [$path]");
        $this->assertTrue(is_readable($path), "Path is not readable [$path]");
        $this->assertTrue(is_file($path), "Path is not a file [$path]");
    }

    public function testFindOther()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo', 'other_config.ini');
        $path = $mc->find();
        $this->assertFalse(empty($path), "Path is empty [$path]");
        $this->assertTrue(is_string($path), "Path is not a string [$path]");
        $this->assertTrue(file_exists($path), "Path does not exist [$path]");
        $this->assertTrue(is_readable($path), "Path is not readable [$path]");
        $this->assertTrue(is_file($path), "Path is not a file [$path]");
    }

    public function testParse()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');
        $result = $mc->parse();
        $this->assertFalse(empty($result), "Result is empty");
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testValidate()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');
        $result = $mc->validate();
    }
}
