<?php

namespace Qobo\Utils\Utility\Locker;

interface LockerInterface
{
    /**
     * lock method
     *
     * @return bool
     */
    public function lock();
    
    /**
     * unlock method
     *
     * @return bool
     */
    public function unlock();
}
