<?php
namespace Qobo\Utils\ModuleConfig;

use MyCLabs\Enum\Enum;

/**
 * ConfigType Enum
 */
class ConfigType extends Enum
{
    /**
     * Database migration configuration (migration.csv)
     */
    const MIGRATION = 'migration';

    /**
     * Module configuration (config.ini)
     */
    const MODULE = 'module';

    /**
     * Menus configuration (menus.json)
     */
    const MENUS = 'menus';

    /**
     * Fields configuration (fields.ini)
     */
    const FIELDS = 'fields';

    /**
     * Reports configuration (reports.ini)
     */
    const REPORTS = 'reports';

    /**
     * List configuration (list.csv)
     *
     * 'list' is a PHP reserved word
     */
    const LISTS = 'list';

    /**
     * View configuration (index.csv)
     */
    const VIEW = 'view';
}
