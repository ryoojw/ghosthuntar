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
  
  <style type="text/css">
  
    body {
      padding-top: 40px;
      padding-bottom: 40px;
      background: url("<?php echo Yii::app()->request->baseUrl; ?>/images/world-map.jpg") no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
    }

    .form-signin {
      max-width: 300px;
      padding: 20px 20px 30px;
      margin: 0 auto 20px;
      background-color: #fff;
      border: 1px solid #e5e5e5;
      -webkit-border-radius: 5px;
         -moz-border-radius: 5px;
              border-radius: 5px;
      -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
         -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
              box-shadow: 0 1px 2px rgba(0,0,0,.05);
      
      -webkit-box-shadow: rgba(0, 0, 0, 0.1) 0 0 3px 3px;
			-moz-box-shadow: rgba(0, 0, 0, 0.1) 0 0 3px 3px;
			box-shadow: rgba(0, 0, 0, 0.1) 0 0 3px 3px;
			border: 1px solid #E7E7E7	9;
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
      margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
      font-size: 16px;
      height: auto;
      margin-bottom: 15px;
      padding: 7px 9px;
    }

  </style>
   
  <!-- Script -->
  <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/bootstrap/js/bootstrap.min.js'); ?>
	
</head>

<body>

	<?php include_once(Yii::app()->basePath."/../analytics.php"); ?>

	<div class="navbar navbar-fixed-top">
		<div class="container-fluid">
			<a class="brand" href="<?php echo Yii::app()->homeUrl; ?>"><img alt="Ghost HuntAR" src="<?php echo Yii::app()->request->baseUrl; ?>/images/Logo_1_large.png" style="width: 300px;"></a>
		</div>
	</div>

	<div class="container" style="margin-top: 40px;">

		<?php echo $content; ?>
		
	</div><!-- container -->

</body>
</html>
