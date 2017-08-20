<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Exception;
use Qobo\Utils\ModuleConfig\ModuleConfig;
use Qobo\Utils\ModuleConfig\Parser\Ini\ConfigParser;
use Qobo\Utils\ModuleConfig\PathFinder\ConfigPathFinder;

class ModuleConfigTest extends TestCase
{
    protected $pf;
    protected $dataDir;

    public function setUp()
    {
        $this->dataDir = dirname(dirname(__DIR__)) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFindNotFoundException()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo', 'this_file_is_not.there');
        $path = $mc->find();
    }

    public function testParse()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');
        $result = null;
        try {
            $result = $mc->parse();
        } catch (Exception $e) {
            debug($e->getMessage());
            debug($mc->getErrors());
            debug($mc->getWarnings());
        }
        $this->assertTrue(is_object($result), "Result is not an object");
        $this->assertFalse(empty(json_decode(json_encode($result), true)), "Result is empty");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseInvalidException()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_LIST, 'Foo', 'invalid_list.csv');
        $parser = $mc->parse();
    }

    public function testGetErrors()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');

        // Before parsing
        $result = $mc->getErrors();
        $this->assertTrue(is_array($result), "Errors is not an array before parsing");
        $this->assertTrue(empty($result), "Errors is not empty before parsing");
        // Parsing
        $result = null;
        try {
            $result = $mc->parse();
        } catch (Exception $e) {
            print_r($mc->getErrors());
        }
        // After parsing
        $result = $mc->getErrors();
        $this->assertTrue(is_array($result), "Errors is not an array after parsing");
    }

    public function testGetWarnings()
    {
        $mc = new ModuleConfig(ModuleConfig::CONFIG_TYPE_MODULE, 'Foo');

        // Before parsing
        $result = $mc->getWarnings();
        $this->assertTrue(is_array($result), "Warnings is not an array before parsing");
        $this->assertTrue(empty($result), "Warnings is not empty before parsing");
        // Parsing
        $result = null;
        try {
            $result = $mc->parse();
        } catch (Exception $e) {
            print_r($mc->getErrors());
        }
        // After parsing
        $result = $mc->getErrors();
        $this->assertTrue(is_array($result), "Warnings is not an array after parsing");
    }
}
