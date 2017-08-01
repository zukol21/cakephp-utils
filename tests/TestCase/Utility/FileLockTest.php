<?php
namespace Qobo\Utils\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Qobo\Utils\Utility\FileLock;

class FileLockTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitEmptyFileName()
    {
        new FileLock('');
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitInvalidFileName()
    {
        new FileLock([]);
    }

    public function testInit()
    {
        $filename = 'some_random_filename';

        $lock = new FileLock($filename);
        $this->assertInstanceOf(FileLock::class, $lock);

        $path = sys_get_temp_dir() . DS . $filename;
        $this->assertTrue(file_exists($path));
    }

    public function testLock()
    {
        $filename = 'some_random_filename';

        $lock = new FileLock($filename);
        $this->assertTrue($lock->lock());
    }

    public function testUnlock()
    {
        $filename = 'some_random_filename';

        $lock = new FileLock($filename);
        $this->assertTrue($lock->unlock());

        $path = sys_get_temp_dir() . DS . $filename;
        $this->assertFalse(file_exists($path));
    }
}
