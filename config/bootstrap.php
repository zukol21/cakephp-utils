<?php
use Burzum\FileStorage\Storage\Listener\BaseListener;
use Burzum\FileStorage\Storage\StorageManager;
use Burzum\FileStorage\Storage\StorageUtils;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Qobo\Utils\Utility;

/**
 * Burzum File-Storage configuration
 */
// get app level config
$config = Configure::read('FileStorage');
$config = $config ? $config : [];

// load default plugin config
Configure::load('Qobo/Utils.file_storage');

// overwrite default plugin config by app level config
Configure::write('FileStorage', array_replace_recursive(
    Configure::read('FileStorage'),
    $config
));

/**
 * ModuleConfig configuration
 */
// get app level config
$config = Configure::read('ModuleConfig');
$config = $config ? $config : [];

// load default plugin config
Configure::load('Qobo/Utils.module_config');

// overwrite default plugin config by app level config
Configure::write('ModuleConfig', array_replace_recursive(
    Configure::read('ModuleConfig'),
    $config
));

/**
 * Icons configuration
 */
// get app level config
$config = Configure::read('Icons');
$config = $config ? $config : [];

// load default plugin config
Configure::load('Qobo/Utils.icons');

// overwrite default plugin config by app level config
Configure::write('Icons', array_replace_recursive(
    Configure::read('Icons'),
    $config
));

/**
 * Colors configuration
 */
// get app level config
$config = Configure::read('Colors');
$config = $config ? $config : [];

// load default plugin config
Configure::load('Qobo/Utils.colors');

// overwrite default plugin config by app level config
Configure::write('Colors', array_replace_recursive(
    Configure::read('Colors'),
    $config
));

// This is very important! The hashes are needed to calculate the image versions!
StorageUtils::generateHashes();

StorageManager::config('Local', [
    'adapterOptions' => [WWW_ROOT, true],
    'adapterClass' => '\Gaufrette\Adapter\Local',
    'class' => '\Gaufrette\Filesystem'
]);

EventManager::instance()->on(new BaseListener(Configure::read('FileStorage')));

/**
 * Convert size value to bytes
 *
 * NOTE: This is left here purely for backward-compatibility reasons.
 *
 * @obsolete
 * @param string|int $size Size to convert
 * @return int
 */
function sizeToBytes($size)
{
    try {
        $result = Utility::valueToBytes($size);
    } catch (Exception $e) {
        // Mimic initial behavior, before exception was introduced
        $result = (string)$size;
        $result = (int)$result;
        return $result;;
    }

    return $result;
}
