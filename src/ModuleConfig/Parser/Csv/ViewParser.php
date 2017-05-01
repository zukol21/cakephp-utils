<?php
namespace Qobo\Utils\ModuleConfig\Parser\Csv;

use League\Csv\Reader;
use Qobo\Utils\Utility;
use StdClass;

/**
 * View CSV Parser
 *
 * This parser is useful for view CSV processing.
 * View CSV files can vary in their format, depending
 * on the need of the view (for example, we currently
 * support add/edit/view with panels and index view,
 * which is completely different.
 *
 * Therefor, it is assumed that the caller of the
 * parser knows how to best handle the returned results.
 *
 * It is assumed that the first row ALWAYS contains
 * the column headers.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ViewParser extends AbstractCsvParser
{
    /**
     * JSON schema
     *
     * This can either be a string, pointing to the file
     * or an StdClass with an instance of an already parsed
     * schema
     *
     * @var string|StdClass $schema JSON schema
     */
    protected $schema = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'view.json';

    /**
     * Read and parse a given path
     *
     * @throws \InvalidArgumentException when cannot read or decode path
     * @param string $path Path to file
     * @return object
     */
    protected function getDataFromPath($path)
    {
        $result = new StdClass();
        $result->items = [];

        try {
            Utility::validatePath($path);
        } catch (Exception $e) {
            // View files are not required
            $this->warnings[] = "Path does not exist: $path";
            $result = $this->mergeWithDefaults($result);

            return $result;
        }

        $reader = Reader::createFromPath($path, $this->open_mode);
        $rows = $reader->setOffset(1)->fetchAll();
        foreach ($rows as $row) {
            $row = json_encode($row);
            if ($row === false) {
                throw new InvalidArgumentException("Failed to encode row from path: $path");
            }
            $row = json_decode($row, true);
            if ($row === null) {
                throw new InvalidArgumentException("Failed to decode row from path: $path");
            }
            $result->items[] = $row;
        }
        $result = $this->mergeWithDefaults($result);

        return $result;
    }
}
