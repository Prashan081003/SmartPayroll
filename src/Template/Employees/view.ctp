<div class="employees view content">
    <h3><?= h($employee->name) ?></h3>
    
    <div class="employee-card">
        <div class="card-header">
            <h4>Employee Information</h4>
            <span class="badge badge-<?= $employee->status == 'active' ? 'success' : 'danger' ?>">
                <?= h($employee->status) ?>
            </span>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Employee ID:</label>
                <span><?= h($employee->employee_id) ?></span>
            </div>
            
            <div class="info-item">
                <label>Name:</label>
                <span><?= h($employee->name) ?></span>
             </div>
            
                <div class="info-item">
            <label>Department:</label>
                    <span><?= $employee->has('department') ? h($employee->department->name) : '<em>Not Assigned</em>' ?></span>
                </div>
            
            <div class="info-item">
                <label>Designation:</label>
                <span><?= h($employee->designation) ?></span>
            </div>
            
            <div class="info-item">
                <label>Base Salary:</label>
                <span>₹<?= $this->Number->format($employee->base_salary, ['places' => 2]) ?></span>
            </div>
            
            <div class="info-item">
                <label>Joining Date:</label>
                <span><?= h($employee->joining_date->format('d M Y')) ?></span>
            </div>
            
            <div class="info-item">
                <label>Email:</label>
                <span><?= h($employee->email) ?></span>
            </div>
            
            <div class="info-item">
                <label>Mobile:</label>
                <span><?= h($employee->mobile) ?></span>
            </div>
        </div>
        
        <div class="actions">
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employee->id], ['class' => 'button']) ?>
            <?= $this->Html->link(__('Back to List'), ['action' => 'index'], ['class' => 'button button-secondary']) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employee->id],
                ['confirm' => __('Are you sure you want to delete this employee?'), 'class' => 'button button-danger']
            ) ?>
        </div>
    </div>

    <?php if (!empty($employee->attendances)): ?>
    <div class="related-section">
        <h4>Recent Attendance Records</h4>
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($employee->attendances, 0, 10) as $attendance): ?>
                        <tr>
                            <td data-label="Date"><?= h($attendance->attendance_date->format('d M Y')) ?></td>
                            <td data-label="Status">
                                <span class="status-badge status-<?= strtolower($attendance->status) ?>">
                                    <?= h($attendance->status) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (count($employee->attendances) > 10): ?>
            <p class="text-muted">Showing 10 of <?= count($employee->attendances) ?> records</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($employee->payslips)): ?>
    <div class="related-section">
        <h4>Payslip History</h4>
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Month/Year</th>
                            <th>Days Worked</th>
                            <th>Net Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employee->payslips as $payslip): ?>
                        <tr>
                            <td data-label="Month/Year">
                                <?= date('F Y', strtotime($payslip->year . '-' . $payslip->month . '-01')) ?>
                            </td>
                            <td data-label="Days Worked"><?= h($payslip->days_worked) ?></td>
                            <td data-label="Net Salary">₹<?= $this->Number->format($payslip->net_salary, ['places' => 2]) ?></td>
                            <td data-label="Actions">
                                <?= $this->Html->link('View', ['controller' => 'Payslips', 'action' => 'view', $payslip->id], ['class' => 'btn-link']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.employee-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 25px;
    margin-bottom: 30px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.card-header h4 {
    margin: 0;
    color: #667eea;
}

.badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: white;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-item label {
    font-weight: 600;
    color: #666;
    font-size: 13px;
    margin-bottom: 5px;
}

.info-item span {
    font-size: 15px;
    color: #333;
}

.actions {
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.button-danger {
    background-color: #dc3545;
}

.button-danger:hover {
    background-color: #c82333;
}

.related-section {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 25px;
    margin-bottom: 30px;
}

.related-section h4 {
    margin: 0 0 20px 0;
    color: #667eea;
    font-size: 18px;
}

.table-wrapper {
    margin-bottom: 15px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1rem;
}

.compact-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    min-width: 100%;
}

.compact-table th,
.compact-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.compact-table th {
    background-color: #f8f9fa;
    color: #495057;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
}

.compact-table tbody tr:hover {
    background-color: #f8f9fa;
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
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

.btn-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
}

.btn-link:hover {
    text-decoration: underline;
}

.text-muted {
    color: #6c757d;
    font-size: 13px;
    margin-top: 10px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .employee-card,
    .related-section {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .actions {
        flex-direction: column;
    }
    
    .actions .button {
        width: 100%;
        text-align: center;
        margin: 0;
    }
    
    /* Responsive Table */
    .compact-table thead {
        display: none;
    }
    
    .compact-table,
    .compact-table tbody,
    .compact-table tr,
    .compact-table td {
        display: block;
        width: 100%;
    }
    
    .compact-table tr {
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 10px;
        background: white;
    }
    
    .compact-table td {
        text-align: right;
        padding: 8px 10px;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
        padding-left: 50%;
    }
    
    .compact-table td:last-child {
        border-bottom: none;
    }
    
    .compact-table td:before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: 600;
        color: #666;
        text-align: left;
    }
}

@media (max-width: 480px) {
    h3 {
        font-size: 20px;
    }
    
    .related-section h4 {
        font-size: 16px;
    }
    
    .info-item label {
        font-size: 12px;
    }
    
    .info-item span {
        font-size: 14px;
    }
}
</style>