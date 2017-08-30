<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

use Cake\Core\Configure;
use InvalidArgumentException;

/**
 * ListPathFinder Class
 *
 * This path finder is here to assist with finding
 * the paths to the list files.  $path
 * parameter is required, but $module is optional.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ListPathFinder extends BasePathFinder
{
    /**
     * Default module
     *
     * A fallback module to use when the list is not
     * found in the current module.
     */
    const DEFAULT_MODULE = 'Common';

    /**
     * @var string $extension Default file extension
     */
    protected $extension = '.csv';

    /**
     * @var string $prefix Path prefix
     */
    protected $prefix = 'lists';

    /**
     * Find path
     *
     * Find path to a given list.  Make sure that $path
     * parameter is required, and, if the value is
     * given without the file extension, attach one to make it
     * easier to find.
     *
     * If the module is empty, or list is not found in the module,
     * we fallback on the Common module.
     *
     * @param string $module Module to look for files in
     * @param string $path   Path to look for
     * @param bool   $validate Validate existence of the result
     * @return null|string|array Null for not found, string for single path, array for multiple paths
     */
    public function find($module, $path = null, $validate = true)
    {
        if (empty($module)) {
            $this->warnings[] = "Module not specified.  Assuming: " . self::DEFAULT_MODULE;
            $module = self::DEFAULT_MODULE;
        }

        if (empty($path)) {
            $this->fail(new InvalidArgumentException("Path is not specified"));
        }

        if (!is_string($path)) {
            $this->fail(new InvalidArgumentException("Path is not a string"));
        }

        $path = $this->addDefaultExtension($path);

        $result = null;
        try {
            $result = parent::find($module, $path, $validate);
        } catch (\Exception $e) {
            if ($module == self::DEFAULT_MODULE) {
                $this->fail($e);
            }
        }

        if (($result === null) && ($module <> self::DEFAULT_MODULE)) {
            $this->warnings[] = "Module list not found.  Falling back on module " . self::DEFAULT_MODULE;
            $result = parent::find(self::DEFAULT_MODULE, $path, $validate);
        }

        return $result;
    }
}
