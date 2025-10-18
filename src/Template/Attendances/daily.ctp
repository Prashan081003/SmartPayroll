<div class="attendances daily content">
    <h3><?= __('Daily Attendance Management') ?></h3>

    <!-- Date Selection -->
    <div class="date-selector">
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'date-form']) ?>
        <div style="display: flex; gap: 5px; align-items: flex-end;">
            <div style="flex: 1;">
                <label for="date">Select Date:</label>
                <input type="date" 
                       name="date" 
                       id="date" 
                       value="<?= h($selectedDate) ?>" 
                       max="<?= date('Y-m-d') ?>"
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" class="button">Load Attendance</button>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <h4>Attendance for: <?= h(date('l, F d, Y', strtotime($selectedDate))) ?></h4>

    <?php if (empty($attendanceData)): ?>
        <div class="message warning">
            No employees found for this date. Either no employees have joined yet or all are inactive.
        </div>
    <?php else: ?>
    <div class="table-responsive">
        <table id="attendance-table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceData as $record): ?>
                <tr data-employee-id="<?= $record['employee_id'] ?>">
                    <td><?= h($record['employee_code']) ?></td>
                    <td><?= h($record['name']) ?></td>
                    <td>
                        <select class="attendance-status" data-employee-id="<?= $record['employee_id'] ?>">
                            <option value="">-- Select --</option>
                            <option value="Present" <?= $record['status'] == 'Present' ? 'selected' : '' ?>>Present</option>
                            <option value="Absent" <?= $record['status'] == 'Absent' ? 'selected' : '' ?>>Absent</option>
                            <option value="Leave" <?= $record['status'] == 'Leave' ? 'selected' : '' ?>>Leave</option>
                        </select>
                        <span class="status-indicator"></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var selectedDate = '<?= $selectedDate ?>';
    var csrfToken = '<?= $this->request->getParam('_csrfToken') ?>';
    
    $('.attendance-status').on('change', function() {
        var $select = $(this);
        var employeeId = $select.data('employee-id');
        var status = $select.val();
        var indicator = $select.siblings('.status-indicator');
        
        if (!status) {
            return;
        }
        
        // Show loading
        indicator.html('<span style="color: blue;">Saving...</span>');
        
        // Make AJAX call
        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'Attendances', 'action' => 'updateStatus']) ?>',
            method: 'POST',
            dataType: 'json',
            data: {
                employee_id: employeeId,
                date: selectedDate,
                status: status,
                _csrfToken: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    indicator.html('<span style="color: green;">✓ Saved</span>');
                    setTimeout(function() {
                        indicator.html('');
                    }, 2000);
                } else {
                    indicator.html('<span style="color: red;">✗ Error</span>');
                    console.error('Error:', response.message);
                    if (response.errors) {
                        console.error('Validation errors:', response.errors);
                    }
                    // Optionally show alert
                    // alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                indicator.html('<span style="color: red;">✗ Failed</span>');
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                // Show user-friendly error
                alert('Failed to update attendance. Please check console for details.');
            }
        });
    });
});
</script>

<style>
.date-selector {
    background: #f5f5f5;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.attendance-status {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 3px;
    margin-right: 10px;
}
.status-indicator {
    font-size: 12px;
    margin-left: 10px;
}
#attendance-table {
    width: 100%;
}
#attendance-table th,
#attendance-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
</style>