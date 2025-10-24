<div class="departments index">
    <h3><?= __('Departments') ?></h3>
    <p><?= $this->Html->link(__('Add Department'), ['action' => 'add'], ['class' => 'button']) ?></p>
    
    <table cellpadding="0" cellspacing="0" class="department-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('code') ?></th>
                <th><?= __('Employees Count') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= $this->Number->format($department->id) ?></td>
                <td><?= h($department->name) ?></td>
                <td><?= h($department->code) ?></td>
                <td><?= count($department->employees) ?></td>
               <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $department->id], ['class' => 'btn-action btn-view']) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $department->id], ['class' => 'btn-action btn-edit']) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $department->id], [
                    'confirm' => __('Are you sure you want to delete # {0}?', $department->id),
                    'class' => 'btn-action btn-delete'
                ]) ?>
</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
  

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
</style>