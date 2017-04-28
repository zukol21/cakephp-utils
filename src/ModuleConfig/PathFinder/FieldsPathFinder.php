<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

use Cake\Core\Configure;

/**
 * FieldsPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the module fields configuration files.
 * If no $path is specified, then the path to the
 * default fields configuration file (fields.ini) is
 * returned.
 */
class FieldsPathFinder extends ConfigPathFinder
{
    /**
     * @var string $fileName File name
     */
    protected $fileName = 'fields.ini';
}
