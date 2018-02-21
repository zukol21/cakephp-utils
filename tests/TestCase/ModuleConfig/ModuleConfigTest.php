<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Exception;
use Qobo\Utils\ModuleConfig\ConfigType;
use Qobo\Utils\ModuleConfig\ModuleConfig;
use Qobo\Utils\ModuleConfig\Parser\Ini\ConfigParser;
use Qobo\Utils\ModuleConfig\PathFinder\ConfigPathFinder;

class ModuleConfigTest extends TestCase
{
    protected $dataDir;

    public function setUp()
    {
        $this->dataDir = dirname(dirname(__DIR__)) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V1');
    }

    public function optionsProvider()
    {
        return [
            ['skip cache', [ 'cacheSkip' => true ]],
            ['with cache', [' cacheSkip' => false]],
        ];
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testFind($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::MODULE(), 'Foo', null, $options);
        $path = $mc->find();
        $this->assertFalse(empty($path), "Path is empty [$path]");
        $this->assertTrue(is_string($path), "Path is not a string [$path]");
        $this->assertTrue(file_exists($path), "Path does not exist [$path]");
        $this->assertTrue(is_readable($path), "Path is not readable [$path]");
        $this->assertTrue(is_file($path), "Path is not a file [$path]");
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testFindOther($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::MODULE(), 'Foo', 'other_config.ini', $options);
        $path = $mc->find();
        $this->assertFalse(empty($path), "Path is empty [$path]");
        $this->assertTrue(is_string($path), "Path is not a string [$path]");
        $this->assertTrue(file_exists($path), "Path does not exist [$path]");
        $this->assertTrue(is_readable($path), "Path is not readable [$path]");
        $this->assertTrue(is_file($path), "Path is not a file [$path]");
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider optionsProvider
     */
    public function testFindNotFoundException($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::MODULE(), 'Foo', 'this_file_is_not.there', $options);
        $path = $mc->find();
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testParse($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::MODULE(), 'Foo', null, $options);
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
     * @dataProvider optionsProvider
     */
    public function testParseInvalidException($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::LISTS(), 'Foo', 'invalid_list.csv', $options);
        $parser = $mc->parse();
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testGetErrors($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::MODULE(), 'Foo', null, $options);

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

    /**
     * @dataProvider optionsProvider
     */
    public function testGetWarnings($description, $options)
    {
        $mc = new ModuleConfig(ConfigType::MODULE(), 'Foo', null, $options);

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
