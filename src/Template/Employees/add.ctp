<div class="employees form content">
    <h3><?= __('Add Employee') ?></h3>
    
    <div class="form-info">
        <h4>Validation Rules:</h4>
        <ul>
            <li>✓ Name is required</li>
            <li>✓ Department is required</li>
            <li>✓ Designation/Role is required</li>
            <li>✓ Base Salary must be a positive number (greater than 0)</li>
            <li>✓ Joining Date is required</li>
            <li>✓ Email must be valid and unique</li>
            <li>✓ Mobile number is required</li>
            <li>✓ Employee ID is auto-generated</li>
        </ul>
    </div>

    <?= $this->Form->create($employee, ['novalidate' => true]) ?>
    <fieldset>
        <legend><?= __('Employee Information') ?></legend>
        
        <div class="form-group">
            <?= $this->Form->control('name', [
                'required' => true,
                'label' => 'Full Name *',
                'placeholder' => 'Enter employee full name',
                'class' => !empty($employee->errors('name')) ? 'error-field' : ''
            ]) ?>
            <?php if (!empty($employee->errors('name'))): ?>
                <div class="error-message"><?= implode(', ', $employee->errors('name')) ?></div>
            <?php endif; ?>
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
                    'label' => 'Role/Designation *',
                    'placeholder' => 'e.g., Manager, Developer',
                    'class' => !empty($employee->errors('designation')) ? 'error-field' : ''
                ]) ?>
                <?php if (!empty($employee->errors('designation'))): ?>
                    <div class="error-message"><?= implode(', ', $employee->errors('designation')) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('base_salary', [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => '0.01',
                    'required' => true,
                    'label' => 'Base Salary (₹) *',
                    'placeholder' => 'e.g., 30000',
                    'class' => !empty($employee->errors('base_salary')) ? 'error-field' : ''
                ]) ?>
                <?php if (!empty($employee->errors('base_salary'))): ?>
                    <div class="error-message"><?= implode(', ', $employee->errors('base_salary')) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <?= $this->Form->control('joining_date', [
                    'type' => 'date',
                    'required' => true,
                    'label' => 'Joining Date *',
                    'max' => date('Y-m-d'),
                    'class' => !empty($employee->errors('joining_date')) ? 'error-field' : ''
                ]) ?>
                <?php if (!empty($employee->errors('joining_date'))): ?>
                    <div class="error-message"><?= implode(', ', $employee->errors('joining_date')) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <?= $this->Form->control('email', [
                'type' => 'email',
                'required' => true,
                'label' => 'Email Address *',
                'placeholder' => 'employee@company.com',
                'class' => !empty($employee->errors('email')) ? 'error-field' : ''
            ]) ?>
            <?php if (!empty($employee->errors('email'))): ?>
                <div class="error-message"><?= implode(', ', $employee->errors('email')) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('mobile', [
                'required' => true,
                'label' => 'Mobile Number *',
                'placeholder' => '9876543210',
                'maxlength' => '15',
                'class' => !empty($employee->errors('mobile')) ? 'error-field' : ''
            ]) ?>
            <?php if (!empty($employee->errors('mobile'))): ?>
                <div class="error-message"><?= implode(', ', $employee->errors('mobile')) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('status', [
                'options' => ['active' => 'Active', 'inactive' => 'Inactive'],
                'default' => 'active',
                'label' => 'Status'
            ]) ?>
        </div>
    </fieldset>
    
    <div class="form-actions">
        <?= $this->Form->button(__('Save Employee'), ['class' => 'button']) ?>
        <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'button button-secondary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<style>
.form-info {
    background-color: #e7f3ff;
    border-left: 4px solid #2196F3;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 4px;
}

.form-info h4 {
    margin-top: 0;
    color: #1976D2;
}

.form-info ul {
    margin: 10px 0 0 20px;
}

.form-info li {
    margin: 5px 0;
    color: #555;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.error-field {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.error-message {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: block;
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

input[type="text"],
input[type="email"],
input[type="number"],
input[type="date"],
select {
    transition: border-color 0.3s, box-shadow 0.3s;
}

input:focus,
select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
