<?php
use Qobo\Utils\ModuleConfig\ModuleConfig;

// Class map of finders and parser for each
// support configuration type

return [
    'ModuleConfig' => [
        'classMap' => [
            ModuleConfig::CONFIG_TYPE_MIGRATION => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MigrationPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\MigrationParser',
            ],
            ModuleConfig::CONFIG_TYPE_MODULE => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ConfigPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ConfigParser',
            ],
            ModuleConfig::CONFIG_TYPE_LIST => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ListPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ListParser',
            ],
            ModuleConfig::CONFIG_TYPE_FIELDS => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\FieldsPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\FieldsParser',
            ],
            ModuleConfig::CONFIG_TYPE_MENUS => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MenusPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Json\\MenusParser',
            ],
            ModuleConfig::CONFIG_TYPE_REPORTS => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ReportsPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ReportsParser',
            ],
            ModuleConfig::CONFIG_TYPE_VIEW => [
                ModuleConfig::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ViewPathFinder',
                ModuleConfig::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ViewParser',
            ],
        ]
    ],
];
