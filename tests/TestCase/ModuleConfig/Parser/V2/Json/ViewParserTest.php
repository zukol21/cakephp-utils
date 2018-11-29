<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\V2\Json;

use Cake\Core\Configure;
use InvalidArgumentException;
use JsonSchema\Exception\ValidationException;

use PHPUnit\Framework\TestCase;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ViewParser;
use Qobo\Utils\Utility\Convert;

class ViewParserTest extends TestCase
{
    /**
     * Config parser
     * @var \Qobo\Utils\ModuleConfig\Parser\V2\Json\ViewParser
     */
    protected $parser;

    /**
     * Path to modules
     * @var string
     */
    protected $dataDir;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function setUp()
    {
        $this->parser = new ViewParser();
        $this->dataDir = TESTS . 'data' . DS . 'Modules';

        Configure::write('CsvMigrations.modules.path', $this->dataDir);
        Configure::write('ModuleConfig.classMapVersion', 'V2');
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        unset($this->dataDir);
        unset($this->parser);
    }

    /**
     * Tests valid views json
     *
     * @return void
     */
    public function testParse(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'views' . DS . 'add.json';

        $result = $this->parser->parse($file);
        $this->assertNotEmpty($result);
        $this->assertEmpty($this->parser->getErrors());
    }

    /**
     * Tests invalid views json
     *
     * @return void
     */
    public function testParseInvalid(): void
    {
        $file = $this->dataDir . DS . 'Foo' . DS . 'views' . DS . 'invalid.json';

        try {
            $this->parser->parse($file);
        } catch (InvalidArgumentException $e) {
            $this->assertContains('Error validating /items/0: There must be a maximum of 13 items in the array', $e->getMessage());
        }
    }
}
