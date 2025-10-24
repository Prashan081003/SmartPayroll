<div class="departments index">
    <h3><?= __('Departments') ?></h3>
    <p><?= $this->Html->link(__('Add Department'), ['action' => 'add'], ['class' => 'button']) ?></p>
    
    <table cellpadding="0" cellspacing="0">
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
                <td>
                    <?= $this->Html->link(__('View'), ['action' => 'view', $department->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $department->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $department->id], ['confirm' => __('Are you sure?')]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>