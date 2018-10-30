<?php
namespace Qobo\Utils\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Qobo\Utils\Utility\Salt;
use RuntimeException;

class SaltTest extends TestCase
{
    /**
     * @var string $saltFile
     */
    protected $saltFile;

    public function setUp()
    {
        $saltFile = tempnam(sys_get_temp_dir(), 'salt_');
        if (empty($saltFile)) {
            throw new RuntimeException("Failed to create temporary file");
        }
        $this->saltFile = $saltFile;
    }

    public function tearDown()
    {
        if (file_exists($this->saltFile)) {
            unlink($this->saltFile);
        }
    }

    public function testGetSaltExisting(): void
    {
        $tmpSaltFile = $this->saltFile;
        $expected = 'this is a pre-generated salt string which is stored in the file';
        file_put_contents($tmpSaltFile, $expected);

        Salt::$saltFile = $tmpSaltFile;
        Salt::$saltMinLength = 10;
        $result = Salt::getSalt();
        $this->assertEquals($expected, $result, "getSalt() returned incorrect salt");
    }

    public function testGetSaltNonExisting(): void
    {
        $tmpSaltFile = $this->saltFile;

        Salt::$saltFile = $tmpSaltFile;
        Salt::$saltMinLength = 10;
        $result = Salt::getSalt();
        $this->assertTrue(is_string($result), "getSalt() returned a non-string result");
        $this->assertFalse(empty($result), "getSalt() returned an empty result");
        $this->assertEquals(Salt::$saltMinLength, strlen($result), "getSalt() returned result of an incorrect length");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetSaltExceptionBadMinLength(): void
    {
        $tmpSaltFile = $this->saltFile;

        Salt::$saltFile = $tmpSaltFile;
        Salt::$saltMinLength = 0;
        $result = Salt::getSalt();
    }
}
