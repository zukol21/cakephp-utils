<?php

namespace Qobo\Utils\Utility\Locker;

use NinjaMutex\Mutex;
use NinjaMutex\Lock\LockInterface;
use Qobo\Utils\Utility\Locker\LockerInterface;

class BaseLocker implements LockerInterface
{
    /**
     * @var $locker
     */
    private $locker = null;

    /**
     * @var $timeout
     * 
     * 1. null if you want blocking lock
     * 2. 0 if you want just lock and go
     * 3. $timeout > 0 if you want to wait for lock some time (in milliseconds)
     */
    protected $timeout = 1000; 
    
    /**
     * __construct method
     *
     * @param string $key to use to lock operation
     * @param LockInterface $lock 
     */
    public function __construct($key, LockInterface $lock)
    {
        $this->locker = new Mutex($key, $lock);
    }

    public function lock()
    {
        $result = false;
        
        if ($this->locker->acquireLock($this->timeout)) {
            $result = true;
        }
        
        return $result;
    }

    public function unlock()
    {
        $result = false;
        
        if ($this->locker->releaseLock()) {
            $result = true;
        }
        
        return $result;
    }
}
