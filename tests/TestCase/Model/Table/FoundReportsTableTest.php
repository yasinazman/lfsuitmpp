<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoundReportsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoundReportsTable Test Case
 */
class FoundReportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoundReportsTable
     */
    protected $FoundReports;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.FoundReports',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoundReports') ? [] : ['className' => FoundReportsTable::class];
        $this->FoundReports = $this->getTableLocator()->get('FoundReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FoundReports);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FoundReportsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
