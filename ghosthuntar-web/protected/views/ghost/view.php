<?php
$this->breadcrumbs=array(
	'Ghosts'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Ghost', 'url'=>array('index')),
	array('label'=>'Create Ghost', 'url'=>array('create')),
	array('label'=>'Update Ghost', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ghost', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ghost', 'url'=>array('admin')),
);
?>

<h1>View Ghost #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'power',
		'latitude',
		'longitude',
		'elevation',
		'spawn_date',
		'owner_id',
	),
)); ?>
