<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class DepartmentsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('departments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // One department has many employees
        $this->hasMany('Employees', [
            'foreignKey' => 'department_id',
            'dependent' => false
        ]);
    }

public function validationDefault(Validator $validator)
{
    $validator
        ->integer('id')
        ->allowEmpty('id', 'create');

    $validator
        ->maxLength('name', 100)
        ->requirePresence('name', 'create')
        ->notEmpty('name', 'Department name is required');

    $validator
        ->maxLength('code', 20)
        ->allowEmpty('code')
        ->add('code', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Code must be unique'
        ]);

    $validator
        ->allowEmpty('description');

    return $validator;
}
}