<?php
namespace Qobo\Utils\ModuleConfig;

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
     * Instance of the PathFinder
     *
     * @var \Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface
     */
    protected $finder;

    /**
     * Instance of the Parser
     *
     * @var \Qobo\Utils\ModuleConfig\Parser\ParserInterface
     */
    protected $parser;

    /**
     * Class lookup map
     *
     * @var array
     */
    protected $lookup = [
        self::CONFIG_TYPE_MIGRATION => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MigrationPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\MigrationParser',
        ],
        self::CONFIG_TYPE_MODULE => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ConfigPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ConfigParser',
        ],
        self::CONFIG_TYPE_LIST => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ListPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ListParser',
        ],
        self::CONFIG_TYPE_FIELDS => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\FieldsPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\FieldsParser',
        ],
        self::CONFIG_TYPE_MENUS => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\MenusPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Json\\MenusParser',
        ],
        self::CONFIG_TYPE_REPORTS => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ReportsPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\ReportsParser',
        ],
        self::CONFIG_TYPE_VIEW => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ViewPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ViewParser',
        ],
    ];

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
     * Get class name by type
     *
     * This is a factory method, which finds the appropriate class
     * name for a given configuration type.
     *
     * @param string $classType Type of class to find
     * @return string
     */
    protected function getClassByType($classType)
    {
        $result = null;

        if (empty($this->lookup[$this->configType][$classType])) {
            $this->fail("Module Config : No [$classType] found for configurationi type [" . $this->configType . "]");
        }
        $result = $this->lookup[$this->configType][$classType];

        return $result;
    }

    /**
     * Get class instance by type
     *
     * This is a factory method, which finds the appropriate class
     * for a given configuration type and returns an instance of it.
     *
     * @param string $classType Type of class to find
     * @return object
     */
    protected function getInstanceByType($classType)
    {
        $className = $this->getClassByType($classType);

        if (!class_exists($className)) {
            $this->fail("Module Config : Class [$className] does not exist");
        }

        return new $className;
    }

    /**
     * Set path finder instance
     *
     * @param \Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface $finder Finder instance
     * @return void
     */
    public function setFinder(PathFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Get path finder instance
     *
     * If the specific instance wasn't set, the automagic kicks in to
     * figure out which class is the most appropriate.
     *
     * @return \Qobo\Utils\ModuleConfig\PathFinder\PathFinderInterface
     */
    public function getFinder()
    {
        if ($this->finder) {
            return $this->finder;
        }

        $this->setFinder($this->getInstanceByType(self::CLASS_TYPE_FINDER));

        return $this->finder;
    }

    /**
     * Set parser instance
     *
     * @param \Qobo\Utils\ModuleConfig\Parser\ParserInterface $parser Parser instance
     * @return void
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Get parser instance
     *
     * If the specific instance wasn't set, the automagic kicks in to
     * figure out which class is the most appropriate.
     *
     * @return \Qobo\Utils\ModuleConfig\Parser\ParserInterface
     */
    public function getParser()
    {
        if ($this->parser) {
            return $this->parser;
        }

        $this->setParser($this->getInstanceByType(self::CLASS_TYPE_PARSER));

        return $this->parser;
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
        $prefix = (string) $prefix;

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
