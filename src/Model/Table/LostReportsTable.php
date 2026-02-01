<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LostReports Model
 *
 * @method \App\Model\Entity\LostReport newEmptyEntity()
 * @method \App\Model\Entity\LostReport newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LostReport> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LostReport get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LostReport findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LostReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LostReport> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LostReport|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LostReport saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LostReport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LostReport>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LostReport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LostReport> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LostReport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LostReport>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LostReport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LostReport> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LostReportsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('lost_reports');
        $this->setDisplayField('item_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('item_name')
            ->maxLength('item_name', 255)
            ->requirePresence('item_name', 'create')
            ->notEmptyString('item_name');

        // ... validation lain ...

        // PART PENTING:
        $validator
            ->allowEmptyFile('image_file') // Benarkan kosong kalau user tak nak upload
            ->add('image_file', [
                'mimeType' => [
                    'rule' => ['mimeType', ['image/jpeg', 'image/png', 'image/jpg']],
                    'message' => 'Please upload only JPG or PNG images.',
                ],
                'fileSize' => [
                    'rule' => ['fileSize', '<=', '5MB'], // Limit 5MB
                    'message' => 'Image file size must be less than 5MB.',
                ],
            ]);

        return $validator;
    }
}
