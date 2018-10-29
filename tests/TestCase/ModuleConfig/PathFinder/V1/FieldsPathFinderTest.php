<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\PathFinder\V1;

use Cake\Core\Configure;
use PHPUnit\Framework\TestCase;
use Qobo\Utils\ModuleConfig\PathFinder\V1\FieldsPathFinder;

class FieldsPathFinderTest extends TestCase
{
    protected $pf;
    protected $dataDir;

    protected function setUp()
    {
        $this->pf = new FieldsPathFinder();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V1');
    }

    public function testInterface(): void
    {
        $implementedInterfaces = array_keys(class_implements($this->pf));
        $this->assertTrue(in_array('Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface', $implementedInterfaces), "PathFinderInterface is not implemented");
    }

    public function testFind(): void
    {
        $path = $this->pf->find('Foo');
        $this->assertFalse(empty($path), "Path is empty [$path]");
        $this->assertTrue(is_string($path), "Path is not a string [$path]");
        $this->assertTrue(file_exists($path), "Path does not exist [$path]");
        $this->assertTrue(is_readable($path), "Path is not readable [$path]");
        $this->assertTrue(is_file($path), "Path is not a file [$path]");
    }

    public function testFindOther(): void
    {
        $path = $this->pf->find('Foo', 'other_fields.ini');
        $this->assertFalse(empty($path), "Path is empty [$path]");
        $this->assertTrue(is_string($path), "Path is not a string [$path]");
        $this->assertTrue(file_exists($path), "Path does not exist [$path]");
        $this->assertTrue(is_readable($path), "Path is not readable [$path]");
        $this->assertTrue(is_file($path), "Path is not a file [$path]");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindExceptionPathNotExist(): void
    {
        $path = $this->pf->find('Foo', 'some_non_existing_file.ini');
    }
}
