<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

class AttendancesController extends AppController
{
    /**
     * AJAX endpoint to update attendance status
     */
    public function updateStatus()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        // For CakePHP 3.4, manually set headers
        $this->response->type('json');

        $employeeId = $this->request->data('employee_id');
        $date = $this->request->data('date');
        $status = $this->request->data('status');

        // Validate input
        if (!$employeeId || !$date || !$status) {
            $this->response->body(json_encode([
                'success' => false,
                'message' => 'Missing required parameters'
            ]));
            return $this->response;
        }

        // Check if attendance record exists
        $attendance = $this->Attendances->find()
            ->where([
                'employee_id' => $employeeId,
                'attendance_date' => $date
            ])
            ->first();

        if ($attendance) {
            // Update existing record
            $attendance = $this->Attendances->patchEntity($attendance, [
                'status' => $status
            ]);
        } else {
            // Create new record
            $attendance = $this->Attendances->newEntity([
                'employee_id' => $employeeId,
                'attendance_date' => $date,
                'status' => $status
            ]);
        }

        if ($this->Attendances->save($attendance)) {
            $this->response->body(json_encode([
                'success' => true,
                'message' => 'Attendance updated successfully'
            ]));
        } else {
            $this->response->body(json_encode([
                'success' => false,
                'message' => 'Failed to update attendance',
                'errors' => $attendance->errors() // use errors() in 3.4
            ]));
        }

