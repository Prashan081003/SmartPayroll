<?php
namespace App\Controller;

use App\Controller\AppController;

class DepartmentsController extends AppController
{
           
public function add()
{
    // Use newEntity() for CakePHP 3.x
    $department = $this->Departments->newEntity();


    // Only run this if POST request
    if ($this->request->is('post')) {
        $department = $this->Departments->patchEntity($department, $this->request->getData());

        if ($this->Departments->save($department)) {
            $this->Flash->success(__('The department has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The department could not be saved. Please try again.'));
    }

    $this->set(compact('department'));
}

    public function index()
    {
        $departments = $this->paginate($this->Departments->find()->contain(['Employees']));
        $this->set(compact('departments'));
    }

    public function view($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => ['Employees']
        ]);
        $this->set(compact('department'));
    }



    public function edit($id = null)
    {
        $department = $this->Departments->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $department = $this->Departments->patchEntity($department, $this->request->getData());
            
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be updated. Please try again.'));
        }
        
        $this->set(compact('department'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $department = $this->Departments->get($id);
        
        if ($this->Departments->delete($department)) {
            $this->Flash->success(__('The department has been deleted.'));
        } else {
            $this->Flash->error(__('The department could not be deleted. Please try again.'));
        }
        
        return $this->redirect(['action' => 'index']);
    }
}