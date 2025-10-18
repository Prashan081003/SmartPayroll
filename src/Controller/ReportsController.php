<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class ReportsController extends AppController
{
    /**
     * Department Monthly Salary Report
     */
    public function departmentMonthlySalary()
    {
        $this->loadModel('Payslips');
        
        $month = $this->request->getQuery('month', date('m'));
        $year = $this->request->getQuery('year', date('Y'));

        $payslips = $this->Payslips->find()
            ->contain(['Employees', 'Bonuses', 'Deductions'])
            ->where([
                'Payslips.month' => $month,
                'Payslips.year' => $year
            ])
            ->all();

        // Group by department
        $reportData = [];
        foreach ($payslips as $payslip) {
            $dept = $payslip->employee->department;
            
            if (!isset($reportData[$dept])) {
                $reportData[$dept] = [
                    'department' => $dept,
                    'base_pay' => 0,
                    'bonus' => 0,
                    'deductions' => 0,
                    'net_salary' => 0
                ];
            }
            
            $reportData[$dept]['base_pay'] += $payslip->base_pay;
            $reportData[$dept]['bonus'] += $payslip->total_bonus;
            $reportData[$dept]['deductions'] += $payslip->total_deductions;
            $reportData[$dept]['net_salary'] += $payslip->net_salary;
        }

        $this->set(compact('reportData', 'month', 'year'));
    }

    /**
     * Employee Monthly Salary Report
     */
    public function employeeMonthlySalary()
    {
        $this->loadModel('Payslips');
        
        $month = $this->request->getQuery('month', date('m'));
        $year = $this->request->getQuery('year', date('Y'));

        $payslips = $this->Payslips->find()
            ->contain(['Employees'])
            ->where([
                'Payslips.month' => $month,
                'Payslips.year' => $year
            ])
            ->order(['Employees.employee_id' => 'ASC'])
            ->all();

        $reportData = [];
        foreach ($payslips as $payslip) {
            $reportData[] = [
                'employee' => $payslip->employee->name,
                'employee_id' => $payslip->employee->employee_id,
                'department' => $payslip->employee->department,
                'base_pay' => $payslip->base_pay,
                'bonus' => $payslip->total_bonus,
                'deductions' => $payslip->total_deductions,
                'net_salary' => $payslip->net_salary
            ];
        }

        $this->set(compact('reportData', 'month', 'year'));
    }

    /**
     * Employee Yearly Salary Report
     */
    public function employeeYearlySalary()
    {
        $this->loadModel('Payslips');
        
        $year = $this->request->getQuery('year', date('Y'));

        $payslips = $this->Payslips->find()
            ->contain(['Employees'])
            ->where([
                'Payslips.year' => $year
            ])
            ->order(['Employees.employee_id' => 'ASC'])
            ->all();

        // Group by employee
        $reportData = [];
        foreach ($payslips as $payslip) {
            $empId = $payslip->employee_id;
            
            if (!isset($reportData[$empId])) {
                $reportData[$empId] = [
                    'employee' => $payslip->employee->name,
                    'employee_id' => $payslip->employee->employee_id,
                    'department' => $payslip->employee->department,
                    'base_pay' => 0,
                    'bonus' => 0,
                    'deductions' => 0,
                    'net_salary' => 0
                ];
            }
            
            $reportData[$empId]['base_pay'] += $payslip->base_pay;
            $reportData[$empId]['bonus'] += $payslip->total_bonus;
            $reportData[$empId]['deductions'] += $payslip->total_deductions;
            $reportData[$empId]['net_salary'] += $payslip->net_salary;
        }

        $this->set(compact('reportData', 'year'));
    }

    /**
     * Dashboard with quick stats
     */
    public function dashboard()
    {
        $this->loadModel('Employees');
        $this->loadModel('Attendances');
        $this->loadModel('Payslips');

        // Total employees
        $totalEmployees = $this->Employees->find()->where(['status' => 'active'])->count();

        // Today's attendance
        $todayPresent = $this->Attendances->find()
            ->where([
                'attendance_date' => date('Y-m-d'),
                'status' => 'Present'
            ])
            ->count();

        // This month's payslips
        $monthPayslips = $this->Payslips->find()
            ->where([
                'month' => date('m'),
                'year' => date('Y')
            ])
            ->count();

        // Total salary this month
        $totalSalary = $this->Payslips->find()
            ->where([
                'month' => date('m'),
                'year' => date('Y')
            ])
            ->select(['total' => 'SUM(net_salary)'])
            ->first();

        $this->set(compact('totalEmployees', 'todayPresent', 'monthPayslips', 'totalSalary'));
    }
}