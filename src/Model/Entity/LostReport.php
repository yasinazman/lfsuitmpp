<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class LostReport extends Entity
{
    // ... (coding atas maintain)

    protected array $_accessible = [
        'item_name' => true,
        'category' => true,
        'description' => true,
        'last_seen_location' => true,
        'lost_date' => true,
        'reporter_name' => true,
        'reporter_contact' => true,
        'reporter_matrix_id' => true,
        'image_file' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'claimer_name' => true,
        'claimer_matrix_id' => true,
        'claimer_contact' => true,
        'claimed_date' => true,
    ];
}