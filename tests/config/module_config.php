<?php
use Qobo\Utils\ModuleConfig\ClassType;
use Qobo\Utils\ModuleConfig\ConfigType;

// Class map of finders and parser for each
// support configuration type
$classTypeFinder = (string)ClassType::FINDER();
$classTypeParser = (string)ClassType::PARSER();

return [
    'ModuleConfig' => [
        'classMap' => [
            (string)ConfigType::MIGRATION() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MigrationPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\MigrationParser',
            ],
            (string)ConfigType::MODULE() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ConfigPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ConfigParser',
            ],
            (string)ConfigType::LISTS() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ListPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ListParser',
            ],
            (string)ConfigType::FIELDS() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\FieldsPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\FieldsParser',
            ],
            (string)ConfigType::MENUS() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MenusPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Json\\MenusParser',
            ],
            (string)ConfigType::REPORTS() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ReportsPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ReportsParser',
            ],
            (string)ConfigType::VIEW() => [
                $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ViewPathFinder',
                $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ViewParser',
            ],
        ]
    ],
];
