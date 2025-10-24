<div class="departments form">
    <?= $this->Form->create($department) ?>
    <fieldset>
        <legend><?= __('Add Department') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('code');
            echo $this->Form->control('description', ['type' => 'textarea']);
        ?>
    </fieldset>
   <?= $this->Form->button(__('Submit'), ['class' => 'button']) ?>

    <?= $this->Form->end() ?>
</div>