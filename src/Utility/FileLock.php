<?php
namespace Qobo\Utils\Utility;

use InvalidArgumentException;
use RuntimeException;

final class FileLock
{
    /**
     * Lock file pointer.
     *
     * @var resource
     */
    protected $_fp;

    /**
     * Lock file path.
     *
     * @var string
     */
    protected $_path;

    /**
     * Initialize lock functionality by creating the lock file.
     *
     * @param string $filename File name
     * @return \Qobo\Utils\Utility\FileLock
     */
    public function __construct($filename)
    {
        $filename = basename(trim($filename));
        if (empty($filename) || !is_string($filename)) {
            throw new InvalidArgumentException('Lock filename is required and must be a string');
        }

        $this->_path = sys_get_temp_dir() . DS . $filename;

        $result = @fopen($this->_path, 'w+');

        if (!is_resource($result)) {
            throw new RuntimeException('Fail to create lock file [' . $this->_path . ']');
        }

        $this->_fp = $result;

        return $this;
    }

    /**
     * Lock the file.
     *
     * @return bool
     */
    public function lock()
    {
        return flock($this->_fp, LOCK_EX | LOCK_NB);
    }

    /**
     * Unlock the file.
     *
     * @return bool
     */
    public function unlock()
    {
        $result = flock($this->_fp, LOCK_UN);

        fclose($this->_fp);

        unlink($this->_path);

        return $result;
    }
}
