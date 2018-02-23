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
namespace Qobo\Utils\ModuleConfig\Parser\V2\Json;

/**
 * List JSON Parser
 *
 * This parser is useful for lists config JSON processing.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ListParser extends AbstractJsonParser
{
    /**
     * JSON schema
     *
     * This can either be a string, pointing to the file
     * or an stdClass with an instance of an already parsed
     * schema
     *
     * @var string|\stdClass $schema JSON schema
     */
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'list.json';

    /**
     * @var array $options Parsing options
     */
    protected $options = ['filter' => false, 'flatten' => false];

    /**
     * Parse
     *
     * Parses a given file according to the specified options
     *
     * @param string $path    Path to file
     * @param array  $options Options for parsing
     * @return object
     */
    public function parse($path, array $options = [])
    {
        $options = array_merge($this->options, $options);

        $result = parent::parse($path, $options);
        $data = json_decode(json_encode($result), true);
        $result->items = $this->normalize($data['items']);

        if ($options['filter']) {
            $result->items = $this->filter($result->items);
        }

        if ($options['flatten']) {
            $result->items = $this->flatten($result->items);
        }

        return $result;
    }

    /**
     * Method that restructures list options csv data for better handling.
     *
     * @param  array  $data     csv data
     * @param  string $prefix   nested option prefix
     * @return array
     */
    protected function normalize(array $data, $prefix = null)
    {
        if ($prefix) {
            $prefix .= '.';
        }

        $result = [];
        foreach ($data as $item) {
            $value = [
                'label' => (string)$item['label'],
                'inactive' => (bool)$item['inactive']
            ];

            if (! empty($item['children'])) {
                $value['children'] = $this->normalize($item['children'], $prefix . $item['value']);
            }
            $result[$prefix . $item['value']] = $value;
        }

        return $result;
    }

    /**
     * Method that filters list options, excluding non-active ones
     *
     * @param  array  $data list options
     * @return array
     */
    protected function filter(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value['inactive']) {
                continue;
            }

            $result[$key] = $value;
            if (isset($value['children'])) {
                $result[$key]['children'] = $this->filter($value['children']);
            }
        }

        return $result;
    }

    /**
     * Flatten list options.
     *
     * @param array $data List options
     * @return array
     */
    protected function flatten(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $item = [
                'label' => $value['label'],
                'inactive' => $value['inactive']
            ];

            $result[$key] = $item;

            if (isset($value['children'])) {
                $result = array_merge($result, $this->flatten($value['children']));
            }
        }

        return $result;
    }
}
