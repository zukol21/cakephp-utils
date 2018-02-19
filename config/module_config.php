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
            ],
            'V1' => [
                (string)ConfigType::MIGRATION() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\MigrationPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Csv\\MigrationParser',
                ],
                (string)ConfigType::MODULE() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\ConfigPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Ini\\ConfigParser',
                ],
                (string)ConfigType::LISTS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\ListPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Csv\\ListParser',
                ],
                (string)ConfigType::FIELDS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\FieldsPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Ini\\FieldsParser',
                ],
                (string)ConfigType::MENUS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\MenusPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Json\\MenusParser',
                ],
                (string)ConfigType::REPORTS() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\ReportsPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Ini\\ReportsParser',
                ],
                (string)ConfigType::VIEW() => [
                    $classTypeFinder => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\V1\\ViewPathFinder',
                    $classTypeParser => 'Qobo\\Utils\\ModuleConfig\\Parser\\V1\\Csv\\ViewParser',
                ],
            ],
        ],
    ],
];
