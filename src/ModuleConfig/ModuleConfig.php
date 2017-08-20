<?php
namespace Qobo\Utils\ModuleConfig;

use Exception;
use Qobo\Utils\ErrorTrait;
use Qobo\Utils\ModuleConfig\Cache\Cache;
use Qobo\Utils\ModuleConfig\Cache\PathCache;

/**
 * ModuleConfig Class
 *
 * This class assists with finding, parsing and validating a
 * variety of module configurations available in CsvMigrations
 * CakePHP plugin.  Some of the things that it understands are:
 *
 * * migration.csv
 * * config.ini
 * * menus.json
 * * fields.ini
 * * reports.ini
 * * lists CSV files
 * * views CSV files
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ModuleConfig
{
    use ErrorTrait;

    /**
     * Type for migration configuration (migration.csv)
     */
    const CONFIG_TYPE_MIGRATION = 'migration';

    /**
     * Type for module configuration (config.ini)
     */
    const CONFIG_TYPE_MODULE = 'module';

    /**
     * Type for menus configuration (menus.json)
     */
    const CONFIG_TYPE_MENUS = 'menus';

    /**
     * Type for fields configuration (fields.ini)
     */
    const CONFIG_TYPE_FIELDS = 'fields';

    /**
     * Type for reports configuration (reports.ini)
     */
    const CONFIG_TYPE_REPORTS = 'reports';

    /**
     * Type for list configuration (list.csv)
     */
    const CONFIG_TYPE_LIST = 'list';

    /**
     * Type for view configuration (index.csv)
     */
    const CONFIG_TYPE_VIEW = 'view';

    /**
     * Class type for path finders
     */
    const CLASS_TYPE_FINDER = 'finder';

    /**
     * Class type for parsers
     */
    const CLASS_TYPE_PARSER = 'parser';

    /**
     * Configuration type, e.g.: migration, list, view, etc.
     *
     * @var string
     */
    protected $configType;

    /**
     * Name of the module
     *
     * @var string
     */
    protected $module;

    /**
     * Name (or other reference) to particular configuration file
     *
     * @var string
     */
    protected $configFile;

    /**
     * Options for finding, parsing, and verifying
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor
     *
     * @param string $configType Type of configuration
     * @param string $module     Module name
     * @param string $configFile (Optional) name of the config file
     * @param array  $options    (Optional) Finding, parsing, etc. options
     */
    public function __construct($configType, $module, $configFile = '', array $options = [])
    {
        $this->configType = (string)$configType;
        $this->module = (string)$module;
        $this->configFile = (string)$configFile;
        $this->options = $options;
    }

    /**
     * Get path finder instance
     *
     * @return \Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface
     */
    protected function getFinder()
    {
        $result = ClassFactory::create($this->configType, self::CLASS_TYPE_FINDER, $this->options);

        return $result;
    }

    /**
     * Get parser instance
     *
     * @return \Qobo\Utils\ModuleConfig\Parser\ParserInterface
     */
    protected function getParser()
    {
        $result = ClassFactory::create($this->configType, self::CLASS_TYPE_PARSER, $this->options);

        return $result;
    }

    /**
     * Find module configuration file
     *
     * @param bool $validate Whether or not validate result
     * @return mixed Whatever the PathFinder returned
     */
    public function find($validate = true)
    {
        $cache = null;
        $finder = null;
        $exception = null;
        try {
            $cache = new Cache(__FUNCTION__, $this->options);
            $cacheKey = $cache->getKey([$this->module, $this->configType, $this->configFile, $validate]);
            $result = $cache->readFrom($cacheKey);
            if ($result !== false) {
                return $result;
            }
            $finder = $this->getFinder();
            $result = $finder->find($this->module, $this->configFile, $validate);
        } catch (Exception $exception) {
            $this->mergeMessages($exception, __FUNCTION__);
        }

        // Get finder errors and warnings, if any
        $this->mergeMessages($finder, __FUNCTION__);
        $this->mergeMessages($cache, __FUNCTION__);

        // Re-throw finder exception
        if ($exception) {
            throw $exception;
        }
        $cache->writeTo($cacheKey, $result);

        return $result;
    }

    /**
     * Parse module configuration file
     *
     * @return object Whatever Parser returned
     */
    public function parse()
    {
        $cache = null;
        $parser = null;
        $exception = null;
        try {
            $path = $this->find(false);
            $cache = new PathCache(__FUNCTION__, $this->options);
            $cacheKey = $cache->getKey([$path]);
            $result = $cache->readFrom($cacheKey);
            if ($result !== false) {
                return $result;
            }
            $parser = $this->getParser();
            $result = $parser->parse($path, $this->options);
        } catch (Exception $exception) {
            $this->mergeMessages($exception, __FUNCTION__);
        }

        // Get parser errors and warnings, if any
        $this->mergeMessages($parser, __FUNCTION__);
        $this->mergeMessages($cache, __FUNCTION__);

        // Re-throw parser exception
        if ($exception) {
            throw $exception;
        }
        $cache->writeTo($cacheKey, $result, ['path' => $path]);

        return $result;
    }

    /**
     * Prefix messages
     *
     * Prefix all given messages with a string
     *
     * @param string|array $messages One or more messages to prefix
     * @param string $prefix Prefix to prepend to all messages
     * @return array List of prefixed messages
     */
    protected function prefixMessages($messages, $prefix)
    {
        $result = [];

        $prefix = (string)$prefix;

        // Convert single messages to array
        if (is_string($messages)) {
            $messages = [$messages];
        }

        // Prefix all messages
        foreach ($messages as $message) {
            $result[] = sprintf("[%s][%s] %s : %s", $this->module, $this->configType, $prefix, $message);
        }

        return $messages;
    }

    /**
     * Merge warning and error messages
     *
     * Merge warning and error messages from a given source
     * object into our warnings and messages.
     *
     * @param object $source Source object (ideally one using ErrorTrait)
     * @param string $caller Caller that generated a message
     * @return void
     */
    protected function mergeMessages($source, $caller = 'ModuleConfig')
    {
        if (!is_object($source)) {
            return;
        }

        if (is_a($source, '\Exception')) {
            $this->errors = array_merge($this->errors, $this->prefixMessages($source->getMessage(), $caller));

            return;
        }

        if (method_exists($source, 'getErrors') && is_callable([$source, 'getErrors'])) {
            $this->errors = array_merge($this->errors, $this->prefixMessages($source->getErrors(), $caller));
        }

        if (method_exists($source, 'getWarnings') && is_callable([$source, 'getWarnings'])) {
            $this->warnings = array_merge($this->warnings, $this->prefixMessages($source->getWarnings(), $caller));
        }
    }
}
