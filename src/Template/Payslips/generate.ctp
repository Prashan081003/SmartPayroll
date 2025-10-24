<div class="payslips generate content">
    <h3><?= __('Generate Payslip') ?></h3>

    <?= $this->Form->create(null) ?>
   <fieldset>
    <legend><?= __('Payslip Details') ?></legend>
    
    <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
        <?= $this->Form->control('employee_id', [
            'options' => $employees,
            'empty' => 'Select Employee',
            'required' => true,
            'label' => 'Employee'
        ]) ?>

        <?= $this->Form->control('month', [
            'type' => 'select',
            'options' => [
                '01' => 'January', '02' => 'February', '03' => 'March',
                '04' => 'April', '05' => 'May', '06' => 'June',
                '07' => 'July', '08' => 'August', '09' => 'September',
                '10' => 'October', '11' => 'November', '12' => 'December'
            ],
            'value' => date('m'),
            'required' => true,
            'label' => 'Month'
        ]) ?>

        <?= $this->Form->control('year', [
            'type' => 'number',
            'value' => date('Y'),
            'min' => 2020,
            'max' => date('Y'),
            'required' => true,
            'label' => 'Year'
        ]) ?>
    </div>

        <div class="bonuses-section">
            <h4>Bonuses</h4>
            <div id="bonuses-container">
                <div class="bonus-row">
                    <?= $this->Form->control('bonuses.0.type', [
                        'type' => 'select',
                        'options' => ['Performance' => 'Performance', 'Festival' => 'Festival'],
                        'empty' => 'Select Type',
                        'label' => 'Bonus Type'
                    ]) ?>
                    <?= $this->Form->control('bonuses.0.amount', [
                        'type' => 'number',
                        'step' => '0.01',
                        'min' => '0',
                        'label' => 'Amount'
                    ]) ?>
                </div>
            </div>
            <button type="button" id="add-bonus" class="button">Add Another Bonus</button>
        </div>

        <div class="deductions-section">
            <h4>Deductions</h4>
            <div id="deductions-container">
                <div class="deduction-row">
                    <?= $this->Form->control('deductions.0.type', [
                        'type' => 'select',
                        'options' => ['TDS' => 'TDS Tax', 'Unpaid Leave' => 'Unpaid Leaves', 'Other' => 'Other'],
                        'empty' => 'Select Type',
                        'label' => 'Deduction Type'
                    ]) ?>
                    <?= $this->Form->control('deductions.0.amount', [
                        'type' => 'number',
                        'step' => '0.01',
                        'min' => '0',
                        'label' => 'Amount'
                    ]) ?>
                </div>
            </div>
            <button type="button" id="add-deduction" class="button">Add Another Deduction</button>
        </div>
    </fieldset>

   <?= $this->Form->button(__('Generate Payslip'), ['type' => 'submit', 'class' => 'button']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'button']) ?>
    <?= $this->Form->end() ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var bonusCount = 1;
    var deductionCount = 1;

    // Add bonus row
    $('#add-bonus').click(function() {
        var bonusHtml = `
            <div class="bonus-row">
                <select name="bonuses[${bonusCount}][type]">
                    <option value="">Select Type</option>
                    <option value="Performance">Performance</option>
                    <option value="Festival">Festival</option>
                </select>
                <input type="number" name="bonuses[${bonusCount}][amount]" step="0.01" min="0" placeholder="Amount">
                <button type="button" class="remove-bonus button small">Remove</button>
            </div>
        `;
        $('#bonuses-container').append(bonusHtml);
        bonusCount++;
    });

    // Remove bonus row
    $(document).on('click', '.remove-bonus', function() {
        $(this).closest('.bonus-row').remove();
    });

    // Add deduction row
    $('#add-deduction').click(function() {
        var deductionHtml = `
            <div class="deduction-row">
                <select name="deductions[${deductionCount}][type]">
                    <option value="">Select Type</option>
                    <option value="TDS">TDS Tax</option>
                    <option value="Unpaid Leave">Unpaid Leaves</option>
                    <option value="Other">Other</option>
                </select>
                <input type="number" name="deductions[${deductionCount}][amount]" step="0.01" min="0" placeholder="Amount">
                <button type="button" class="remove-deduction button small">Remove</button>
            </div>
        `;
        $('#deductions-container').append(deductionHtml);
        deductionCount++;
    });

    // Remove deduction row
    $(document).on('click', '.remove-deduction', function() {
        $(this).closest('.deduction-row').remove();
    });
});
</script>

<style>
.bonuses-section, .deductions-section {
    margin: 20px 0;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 5px;
}
.bonus-row, .deduction-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}
.bonus-row select, .bonus-row input,
.deduction-row select, .deduction-row input {
    flex: 1;
    padding: 8px;
}
.button.small {
    padding: 5px 10px;
    font-size: 12px;
}
</style>