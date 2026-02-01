<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class FoundReportsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('found_reports'); // Pastikan ejaan sama dengan database awak
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');  // Auto isi created/modified
    }
}