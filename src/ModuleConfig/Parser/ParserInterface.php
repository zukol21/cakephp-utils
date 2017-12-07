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

use Qobo\Utils\ErrorAwareInterface;

interface ParserInterface extends ErrorAwareInterface
{
    /**
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Options for parsing
     * @return object
     */
    public function parse($path, array $options = []);
}
