<?php
use Qobo\Utils\ModuleConfig\ClassType;
use Qobo\Utils\ModuleConfig\ConfigType;

// Class map of finders and parser for each
// support configuration type
$classTypeFinder = (string)ClassType::FINDER();
$classTypeParser = (string)ClassType::PARSER();

return [
    'ModuleConfig' => [
        'classMapVersion' => 'V2',
        'classMap' => [
            'V2' => [
                (string)ConfigType::MIGRATION() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\MigrationPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\MigrationParser',
                ],
                (string)ConfigType::MODULE() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\ConfigPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\ConfigParser',
                ],
                (string)ConfigType::LISTS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\ListPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\ListParser',
                ],
                (string)ConfigType::FIELDS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\FieldsPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\FieldsParser',
                ],
                (string)ConfigType::MENUS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\MenusPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\MenusParser',
                ],
                (string)ConfigType::REPORTS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\ReportsPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\ReportsParser',
                ],
                (string)ConfigType::VIEW() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\ViewPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\ViewParser',
                ],
                (string)ConfigType::DUPLICATES() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V2\\DuplicatesPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V2\\Json\\DuplicatesParser',
                ],
            ],
        ],
    ],
];
