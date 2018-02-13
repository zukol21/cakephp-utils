<?php
namespace Qobo\Utils\Test\TestCase;

use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\Utility;

class UtilityTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatePathExceptionNotExist()
    {
        Utility::validatePath('/some/non/existing/path');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatePathExceptionNotReadable()
    {
        Utility::validatePath('/etc/shadow');
    }

    public function testGetControllers()
    {
        $result = Utility::getControllers();
        $this->assertTrue(is_array($result), "Result is not an array");
        // Should return at least the Users controller
        $this->assertFalse(empty($result), "Result is empty");

        $result = Utility::getControllers(false);
        $this->assertTrue(is_array($result), "Result is not an array");
        // Should return at least the Users controller
        $this->assertFalse(empty($result), "Result is empty");
    }

    public function testGetDirControllers()
    {
        $result = Utility::getDirControllers('/some/non/existing/path');
        $this->assertTrue(is_array($result), "Result is not an array");
        $this->assertTrue(empty($result), "Result is not empty");

        $testApp = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Controller';

        // plugin=false, fqcn=true
        $result = Utility::getDirControllers($testApp);
        $this->assertTrue(is_array($result), "Result is not an array (plugin=false,fqcn=true)");
        $this->assertFalse(empty($result), "Result is not empty (plugin=false,fqcn=true)");
        // Make sure non-fqcn controllers are not in the list
        $this->assertFalse(in_array('UsersController', $result), "Non-fqcn UsersController is in the list (plugin=false,fqcn=true)");
        // Convert fqcn to non-fqcn
        foreach ($result as $key => $item) {
            $parts = explode('\\', $item);
            $result[$key] = array_pop($parts);
        };
        // Make sure fqcn controllers are in the list
        $this->assertTrue(in_array('UsersController', $result), "Test app UsersController is not in the list (plugin=false,fqcn=true)");
        $this->assertFalse(in_array('AppController', $result), "Test app AppController is in the list (plugin=false,fqcn=true)");

        // plugin=false, fqcn=false
        $result = Utility::getDirControllers($testApp, null, false);
        $this->assertTrue(is_array($result), "Result is not an array (plugin=false,fqcn=false)");
        $this->assertFalse(empty($result), "Result is not empty (plugin=false,fqcn=false)");
        $this->assertTrue(in_array('UsersController', $result), "Test app UsersController is not in the list (plugin=false,fqcn=false)");
        $this->assertFalse(in_array('AppController', $result), "Test app AppController is in the list (plugin=false,fqcn=false)");

        // plugin=Blah, fqcn=false
        $result = Utility::getDirControllers($testApp, 'Blah', false);
        $this->assertTrue(is_array($result), "Result is not an array (plugin=Blah,fqcn=false)");
        $this->assertFalse(empty($result), "Result is not empty (plugin=Blah,fqcn=false)");
        $this->assertTrue(in_array('Blah.UsersController', $result), "Test app UsersController is not in the list (plugin=Blah,fqcn=false)");
        $this->assertFalse(in_array('Blah.AppController', $result), "Test app AppController is in the list (plugin=Blah,fqcn=false)");
    }

    public function testGetModels()
    {
        $result = Utility::getModels('test');
        $this->assertTrue(is_array($result));
    }

    public function testGetModelColumns()
    {
        $result = Utility::getModelColumns('Users', 'test');

        $this->assertTrue(is_array($result));
    }

    public function testFindDirs()
    {
        // Proper path
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Modules';
        $result = Utility::findDirs($path);
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(in_array('Common', $result), "Failed to find Common directory");
        $this->assertTrue(in_array('Foo', $result), "Failed to find Foo directory");
        $this->assertFalse(in_array('.', $result), "Failed to remove dot directory");

        // Path with no directories
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'db';
        $result = Utility::findDirs($path);
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));

        // Invalid path
        $path = 'this_path_does_not_exist';
        $result = Utility::findDirs($path);
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
    }

    /**
     * @dataProvider getIconProvider
     */
    public function testGetIcons($configFile, $isArray, $isEmpty)
    {
        $config = \Cake\Core\Configure::read($configFile);
        $result = Utility::getIcons($config);

        $this->assertEquals(is_array($result), $isArray);
        $this->assertEquals(empty($result), $isEmpty);
    }

    public function getIconProvider()
    {
        return [
            ['Icons', true, false],
        ];
    }

    public function testGetColors()
    {
        $config = \Cake\Core\Configure::read('Colors');
        $result = Utility::getColors($config);

        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);

        $result = Utility::getColors($config, false);
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
    }

    public function testGetApiVersions()
    {
        $testDataPath = dirname(dirname(__FILE__)) . DS . 'data';

        $versions = Utility::getApiVersions($testDataPath);

        $this->assertTrue(is_array($versions));
        $this->assertNotEmpty($versions);
    }
}
