<?php
namespace Qobo\Utils\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Qobo\Utils\Utility\User;

class FootprintBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'created_by' => 'created_by',
        'modified_by' => 'modified_by',
        'callback' => [User::class, 'getCurrentUser']
    ];

    /**
     * Initialize method.
     *
     * @param array $config Behavior configuration
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setConfig($config);
    }

    /**
     * BeforeSave callback method.
     *
     * @param \Cake\Event\Event $event Event object
     * @param \Cake\Datasource\EntityInterface $entity Entity instance
     * @param \ArrayObject $options Query options
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (! is_callable($this->getConfig('callback'))) {
            return;
        }

        $user = call_user_func($this->getConfig('callback'));
        if (empty($user['id'])) {
            return;
        }

        if ($entity->isNew()) {
            $entity->set($this->getConfig('created_by'), $user['id']);
        }

        $entity->set($this->getConfig('modified_by'), $user['id']);
    }
}
