

<div class="employees index content">
    <h3><?= __('Employees') ?></h3>
    
    <div class="actions">
        <?= $this->Html->link(__('Add Employee'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    </div>

    <!-- Filters -->
    <div class="filters">
        <?= $this->Form->create(null, ['type' => 'get']) ?>
        <fieldset>
            <legend><?= __('Filter Employees') ?></legend>
            <?= $this->Form->control('department', [
                'options' => $departments,
                'empty' => 'All Departments',
                'value' => $this->request->getQuery('department')
            ]) ?>
            <?= $this->Form->control('designation', [
                'options' => $designations,
                'empty' => 'All Designations',
                'value' => $this->request->getQuery('designation')
            ]) ?>
            <?= $this->Form->control('status', [
                'options' => ['active' => 'Active', 'inactive' => 'Inactive'],
                'empty' => 'All Status',
                'value' => $this->request->getQuery('status')
            ]) ?>
        </fieldset>
        <?= $this->Form->button(__('Filter'), ['type' => 'submit', 'class' => 'button']) ?>
        <?= $this->Html->link(__('Clear'), ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        <?= $this->Form->end() ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('employee_id', 'Employee ID') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('department') ?></th>
                    <th><?= $this->Paginator->sort('designation') ?></th>
                    <th><?= $this->Paginator->sort('base_salary', 'Salary') ?></th>
                    <th><?= $this->Paginator->sort('joining_date') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('mobile') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= h($employee->employee_id) ?></td>
                    <td><?= h($employee->name) ?></td>
                    <td><?= h($employee->department) ?></td>
                    <td><?= h($employee->designation) ?></td>
                    <td>₹<?= $this->Number->format($employee->base_salary) ?></td>
                    <td><?= h($employee->joining_date->format('Y-m-d')) ?></td>
                    <td><?= h($employee->email) ?></td>
                    <td><?= h($employee->mobile) ?></td>
                    <td>
                        <span class="badge <?= $employee->status == 'active' ? 'badge-success' : 'badge-danger' ?>">
                            <?= h($employee->status) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $employee->id], ['class' => 'btn-action btn-view']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employee->id], ['class' => 'btn-action btn-edit']) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employee->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employee->id), 'class' => 'btn-action btn-delete']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>

<style>
.badge {
    padding: 5px 10px;
    border-radius: 3px;
    color: white;
}
.badge-success {
    background-color: #28a745;
}
.badge-danger {
    background-color: #dc3545;
}
.filters {
    background: #f5f5f5;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

/* Action Buttons Styling */
.btn-action {
    display: inline-block;
    padding: 6px 12px;
    margin: 2px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
    color: white;
}

.btn-view {
    background-color: #17a2b8;
}

.btn-view:hover {
    background-color: #138496;
    color: white;
}

.btn-edit {
    background-color: #ffc107;
    color: #333;
}

.btn-edit:hover {
    background-color: #e0a800;
}

.btn-delete {
    background-color: #dc3545;
}

.btn-delete:hover {
    background-color: #c82333;
    color: white;
}

.button-secondary {
    background-color: #6c757d;
}

.button-secondary:hover {
    background-color: #5a6268;
}

/* Table Header - Black Text */
table th {
    background-color: #667eea;
    color: #000000 !important;
    font-weight: 600;
}
</style>