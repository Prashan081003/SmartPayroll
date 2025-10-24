<div class="payslips view content">
    <h3><?= __('Payslip') ?></h3>
    
    <div class="payslip-container">
        <!-- Company Header -->
        <div class="payslip-header">
            <h2>Payslip</h2>
            <p>For the Month of <?= date('F Y', strtotime("{$payslip->year}-{$payslip->month}-01")) ?></p>
        </div>

        <!-- Employee Details -->
        <div class="employee-details">
            <h4>Employee Details</h4>
            <table>
                <tr>
                    <td><strong>Employee ID:</strong></td>
                    <td><?= h($payslip->employee->employee_id) ?></td>
                    <td><strong>Name:</strong></td>
                    <td><?= h($payslip->employee->name) ?></td>
                </tr>
                <tr>
                    <td><strong>Department:</strong></td>
                    <td><?= h($payslip->employee->department->name) ?></td>
                    <td><strong>Designation:</strong></td>
                    <td><?= h($payslip->employee->designation) ?></td>
                </tr>
                <tr>
                    <td><strong>Joining Date:</strong></td>
                    <td><?= h($payslip->employee->joining_date->format('Y-m-d')) ?></td>
                    <td><strong>Payment Date:</strong></td>
                    <td><?= $payslip->payment_date ? h($payslip->payment_date->format('Y-m-d')) : 'Not Paid' ?></td>
                </tr>
            </table>
        </div>

       <!-- Salary Breakdown -->
<div class="salary-breakdown" style="width: 60%; margin: 0 auto;">
    <h4 style="text-align:center;">Salary Breakdown</h4>

    <!-- Earnings Table -->
    <table class="breakdown-table" style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <thead>
            <tr style="background-color:#000; color:#fff;">
                <th colspan="2" style="text-align:left; padding:8px;">Earnings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:8px;">Base Pay</td>
                <td style="padding:8px; text-align:right;">₹<?= $this->Number->format($payslip->employee->base_salary, ['places' => 2]) ?></td>
            </tr>
           
        </tbody>
    </table>

    <!-- Bonuses Table -->
    <?php if (!empty($payslip->bonuses)): ?>
    <table class="breakdown-table" style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <thead>
            <tr style="background-color:#000; color:#fff;">
                <th colspan="2" style="text-align:left; padding:8px;">Bonuses</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payslip->bonuses as $bonus): ?>
                <tr>
                    <td style="padding:8px;"><?= h($bonus->bonus_type) ?></td>
                    <td style="padding:8px; text-align:right;">₹<?= $this->Number->format($bonus->amount, ['places' => 2]) ?></td>
                </tr>
            <?php endforeach; ?>
             <tr>
                <td style="padding:8px;"><strong>Total Bonuses</strong></td>
                <td style="padding:8px; text-align:right;"><strong>₹<?= $this->Number->format($payslip->total_bonus, ['places' => 2]) ?></strong></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- Deductions Table -->
    <?php if (!empty($payslip->deductions)): ?>
    <table class="breakdown-table" style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <thead>
            <tr style="background-color:#000; color:#fff;">
                <th colspan="2" style="text-align:left; padding:8px;">Deductions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payslip->deductions as $deduction): ?>
                <tr>
                    <td style="padding:8px;"><?= h($deduction->deduction_type) ?></td>
                    <td style="padding:8px; text-align:right;">₹<?= $this->Number->format($deduction->amount, ['places' => 2]) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td style="padding:8px;"><strong>Total Deductions</strong></td>
                <td style="padding:8px; text-align:right;"><strong>₹<?= $this->Number->format($payslip->total_deductions, ['places' => 2]) ?></strong></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
</div>


        <!-- Net Salary -->
        <div class="net-salary">
            <table>
               <tr>
                    <td><strong>Days Worked:</strong></td>
                    <td><?= h($payslip->days_worked) ?> / <?= h($totalWorkingDays) ?> days</td>
                </tr>

                <tr class="highlight">
                    <td><strong>Net Salary:</strong></td>
                    <td><strong>₹<?= $this->Number->format($payslip->net_salary, ['places' => 2]) ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Actions -->
        <div class="actions">
            <?= $this->Html->link(__('Back to List'), ['action' => 'index'], ['class' => 'button']) ?>
            <?= $this->Html->link(__('Print'), '#', ['class' => 'button', 'onclick' => 'window.print(); return false;']) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $payslip->id],
                ['confirm' => __('Are you sure you want to delete this payslip?'), 'class' => 'button']
            ) ?>
        </div>
    </div>
</div>

<style>
.payslip-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px;
    background: white;
    border: 1px solid #ddd;
}
.payslip-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #333;
    padding-bottom: 20px;
}
.employee-details, .salary-breakdown, .net-salary {
    margin-bottom: 30px;
}
.employee-details table,
.breakdown-table,
.net-salary table {
    width: 100%;
    border-collapse: collapse;
}
.employee-details table td {
    padding: 8px;
    border: 1px solid #ddd;
}
.breakdown-table th,
.breakdown-table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}
.breakdown-table th {
    background-color: #f5f5f5;
}
.amount {
    text-align: right !important;
}
.totals {
    background-color: #f9f9f9;
    font-weight: bold;
}
.net-salary table {
    background-color: #e8f5e9;
    font-size: 18px;
}
.net-salary table td {
    padding: 15px;
}
.highlight {
    background-color: #c8e6c9;
}
.actions {
    margin-top: 30px;
    text-align: center;
}

@media print {
    .actions {
        display: none;
    }
    .payslip-container {
        border: none;
    }
}
</style>