<?php
namespace Qobo\Utils\Controller\Component;

use ArrayObject;
use Cake\Controller\Component;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\QueryInterface;
use Cake\Event\Event;

/**
 * This component class is responsible for attaching current user information to the ORM Table's callback methods.
 */
class FootprintComponent extends Component
{
    /**
     * Initialize method.
     *
     * @param array $config Component configuration
     * @return void
     */
    public function initialize(array $config)
    {
        $this->attachCurrentUser();
    }

    /**
     * Attaches current user to the following ORM Table's lifecycle callbacks:
     *
     * Model.beforeSave
     * Model.beforeFind
     *
     * @return void
     */
    private function attachCurrentUser()
    {
        $this->getEventManager()->on(
            'Model.beforeSave',
            ['priority' => -PHP_INT_MAX],
            function (Event $event, EntityInterface $entity, ArrayObject $options) {
                if (empty($options['footprint']['user'])) {
                    $options['footprint']['user'] = $this->getCurrentUser();
                }
            }
        );

        $this->getEventManager()->on(
            'Model.beforeFind',
            ['priority' => -PHP_INT_MAX],
            function (Event $event, QueryInterface $query, ArrayObject $options) {
                if (empty($options['footprint']['user'])) {
                    $options['footprint']['user'] = $this->getCurrentUser();
                }
            }
        );
    }

    /**
     * Current user getter.
     *
     * @return array
     */
    private function getCurrentUser()
    {
        if (! property_exists($this->_registry->getController(), 'Auth')) {
            return [];
        }

        return $this->_registry->getController()->Auth->user();
    }

    /**
     * Table instance Event Manager getter.
     *
     * @return \Cake\Event\EventManager
     */
    private function getEventManager()
    {
        $tableName = $this->_registry->getController()->modelClass;

        return $this->_registry->getController()->{$tableName}->getEventManager();
    }
}
