<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

use Cake\Core\Configure;

/**
 * MenusPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the menu configuration files.  If
 * no $path is specified, then the path to the
 * default configuration file (menus.json) is
 * returned.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class MenusPathFinder extends BasePathFinder
{
    /**
     * @var string $prefix Path prefix
     */
    protected $prefix = 'config';

    /**
     * @var string $fileName File name
     */
    protected $fileName = 'menus.json';
}
