<div class="attendances report content">
    <h3><?= __('Monthly Attendance Report') ?></h3>

    <!-- Month Selection -->
    <div class="month-selector">
        <?= $this->Form->create(null, ['type' => 'get']) ?>
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
        <?= $this->Form->control('year', [
            'type' => 'number',
            'value' => $year,
            'min' => 2020,
            'max' => date('Y'),
            'label' => 'Year'
        ]) ?>
        <?= $this->Form->button(__('Generate Report'), ['type' => 'submit']) ?>
        <?= $this->Form->end() ?>
    </div>

    <h4>Report for: <?= date('F Y', strtotime("$year-$month-01")) ?></h4>

    <div class="table-responsive" style="overflow-x: auto;">
        <table class="attendance-report-table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                        <?php 
                            $date = sprintf("%s-%s-%02d", $year, $month, $day);
                            $dayName = date('D', strtotime($date));
                        ?>
                        <th class="day-header" title="<?= $dayName ?>"><?= $day ?><br><small><?= $dayName ?></small></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $record): ?>
                <tr>
                    <td><?= h($record['employee_id']) ?></td>
                    <td><?= h($record['name']) ?></td>
                    <td><?= h($record['department']) ?></td>
                    <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                        <?php 
                            $status = $record['daily_attendance'][$day];
                            $cssClass = '';
                            $displayText = $status;
                            
                            if ($status == 'Present') {
                                $cssClass = 'status-present';
                                $displayText = 'P';
                            } elseif ($status == 'Absent') {
                                $cssClass = 'status-absent';
                                $displayText = 'A';
                            } elseif ($status == 'Leave') {
                                $cssClass = 'status-leave';
                                $displayText = 'L';
                            } else {
                                $cssClass = 'status-unmarked';
                                $displayText = '-';
                            }
                        ?>
                        <td class="<?= $cssClass ?>"><?= $displayText ?></td>
                    <?php endfor; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="legend">
        <h4>Legend:</h4>
        <span class="status-present">P</span> = Present &nbsp;&nbsp;
        <span class="status-absent">A</span> = Absent &nbsp;&nbsp;
        <span class="status-leave">L</span> = Leave &nbsp;&nbsp;
        <span class="status-unmarked">-</span> = Not Marked
    </div>
</div>

<style>
.month-selector {
    background: #f5f5f5;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.attendance-report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.attendance-report-table th,
.attendance-report-table td {
    padding: 8px 5px;
    text-align: center;
    border: 1px solid #ddd;
}
.day-header {
    min-width: 40px;
    font-size: 11px;
}
.status-present {
    background-color: #d4edda;
    color: #155724;
    font-weight: bold;
}
.status-absent {
    background-color: #f8d7da;
    color: #721c24;
    font-weight: bold;
}
.status-leave {
    background-color: #fff3cd;
    color: #856404;
    font-weight: bold;
}
.status-unmarked {
    background-color: #e9ecef;
    color: #6c757d;
}
.legend {
    margin-top: 20px;
    padding: 15px;
    background: #f5f5f5;
    border-radius: 5px;
}
.legend span {
    display: inline-block;
    padding: 5px 10px;
    margin-right: 10px;
    border-radius: 3px;
}
</style>