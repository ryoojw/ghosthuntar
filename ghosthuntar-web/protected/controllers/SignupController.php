<?php

class SignupController extends Controller {
	
	public $layout = '//layouts/mural';
	
	public function actionIndex() {
	
		$model = new User;
	
		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			
			if ($model->save()) {
				
				
				$this->redirect(array('user/view', 'id' => $model->id));
			}
		}
		
		$this->render('index', array(
			'model'=>$model,
		));
	}
	
}