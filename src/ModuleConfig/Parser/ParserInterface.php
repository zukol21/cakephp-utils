<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Qobo\Utils\ModuleConfig\Parser;

use InvalidArgumentException;
use Qobo\Utils\ErrorAwareInterface;

interface ParserInterface extends ErrorAwareInterface
{
    /**
     * Sets the schema.
     *
     * @param \Qobo\Utils\ModuleConfig\Parser\SchemaInterface $schema JSON schema.
     * @return void
     */
    public function setSchema(SchemaInterface $schema): void;

    /**
     * Get the schema object.
     *
     * @return \Qobo\Utils\ModuleConfig\Parser\SchemaInterface JSON schema.
     */
    public function getSchema(): SchemaInterface;

    /**
     * Parses a given file against the schema according to the specified options
     *
     * @param string $path Full path to JSON file.
     * @return object
     */
    public function parse(string $path);
}
