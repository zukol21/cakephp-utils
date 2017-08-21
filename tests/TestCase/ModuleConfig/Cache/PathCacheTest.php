<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Cache;

use Cake\TestSuite\TestCase;
use Qobo\Utils\ModuleConfig\Cache\PathCache;

class PathCacheTest extends TestCase
{
    public function testWriteTo()
    {
        $cache = new PathCache('foo');
        $result = $cache->writeTo('blah', 'something');
        $this->assertEquals(false, $result, "writeTo() did not fail without 'path' parameter");
    }
}
