<?php
namespace Qobo\Utils\ModuleConfig\Parser\Ini;

/**
 * Config INI Parser
 *
 * This parser is useful for module config INI processing.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ConfigParser extends AbstractIniParser
{
    /**
     * JSON schema
     *
     * This can either be a string, pointing to the file
     * or an StdClass with an instance of an already parsed
     * schema
     *
     * @var string|StdClass $schema JSON schema
     */
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'config.json';

    /**
     * @var array $defaults Default configuration
     */
    protected $defaults = [
        'table' => [
            'icon' => 'cube',
            'searchable' => true,
        ],
    ];
}
