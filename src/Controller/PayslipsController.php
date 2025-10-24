<?php
namespace App\Controller;

use App\Controller\AppController;

class PayslipsController extends AppController
{
    public function index()
    {
        $this->paginate = [
            'contain' => ['Employees'],
            'order' => ['year' => 'DESC', 'month' => 'DESC']
        ];
        $payslips = $this->paginate($this->Payslips);

        $this->set(compact('payslips'));
    }

public function view($id = null)
{
  $payslip = $this->Payslips->get($id, [
    'contain' => [
        'Employees' => ['Departments'], 
        'Bonuses', 
        'Deductions'
    ]
]);

    // Calculate total working days for this payslip’s month & year
    $totalWorkingDays = $this->Payslips->calculateWorkingDays($payslip->month, $payslip->year);

    // Pass it to view
    $this->set(compact('payslip', 'totalWorkingDays'));
}


    /**
     * Generate payslip form
     */
    public function generate()
    {
        $employees = $this->Payslips->Employees->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['status' => 'active']);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            $employeeId = $data['employee_id'];
            $month = $data['month'];
            $year = $data['year'];

            // Validate that employee has attendance data for this month
            $attendanceCount = $this->Payslips->Employees->Attendances->find()
                ->where([
                    'employee_id' => $employeeId,
                    'attendance_date >=' => "$year-$month-01",
                    'attendance_date <=' => date("Y-m-t", strtotime("$year-$month-01"))
                ])
                ->count();

            if ($attendanceCount == 0) {
                $this->Flash->error(__('Cannot generate payslip. No attendance data found for this employee in the selected month.'));
                $this->set(compact('employees'));
                return;
            }

            // Prepare bonuses
            $bonuses = [];
            if (!empty($data['bonuses'])) {
                foreach ($data['bonuses'] as $bonus) {
                    if (!empty($bonus['amount']) && $bonus['amount'] > 0) {
                        $bonuses[] = [
                            'type' => $bonus['type'],
                            'amount' => $bonus['amount']
                        ];
                    }
                }
            }

            // Prepare deductions
            $deductions = [];
            if (!empty($data['deductions'])) {
                foreach ($data['deductions'] as $deduction) {
                    if (!empty($deduction['amount']) && $deduction['amount'] > 0) {
                        $deductions[] = [
                            'type' => $deduction['type'],
                            'amount' => $deduction['amount']
                        ];
                    }
                }
            }

            // Generate payslip
            $payslip = $this->Payslips->generatePayslip($employeeId, $month, $year, $bonuses, $deductions);

            if ($payslip) {
                $this->Flash->success(__('Payslip generated successfully.'));
                return $this->redirect(['action' => 'view', $payslip->id]);
            } else {
                $this->Flash->error(__('Failed to generate payslip. Please try again.'));
            }
        }

        $this->set(compact('employees'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payslip = $this->Payslips->get($id);
        
        if ($this->Payslips->delete($payslip)) {
            $this->Flash->success(__('The payslip has been deleted.'));
        } else {
            $this->Flash->error(__('The payslip could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}