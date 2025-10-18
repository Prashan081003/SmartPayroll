<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PayslipsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PayslipsTable Test Case
 */
class PayslipsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PayslipsTable
     */
    public $Payslips;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.payslips',
        'app.employees',
        'app.attendances',
        'app.bonuses',
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
        $config = TableRegistry::exists('Payslips') ? [] : ['className' => PayslipsTable::class];
        $this->Payslips = TableRegistry::get('Payslips', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Payslips);

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
