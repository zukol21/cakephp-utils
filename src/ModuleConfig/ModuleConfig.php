<?php
namespace Qobo\Utils\ModuleConfig;

use BadMethodCallException;
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
 * * fields.ini
 * * reports.ini
 * * lists CSV files
 * * views CSV files
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ModuleConfig
{
    /**
     * Type for migration configuration (migration.csv)
     */
    const CONFIG_TYPE_MIGRATION = 'migration';

    /**
     * Type for module configuration (config.ini)
     */
    const CONFIG_TYPE_MODULE = 'module';

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
     * @var \Qobo\Utils\ModuleConfig\PathFinderInterface
     */
    protected $finder;

    /**
     * Instance of the Parser
     *
     * @var \Qobo\Utils\ModuleConfig\ParserInterface
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
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\Parser',
        ],
        self::CONFIG_TYPE_LIST => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ListPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Csv\\ListParser',
        ],
        self::CONFIG_TYPE_FIELDS => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\FieldsPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\Parser',
        ],
        self::CONFIG_TYPE_REPORTS => [
            self::CLASS_TYPE_FINDER => 'Qobo\\Utils\\ModuleConfig\\PathFinder\\ReportsPathFinder',
            self::CLASS_TYPE_PARSER => 'Qobo\\Utils\\ModuleConfig\\Parser\\Ini\\Parser',
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
     * @throws \RuntimeException When the class is not defined.
     * @param string $classType Type of class to find
     * @return string
     */
    protected function getClassByType($classType)
    {
        $result = null;

        if (empty($this->lookup[$this->configType][$classType])) {
            throw new RuntimeException("No [$classType] found for configurationi type [" . $this->configType . "]");
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
     * @throws \RuntimeException When the defined class does not exist.
     * @param string $classType Type of class to find
     * @return object
     */
    protected function getInstanceByType($classType)
    {
        $className = $this->getClassByType($classType);

        if (!class_exists($className)) {
            throw new RuntimeException("Class [$className] does not exist");
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
     * @return mixed Whatever the PathFinder returned
     */
    public function find()
    {
        return $this->getFinder()->find($this->module, $this->configFile);
    }

    /**
     * Parse module configuration file
     *
     * @return mixed Whatever Parser returned
     */
    public function parse()
    {
        return $this->getParser()->parse($this->find(), $this->options);
    }

    /**
     * Validate module configuration file
     *
     * @return mixed Whatever Parser returned
     */
    public function validate()
    {
        throw new BadMethodCallException("This method is not implemented yet, and is just a placeholder");
    }
}
