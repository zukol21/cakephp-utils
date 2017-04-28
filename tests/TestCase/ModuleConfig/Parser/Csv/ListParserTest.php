<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Csv;

use Cake\Core\Configure;
use Exception;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Csv\ListParser;

class ListParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new ListParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Common' . DS . 'lists' . DS . 'genders.csv';
        $result = null;
        try {
            $result = $this->parser->parse($file);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            print_r($this->parser->getErrors());
            print_r($this->parser->getWarnings());
        }

        $this->assertTrue(is_object($result), "Parser returned a non-object");

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);
        $this->assertFalse(empty($result), "Parser returned empty result");
        $this->assertFalse(empty($result['items']), "Parser returned empty items");
        $this->assertEquals(2, count($result['items']), "Parser returned incorrect count of list items");
        $this->assertTrue(array_key_exists('value', $result['items'][0]), "Parser missed 'value' key in first element of gender list");
        $this->assertEquals($result['items'][0]['value'], 'm', "Parser missed 'm' as 'value' key in first element of gender list");
    }

    public function testParseInvalid()
    {
        $file = $this->dataDir . 'Common' . DS . 'lists' . DS . 'invalid_list.csv';
        $exception = null;
        try {
            $result = $this->parser->parse($file, [ 'structure' => ['value', 'label', 'inactive', 'foobar'] ]);
        } catch (Exception $e) {
            $exception = $e;
        }

        $this->assertFalse(empty($exception), "Empty exception on invalid list parsing");

        $errors = $this->parser->getErrors();
        $this->assertTrue(is_array($errors), "Errors is not an array");
        $this->assertFalse(empty($errors), "Parsing errors are empty");

        $badValue = false;
        foreach ($errors as $error) {
            if (preg_match('/any of the provided schemas/', $error)) {
                $badValue = true;
                break;
            }
        }
        $this->assertTrue($badValue, "Parser errors do not complain about 'bad_value'");

        $foobarProperty = false;
        foreach ($errors as $error) {
            if (preg_match('/foobar/', $error)) {
                $foobarProperty = true;
                break;
            }
        }
        $this->assertTrue($foobarProperty, "Parser errors do not complain about 'foobar' property");
    }
}
