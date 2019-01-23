<?php
namespace Qobo\Utils\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\Utility\Security;
use RuntimeException;

/**
 * EncryptedFields behavior
 */
class EncryptedFieldsBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * - enabled: Boolean or custom callable which returns a boolean and accepts
     *      {@link \Cake\Datasource\EntityInterface} as a parameter.
     *      Determines if encryption is enabled for this particular entity.
     * - encryptionKey: Encryption key. Mandatory if `enabled` is set to `true`.
     * - fields: Array of fields to encrypt. Each field may be an array of the following settings:
     *      - decrypt: boolean or callable which accepts {@link \Cake\Datasource\EntityInterface}
     *          and field name as parameters, and return a boolean, which determines
     *          if decryption is enabled for this field. Useful for permission checks.
     * - base64: Whether to encode/decode the encrypted string in base64 so the
     *      encrypted field can be stored as a string.
     * - decryptAll: Set to true to allow decrypting all `fields`.
     *      Overrides the decryption values specified in `fields`.
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
        if (!$this->isEncryptable($entity)) {
            return $entity;
        }

        $fields = $this->getFields();
        $encryptionKey = $this->getConfig('encryptionKey');
        $base64 = $this->getConfig('base64');
        $table = $this->getTable();

        $patch = [];
        foreach ($fields as $name => $field) {
            if (!$table->hasField($name)) {
                continue;
            }
            $value = $entity->get($name);
            $encrypted = Security::encrypt($value, $encryptionKey);
            if ($base64 === true) {
                $encrypted = base64_encode($encrypted);
            }
            $patch[$name] = $encrypted;
        }

        if (!empty($patch)) {
            $accessible = array_fill_keys(array_keys($patch), true);
            $entity = $table->patchEntity($entity, $patch, [
                'accessibleField' => $accessible,
            ]);
        }

        return $entity;
    }

    /**
     * Checks whether the given entity can be encrypted.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity object.
     * @throws \RuntimeException When `condition` callable returns a non-boolean value.
     * @return bool True if encryption is allowed
     */
    public function isEncryptable(EntityInterface $entity): bool
    {
        $enabled = $this->getConfig('enabled');
        if (is_callable($enabled)) {
            $enabled = $enabled($entity);
            if (!is_bool($enabled)) {
                throw new RuntimeException('Condition callable must return a boolean.');
            }
        }

        return $enabled;
    }

    /**
     * Returns a processed list of fields with applied default values.
     *
     * @return mixed[] Fields array.
     */
    protected function getFields(): array
    {
        $fields = $this->getConfig('fields');

        $defaults = [
            'decrypt' => false,
        ];

        $result = [];
        foreach ($fields as $field => $values) {
            if (is_numeric($field)) {
                $field = $values;
                $values = [];
            }
            $values = array_merge($defaults, $values);
            $result[$field] = $values;
        }

        return $result;
    }
}
