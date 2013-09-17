<?php echo $this->Form->create('Participant', array('url'=>Router::normalize(array('controller'=>'Experiments','action'=>'household_income'))));  ?>
<?php echo $this->Form->input('id',array('type'=>'hidden'));  ?>
<?php echo $this->Form->input('household_income'); ?>
<?php echo $this->Form->end(); ?>