        return $this->response;
    }
        /**
     * Daily Attendance Management
     */
   
       public function daily()
    {
        // Get date from query string, default to today
        $selectedDate = $this->request->query('date');

        // Validate and sanitize date
        if (empty($selectedDate)) {
            $selectedDate = date('Y-m-d');
        } else {
            // Ensure date is in correct format
            $timestamp = strtotime($selectedDate);
            if ($timestamp === false) {
                $selectedDate = date('Y-m-d');
                $this->Flash->warning('Invalid date format. Showing today\'s attendance.');
            } else {
                $selectedDate = date('Y-m-d', $timestamp);
            }
        }

        // Don't allow future dates
        $today = date('Y-m-d');
        if ($selectedDate > $today) {
            $selectedDate = $today;
            $this->Flash->warning('Cannot mark attendance for future dates. Showing today\'s attendance.');
        }

        // Get all active employees who joined on or before this date
        $employees = $this->Attendances->Employees->find()
            ->where([
                'Employees.status' => 'active',
                'Employees.joining_date <=' => $selectedDate
            ])
            ->order(['Employees.employee_id' => 'ASC'])
            ->all();

        // Get attendance records for this date
        $attendanceRecords = $this->Attendances->find()
            ->where(['attendance_date' => $selectedDate])
            ->indexBy('employee_id')
            ->toArray();

        // Count marked attendance
        $markedCount = count($attendanceRecords);
        $totalEmployees = count($employees);

        // Merge employees with their attendance status
        $attendanceData = [];
        foreach ($employees as $employee) {
            $status = isset($attendanceRecords[$employee->id])
                ? $attendanceRecords[$employee->id]->status
                : null;

            $attendanceData[] = [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_id,
                'name' => $employee->name,
                'status' => $status
            ];
        }

        // Show success message after loading
        if ($this->request->is('get') && $this->request->query('date')) {
            $dateFormatted = date('l, F d, Y', strtotime($selectedDate));
            if ($totalEmployees > 0) {
                $this->Flash->success(
                    "Attendance loaded for {$dateFormatted}. " .
                    "Found {$totalEmployees} employee(s). " .
                    "{$markedCount} attendance record(s) already marked."
                );
            } else {
                $this->Flash->info(
                    "No active employees found for {$dateFormatted}. " .
                    "Employees must have joined on or before this date."
                );
            }
        }

        $this->set(compact('attendanceData', 'selectedDate', 'markedCount', 'totalEmployees'));
    }
    /**
     * Monthly Attendance Report
     */
  public function monthlyReport()
  {
        $month = $this->request->getQuery('month') ?: date('m');
        $year = $this->request->getQuery('year') ?: date('Y');
        $employeeId = $this->request->getQuery('employee_id');
        $departmentId = $this->request->getQuery('department_id');
        $statusFilter = $this->request->getQuery('status');

        // Get all employees (for filter dropdown)
        $employees = $this->Attendances->Employees->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])
        ->where(['Employees.status' => 'active'])
        ->order(['Employees.employee_id' => 'ASC'])
        ->toArray();

        // Get all departments (for filter dropdown)
        $departments = $this->Attendances->Employees->Departments->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])
        ->order(['Departments.name' => 'ASC'])
        ->toArray();

        // Base query for attendance records
        $query = $this->Attendances->find()
            ->contain(['Employees.Departments'])
            ->where([
                'MONTH(Attendances.attendance_date)' => $month,
                'YEAR(Attendances.attendance_date)' => $year
            ]);

        // Apply filters dynamically
        if (!empty($employeeId)) {
            $query->where(['Attendances.employee_id' => $employeeId]); //  unambiguous
        }

        if (!empty($departmentId)) {
            $query->where(['Employees.department_id' => $departmentId]); //  unambiguous
        }

        if (!empty($statusFilter)) {
            $query->where(['Attendances.status' => $statusFilter]); //  unambiguous
        }

        $attendanceRecords = $query->all();

        // Organize data by employee
        $attendanceByEmployee = [];
        foreach ($attendanceRecords as $record) {
            $empId = $record->employee_id;
            $day = date('d', strtotime($record->attendance_date));

            if (!isset($attendanceByEmployee[$empId])) {
                $attendanceByEmployee[$empId] = [];
            }

            $attendanceByEmployee[$empId][$day] = $record->status;
        }

        // Get number of days in month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Fetch filtered employees for display
        $filteredEmployees = $this->Attendances->Employees->find()
            ->contain(['Departments'])
            ->where(['Employees.status' => 'active'])
            ->order(['Employees.employee_id' => 'ASC']);

        if (!empty($employeeId)) {
            $filteredEmployees->where(['Employees.id' => $employeeId]);
        }

        if (!empty($departmentId)) {
            $filteredEmployees->where(['Employees.department_id' => $departmentId]);
        }

        // Build final report data
        $reportData = [];
        foreach ($filteredEmployees as $employee) {
            $dailyAttendance = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dailyAttendance[$day] = isset($attendanceByEmployee[$employee->id][$day])
                    ? $attendanceByEmployee[$employee->id][$day]
                    : '-';
            }

            $reportData[] = [
                'employee_id' => $employee->employee_id,
                'name' => $employee->name,
                'department' => $employee->department->name ?? '-', //  safe access
                'daily_attendance' => $dailyAttendance
            ];
        }

        $this->set(compact(
            'reportData',
            'month',
            'year',
            'daysInMonth',
            'employees',
            'departments'
        ));
}



    public function index()
    {
        $this->paginate = [
            'contain' => ['Employees'],
            'order' => ['attendance_date' => 'DESC']
        ];
        $attendances = $this->paginate($this->Attendances);

        $this->set(compact('attendances'));
    }

    public function view($id = null)
    {
        $attendance = $this->Attendances->get($id, [
            'contain' => ['Employees']
        ]);

        $this->set('attendance', $attendance);
    }

    public function add()
    {
        $attendance = $this->Attendances->newEntity();
        if ($this->request->is('post')) {
            $attendance = $this->Attendances->patchEntity($attendance, $this->request->data());
            if ($this->Attendances->save($attendance)) {
                $this->Flash->success(__('The attendance has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attendance could not be saved. Please, try again.'));
        }
        $employees = $this->Attendances->Employees->find('list', ['limit' => 200]);
        $this->set(compact('attendance', 'employees'));
    }

    public function edit($id = null)
    {
        $attendance = $this->Attendances->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attendance = $this->Attendances->patchEntity($attendance, $this->request->data());
            if ($this->Attendances->save($attendance)) {
                $this->Flash->success(__('The attendance has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attendance could not be saved. Please, try again.'));
        }
        $employees = $this->Attendances->Employees->find('list', ['limit' => 200]);
        $this->set(compact('attendance', 'employees'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attendance = $this->Attendances->get($id);
        if ($this->Attendances->delete($attendance)) {
            $this->Flash->success(__('The attendance has been deleted.'));
        } else {
            $this->Flash->error(__('The attendance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
