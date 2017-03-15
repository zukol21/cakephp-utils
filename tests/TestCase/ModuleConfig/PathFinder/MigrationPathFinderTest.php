<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\PathFinder;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\PathFinder\MigrationPathFinder;

class MigrationPathFinderTest extends PHPUnit_Framework_TestCase
{
    protected $pf;

    protected function setUp()
    {
        $this->pf = new MigrationPathFinder();
        $dir = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $dir);
    }

    public function testInterface()
    {
        $implementedInterfaces = array_keys(class_implements($this->pf));
        $this->assertTrue(in_array('Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface', $implementedInterfaces), "PathFinderInterface is not implemented");
    }

    public function testFind()
    {
        $path = $this->pf->find('Foo');
        $this->assertFalse(empty($path), "Path is empty");
        $this->assertTrue(is_string($path), "Path is not a string");
        $this->assertTrue(file_exists($path), "Path does not exist");
        $this->assertTrue(is_readable($path), "Path is not readable");
        $this->assertTrue(is_file($path), "Path is not a file");
    }

    public function testFindOther()
    {
        $path = $this->pf->find('Foo', 'other_migration.csv');
        $this->assertFalse(empty($path), "Path is empty");
        $this->assertTrue(is_string($path), "Path is not a string");
        $this->assertTrue(file_exists($path), "Path does not exist");
        $this->assertTrue(is_readable($path), "Path is not readable");
        $this->assertTrue(is_file($path), "Path is not a file");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindExceptionModuleEmpty()
    {
        $path = $this->pf->find(null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindExceptionModuleNotString()
    {
        $path = $this->pf->find(['foo' => 'bar']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindExceptionPathNotString()
    {
        $path = $this->pf->find('Foo', ['foo' => 'bar']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindExceptionPathNotExist()
    {
        $path = $this->pf->find('Foo', 'some_non_existing_file.csv');
    }
}
