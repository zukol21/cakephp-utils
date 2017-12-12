<?php

namespace Qobo\Utils\Utility\Locker;

use Cake\Core\Configure;
use NinjaMutex\Lock\FlockLock;
use Qobo\Utils\Utility\Locker\BaseLocker;

class FileLocker extends BaseLocker
{
    /**
     * @var $lockDir
     */
    private $lockDir = null;

    /**
     * __construct method
     *
     * @param string $key to use to lock operation
     * @param string $dir to store lock files
     * @return void
     */
    public function __construct($key, $dir = null)
    {
        $config = Configure::read('Locker.FileLocker');
        if (!empty($config['dir'])) {
            $this->lockDir = $config['dir'];
        }

        if (!empty($dir)) {
            $this->lockDir = $dir;
        }

        if (empty($this->lockDir)) {
            $this->lockDir = sys_get_temp_dir();
        }

        $lock = new FlockLock($this->lockDir);

        parent::__construct($key, $lock);
    }
}
