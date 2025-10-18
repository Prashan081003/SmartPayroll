<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

class AttendancesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('attendances');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Associations
        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        // id
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        // employee_id
        $validator
            ->integer('employee_id')
            ->requirePresence('employee_id', 'create')
            ->notEmpty('employee_id');

        // attendance_date
        $validator
            ->add('attendance_date', 'valid', ['rule' => 'date'])
            ->requirePresence('attendance_date', 'create')
            ->notEmpty('attendance_date')
            ->add('attendance_date', 'notFuture', [
                'rule' => function ($value, $context) {
                    $today = Time::now()->format('Y-m-d');
                    $inputDate = date('Y-m-d', strtotime($value));
                    return $inputDate <= $today;
                },
                'message' => 'Attendance date should not be in the future'
            ]);

        // status (since scalar() doesn’t exist in 3.4)
        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status')
            ->add('status', 'validValue', [
                'rule' => ['inList', ['Present', 'Absent', 'Leave']],
                'message' => 'Please select a valid status'
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));
        return $rules;
    }

    /**
     * Get attendance for a specific date
     */
    public function getAttendanceByDate($date)
    {
        return $this->find()
            ->contain(['Employees'])
            ->where(['attendance_date' => $date])
            ->all();
    }

    /**
     * Get attendance report for a month
     */
    public function getMonthlyAttendance($month, $year)
    {
        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        return $this->find()
            ->contain(['Employees'])
            ->where([
                'attendance_date >=' => $startDate,
                'attendance_date <=' => $endDate
            ])
            ->order(['Employees.employee_id' => 'ASC', 'attendance_date' => 'ASC'])
            ->all();
    }

    /**
     * Count present days for an employee in a month
     */
    public function countPresentDays($employeeId, $month, $year)
    {
        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        return $this->find()
            ->where([
                'employee_id' => $employeeId,
                'status' => 'Present',
                'attendance_date >=' => $startDate,
                'attendance_date <=' => $endDate
            ])
            ->count();
    }
}
