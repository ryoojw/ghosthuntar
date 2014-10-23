<?php

class GhostController extends RController {

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/main';
	
	/**
	 * Rights permissions.
	 */
	/*public function filters() {
		return array(
			'rights',
		);
	}*/

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
	
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNew() {
	
		$model = new Ghost;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Ghost'])) {
		
			$model->attributes 	= $_POST['Ghost'];

			//print_r($model->attributes);
			//die();
			
			// Set the elevation biased closer to 0
			if (rand(0, 10) > 9)
				$elevation = (double) rand(900, 1500);
			else if (rand(0, 10) > 8)
				$elevation = (double) rand(600, 899);
			else if (rand(0, 10) > 7)
				$elevation = (double) rand(400, 599);
			else if (rand(0, 10) > 6)
				$elevation = (double) rand(300, 399);
			else
				$elevation = (double) rand(0, 299);
			
			//$model->elevation = $elevation;
			$model->elevation = 1;
			
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('new', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ghost']))
		{
			$model->attributes=$_POST['Ghost'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Ghost');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ghost('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ghost']))
			$model->attributes=$_GET['Ghost'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Ghost::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionJson() {
		$connection = Yii::app()->db;
		$query 			= $connection->createCommand("SELECT id, name, type, latitude, longitude FROM tbl_ghost")->queryAll();
		
		$connection->active = false;
		
		echo '{"ghosts":'.json_encode($query).'}';
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ghost-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionLocations() {
		$connection = Yii::app()->db;
		$query 			= $connection->createCommand("SELECT id, name, type, latitude, longitude FROM tbl_ghost")->queryAll();
		
		$connection->active = false;
		
		print(json_encode($query));
	}
	
	/**
	 * Gets the ghosts of the provided latitude and longitude
	 */
	/*public function actionGetGhosts() {
		$latitude  = null;
		$longitude = null;
		$radius    = 1;
		
		if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
			$latitude  = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			
			$connection = Yii::app()->db;
			$query 			= $connection->createCommand(
										'SELECT `id`, `name`, `power`, `latitude`, `longitude`, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance
										FROM tbl_ghost
										WHERE latitude > ('.$latitude.' - ('.$radius.' / 69)) AND latitude < ('.$latitude.' + ('.$radius.' / 69)) AND longitude > ('.$longitude.' - '.$radius.' / abs(cos(radians('.$latitude.')) * 69)) AND longitude < ('.$longitude.' + '.$radius.' / abs(cos(radians('.$latitude.')) * 69))')->queryAll();
			
			$connection->active = false;
			
			print(json_encode($query));
		}
	}*/
	
	public function actionGetGhosts($latitude, $longitude) {
		$radius = 1;
		
		if (isset($latitude) && isset($longitude)) {
			
			$connection = Yii::app()->db;
			$query 			= $connection->createCommand(
										'SELECT `id`, `name`, `power`, `latitude`, `longitude`, `elevation`, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance
										FROM tbl_ghost
										WHERE latitude > ('.$latitude.' - ('.$radius.' / 69)) AND latitude < ('.$latitude.' + ('.$radius.' / 69)) AND longitude > ('.$longitude.' - '.$radius.' / abs(cos(radians('.$latitude.')) * 69)) AND longitude < ('.$longitude.' + '.$radius.' / abs(cos(radians('.$latitude.')) * 69))')->queryAll();
			
			$connection->active = false;
			
			print('{
								"status": "OK",
								"num_results": '.count($query).',
								"results": '.json_encode($query).'
						}');
		}
	}
}
