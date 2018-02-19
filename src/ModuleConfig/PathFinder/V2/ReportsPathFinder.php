<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Qobo\Utils\ModuleConfig\PathFinder\V2;

use Cake\Core\Configure;
use Qobo\Utils\ModuleConfig\PathFinder\BasePathFinder;

/**
 * ReportsPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the module reports configuration files.
 * If no $path is specified, then the path to the
 * default reports configuration file (reports.json) is
 * returned.
 */
class ReportsPathFinder extends ConfigPathFinder
{
    /**
     * @var string $fileName File name
     */
    protected $fileName = 'reports.json';
}
