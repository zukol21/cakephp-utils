<?php
use Burzum\FileStorage\Storage\Listener\BaseListener;
use Burzum\FileStorage\Storage\StorageManager;
use Burzum\FileStorage\Storage\StorageUtils;
use Cake\Core\Configure;
use Cake\Event\EventManager;

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

// This is very important! The hashes are needed to calculate the image versions!
StorageUtils::generateHashes();

StorageManager::config('Local', [
    'adapterOptions' => [WWW_ROOT, true],
    'adapterClass' => '\Gaufrette\Adapter\Local',
    'class' => '\Gaufrette\Filesystem'
]);

EventManager::instance()->on(new BaseListener(Configure::read('FileStorage')));
