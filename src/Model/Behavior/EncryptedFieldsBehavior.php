<?php
namespace Qobo\Utils\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;

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

    /**
     * Callback to never really delete a record but instead mark it as `trashed`.
     *
     * @param \Cake\Event\Event $event The beforeDelete event that was fired.
     * @param \Cake\Datasource\EntityInterface $entity The entity to be deleted.
     * @param \ArrayObject $options Options.
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        $entity = $this->encrypt($entity);
    }

    /**
     * Encrypts the fields.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity object.
     * @return \Cake\Datasource\EntityInterface Entity object.
     */
    public function encrypt(EntityInterface $entity): EntityInterface
    {
        return $entity;
    }
}
