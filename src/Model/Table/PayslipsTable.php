<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class PayslipsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('payslips');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Associations
        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Bonuses', [
            'foreignKey' => 'payslip_id',
            'dependent' => true
        ]);

        $this->hasMany('Deductions', [
            'foreignKey' => 'payslip_id',
            'dependent' => true
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('employee_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('employee_id', 'create')
            ->notEmpty('employee_id', 'Employee ID is required');

        $validator
            ->add('month', 'valid', ['rule' => 'numeric'])
            ->requirePresence('month', 'create')
            ->notEmpty('month', 'Month is required')
            ->add('month', 'range', ['rule' => ['range', 0, 13], 'message' => 'Month must be between 1 and 12']);

        $validator
            ->add('year', 'valid', ['rule' => 'numeric'])
            ->requirePresence('year', 'create')
            ->notEmpty('year', 'Year is required')
            ->add('year', 'range', ['rule' => ['range', 1999, 2101], 'message' => 'Year must be between 2000 and 2100']);

        $validator
            ->add('base_pay', 'valid', ['rule' => 'decimal'])
            ->requirePresence('base_pay', 'create')
            ->notEmpty('base_pay', 'Base Pay is required')
            ->add('base_pay', 'positive', ['rule' => function ($value) { return $value > 0; }, 'message' => 'Base pay must be positive']);

        $validator
            ->add('days_worked', 'valid', ['rule' => 'numeric'])
            ->requirePresence('days_worked', 'create')
            ->notEmpty('days_worked', 'Days worked is required')
            ->add('days_worked', 'nonNegative', ['rule' => function ($value) { return $value >= 0; }, 'message' => 'Days worked must be zero or positive']);

        $validator
            ->add('total_bonus', 'valid', ['rule' => 'decimal'])
            ->allowEmpty('total_bonus');

        $validator
            ->add('total_deductions', 'valid', ['rule' => 'decimal'])
            ->allowEmpty('total_deductions');

        $validator
            ->add('net_salary', 'valid', ['rule' => 'decimal'])
            ->requirePresence('net_salary', 'create')
            ->notEmpty('net_salary', 'Net salary is required');

        $validator
            ->add('payment_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('payment_date');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));

        return $rules;
    }

    /**
     * Generate payslip for an employee
     */
                public function generatePayslip($employeeId, $month, $year, $bonuses = [], $deductions = [])
            {
                // Use TableRegistry to get other models
                $employeesTable = TableRegistry::get('Employees');
                $attendancesTable = TableRegistry::get('Attendances');
                
                $employee = $employeesTable->get($employeeId);
                
                // Count present days (days employee actually worked)
                $daysWorked = $attendancesTable->countPresentDays($employeeId, $month, $year);
                
                // Calculate total working days in the month (excluding Sat/Sun)
                $totalWorkingDays = $this->calculateWorkingDays($month, $year);
                
                // Calculate daily rate and earned salary
                $dailyRate = $employee->base_salary / $totalWorkingDays;
                $earnedSalary = $dailyRate * $daysWorked;
                
                // Calculate totals
                $totalBonus = array_sum(array_column($bonuses, 'amount'));
                $totalDeductions = array_sum(array_column($deductions, 'amount'));
                
                // Calculate net salary (earned salary + bonuses - deductions)
                $netSalary = $earnedSalary + $totalBonus - $totalDeductions;
                
                // Check if payslip already exists
                $existingPayslip = $this->find()  
                    ->where([
                        'employee_id' => $employeeId,
                        'month' => $month,
                        'year' => $year
                    ])
                    ->first();
                
                if ($existingPayslip) {
                    // Update existing payslip
                    $payslip = $this->patchEntity($existingPayslip, [
                        'base_pay' => $earnedSalary, // Store earned salary instead of base salary
                        'days_worked' => $daysWorked,
                        'total_bonus' => $totalBonus,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'payment_date' => date('Y-m-d')
                    ]);
                } else {
                    // Create new payslip
                    $payslip = $this->newEntity([
                        'employee_id' => $employeeId,
                        'month' => $month,
                        'year' => $year,
                        'base_pay' => $earnedSalary, // Store earned salary
                        'days_worked' => $daysWorked,
                        'total_bonus' => $totalBonus,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'payment_date' => date('Y-m-d')
                    ]);
                }
                
                if ($this->save($payslip)) {
                    // Delete old bonuses and deductions
                    $this->Bonuses->deleteAll(['payslip_id' => $payslip->id]);
                    $this->Deductions->deleteAll(['payslip_id' => $payslip->id]);
                    
                    // Save new bonuses
                    foreach ($bonuses as $bonus) {
                        $bonusEntity = $this->Bonuses->newEntity([
                            'payslip_id' => $payslip->id,
                            'bonus_type' => $bonus['type'],
                            'amount' => $bonus['amount']
                        ]);
                        $this->Bonuses->save($bonusEntity);
                    }
                    
                    // Save new deductions
                    foreach ($deductions as $deduction) {
                        $deductionEntity = $this->Deductions->newEntity([
                            'payslip_id' => $payslip->id,
                            'deduction_type' => $deduction['type'],
                            'amount' => $deduction['amount']
                        ]);
                        $this->Deductions->save($deductionEntity);
                    }
                    
                    return $payslip;
                }
                
                return false;
            }

            /**
             * Calculate total working days in a month (excluding Saturdays and Sundays)
             * 
             * @param int $month Month (1-12)
             * @param int $year Year
             * @return int Number of working days
             */
            private function calculateWorkingDays($month, $year)
            {
                $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $workingDays = 0;
                
                for ($day = 1; $day <= $totalDays; $day++) {
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $dayOfWeek = date('N', strtotime($date)); // 1 (Monday) to 7 (Sunday)
                    
                    // Count only Monday to Friday (1-5)
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $workingDays++;
                    }
                }
                
                return $workingDays;
            }
}
