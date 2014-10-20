<?php
$this->breadcrumbs=array(
	'Ghosts',
);

$this->menu=array(
	array('label'=>'Create Ghost', 'url'=>array('create')),
	array('label'=>'Manage Ghost', 'url'=>array('admin')),
);
?>

<h1>Ghosts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
