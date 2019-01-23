<?php
namespace Qobo\Utils\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Qobo\Utils\Model\Behavior\EncryptedFieldsBehavior;

/**
 * Qobo\Utils\Model\Behavior\EncryptedFieldsBehavior Test Case
 */
class EncryptedFieldsBehaviorTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Qobo\Utils\Model\Behavior\EncryptedFieldsBehavior
     */
    public $EncryptedFields;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->EncryptedFields = new EncryptedFieldsBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EncryptedFields);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
