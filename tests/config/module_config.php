<?php
use Qobo\Utils\ModuleConfig\ClassType;
use Qobo\Utils\ModuleConfig\ConfigType;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ConfigParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\DuplicatesParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\FieldsParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ListParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\MenusParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\MigrationParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ReportsParser;
use Qobo\Utils\ModuleConfig\Parser\V2\Json\ViewParser;
use Qobo\Utils\ModuleConfig\PathFinder\V2\ConfigPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\DuplicatesPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\FieldsPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\ListPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\MenusPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\MigrationPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\ReportsPathFinder;
use Qobo\Utils\ModuleConfig\PathFinder\V2\ViewPathFinder;

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
                    $classTypeFinder => MigrationPathFinder::class,
                    $classTypeParser => MigrationParser::class,
                ],
                (string)ConfigType::MODULE() => [
                    $classTypeFinder => ConfigPathFinder::class,
                    $classTypeParser => ConfigParser::class,
                ],
                (string)ConfigType::LISTS() => [
                    $classTypeFinder => ListPathFinder::class,
                    $classTypeParser => ListParser::class,
                ],
                (string)ConfigType::FIELDS() => [
                    $classTypeFinder => FieldsPathFinder::class,
                    $classTypeParser => FieldsParser::class,
                ],
                (string)ConfigType::MENUS() => [
                    $classTypeFinder => MenusPathFinder::class,
                    $classTypeParser => MenusParser::class,
                ],
                (string)ConfigType::REPORTS() => [
                    $classTypeFinder => ReportsPathFinder::class,
                    $classTypeParser => ReportsParser::class,
                ],
                (string)ConfigType::VIEW() => [
                    $classTypeFinder => ViewPathFinder::class,
                    $classTypeParser => ViewParser::class,
                ],
                (string)ConfigType::DUPLICATES() => [
                    $classTypeFinder => DuplicatesPathFinder::class,
                    $classTypeParser => DuplicatesParser::class,
                ],
            ],
        ],
    ],
];
