<?php

namespace Qobo\Utils\Utility\Locker;

use NinjaMutex\Lock\FlockLock;
use Qobo\Utils\Utility\Locker\BaseLocker;

class FileLocker extends BaseLocker
{
    /**
     * @var $lockDir
     */
    private $lockDir = '/tmp';

    /**
     * __construct method
     *
     * @param string $key to use to lock operation
     * @param string $dir to store lock files
     * @return void
     */
    public function __construct($key, $dir = null)
    {
        if (!empty($dir)) {
            $this->lockDir = $dir;
        }

        $lock = new FlockLock($this->lockDir);

        parent::__construct($key, $lock);
    }
}
