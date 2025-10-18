<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Payslips'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bonuses'), ['controller' => 'Bonuses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bonus'), ['controller' => 'Bonuses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Deductions'), ['controller' => 'Deductions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Deduction'), ['controller' => 'Deductions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="payslips form large-9 medium-8 columns content">
    <?= $this->Form->create($payslip) ?>
    <fieldset>
        <legend><?= __('Add Payslip') ?></legend>
        <?php
            echo $this->Form->control('employee_id', ['options' => $employees]);
            echo $this->Form->control('month');
            echo $this->Form->control('year');
            echo $this->Form->control('base_pay');
            echo $this->Form->control('days_worked');
            echo $this->Form->control('total_bonus');
            echo $this->Form->control('total_deductions');
            echo $this->Form->control('net_salary');
            echo $this->Form->control('payment_date', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
