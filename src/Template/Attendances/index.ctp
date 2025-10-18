<div class="attendances index content">
    <div class="page-header">
        <h3><?= __('All Attendance Records') ?></h3>
        <div class="header-actions">
            <?= $this->Html->link(__('Daily Attendance'), ['action' => 'daily'], ['class' => 'button']) ?>
            <?= $this->Html->link(__('Monthly Report'), ['action' => 'monthlyReport'], ['class' => 'button']) ?>
        </div>
    </div>

    <div class="content-card">
        <div class="table-container">
            <div class="table-responsive">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('Employees.name', 'Employee') ?></th>
                            <th><?= $this->Paginator->sort('Employees.employee_id', 'Employee ID') ?></th>
                            <th><?= $this->Paginator->sort('attendance_date', 'Date') ?></th>
                            <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                            <th class="actions-col"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($attendances)): ?>
                            <tr>
                                <td colspan="6" class="text-center empty-state">
                                    <div class="empty-icon">📋</div>
                                    <p>No attendance records found</p>
                                    <p class="text-muted">Start by marking daily attendance</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($attendances as $attendance): ?>
                            <tr>
                                <td data-label="ID"><?= $this->Number->format($attendance->id) ?></td>
                                <td data-label="Employee">
                                    <strong><?= h($attendance->employee->name) ?></strong>
                                </td>
                                <td data-label="Employee ID"><?= h($attendance->employee->employee_id) ?></td>
                                <td data-label="Date">
                                    <?= h($attendance->attendance_date->format('d M Y')) ?>
                                    <span class="day-name"><?= h($attendance->attendance_date->format('D')) ?></span>
                                </td>
                                <td data-label="Status">
                                    <span class="status-badge status-<?= strtolower($attendance->status) ?>">
                                        <?= h($attendance->status) ?>
                                    </span>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <?= $this->Html->link('View', ['action' => 'view', $attendance->id], ['class' => 'btn-action btn-view']) ?>
                                    <?= $this->Html->link('Edit', ['action' => 'edit', $attendance->id], ['class' => 'btn-action btn-edit']) ?>
                                    <?= $this->Form->postLink(
                                        'Delete',
                                        ['action' => 'delete', $attendance->id],
                                        ['confirm' => __('Are you sure?'), 'class' => 'btn-action btn-delete']
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>   
            </div>
        </div>

        <?php if (!empty($attendances)): ?>
        <div class="pagination-wrapper">
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->first('<< ' . __('first')) ?>
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                    <?= $this->Paginator->last(__('last') . ' >>') ?>
                </ul>
                <p class="pagination-info">
                    <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h3 {
    margin: 0;
    color: #333;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.content-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-container {
    width: 100%;
    position: relative;
    min-height: 300px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.responsive-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    min-width: 100%;
}

.responsive-table th,
.responsive-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.responsive-table th {
    background-color: #f8f9fa;
    color: #495057;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    position: sticky;
    top: 0;
    z-index: 10;
}

.responsive-table tbody tr {
    transition: background-color 0.2s;
}

.responsive-table tbody tr:hover {
    background-color: #f8f9fa;
}

.day-name {
    display: inline-block;
    margin-left: 5px;
    color: #6c757d;
    font-size: 12px;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.status-present {
    background-color: #d4edda;
    color: #155724;
}

.status-absent {
    background-color: #f8d7da;
    color: #721c24;
}

.status-leave {
    background-color: #fff3cd;
    color: #856404;
}

.actions-cell {
    white-space: nowrap;
}

.btn-action {
    display: inline-block;
    padding: 6px 12px;
    margin: 0 3px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-view {
    background-color: #17a2b8;
    color: white;
}

.btn-view:hover {
    background-color: #138496;
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
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
}

.empty-state {
    padding: 60px 20px !important;
    text-align: center;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.empty-state p {
    margin: 5px 0;
    color: #666;
}

.text-muted {
    color: #6c757d;
    font-size: 14px;
}

.pagination-wrapper {
    padding: 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #e0e0e0;
}

.pagination-info {
    text-align: center;
    margin-top: 10px;
    color: #6c757d;
    font-size: 14px;
}

/* Responsive Styles */
@media (max-width: 1024px) {
    .responsive-table th,
    .responsive-table td {
        padding: 12px 10px;
        font-size: 13px;
    }
    
    .btn-action {
        padding: 5px 10px;
        font-size: 12px;
        margin: 2px;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .button {
        flex: 1;
        text-align: center;
    }
    
    .content-card {
        margin: 0 -10px;
        border-radius: 0;
    }
    
    /* Mobile Table Layout */
    .responsive-table thead {
        display: none;
    }
    
    .responsive-table,
    .responsive-table tbody,
    .responsive-table tr,
    .responsive-table td {
        display: block;
        width: 100%;
    }
    
    .responsive-table tr {
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .responsive-table td {
        text-align: right;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
        padding-left: 50%;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    
    .responsive-table td:last-child {
        border-bottom: none;
    }
    
    .responsive-table td:before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        font-weight: 600;
        color: #666;
        text-align: left;
        font-size: 13px;
    }
    
    .actions-cell {
        white-space: normal;
        display: flex !important;
        flex-wrap: wrap;
        gap: 5px;
        justify-content: flex-end;
    }
    
    .actions-cell:before {
        align-self: flex-start;
    }
    
    .btn-action {
        display: inline-block;
        margin: 0;
        flex: 0 0 auto;
    }
    
    .day-name {
        display: block;
        margin-left: 0;
        margin-top: 3px;
    }
    
    .pagination-wrapper {
        padding: 15px;
    }
    
    .pagination {
        font-size: 13px;
        justify-content: center;
    }
    
    .pagination li {
        margin: 2px;
    }
    
    .pagination a {
        padding: 6px 10px;
    }
}

@media (max-width: 480px) {
    .page-header h3 {
        font-size: 18px;
    }
    
    .header-actions {
        flex-direction: column;
    }
    
    .header-actions .button {
        width: 100%;
        margin: 0 0 8px 0;
    }
    
    .responsive-table tr {
        padding: 12px;
    }
    
    .responsive-table td {
        padding: 8px 0;
        font-size: 13px;
    }
    
    .btn-action {
        padding: 5px 10px;
        font-size: 12px;
    }
    
    .empty-state {
        padding: 40px 15px !important;
    }
    
    .empty-icon {
        font-size: 36px;
    }
}

/* Ensure footer doesn't overlap */
.attendances.index.content {
    min-height: calc(100vh - 200px);
    padding-bottom: 40px;
}
</style>