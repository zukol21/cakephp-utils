<?php
namespace Qobo\Utils\Test\TestCase\ModuleConfig\Parser\Schema;

use Cake\Core\Configure;
use DirectoryIterator;
use PHPUnit_Framework_TestCase;

class SchemaTest extends PHPUnit_Framework_TestCase
{
    /**
     * Lint JSON files
     *
     * This is a temporary test until we integrate a proper
     * JSON linting tool.  At a very minimum we need to make
     * sure that the schema files are parseable.
     */
    public function testJsonLint()
    {
        // All your dir are belong to us!
        $schemaPath = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        $schemaPath .= DIRECTORY_SEPARATOR . 'src';
        $schemaPath .= DIRECTORY_SEPARATOR . 'ModuleConfig';
        $schemaPath .= DIRECTORY_SEPARATOR . 'Parser';
        $schemaPath .= DIRECTORY_SEPARATOR . 'Schema';

        $schemaPath = realpath($schemaPath);

        $this->assertTrue(file_exists($schemaPath), "Path does not exist: $schemaPath");
        $this->assertTrue(is_dir($schemaPath), "Path is not a directory: $schemaPath");
        $this->assertTrue(is_readable($schemaPath), "Path is not readable: $schemaPath");

        foreach (new DirectoryIterator($schemaPath) as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }
            if ($file->getExtension() !== 'json') {
                continue;
            }
            $content = file_get_contents($file->getRealPath());
            $this->assertFalse(empty($content), "Empty file or failed to read: " . $file->getRealPath());
            $data = json_decode($content);
            $this->assertFalse(empty($data), "Empty structure or failed to parse: " . $file->getRealPath());
        }
    }
}
