<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Cache;

use Cake\TestSuite\TestCase;
use Qobo\Utils\ModuleConfig\Cache\Cache;
use Qobo\Utils\ModuleConfig\Cache\PathCache;

class PathCacheTest extends TestCase
{
    public function testWriteTo()
    {
        $cache = new PathCache('foo');
        $result = $cache->writeTo('blah', 'something');
        $this->assertEquals(false, $result, "writeTo() did not fail without 'path' parameter");
    }

    public function testReadFromWriteTo()
    {
        // Prepare test file
        $tmpFile = tempnam(sys_get_temp_dir(), 'pathcache_test.');
        $this->assertTrue(file_exists($tmpFile), "Failed to create temporary file: $tmpFile");
        $testValue = 'hello';
        $result = file_put_contents($tmpFile, $testValue);
        $this->assertEquals(strlen($testValue), $result, "Failed to save test value to file");

        // Write cache
        $cache = new PathCache('foo');
        $result = $cache->writeTo('test_key', file_get_contents($tmpFile), ['path' => $tmpFile]);
        $this->assertTrue(is_bool($result), "writeTo() returned a non-boolean result");
        $this->assertTrue($result, "writeTo() failed to write cache");

        // Read cache
        $result = $cache->readFrom('test_key');
        $this->assertEquals($testValue, $result, "readFrom() failed to read cache");

        // Update file, thus invalidating cache
        $testValue = 'bye';
        $result = file_put_contents($tmpFile, $testValue);
        $this->assertEquals(strlen($testValue), $result, "Failed to update test value in file");

        // Read cache
        $result = $cache->readFrom('test_key');
        $this->assertTrue(is_bool($result), "readFrom() returnd a non-boolean result: $result");
        $this->assertFalse($result, "readFrom() did not fail reading stale value");

        // Cleanup
        unlink($tmpFile);
    }

    public function testReadFromWriteToBadFile()
    {
        // Prepare test file
        $tmpFile = '/this/file/does/not/exist';
        $this->assertFalse(file_exists($tmpFile), "Non-existing file exists: $tmpFile");
        $testValue = 'hello';

        // Write cache
        $cache = new PathCache('foo');
        $result = $cache->writeTo('test_another_key', $testValue, ['path' => $tmpFile]);
        $this->assertTrue(is_bool($result), "writeTo() returned a non-boolean result");
        $this->assertFalse($result, "writeTo() successfully cached non-existing file");

        // Read cache
        $result = $cache->readFrom('test_another_key');
        $this->assertTrue(is_bool($result), "readFrom() returned a non-boolean result");
        $this->assertFalse($result, "readFrom() successfully read non-existing file cache");
    }

    public function testReadFromWriteToDeleted()
    {
        // Prepare test file
        $tmpFile = tempnam(sys_get_temp_dir(), 'pathcache_test.');
        $this->assertTrue(file_exists($tmpFile), "Failed to create temporary file: $tmpFile");
        $testValue = 'hello';
        $result = file_put_contents($tmpFile, $testValue);
        $this->assertEquals(strlen($testValue), $result, "Failed to save test value to file");

        // Write cache
        $cache = new PathCache('foo');
        $result = $cache->writeTo('deleted_key', file_get_contents($tmpFile), ['path' => $tmpFile]);
        $this->assertTrue(is_bool($result), "writeTo() returned a non-boolean result");
        $this->assertTrue($result, "writeTo() failed to write cache");

        // Read cache
        $result = $cache->readFrom('deleted_key');
        $this->assertEquals($testValue, $result, "readFrom() failed to read cache");

        // Delete file, thus invalidating cache
        unlink($tmpFile);
        $this->assertFalse(file_exists($tmpFile), "Failed to delete temporary file: $tmpFile");

        // Read cache
        $result = $cache->readFrom('deleted_key');
        $this->assertTrue(is_bool($result), "readFrom() returnd a non-boolean result: $result");
        $this->assertFalse($result, "readFrom() did not fail reading stale value");
    }

    public function testReadFrom()
    {
        // Use generic Cache to write the data
        $cache = new Cache('foo');
        $data = ['data' => 'no required fields for PathCache'];
        $result = $cache->writeTo('bad_data', $data, ['raw' => true]);
        $this->assertTrue(is_bool($result), "writeTo() returned a non-boolean result");
        $this->assertTrue($result, "writeTo() failed to save raw data");

        // Use PathCache to read the data
        $cache = new PathCache('foo');
        $result = $cache->readFrom('bad_data');
        $this->assertTrue(is_bool($result), "readFrom() returned a non-boolean result");
        $this->assertFalse($result, "readFrom() failed to validate data properly");
    }
}
