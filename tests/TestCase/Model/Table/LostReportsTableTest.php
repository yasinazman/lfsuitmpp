<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LostReportsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LostReportsTable Test Case
 */
class LostReportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LostReportsTable
     */
    protected $LostReports;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.LostReports',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('LostReports') ? [] : ['className' => LostReportsTable::class];
        $this->LostReports = $this->getTableLocator()->get('LostReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->LostReports);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\LostReportsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
