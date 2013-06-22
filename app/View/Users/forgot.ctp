<?php 
echo $this->Form->create(array('controller'=>'users', 'action'=>'forgot'));
echo $this->Form->input("email");
echo $this->Form->end("Reset Password");

?>