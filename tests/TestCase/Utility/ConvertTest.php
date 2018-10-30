<?php
namespace Qobo\Utils\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Qobo\Utils\Utility\Convert;
use RuntimeException;
use stdClass;

class ConvertTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public function valueBytesProvider(): array
    {
        return [
            [-42, -42, 'Negative integer value'],
            [42, 42, 'Positive integer value'],

            ['-42', -42, 'Negative integer as string'],
            ['42', 42, 'Positive integer as string'],

            ['-42k', -43008, 'Negative lowercase kilobytes'],
            ['42k', 43008, 'Positive lowercase kilobytes'],

            ['-42K', -43008, 'Negative uppercase kilobytes'],
            ['42K', 43008, 'Positive uppercase kilobytes'],

            ['-42m', -44040192, 'Negative lowercase megabytes'],
            ['42m', 44040192, 'Positive lowercase megabytes'],

            ['-42M', -44040192, 'Negative uppercase megabytes'],
            ['42M', 44040192, 'Positive uppercase megabytes'],

            ['-42g', -45097156608, 'Negative lowercase gigabytes'],
            ['42g', 45097156608, 'Positive lowercase gigabytes'],

            ['-42G', -45097156608, 'Negative uppercase gigabytes'],
            ['42G', 45097156608, 'Positive uppercase gigabytes'],
        ];
    }

    /**
     * @param mixed $value
     * @dataProvider valueBytesProvider
     */
    public function testValueToBytes($value, int $expected, string $description): void
    {
        $result = Convert::valueToBytes($value);
        $this->assertEquals($expected, $result, "valueToBytes() failed for: $description");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValueToBytesException(): void
    {
        $result = Convert::valueToBytes('this is not a byte size value');
    }

    public function testObjectToArray(): void
    {
        // null
        $result = Convert::objectToArray(null);
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));

        // scalar
        $result = Convert::objectToArray('foobar');
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));

        // object with resource
        $fh = fopen(__FILE__, "r");
        if (!is_resource($fh)) {
            throw new RuntimeException("Failed to open file for reading");
        }
        $source = new stdClass();
        $source->foo = $fh;
        $result = Convert::objectToArray($source);
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
        // close file handler, cause we are nice people
        fclose($fh);

        // object (good)
        $source = new stdClass();
        $source->foo = 'bar';
        $result = Convert::objectToArray($source);
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertArrayHasKey('foo', $result);
        $this->assertEquals('bar', $result['foo']);
    }

    public function testArrayToObject(): void
    {
        // array with resource
        $fh = fopen(__FILE__, "r");
        if (!is_resource($fh)) {
            throw new RuntimeException("Failed to open file for reading");
        }
        $source = [];
        $source['foo'] = $fh;
        $result = Convert::arrayToObject($source);
        $this->assertTrue(is_object($result));
        // close file handler, cause we are nice people
        fclose($fh);

        // array (good)
        $source = [];
        $source['foo'] = 'bar';
        $result = Convert::arrayToObject($source);
        $this->assertTrue(is_object($result));
        $this->assertTrue(property_exists($result, 'foo'));
        $this->assertEquals('bar', $result->foo);
    }
}
