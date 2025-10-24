<div class="reports department-monthly content">
    <h3><?= __('Department Monthly Salary Report') ?></h3>

   <!-- Filter -->
<div class="filters" style="margin-bottom: 20px;">
    <?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline']) ?>
    <div style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <div>
            <?= $this->Form->control('month', [
                'type' => 'select',
                'options' => [
                    '01' => 'January', '02' => 'February', '03' => 'March',
                    '04' => 'April', '05' => 'May', '06' => 'June',
                    '07' => 'July', '08' => 'August', '09' => 'September',
                    '10' => 'October', '11' => 'November', '12' => 'December'
                ],
                'value' => $month,
                'label' => 'Month'
            ]) ?>
        </div>

        <div>
            <?= $this->Form->control('year', [
                'type' => 'number',
                'value' => $year,
                'min' => 2020,
                'max' => date('Y'),
                'label' => 'Year'
            ]) ?>
        </div>

        <div>
            <?= $this->Form->control('department_id', [
                'type' => 'select',
                'options' => $departments,
                'empty' => 'All Departments',
                'value' => $selectedDepartment,
                'label' => 'Department'
            ]) ?>
        </div>

        <div>
            <?= $this->Form->button(__('Generate Report'), ['type' => 'submit', 'class' => 'button']) ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>


    <h4>Report for: <?= date('F Y', strtotime("$year-$month-01")) ?></h4>

    <div class="table-responsive">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Month</th>
                    <th>Base Pay</th>
                    <th>Bonus</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalBasePay = 0;
                $totalBonus = 0;
                $totalDeductions = 0;
                $totalNetSalary = 0;
                
                foreach ($reportData as $data): 
                    $totalBasePay += $data['base_pay'];
                    $totalBonus += $data['bonus'];
                    $totalDeductions += $data['deductions'];
                    $totalNetSalary += $data['net_salary'];
                ?>
                <tr>
                    <td><?= h($data['department']) ?></td>
                    <td><?= date('F Y', strtotime("$year-$month-01")) ?></td>
                    <td>₹<?= $this->Number->format($data['base_pay'], ['places' => 2]) ?></td>
                    <td>₹<?= $this->Number->format($data['bonus'], ['places' => 2]) ?></td>
                    <td>₹<?= $this->Number->format($data['deductions'], ['places' => 2]) ?></td>
                    <td><strong>₹<?= $this->Number->format($data['net_salary'], ['places' => 2]) ?></strong></td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($reportData)): ?>
                <tr>
                    <td colspan="6" class="text-center">No data available for selected month</td>
                </tr>
                <?php else: ?>
                <tr class="total-row">
                    <td colspan="2"><strong>Total</strong></td>
                    <td><strong>₹<?= $this->Number->format($totalBasePay, ['places' => 2]) ?></strong></td>
                
                    <td><strong>₹<?= $this->Number->format($totalBonus, ['places' => 2]) ?></strong></td>
                    <td><strong>₹<?= $this->Number->format($totalDeductions, ['places' => 2]) ?></strong></td>
                    <td><strong>₹<?= $this->Number->format($totalNetSalary, ['places' => 2]) ?></strong></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="actions">
        <?= $this->Html->link(__('Export to CSV'), '#', ['class' => 'button', 'onclick' => 'exportTableToCSV(); return false;']) ?>
        <?= $this->Html->link(__('Print'), '#', ['class' => 'button', 'onclick' => 'window.print(); return false;']) ?>
    </div>
</div>

<script>
function exportTableToCSV() {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) {
            row.push(cols[j].innerText);
        }
        
        csv.push(row.join(","));
    }
    
    var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    var downloadLink = document.createElement("a");
    downloadLink.download = "department_monthly_report.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}
</script>

<style>
.filters {
    background: #f5f5f5;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.report-table {
    width: 100%;
    border-collapse: collapse;
}
.report-table th,
.report-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}
.report-table th {
    background-color: #4CAF50;
    color: white;
}
.report-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
.total-row {
    background-color: #e8f5e9 !important;
    font-weight: bold;
}
.text-center {
    text-align: center !important;
}
.actions {
    margin-top: 20px;
}
</style>