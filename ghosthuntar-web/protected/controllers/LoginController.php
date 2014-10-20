<?php

class LoginController extends Controller {

	public $layout = '//layouts/mural';
	
	public function actionIndex() {
		
		if (!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->homeUrl);
		
		$model = new LoginForm;
		
		if (isset($_POST['LoginForm'])) {
		
			$model->attributes = $_POST['LoginForm'];
			
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		
		$this->render('index', array(
			'model' => $model,
		));
	}
	
}