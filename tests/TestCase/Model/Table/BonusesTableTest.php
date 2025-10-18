<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BonusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BonusesTable Test Case
 */
class BonusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BonusesTable
     */
    public $Bonuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bonuses',
        'app.payslips',
        'app.employees',
        'app.attendances',
        'app.deductions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Bonuses') ? [] : ['className' => BonusesTable::class];
        $this->Bonuses = TableRegistry::get('Bonuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bonuses);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
