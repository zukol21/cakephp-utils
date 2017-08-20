<?php
namespace Qobo\Utils\ModuleConfig;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Exception;
use Qobo\Utils\ErrorTrait;
use Qobo\Utils\ModuleConfig\Parser\ParserInterface;
use Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface;
use RuntimeException;

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
        $finder = null;
        $exception = null;
        try {
            $finder = $this->getFinder();
            $result = $finder->find($this->module, $this->configFile, $validate);
        } catch (Exception $exception) {
            $this->mergeMessages($exception, __FUNCTION__);
        }

        // Get finder errors and warnings, if any
        $this->mergeMessages($finder, __FUNCTION__);

        // Re-throw finder exception
        if ($exception) {
            throw $exception;
        }

        return $result;
    }

    /**
     * Parse module configuration file
     *
     * @return object Whatever Parser returned
     */
    public function parse()
    {
        $parser = null;
        $exception = null;
        try {
            $path = $this->find(false);
            $result = $this->readFromCache($path);
            if ($result) {
                return $result;
            }
            $parser = $this->getParser();
            $result = $parser->parse($path, $this->options);
        } catch (Exception $exception) {
            $this->mergeMessages($exception, __FUNCTION__);
        }

        // Get parser errors and warnings, if any
        $this->mergeMessages($parser, __FUNCTION__);

        // Re-throw parser exception
        if ($exception) {
            throw $exception;
        }
        $this->writeToCache($path, $result);

        return $result;
    }

    /**
     * Get cache key
     *
     * The key is combined from the path the parsed configuration file
     * and the options which were used to parse it.  Since the path
     * can get quite long, and options are an array, we md5 each of
     * these parts and then combine them together.
     *
     * @param string $path Path to configuration file
     * @return string
     */
    protected function getCacheKey($path)
    {
        $result = md5($path) . '_' . md5(json_encode($this->options));

        return $result;
    }

    /**
     * Figure out which cache configuration to use
     *
     * If the cache configuration specified in `cacheConfig` key of the
     * options, use that.  Otherwise return the 'default'.
     *
     * @return string Cache configuration to use
     */
    protected function getCacheConfig()
    {
        $result = 'default';

        if (empty($this->options['cacheConfig'])) {
            return $result;
        }

        $result = (string)$this->options['cacheConfig'];

        return $result;
    }

    /**
     * Check if the caching should be skipped or not
     *
     * @return bool True if skipping, false otherwise
     */
    protected function skipCache()
    {
        $result = false;
        // Skip cache altogether if the options demand so
        if (!empty($this->options['cacheSkip']) && $this->options['cacheSkip']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Read parsed result from cache
     *
     * @param string $path Path to configuration file
     * @return null|object Null if no cache, object otherwise
     */
    protected function readFromCache($path)
    {
        $result = null;

        if ($this->skipCache()) {
            $this->warnings[] = 'Skipping read from cache';

            return $result;
        }

        $cachedData = Cache::read($this->getCacheKey($path), $this->getCacheConfig());
        if (!$cachedData) {
            $this->warnings[] = 'Value not found in cache';

            return $result;
        }

        // Check if the config file was modified since it's
        // parsed value was cached
        if (md5($cachedData['path']) <> $cachedData['md5']) {
            $this->warnings[] = 'Stale cache found. Cleaning up and ignoring';
            Cache::delete($this->getCacheKey($path), $this->getCacheConfig());

            return $result;
        }

        $result = $cachedData['data'];

        return $result;
    }

    /**
     * Write parsed result to cache
     *
     * @param string $path Path to configuration file
     * @param object $data Parsed config
     * @return bool True if the data was successfully cached, false on failure
     */
    protected function writeToCache($path, $data)
    {
        $result = false;

        if ($this->skipCache()) {
            $this->warnings[] = 'Skipping write to cache';

            return $result;
        }

        $cachedData = [
            'path' => $path,
            'md5' => md5($path),
            'data' => $data,
        ];
        $result = Cache::write($this->getCacheKey($path), $cachedData, $this->getCacheConfig());
        if (!$result) {
            $this->errors[] = 'Failed to write value to cache';
        }

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
        $prefix = (string)$prefix;

        // Convert single messages to array
        if (is_string($messages)) {
            $messages = [$messages];
        }

        // Prefix all messages
        $messages = array_map(function ($item) use ($prefix) {
            return sprintf("[%s][%s] %s : %s", $this->module, $this->configType, $prefix, $item);
        }, $messages);

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
