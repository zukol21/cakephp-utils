<?php
use Qobo\Utils\ModuleConfig\ClassType;
use Qobo\Utils\ModuleConfig\ModuleConfig;

// Class map of finders and parser for each
// support configuration type
$classTypeFinder = (string)ClassType::FINDER();
$classTypeParser = (string)ClassType::PARSER();

return [
    'ModuleConfig' => [
        'classMap' => [
            ModuleConfig::CONFIG_TYPE_MIGRATION => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MigrationPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\MigrationParser',
            ],
            ModuleConfig::CONFIG_TYPE_MODULE => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ConfigPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ConfigParser',
            ],
            ModuleConfig::CONFIG_TYPE_LIST => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ListPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ListParser',
            ],
            ModuleConfig::CONFIG_TYPE_FIELDS => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\FieldsPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\FieldsParser',
            ],
            ModuleConfig::CONFIG_TYPE_MENUS => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MenusPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Json\\MenusParser',
            ],
            ModuleConfig::CONFIG_TYPE_REPORTS => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ReportsPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ReportsParser',
            ],
            ModuleConfig::CONFIG_TYPE_VIEW => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ViewPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ViewParser',
            ],
        ]
    ],
];
