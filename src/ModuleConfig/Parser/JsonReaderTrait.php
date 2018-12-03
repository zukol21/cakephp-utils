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
use Seld\JsonLint\JsonParser;

trait JsonReaderTrait
{
    /**
     * Linter instance.
     * @var \Seld\JsonLint\JsonParser|null
     */
    protected $_linter = null;

    /**
     * Returns the json encoded data and applies linting if necessary.
     *
     * @param string $data JSON string.
     * @param bool $lint True to apply json linting.
     * @throws \Seld\JsonLint\ParsingException When linting is enabled and it fails.
     * @throws \InvalidArgumentException When data is not a valid JSON object and linting is not enabled.
     * @return object JSON object.
     */
    protected function dataToJson(string $data, bool $lint = false): object
    {
        if ($lint) {
            $this->getLinter()->parse($data);
        }

        $data = json_decode($data);
        if ($data === null) {
            throw new InvalidArgumentException("Failed to decode json");
        }

        return (object)$data;
    }

    /**
     * Gets the linter instance.
     *
     * @return \Seld\JsonLint\JsonParser Linter instance.
     */
    protected function getLinter(): JsonParser
    {
        if (is_null($this->_linter)) {
            $this->_linter = new JsonParser();
        }

        return $this->_linter;
    }
}
