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
    $this->loadModel('Departments');

    $month = $this->request->getQuery('month', date('m'));
    $year = $this->request->getQuery('year', date('Y'));
    $selectedDepartment = $this->request->getQuery('department_id');

    // 🔹 Base query
    $query = $this->Payslips->find()
        ->contain(['Employees.Departments', 'Bonuses', 'Deductions'])
        ->where([
            'Payslips.month' => $month,
            'Payslips.year' => $year
        ]);

    // 🔹 Filter by selected department if provided
    if (!empty($selectedDepartment)) {
        $query->matching('Employees.Departments', function ($q) use ($selectedDepartment) {
            return $q->where(['Departments.id' => $selectedDepartment]);
        });
    }

    $payslips = $query->all();

    // 🔹 Group by department
    $reportData = [];
    foreach ($payslips as $payslip) {
        $deptName = $payslip->employee->department->name ?? 'Unknown Department';

        if (!isset($reportData[$deptName])) {
            $reportData[$deptName] = [
                'department' => $deptName,
                'base_pay' => 0,
                'bonus' => 0,
                'deductions' => 0,
                'net_salary' => 0
            ];
        }

        $reportData[$deptName]['base_pay'] += $payslip->base_pay;
        $reportData[$deptName]['bonus'] += $payslip->total_bonus;
        $reportData[$deptName]['deductions'] += $payslip->total_deductions;
        $reportData[$deptName]['net_salary'] += $payslip->net_salary;
    }
 $testReportData= [] ;
   $testReportData[] = $reportData; 
    // 🔹 Get department list for dropdown
    $departments = $this->Departments->find('list', [
        'keyField' => 'id',
        'valueField' => 'name'
    ])->toArray();

    $this->set(compact('reportData', 'month', 'year', 'departments', 'selectedDepartment'));
}

    /**
     * Employee Monthly Salary Report
     */
public function employeeMonthlySalary()
{
    $this->loadModel('Payslips');
    $this->loadModel('Departments'); // to fetch departments for filter

    $month = $this->request->getQuery('month', date('m'));
    $year = $this->request->getQuery('year', date('Y'));
    $departmentId = $this->request->getQuery('department_id'); // department filter

    // Fetch all departments for filter dropdown
    $departments = $this->Departments->find('list', [
        'keyField' => 'id',
        'valueField' => 'name'
    ])->order(['name' => 'ASC'])->toArray();

    // Build payslip query
    $query = $this->Payslips->find()
        ->contain(['Employees' => ['Departments']])
        ->where([
            'Payslips.month' => $month,
            'Payslips.year' => $year
        ])
        ->order(['Employees.employee_id' => 'ASC']);

    // Apply department filter if selected
    if (!empty($departmentId)) {
        $query->where(['Employees.department_id' => $departmentId]);
    }

    $payslips = $query->all();

    // Prepare report data
    $reportData = [];
    foreach ($payslips as $payslip) {
        $reportData[] = [
            'employee' => $payslip->employee->name,
            'employee_id' => $payslip->employee->employee_id,
            'department' => $payslip->employee->department->name ?? '-',
            'base_pay' => $payslip->base_pay,
            'bonus' => $payslip->total_bonus,
            'deductions' => $payslip->total_deductions,
            'net_salary' => $payslip->net_salary
        ];
    }

    $this->set(compact('reportData', 'month', 'year', 'departments', 'departmentId'));
}



    /**
     * Employee Yearly Salary Report
     */
 public function employeeYearlySalary()
{
    $this->loadModel('Payslips');
    $this->loadModel('Departments'); // load Departments model for filter dropdown

    $year = $this->request->getQuery('year', date('Y'));
    $departmentId = $this->request->getQuery('department_id'); // get selected department

    // Fetch all departments for filter dropdown
    $departments = $this->Departments->find('list', [
        'keyField' => 'id',
        'valueField' => 'name'
    ])->order(['name' => 'ASC'])->toArray();

    // Build payslip query
    $query = $this->Payslips->find()
        ->contain(['Employees' => ['Departments']])
        ->where(['Payslips.year' => $year])
        ->order(['Employees.employee_id' => 'ASC']);

    // Apply department filter if selected
    if (!empty($departmentId)) {
        $query->where(['Employees.department_id' => $departmentId]);
    }

    $payslips = $query->all();

    // Group by employee
    $reportData = [];
    foreach ($payslips as $payslip) {
        $empId = $payslip->employee_id;

        if (!isset($reportData[$empId])) {
            $reportData[$empId] = [
                'employee' => $payslip->employee->name,
                'employee_id' => $payslip->employee->employee_id,
                'department' => $payslip->employee->department->name ?? 'N/A',
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

    $this->set(compact('reportData', 'year', 'departments', 'departmentId'));
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