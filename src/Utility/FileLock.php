<?php
namespace Qobo\Utils\Utility;

use InvalidArgumentException;
use RuntimeException;

final class FileLock
{
    /**
     * Lock file handler.
     *
     * @var resource
     */
    protected $handler;

    /**
     * Lock file path.
     *
     * @var string
     */
    protected $path;

    /**
     * Initialize lock functionality by creating the lock file.
     *
     * @param string $filename File name
     * @return \Qobo\Utils\Utility\FileLock
     */
    public function __construct($filename)
    {
        if (!is_string($filename)) {
            throw new InvalidArgumentException('Lock filename must be a string');
        }

        $filename = trim($filename);

        if (empty($filename)) {
            throw new InvalidArgumentException('Lock filename is required');
        }

        $filename = basename($filename);

        $this->path = sys_get_temp_dir() . DS . $filename;

        $result = @fopen($this->path, 'w+');

        if (!is_resource($result)) {
            throw new RuntimeException('Fail to create lock file [' . $this->path . ']');
        }

        $this->handler = $result;

        return $this;
    }

    /**
     * Lock the file.
     *
     * @return bool
     */
    public function lock()
    {
        return flock($this->handler, LOCK_EX | LOCK_NB);
    }

    /**
     * Unlock the file.
     *
     * @return bool
     */
    public function unlock()
    {
        $result = flock($this->handler, LOCK_UN);

        fclose($this->handler);

        unlink($this->path);

        return $result;
    }
}
