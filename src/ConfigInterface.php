<?php

namespace Qobo\Utils;

interface ConfigInterface
{
    /**
     * Sets the config.
     *
     * @param string|array $key The key to set, or a complete array of configs.
     * @param mixed|null $value The value to set.
     * @param mixed $merge Whether to recursively merge or overwrite existing config, defaults to true.
     * @return $this
     * @throws \Cake\Core\Exception\Exception When trying to set a key that is invalid.
     */
    public function setConfig($key, $value = null, $merge = true);

    /**
     * Returns the config.
     *
     * @param mixed $key The key to get or null for the whole config.
     * @param mixed $default The return value when the key does not exist.
     * @return mixed Config value being read.
     */
    public function getConfig($key = null, $default = null);
}
