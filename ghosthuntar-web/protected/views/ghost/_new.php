<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'ghost_form',
	'enableAjaxValidation' => false,
)); ?>

	<div class="row">
	
		<div class="span12">
		
			<?php echo CHtml::textField('address', '', array('id' => 'address', 'placeholder' => 'Enter a location', 'autocomplete' => 'off')); ?>
			<input class="btn btn-success" id="search_button" type="button" value="Search">
			
		</div>
		
		<div class="span9">
			
			<div id="map_canvas" style="width:100%; height:350px;"></div>
			
		</div>
		
		<div class="span3">
			
			<div id="location_header"></div>
			
			<div id="location_area">
				<?php echo $form->hiddenField($model, 'latitude', array()); ?>
				<div id="latitude"></div>
				
				<?php echo $form->hiddenField($model, 'longitude', array()); ?>
				<div id="longitude"></div>
			</div>
			
		</div>
		
		<div class="span12">
		
			<?php echo $form->labelEx($model,'type'); ?>
			<?php echo $form->dropDownList($model, 'type', array('blinky' => 'Blinky', 'pinky' => 'Pinky', 'inky' => 'Inky', 'clyde' => 'Clyde')); ?>
			<?php echo $form->error($model,'type'); ?>
		
		</div>
		
		<div class="span12">
		
			<?php echo $form->errorSummary($model); ?>
		
		</div>
		
		<div class="span12">
		
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-large btn-success')); ?>
		
		</div>
	
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->