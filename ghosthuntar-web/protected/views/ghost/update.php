<?php
$this->breadcrumbs=array(
	'Ghosts'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ghost', 'url'=>array('index')),
	array('label'=>'Create Ghost', 'url'=>array('create')),
	array('label'=>'View Ghost', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Ghost', 'url'=>array('admin')),
);
?>

<h1>Update Ghost <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>