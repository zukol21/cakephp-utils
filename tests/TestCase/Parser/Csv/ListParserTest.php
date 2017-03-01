<?php
namespace Qobo\Utils\Test\TestCase\Parser\Csv;

use PHPUnit_Framework_TestCase;
use Qobo\Utils\Parser\Csv\ListParser;

class ListParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseFromPath()
    {
        $file = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . 'Modules' . DS . 'Common' . DS . 'lists' . DS . 'genders.csv';
        $parser = new ListParser();
        $result = $parser->parseFromPath($file);

        $this->assertTrue(is_array($result), "Parser returned a non-array");
        $this->assertFalse(empty($result), "Parser returned empty result");
    }
}
