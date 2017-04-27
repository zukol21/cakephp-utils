<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Ini;

use Cake\Core\Configure;
use PHPUnit_Framework_TestCase;
use Qobo\Utils\ModuleConfig\Parser\Ini\ReportsParser;

class ReportsParserTest extends PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $dataDir;

    protected function setUp()
    {
        $this->parser = new ReportsParser();
        $this->dataDir = dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'data' . DS . 'Modules' . DS;
        Configure::write('CsvMigrations.modules.path', $this->dataDir);
    }

    public function testParse()
    {
        $file = $this->dataDir . 'Foo' . DS . 'config' . DS . 'reports.ini';
        $result = $this->parser->parse($file);

        $this->assertTrue(is_object($result), "Parser returned a non-object");

        // Convert object to array recursively
        $result = json_decode(json_encode($result), true);

        $this->assertFalse(empty($result), "Parser returned empty result");
        $this->assertFalse(empty($result['by_campaign_name']), "Parser missed 'by_campaign_name' section");
        $this->assertFalse(empty($result['by_campaign_name']['widget_type']), "Parser missed 'widget_type' key");
        $this->assertEquals('report', $result['by_campaign_name']['widget_type'], "Parser misinterpreted 'widget_type' value");
    }
}
