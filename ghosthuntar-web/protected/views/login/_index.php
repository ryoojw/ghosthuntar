<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'login_form',
	'htmlOptions' => array(
		'class' => 'form-signin',
	),
)); ?>

	<?php echo $form->textField($model, 'email', array('class' => 'input-block-level', 'placeholder' => 'Email address')) ?>
	<?php echo $form->error($model, 'email'); ?>
	
	<?php echo $form->passwordField($model, 'password', array('class' => 'input-block-level', 'placeholder' => 'Password')); ?>
	<?php echo $form->error($model, 'password'); ?>
	
	<?php echo CHtml::submitButton('Log In', array('class' => 'btn btn-success', 'style' => 'width: 300px;height: 40px;')); ?>

<?php $this->endWidget(); ?>