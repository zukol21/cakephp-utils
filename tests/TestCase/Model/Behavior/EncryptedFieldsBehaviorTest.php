<?php
namespace Qobo\Utils\Test\TestCase\Model\Behavior;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Qobo\Utils\Model\Behavior\EncryptedFieldsBehavior;

/**
 * Qobo\Utils\Model\Behavior\EncryptedFieldsBehavior Test Case
 */
class EncryptedFieldsBehaviorTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.qobo/utils.users',
    ];

    /**
     * Test subject
     *
     * @var \Qobo\Utils\Model\Behavior\EncryptedFieldsBehavior
     */
    public $EncryptedFields;

    /**
     * Test table
     *
     * @var \Qobo\Utils\Test\App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Encryption key
     *
     * @var string
     */
    protected $key;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->key = Configure::readOrFail('Qobo/Utils.encryptionKey');
        /** @var \Qobo\Utils\Test\App\Model\Table\UsersTable $table */
        $table = TableRegistry::getTableLocator()->get('Users');
        $this->Users = $table;
        $this->Users->setTable('users');

        $config = [
            'encryptionKey' => $this->key,
            'fields' => [
                'name' => [
                    'decrypt' => true,
                ],
            ],
        ];
        $this->EncryptedFields = new EncryptedFieldsBehavior($this->Users, $config);
        $this->Users->addBehavior('Qobo/Utils.EncryptedFields', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EncryptedFields);
        unset($this->key);
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
