<?php
namespace Qobo\Utils\PathFinder;

use Cake\Core\Configure;

/**
 * MigrationPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the module migration files.  If
 * no $path is specified, then the path to the
 * default migration file (migration.csv) is
 * returned.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class MigrationPathFinder extends BasePathFinder
{
    protected $prefix = 'db';
    protected $fileName = 'migration.csv';
}