<?php
namespace Qobo\Utils\ModuleConfig\PathFinder;

/**
 * PathFinderInterface Interface
 *
 * This interface defines the standard approach for
 * finding paths (files and directories) in standard
 * places, with a bit of flexibility for custom
 * situations.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
interface PathFinderInterface
{
    /**
     * Find path
     *
     * @param string $module   Module to look for files in
     * @param string $path     Path to look for
     * @param bool   $validate Validate existence of the result
     * @return null|string|array Null for not found, string for single path, array for multiple paths
     */
    public function find($module, $path = null, $validate = true);

    /**
     * Get finder errors
     *
     * @return array List of errors from last find
     */
    public function getErrors();

    /**
     * Get finder warnings
     *
     * @return array List of warnings from last find
     */
    public function getWarnings();
}