<?php

class AppController extends Controller {
	
	public function actionIndex() {
		
		if (isset($_POST['tag']) && $_POST['tag'] != '') {
			$tag = $_POST['tag'];
			
			$response = array("tag" => $tag, "success" => 0, "error" => 0);
			
			if ($tag == "login") {
				$model = new LoginForm;
				
				$email = $_POST['email'];
				$password = $_POST['password'];
				
				$model->email = $email;
				$model->password = $password;
				
				if ($model->validate() && $model->loginApp()) {
					$model = User::model()->find("email=:email", array(
						':email' => $email,
					));
				
					$response['success'] 						= 1;
	    		$response['uid'] 								= $model->id;
	    		$response['user']['email'] 			= $model->email;
	    		$response['user']['name'] 			= $model->name;
	    		$response['user']['join_date']	= $model->join_date;
	    		
					echo json_encode($response);
					
				} else {
					// user not found
					// echo json with error = 0
					$response["error"] = 1;
					$response["error_msg"] = "Incorrect email or password!";
					
					echo json_encode($response);
        }
        
			} else if ($tag == "signup") {
				
				// Request type is Register new user
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
 
 				$model = User::model()->exists('email=:email', array(
 					':email' => $email,
 				));
 
        // check user exists
        if ($model) {
        	// user already exists - error
        	$response["error"]			= 2;
        	$response["error_msg"] 	= "User already exists!";
        	
        	echo json_encode($response);
        	
        } else {
        	// Save user
        	$model = new User;
        	$model->name = $name;
        	$model->email = $email;
        	$model->password = $password;
        	$model->confirm_password = $password;
        	
        	if ($model->save()) {
        		// User saved
        		$response["success"] 						= 1;
        		$response["uid"] 								= $model->id;
        		$response["user"]["email"] 			= $model->email;
        		$response["user"]["name"] 			= $model->name;
        		$response["user"]["join_date"] 	= $model->join_date;
        		
        		echo json_encode($response);
        		
        	} else {
        	
        		// user failed to store
        		$response["error"] = 1;
        		$response["error_msg"] = "Error occurred during Sign up";
        		
        		echo json_encode($response);
        	}
        }
				
			} else
				echo "Invalid Request";
			
		}	else
			echo "Access Denied";
	}
	
	public function actionLogin($tag, $email, $password) {
		
		if (isset($tag) && $tag != '') {
			
			$response = array("tag" => $tag, "success" => 0, "error" => 0);
			
			if ($tag == "login") {
				
				$model = new LoginForm;
				
				$model->email = $email;
				$model->password = $password;
				
				if ($model->validate() && $model->loginApp()) {
				
					$model = User::model()->find("email=:email", array(
						':email' => $email,
					));
				
					$response['success'] 						= 1;
	    		$response['uid'] 								= $model->id;
	    		$response['user']['email'] 			= $model->email;
	    		$response['user']['name'] 			= $model->name;
	    		$response['user']['join_date']	= $model->join_date;
	    		
					echo json_encode($response);
				}
			}
		}
	}
	
	public function actionChangeOwner() {
	
		if (isset($_POST['tag']) && $_POST['tag'] != '') {
			$tag = $_POST['tag'];
			
			$response = array("tag" => $tag, "success" => 0, "error" => 0);
			
			if ($tag == "capture") {
				$player_email = $_POST['player_email'];
				$ghost_id 		= $_POST['ghost_id'];
				
				// Find the player
				$player = User::model()->find("email=:email", array(
					':email' => $player_email,
				));
				
				// Find the ghost that was captured
				$ghost = Ghost::model()->findByPk($ghost_id);
				
				if ($player && $ghost) {
					$ghost->owner_id = $player->id;
					
					// Update was successful?
					if ($ghost->update(array("owner_id"))) {
						$response["success"] 				= 1;
						$response["ghost"]["id"] 		= $ghost->id;
						$response["ghost"]["name"]	= $ghost->name;
						
						echo json_encode($response);
						
					} else {
						$response["error"] 			= 2;
						$response["error_msg"] 	= "Failed to capture ghost!";
						
						echo json_encode($response);
					}
				}
				
			} else
				echo "Invalid Request";
			
		} else
			echo "Access Denied";
	}
	
	public function actionGetGhosts($latitude, $longitude) {
		$radius = 10;
		$capture_image_url = "http://ghosthuntar.com/images/ghosts/blinky_capture.png";
		
		if (isset($latitude) && isset($longitude)) {
			
			$connection = Yii::app()->db;
			$query 			= $connection->createCommand(
										'SELECT `id`, `latitude`, `longitude`, `elevation`, `name`, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance, `power`, `type`
										FROM tbl_ghost
										WHERE latitude > ('.$latitude.' - ('.$radius.' / 69)) AND latitude < ('.$latitude.' + ('.$radius.' / 69)) AND longitude > ('.$longitude.' - '.$radius.' / abs(cos(radians('.$latitude.')) * 69)) AND longitude < ('.$longitude.' + '.$radius.' / abs(cos(radians('.$latitude.')) * 69)) AND owner_id=1')->queryAll();
			
			$connection->active = false;
			
			$results = array();
						
			foreach ($query as $ghost) {
				
				$image_url = 'http://www.ghosthuntar.com/images/ghosts/blinky.png';
				
				if ($ghost['type'] == 'blinky') {
					
					$image_url = 'http://www.ghosthuntar.com/images/ghosts/blinky.png';
					
				} else if ($ghost['type'] == 'pinky') {
					
					$image_url = 'http://www.ghosthuntar.com/images/ghosts/pinky.png';
					
				} else if ($ghost['type'] == 'inky') {
					
					$image_url = 'http://www.ghosthuntar.com/images/ghosts/inky.png';
					
				} else if ($ghost['type'] == 'clyde') {
					
					$image_url = 'http://www.ghosthuntar.com/images/ghosts/clyde.png';
				}
			
				$temp = array(
					'id' 								=> (int) $ghost['id'],
					'name' 							=> '#'.$ghost['id'].' '.$ghost['name'],
					'latitude' 					=> (float) $ghost['latitude'],
					'longitude' 				=> (float) $ghost['longitude'],
					'elevation'					=> (float) $ghost['elevation'],
					'distance'					=> $ghost['distance'],
					'power'							=> $ghost['power'],
					'ghost_type' 				=> $ghost['type'],
					'ghost_image_url' 	=> $image_url,
					'capture_image_url'	=> $capture_image_url,
				);
				
				array_push($results, $temp);
			}
			
			$ghosts = array(
				'status' 			=> "OK",
				'num_results' => count($query),
				'results' 		=> $results,
			);
			
			print(json_encode($ghosts));
		}
	}
}