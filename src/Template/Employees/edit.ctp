
<div class="employees form content">
    <h3><?= __('Edit Employee') ?></h3>
    
    <div class="employee-info">
        <strong>Employee ID:</strong> <?= h($employee->employee_id) ?> 
        <span style="margin-left: 20px;"><strong>Current Status:</strong> 
            <span class="badge badge-<?= $employee->status == 'active' ? 'success' : 'danger' ?>">
                <?= h($employee->status) ?>
            </span>
        </span>
    </div>

    <?= $this->Form->create($employee) ?>
    <fieldset>
        <legend><?= __('Employee Information') ?></legend>
        
        <div class="form-group">
            <?= $this->Form->control('name', [
                'required' => true,
                'label' => 'Full Name *'
            ]) ?>
        </div>

        <div class="form-row">
             <div class="form-group">
                <?= $this->Form->control('department_id', [
                    'options' => $departments,
                    'empty' => '-- Select Department --',
                    'required' => true,
                    'label' => 'Department *',
                    'class' => !empty($employee->errors('department_id')) ? 'error-field' : ''
                ]) ?>
                <?php if (!empty($employee->errors('department_id'))): ?>
                    <div class="error-message"><?= implode(', ', $employee->errors('department_id')) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <?= $this->Form->control('designation', [
                    'required' => true,
                    'label' => 'Role/Designation *'
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('base_salary', [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => '0.01',
                    'required' => true,
                    'label' => 'Base Salary (₹) *'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->control('joining_date', [
                    'type' => 'date',
                    'required' => true,
                    'label' => 'Joining Date *'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= $this->Form->control('email', [
                'type' => 'email',
                'required' => true,
                'label' => 'Email Address *'
            ]) ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('mobile', [
                'required' => true,
                'label' => 'Mobile Number *',
                'maxlength' => '15'
            ]) ?>
        </div>
  <div class="form-group">
        <?= $this->Form->control('status', [
            'options' => ['active' => 'Active', 'inactive' => 'Inactive'],
            'label' => 'Status'
        ]) ?>
    </div>

</fieldset>

<!-- Profile Image Section with Upload/Re-upload -->
<div class="employee-photo-section mt-3">
    <h4>Profile Image</h4>
    <?= $this->cell('Image', [
        'Employees',
        $employee->id,
        'photo',
        ['controller' => 'Employees', 'action' => 'uploadPhoto', $employee->id],
        'edit'
    ]) ?>
</div>

<div class="form-actions">
    <?= $this->Form->button(__('Update Employee'), ['class' => 'button']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'button button-secondary']) ?>
    <?= $this->Html->link(__('View'), ['action' => 'view', $employee->id], ['class' => 'button button-info']) ?>
</div>
<?= $this->Form->end() ?>
</div>

<style>
.employee-info {
    background-color: #f8f9fa;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 4px;
    border-left: 4px solid #667eea;
}

.badge {
    padding: 5px 10px;
    border-radius: 3px;
    color: white;
    font-size: 12px;
    text-transform: uppercase;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-actions {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.button-secondary {
    background-color: #6c757d;
}

.button-secondary:hover {
    background-color: #5a6268;
}

.button-info {
    background-color: #17a2b8;
}

.button-info:hover {
    background-color: #138496;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>