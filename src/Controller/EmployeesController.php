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
            if (!empty($request['department'])) {
                $query->where(['Employees.department_id' => $request['department']]);
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
            'contain' => ['Attendances', 'Payslips','Departments']
        ]);

        $this->set('employee', $employee);
    }

    public function add()
    {
        $employee = $this->Employees->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->data; // CakePHP 3.4 compatible
            unset($data['employee_id']); // auto-generated

          $employee = $this->Employees->patchEntity($employee, $data, [
            'associated' => ['Addresses']  // Important: include associated table
        ]);
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
     public function uploadPhoto($id = null)
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        
        $employee = $this->Employees->get($id);
        
        if (empty($_FILES['photo'])) {
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
            return;
        }
        
        $file = $_FILES['photo'];
        
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF allowed.']);
            return;
        }
        
        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit.']);
            return;
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Upload error occurred.']);
            return;
        }
        
        // Create upload directory if not exists
        $uploadPath = WWW_ROOT . 'files' . DS . 'employees';
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Delete old photo if exists
        if (!empty($employee->photo)) {
            $oldFile = WWW_ROOT . 'files' . DS . $employee->photo;
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'emp_' . $id . '_' . time() . '.' . $extension;
        $destination = $uploadPath . DS . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Update database
            $employee->photo = 'employees/' . $filename;
            if ($this->Employees->save($employee)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Photo uploaded successfully',
                    'imageUrl' => $this->request->getAttribute('webroot') . 'files/employees/' . $filename
                ]);
            } else {
                @unlink($destination);
                echo json_encode(['success' => false, 'message' => 'Failed to update database.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
        }
    }
    
        /**
     * Remove employee photo
     */
    public function removePhoto($id = null)
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        
        $employee = $this->Employees->get($id);
        
        if (!empty($employee->photo)) {
            $filePath = WWW_ROOT . 'files' . DS . $employee->photo;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            $employee->photo = null;
            if ($this->Employees->save($employee)) {
                echo json_encode(['success' => true, 'message' => 'Photo removed successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update database.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No photo to remove.']);
        }
    }

}
