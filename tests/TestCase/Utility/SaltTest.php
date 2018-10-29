<?php
namespace Qobo\Utils\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Cake\Utility\Security;
use Qobo\Utils\Utility\Salt;

class SaltTest extends TestCase
{
    public function testGetSaltExisting(): void
    {
        $tmpSaltFile = tempnam(sys_get_temp_dir(), 'salt_');
        $expected = 'this is a pre-generated salt string which is stored in the file';
        file_put_contents($tmpSaltFile, $expected);

        Salt::$saltFile = $tmpSaltFile;
        Salt::$saltMinLength = 10;
        $result = Salt::getSalt();
        unlink($tmpSaltFile);
        $this->assertEquals($expected, $result, "getSalt() returned incorrect salt");
    }

    public function testGetSaltNonExisting(): void
    {
        $tmpSaltFile = tempnam(sys_get_temp_dir(), 'salt_');

        Salt::$saltFile = $tmpSaltFile;
        Salt::$saltMinLength = 10;
        $result = Salt::getSalt();
        unlink($tmpSaltFile);
        $this->assertTrue(is_string($result), "getSalt() returned a non-string result");
        $this->assertFalse(empty($result), "getSalt() returned an empty result");
        $this->assertEquals(Salt::$saltMinLength, strlen($result), "getSalt() returned result of an incorrect length");
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetSaltExceptionBadMinLength(): void
    {
        $tmpSaltFile = tempnam(sys_get_temp_dir(), 'salt_');

        Salt::$saltFile = $tmpSaltFile;
        Salt::$saltMinLength = 0;
        $result = Salt::getSalt();
        if ($tmpSaltFile) {
            unlink($tmpSaltFile);
        }
    }
}
