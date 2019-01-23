<?php
namespace Qobo\Utils\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;

/**
 * EncryptedFields behavior
 */
class EncryptedFieldsBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => true,
        'encryptionKey' => '',
        'fields' => [
        ],
        'base64' => true,
        'decryptAll' => true,
    ];

    /**
     * Initialize the behavior.
     *
     * @param array $config The config for this behavior.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setConfig($config);
    }
}
