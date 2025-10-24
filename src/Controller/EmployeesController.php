<?php
namespace App\Controller;

use App\Controller\AppController;

class EmployeesController extends AppController
{
            public function index()
        {
            // Start query with relation (if departments table is associated)
            $query = $this->Employees->find('all', [
                'contain' => ['Departments'] // include department data
            ]);

            $request = $this->request->getQueryParams(); // safer way for CakePHP 3.4+

            // 🔹 Filtering by Department
            if (!empty($request['department_id'])) {
                $query->where(['Employees.department_id' => $request['department_id']]);
            }

            // 🔹 Filtering by Designation
            if (!empty($request['designation'])) {
                $query->where(['Employees.designation' => $request['designation']]);
            }

            // 🔹 Filtering by Status
            if (!empty($request['status'])) {
                $query->where(['Employees.status' => $request['status']]);
            }

            // 🔹 Sorting (default: employee_id ASC)
            $sortField = !empty($request['sort']) ? $request['sort'] : 'Employees.employee_id';
            $sortDirection = !empty($request['direction']) ? $request['direction'] : 'asc';
            $query->order([$sortField => $sortDirection]);

            // 🔹 Pagination
            $employees = $this->paginate($query);

            // 🔹 Fetch distinct departments for filter dropdown (from Departments table)
            $departments = $this->Employees->Departments->find('list', [
                'keyField' => 'id',
                'valueField' => 'name'
            ])->toArray();

            // 🔹 Fetch unique designations for filter dropdown
            $designations = $this->Employees->find('list', [
                'keyField' => 'designation',
                'valueField' => 'designation'
            ])
            ->distinct(['Employees.designation'])
            ->toArray();

            // 🔹 Pass data to view
            $this->set(compact('employees', 'departments', 'designations'));
}

    public function view($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['Attendances', 'Payslips']
        ]);

        $this->set('employee', $employee);
    }

    public function add()
    {
        $employee = $this->Employees->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->data; // CakePHP 3.4 compatible
            unset($data['employee_id']); // auto-generated

            $employee = $this->Employees->patchEntity($employee, $data);

            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved successfully. Employee ID: ' . $employee->employee_id));
                return $this->redirect(['action' => 'index']);
            }

            // Show detailed error messages
            if (!empty($employee->errors())) {
                $errorMessages = [];
                foreach ($employee->errors() as $field => $error) {
                    foreach ($error as $rule => $message) {
                        $errorMessages[] = ucfirst($field) . ': ' . $message;
                    }
                }
                $this->Flash->error(__('Please fix the following errors: ') . implode(' | ', $errorMessages));
            } else {
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
       // Added this line
    $departments = $this->Employees->Departments->find('list', ['limit' => 200]);
    
    $this->set(compact('employee', 'departments'));
    }

    public function edit($id = null)
    {
        $employee = $this->Employees->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->data);

            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved successfully.'));
                return $this->redirect(['action' => 'index']);
            }

            // Show detailed error messages
            if (!empty($employee->errors())) {
                $errorMessages = [];
                foreach ($employee->errors() as $field => $error) {
                    foreach ($error as $rule => $message) {
                        $errorMessages[] = ucfirst($field) . ': ' . $message;
                    }
                }
                $this->Flash->error(__('Please fix the following errors: ') . implode(' | ', $errorMessages));
            } else {
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
        // Add this line
          $departments = $this->Employees->Departments->find('list', ['limit' => 200]);
    
            $this->set(compact('employee', 'departments'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employee = $this->Employees->get($id);

        if ($this->Employees->delete($employee)) {
            $this->Flash->success(__('The employee has been deleted.'));
        } else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
