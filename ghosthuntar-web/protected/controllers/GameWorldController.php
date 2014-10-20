<?php

class GameWorldController extends Controller {
	
	public function actionIndex() {
	
		$this->layout = '//layouts/gameworld';
		
		$this->render('index');
	}
	
}