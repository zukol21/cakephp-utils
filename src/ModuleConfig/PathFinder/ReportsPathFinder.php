<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

use Cake\Core\Configure;

/**
 * ReportsPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the module reports configuration files.
 * If no $path is specified, then the path to the
 * default reports configuration file (reports.ini) is
 * returned.
 */
class ReportsPathFinder extends ConfigPathFinder
{
    /**
     * @var string $fileName File name
     */
    protected $fileName = 'reports.ini';
}
