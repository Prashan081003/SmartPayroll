<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeductionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeductionsTable Test Case
 */
class DeductionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DeductionsTable
     */
    public $Deductions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.deductions',
        'app.payslips',
        'app.employees',
        'app.attendances',
        'app.bonuses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Deductions') ? [] : ['className' => DeductionsTable::class];
        $this->Deductions = TableRegistry::get('Deductions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Deductions);

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
