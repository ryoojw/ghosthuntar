<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'signup_form',
	'htmlOptions' => array(
		'class' => 'form-signin',
	),
)); ?>

	<?php echo $form->textField($model, 'name', array('class' => 'input-block-level', 'placeholder' => 'Full Name')); ?>
	<?php echo $form->error($model, 'name'); ?>

	<?php echo $form->textField($model, 'email', array('class' => 'input-block-level', 'placeholder' => 'Email')); ?>
	<?php echo $form->error($model, 'email'); ?>
	
	<?php echo $form->passwordField($model, 'password', array('class' => 'input-block-level', 'placeholder' => 'Password')); ?>
	<?php echo $form->error($model, 'password'); ?>

	<?php echo $form->passwordField($model, 'confirm_password', array('class' => 'input-block-level', 'placeholder' => 'Confirm Password')); ?>
	<?php echo $form->error($model, 'confirm_password'); ?>
	
	<?php echo CHtml::submitButton('Sign Up', array('class' => 'btn btn-success', 'style' => 'width: 300px;height: 40px;')); ?>

<?php $this->endWidget(); ?>