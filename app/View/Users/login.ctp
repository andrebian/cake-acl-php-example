<?php echo $this->Form->create('User', array('id' => 'login')); ?>
<p>
    <?php echo $this->Form->input('email'); ?>
</p>
<p>
    <?php echo $this->Form->input('password'); ?>
</p>
<p>
    <?php echo $this->Form->end(__('Realizar login')); ?>
</p>