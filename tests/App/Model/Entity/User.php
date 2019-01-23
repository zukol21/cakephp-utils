<?php
namespace Qobo\Utils\Test\App\Model\Entity;

use Cake\ORM\Entity;

class User extends Entity
{
    /**
     * {@inheritDoc}
     */
    protected $_accessible = ['*' => true, 'id' => false];
}
