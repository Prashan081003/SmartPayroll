<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BonusesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('bonuses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Payslips', [
            'foreignKey' => 'payslip_id',
            'joinType' => 'INNER'
        ]);
    }
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('payslip_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('payslip_id', 'create')
            ->notEmpty('payslip_id', 'Payslip ID is required');

        $validator
            ->requirePresence('bonus_type', 'create')
            ->notEmpty('bonus_type', 'Bonus type is required')
            ->add('bonus_type', 'inList', [
                'rule' => ['inList', ['Performance', 'Festival']],
                'message' => 'Bonus type must be either Performance or Festival'
            ]);

        $validator
            ->add('amount', 'valid', ['rule' => 'decimal'])
            ->requirePresence('amount', 'create')
            ->notEmpty('amount', 'Amount is required')
            ->add('amount', 'positive', [
                'rule' => function ($value) { return $value > 0; },
                'message' => 'Amount must be greater than 0'
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['payslip_id'], 'Payslips'));

        return $rules;
    }
}
