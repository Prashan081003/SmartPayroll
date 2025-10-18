<?php
namespace App\Controller;

use App\Controller\AppController;

class EmployeesController extends AppController
{
    public function index()
    {
        $query = $this->Employees->find();

        // Filtering
        if ($this->request->query('department')) {
            $query->where(['department' => $this->request->query('department')]);
        }

        if ($this->request->query('designation')) {
            $query->where(['designation' => $this->request->query('designation')]);
        }

        if ($this->request->query('status')) {
            $query->where(['status' => $this->request->query('status')]);
        }

        // Sorting
        $sortField = $this->request->query('sort') ?: 'employee_id';
        $sortDirection = $this->request->query('direction') ?: 'asc';
        $query->order([$sortField => $sortDirection]);

        $employees = $this->paginate($query);

        // Get unique departments and designations for filters
        $departments = $this->Employees->find('list', [
            'keyField' => 'department',
            'valueField' => 'department'
        ])->distinct(['department'])->toArray();

        $designations = $this->Employees->find('list', [
            'keyField' => 'designation',
            'valueField' => 'designation'
        ])->distinct(['designation'])->toArray();

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

        $this->set(compact('employee'));
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

        $this->set(compact('employee'));
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
