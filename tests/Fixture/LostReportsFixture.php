<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LostReportsFixture
 */
class LostReportsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'item_name' => 'Lorem ipsum dolor sit amet',
                'category' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'last_seen_location' => 'Lorem ipsum dolor sit amet',
                'lost_date' => '2026-01-02',
                'reporter_name' => 'Lorem ipsum dolor sit amet',
                'reporter_contact' => 'Lorem ipsum dolor ',
                'reporter_matrix_id' => 'Lorem ipsum dolor ',
                'image_file' => 'Lorem ipsum dolor sit amet',
                'status' => 'Lorem ipsum dolor ',
                'created' => '2026-01-02 04:26:24',
                'modified' => '2026-01-02 04:26:24',
            ],
        ];
        parent::init();
    }
}
