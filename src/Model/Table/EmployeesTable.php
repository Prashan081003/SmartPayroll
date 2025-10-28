<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\Event\Event;
use ArrayObject;

class EmployeesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('employees');
        $this->setPrimaryKey('id');

        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Attendances', [
            'foreignKey' => 'employee_id'
        ]);

    }

 public function validationDefault(Validator $validator)
{
    $validator
        ->integer('id')
        ->allowEmpty('id', 'create');

    // Employee ID
    $validator
        ->maxLength('employee_id', 20)
        ->allowEmpty('employee_id', 'create')
        ->add('employee_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

    // Name
    $validator
        ->maxLength('name', 100, 'Name cannot be longer than 100 characters')
        ->requirePresence('name', 'create')
        ->notEmpty('name', 'Please enter the name');

    //Department 
    $validator
        ->integer('department_id')
        ->requirePresence('department_id', 'create')
        ->notEmpty('department_id', 'Please select a department');

    // Designation
    $validator
        ->maxLength('designation', 50)
        ->requirePresence('designation', 'create')
        ->notEmpty('designation', 'Please enter the designation');

    // Base Salary
    $validator
    ->requirePresence('base_salary', 'create')
    ->notEmpty('base_salary', 'Please enter the base salary')
    ->add('base_salary', 'numeric', [
        'rule' => 'numeric',
        'message' => 'Base salary must be a number'
    ])
    ->add('base_salary', 'positive', [
        'rule' => function ($value, $context) {
            return $value > 0;
        },
        'message' => 'Salary must be a positive number'
    ]);
    // Joining Date
    $validator
        ->date('joining_date', ['ymd'], 'Please provide a valid date')
        ->requirePresence('joining_date', 'create')
        ->notEmpty('joining_date', 'Joining date is required');

    // Email
    $validator
        ->email('email', false, 'Please provide a valid email')
        ->requirePresence('email', 'create')
        ->notEmpty('email', 'Email is required')
        ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

    // Mobile
    $validator
        ->maxLength('mobile', 15)
        ->requirePresence('mobile', 'create')
        ->notEmpty('mobile', 'Mobile number is required');

    // Status
    $validator
        ->inList('status', ['active', 'inactive'], 'Please select a valid status')
        ->notEmpty('status', 'Status is required');

    return $validator;
}
  public function findWithFilters(Query $query, array $options)
    {
        $query = $query->contain(['Departments', 'Attendances']);

        if (!empty($options['employee_id'])) {            
            $query->where(['Employees.id' => $options['employee_id']]);
        }

        if (!empty($options['department'])) {
            $query->where(['Employees.department_id' => $options['department']]);
        }
         
          if (!empty($options['designation'])) {
            $query->where(['Employees.designation' => $options['designation']]);
        }
        if (!empty($options['month']) && !empty($options['year'])) {
            $start = "{$options['year']}-{$options['month']}-01";
            $end = date("Y-m-t", strtotime($start));

            $query->matching('Attendances', function ($q) use ($start, $end) {
                return $q->where([
                    'Attendances.attendance_date >=' => $start,
                    'Attendances.attendance_date <=' => $end
                ]);
            });
        }

        return $query;
    }
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['employee_id']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    public function beforeSave(Event $event, $entity, ArrayObject $options)
    {
        // Auto-generate employee ID if not provided
        if ($entity->isNew() && empty($entity->employee_id)) {
            $entity->employee_id = $this->generateEmployeeId();
        }
        return true;
    }

    private function generateEmployeeId()
    {
        // Get the last employee
        $lastEmployee = $this->find()
            ->select(['employee_id'])
            ->order(['id' => 'DESC'])
            ->first();

        if ($lastEmployee) {
            // Extract number from last employee_id (e.g., EMP654656 -> 654656)
            $lastNumber = (int)substr($lastEmployee->employee_id, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 100001; // Starting number
        }

        return 'EMP' . $newNumber;
    }
}
