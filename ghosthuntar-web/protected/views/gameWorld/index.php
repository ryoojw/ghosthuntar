<?php
$this->pageTitle = "Game World";
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/modules/markerclusterer_packed.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/gameWorld/index.js', CClientScript::POS_END);
?>

<div class="span12 pull-right">
  <div class="hero-unit" style="padding: 10px;">
    <div id="map_canvas" style="width:100%; height:475px;"></div>
  </div>
  
</div><!--/span-->