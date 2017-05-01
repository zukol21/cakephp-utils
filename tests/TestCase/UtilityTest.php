<?php
namespace Qobo\Utils\Test\TestCase;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\Utility;

class UtilityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatePathExceptionNotExist()
    {
        Utility::validatePath('/some/non/existing/path');
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
}
