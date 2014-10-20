<?php
$this->pageTitle = "New Ghost";
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/ghost/new.js', CClientScript::POS_END);
?>

<h1>New Ghost</h1>

<?php echo $this->renderPartial('_new', array('model' => $model)); ?>