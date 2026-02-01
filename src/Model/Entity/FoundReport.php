<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class FoundReport extends Entity
{
    protected array $_accessible = [
        'item_name' => true,
        'category' => true,
        'description' => true,
        'found_location' => true,
        'found_date' => true,
        'finder_name' => true,
        'finder_contact' => true,
        'finder_matrix_id' => true,
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