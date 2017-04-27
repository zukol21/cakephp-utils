<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

use Cake\Core\Configure;

/**
 * ConfigPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the module configuration files.  If
 * no $path is specified, then the path to the
 * default configuration file (config.ini) is
 * returned.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ConfigPathFinder extends BasePathFinder
{
    /**
     * @var string $prefix Path prefix
     */
    protected $prefix = 'config';

    /**
     * @var string $fileName File name
     */
    protected $fileName = 'config.ini';
}
