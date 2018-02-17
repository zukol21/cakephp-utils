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
namespace Qobo\Utils\ModuleConfig\PathFinder\V1;

use Cake\Core\Configure;
use Qobo\Utils\ModuleConfig\PathFinder\BasePathFinder;

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
