<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico?" type="image/x-icon">
	
	<!-- CSS -->
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" rel="stylesheet">
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
   
	<!-- Script -->
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/bootstrap/js/bootstrap.min.js'); ?>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1BqQUep7SlCc2YCo196gN6w7vw36D0RQ&sensor=false&region=gb&libraries=places"></script>
	
</head>

<body>

	<?php include_once(Yii::app()->basePath."/../analytics.php"); ?>

	<div class="navbar navbar-fixed-top">
    	<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="<?php echo Yii::app()->homeUrl; ?>"><img alt="Ghost HuntAR" src="<?php echo Yii::app()->request->baseUrl; ?>/images/Logo_1.png" style="width: 140px;"></a>
				<div class="nav-collapse collapse">
					<p class="navbar-text pull-right">
					<?php
					if (Yii::app()->user->isGuest) {
						
						echo CHtml::link('Log In', array('/login'), array('class' => 'navbar-link'));
					
					} else {
					
						echo CHtml::link('Add Ghost', array('/ghost/new'), array('class' => 'navbar-link', 'style' => 'margin-right: 20px;'));
						echo CHtml::link('Log Out', array('/site/logout'), array('class' => 'navbar-link'));
					}
					?>
					</p>
					<ul class="nav">
						<li><a href="<?php echo Yii::app()->homeUrl; ?>">Home</a></li>
						<li><a href="<?php echo Yii::app()->createUrl('/gameWorld'); ?>">Game World</a></li>
						<li><a href="http://ghosthuntar.posterous.com" target="_blank">Blog</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	
	<div class="container" style="margin-top: 40px;">

		<?php echo $content; ?>
		
		<footer style="margin-top: 30px;">
			<p class="pull-right"><a href="#">Back to top</a></p>
			<p>&copy; 2012 Ghost HuntAR &middot; <a href="http://ghosthuntar.posterous.com" target="_blank">Blog</a> &middot; <a href="<?php echo Yii::app()->createUrl('/about'); ?>">About</a> &middot; <a href="<?php echo Yii::app()->createUrl('/help'); ?>">Help</a></p>
		</footer><!-- footer -->
		
	</div><!-- container -->

</body>
</html>
