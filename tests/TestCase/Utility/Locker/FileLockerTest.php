<?php

namespace Qobo\Utils\Test\TestCase\Utility\Locker;

use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Qobo\Utils\Utility\Locker\FileLocker;

class FileLockerTest extends TestCase
{
    public function testInit()
    {
        $filename = 'some_random_filename';

        $lock = new FileLocker($filename);
        $this->assertInstanceOf(FileLocker::class, $lock);
    }

    public function testLockUnlock()
    {
        $filename = 'some_random_filename';

        $lock = new FileLocker($filename);
        $this->assertTrue($lock->lock());

        $this->assertTrue($lock->unlock());
    }
}
