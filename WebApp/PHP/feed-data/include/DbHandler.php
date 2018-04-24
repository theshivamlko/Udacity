<?php
/**
 * Class to handle all db operations
 * This file belongs to Control Panel @ WeVandS
 */

 require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';

class DbHandler {
	private $conn;
	private $tf = "\"Feed data\"";
	function __construct() {
		require_once ROOT_DIR.'include/DbConnect.php';
		// opening db connection
		$db = new DbConnect();
		$this->conn = $db->connect();
	}

		public function getStates(){
			$query = "
						SELECT
							`state_id`, `state_name`, `state_code`, `description`, `img`, `state_status`
						FROM
							`location_state`
						ORDER BY
							`state_name`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			}

			if ($result->num_rows > 0) {
	/*			$resp = array();
				$resp[] = array('User ID', 'Mobile', 'Name',
								'u_loc', 'DOB', 'BG', 'Gender',
								'Reg. Date', 'Last Login', 'Last Active',
								'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
								*/
								$results = array();
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$results[] = $row;
				}
				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

		public function getCities($state, $debug){
			//$state_id = $this->getState($state, "id");
			$deb = "";
			if ($debug==2) {
				$deb = "

				";
			} else {
				$deb = "
					AND
						`debug` = 1
					";
			}
			$query = "
						SELECT
							`city_id`, `city_name`, `city_status`, `user_id`, `add_date`
						FROM
							`location_city`
						WHERE
							`state_code` = '$state'
						$deb
						ORDER BY
							`city_name`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			}
				if ($result->num_rows > 0) {
	/*			$resp = array();
				$resp[] = array('User ID', 'Mobile', 'Name',
								'u_loc', 'DOB', 'BG', 'Gender',
								'Reg. Date', 'Last Login', 'Last Active',
								'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
								*/
								$results = array();
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$results[] = $row;
				}
				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

		public function getPlaces($city, $debug){
			$deb = "";
			if ($debug==2) {
				$deb = "

				";
			} else {
				$deb = "
					AND
						`debug` = 1
					";
			}
			$query = "
						SELECT
							`place_id`, `place_name`, `place_local_name`, `place_description`,
							`city_id`, `place_google_place_id`, `place_type`,
							`place_add_by`, `place_add_date`, `place_status`
						FROM
							`location_place`
						WHERE
							`city_id` = '$city'
						$deb
						ORDER BY
							`place_name`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			}
				if ($result->num_rows > 0) {
		/*			$resp = array();
				$resp[] = array('User ID', 'Mobile', 'Name',
								'u_loc', 'DOB', 'BG', 'Gender',
								'Reg. Date', 'Last Login', 'Last Active',
								'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
								*/
								$results = array();
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$results[] = $row;
				}
				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

		public function getImages($place_id, $debug){
			$deb = "";
			if ($debug==2) {
				$deb = "

				";
			} else {
				$deb = "
					AND
						`debug` = 1
					";
			}
			$query = "
						SELECT
							`image_path`, `image_name`, `place_id`, `image_alt`, `image_discription`, `add_date`, `add_by`, `image_status`
						FROM
							`images`
						WHERE
							`place_id` = '$place_id'
						$deb
						ORDER BY
							`add_date`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			}
				if ($result->num_rows > 0) {
		/*			$resp = array();
				$resp[] = array('User ID', 'Mobile', 'Name',
								'u_loc', 'DOB', 'BG', 'Gender',
								'Reg. Date', 'Last Login', 'Last Active',
								'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
								*/
								$results = array();
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$results[] = $row;
				}
				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

		public function getImage($image_id){
			$query = "
						SELECT
							`image_path`, `image_name`, `place_id`, `image_alt`, `image_discription`, `add_date`, `add_by`, `image_status`
						FROM
							`images`
						WHERE
							`image_id` = '$image_id'
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			}
				if ($result->num_rows > 0) {
		/*			$resp = array();
				$resp[] = array('User ID', 'Mobile', 'Name',
								'u_loc', 'DOB', 'BG', 'Gender',
								'Reg. Date', 'Last Login', 'Last Active',
								'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
								*/
								$results = array();
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$results[] = $row;
				}
				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

		public function getPlace($place_id, $type = "all"){
			$query = "
					SELECT
						`place_id`, `place_name`, `place_local_name`, `place_description`,
						`city_id`, `place_google_place_id`, `image_id`, `place_type`,
						`place_add_by`, `place_add_date`, `place_status`
					FROM
						`location_place`
					WHERE
						`place_id` = $place_id
						;
					";
					if (!$result = $this->conn->query($query)){
						return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
					}

					if ($result->num_rows > 0) {
					$results = array();
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				switch ($type) {
					case 'name':
						$results = $row;
						break;
					default:
						$results = $row;
						break;
				}

				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

		public function getState($state_code, $type = "name"){
			$query = "
						SELECT
							`state_id`, `state_name`, `state_code`, `description`, `img`, `state_status`
						FROM
							`location_state`
						WHERE
							`state_code` = '".$state_code."'
						;
					";
					if (!$result = $this->conn->query($query)){
						return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
					}

					if ($result->num_rows > 0) {
	/*			$resp = array();
				$resp[] = array('User ID', 'Mobile', 'Name',
								'u_loc', 'DOB', 'BG', 'Gender',
								'Reg. Date', 'Last Login', 'Last Active',
								'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
								*/
								$results = array();
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				switch ($type) {
					case 'name':
						$results = $row["state_name"];
						break;
					case 'code':
						$results = $row["state_code"];
						break;

					default:
						$results = $row["state_name"];
						break;
				}

				return array(true,$results);
			} else {
				return array(false,"Error");
			}
		}

    public function getState2($state_code){
      $query = "
            SELECT
              `state_id`, `state_name`, `state_code`, `description`, `img`, `state_status`
            FROM
              `location_state`
            WHERE
              `state_code` = '".$state_code."'
            ;
          ";
          if (!$result = $this->conn->query($query)){
            return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
          }

          if ($result->num_rows > 0) {
    /*			$resp = array();
        $resp[] = array('User ID', 'Mobile', 'Name',
                'u_loc', 'DOB', 'BG', 'Gender',
                'Reg. Date', 'Last Login', 'Last Active',
                'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
                */
                $results = array();
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return array(true,$row);
      } else {
        return array(false,"Error");
      }
    }

	public function getCity($city_id, $type = "all"){
		$query = "
					SELECT
						`city_name`, `state_code`, `city_status`, `user_id`, `add_date`
					FROM
						`location_city`
					WHERE
						`city_id` = '".$city_id."'
					;
				";
				if (!$result = $this->conn->query($query)){
					return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
				}

				if ($result->num_rows > 0) {
	/*			$resp = array();
			$resp[] = array('User ID', 'Mobile', 'Name',
							'u_loc', 'DOB', 'BG', 'Gender',
							'Reg. Date', 'Last Login', 'Last Active',
							'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
							*/
							$results = array();
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			switch ($type) {
				case 'all':
					$results = $row;
					break;
				case 'state':
					$results = $row["state_code"];
					break;
				case 'name':
					$results = $row["city_name"];
					break;

				default:
					$results = $row["city_name"];
					break;
			}

			return array(true,$results);
		} else {
			return array(false,"Error");
		}
	}

	public function createCity($city_name, $state, $user_id, $debug) {
		$now = timestamp();
		$activity_table = "location_city";
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `$activity_table` (
														`city_name`, `state_code`, `city_status`, `user_id`, `add_date`, `debug`
														)
						VALUES (?, ?, 1, ?, ?, ?)
			")){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$stmt->bind_param("sssss",
							 	$city_name, $state, $user_id, $now, $debug
			)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$result = $stmt->execute()){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}else{
			$new_user_id = $stmt->insert_id;
			$activity_res = array(true,$new_user_id);
			$activity_table_id = $new_user_id;
		}

	 	$stmt->close();
		$activity_type = "create";
		$activity_function = __function__;
		if ($activity_res[0]) {
			$activity_result = 2;
		} else {
			$activity_result = 1;
		}

		$description = $activity_res[1];

		$this->createUserActivity(
							$user_id, $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;
	}

	public function createPlace(
								$place_name, $place_local_name, $place_discription,
								$city_id, $place_google_place_id, $place_type,
								$place_add_by, $debug
												) {
		$now = timestamp();
		$activity_table = "location_place";
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `$activity_table`(
														`place_name`, `place_local_name`, `place_description`,
														`city_id`, `place_google_place_id`, `place_type`,
														`place_add_by`, `place_add_date`, `place_status`, `debug`
														)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)"
			)){
				$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
				$activity_table_id = 0;
			}
		if (!$stmt->bind_param("sssssssss",
										$place_name, $place_local_name, $place_discription,
										$city_id, $place_google_place_id, $place_type,
										$place_add_by, $now, $debug
			)){
				$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
				$activity_table_id = 0;
			}
		if (!$result = $stmt->execute()){
							$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
							$new_user_id = 0;
							$activity_table_id = $new_user_id;

						} else {
							$new_user_id = $stmt->insert_id;
							$activity_res = array(true,$new_user_id);
							$activity_table_id = $new_user_id;

						}
		 $stmt->close();
		 $activity_type = "create";
		 $activity_function = __function__;
		if ($activity_res[0]) {
 			$activity_result = 2;
 		} else {
 			$activity_result = 1;
 		}
		$description = $activity_res[1];
		 $this->createUserActivity(
									 $place_add_by, $activity_type, $activity_function, $activity_result,
									 $description, $activity_table, $activity_table_id
						 );
		 return $activity_res;
	}

	function createStory($place_id, $city_id, $state_id, $title, $year, $month, $day, $description, $add_by, $debug) {
		$now = timestamp();
		$token = generateApiKey();
		$activity_table = "story";
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `$activity_table`(
											`place_id`, `city_id`, `state_id`, `title`, `year`, `month`, `day`, `description`, `add_by`, `add_date`, `status`, `debug`
											)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?);
						")){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$stmt->bind_param("sssssssssss",
										$place_id, $city_id, $state_id, $title, $year, $month, $day, $description, $add_by, $now, $debug
										)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$result = $stmt->execute()){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$new_user_id = 0;
			$activity_table_id = $new_user_id;
		} else {
			$new_user_id = $stmt->insert_id;
			$retn = array('id' => $new_user_id);

			$activity_res = array(true,$retn);

		}
		$stmt->close();
		$activity_type = "create";
		$activity_function = __function__;
		if ($activity_res[0]) {
 			$activity_result = 2;
 		} else {
 			$activity_result = 1;
 		}
		$description = $activity_res[1];

		$this->createUserActivity(
							$add_by, $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;

	}
	public function getStorys($place_id, $debug){
		$deb = "";
		if ($debug==2) {
			$deb = "

			";
		} else {
			$deb = "
				AND
					`debug` = 1
				";
		}
		$query = "
					SELECT
						`story_id`, `place_id`, `city_id`, `state_id`, `title`, `year`, `month`, `day`, `description`, `add_by`, `add_date`, `status`
					FROM
						`story`
					WHERE
						`place_id` = '$place_id'
					$deb
					ORDER BY
						`year`
					;
				";
		if (!$result = $this->conn->query($query)){
			return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
		}
			if ($result->num_rows > 0) {
	/*			$resp = array();
			$resp[] = array('User ID', 'Mobile', 'Name',
							'u_loc', 'DOB', 'BG', 'Gender',
							'Reg. Date', 'Last Login', 'Last Active',
							'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
							*/
							$results = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$results[] = $row;
			}
			return array(true,$results);
		} else {
			return array(false,"Error");
		}
	}
	function updateCity($cityId, $cityName) {
		$now = timestamp();
		$token = generateApiKey();

		$activity_table = "location_city";
		$query = "
			UPDATE
				`$activity_table`
			SET
				`city_name` = '$cityName',
				`add_date` = '$now',
				`city_status` = 1
			WHERE
				`user_id` = '$user_id'
					;
				";
		if (!$result = $this->conn->query($query)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		} else {
			$activity_res = array(true, array($pref_key, $pref_value));
			$activity_table_id = 0;
		}
		$activity_type = "update";
		$activity_function = __function__;
		if ($activity_res[0]) {
			$activity_result = 2;
		} else {
			$activity_result = 1;
		}

		$description = json_encode($activity_res[1]);

		$this->createUserActivity(
							$user_id, $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;
	}
	public function isExistingUser($email){
		$query = "
					SELECT
						`passcode`, `status`, `u_api`
					FROM
						`users`
					WHERE
						`user_email` = '$email'
					;
				";
		if (!$result = $this->conn->query($query)){
			return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
		}

		if ($result->num_rows > 0) {
/*			$resp = array();
			$resp[] = array('User ID', 'Mobile', 'Name',
							'u_loc', 'DOB', 'BG', 'Gender',
							'Reg. Date', 'Last Login', 'Last Active',
							'Number status', 'User Status', 'Num status date', 'FCM ID', 'API');
							*/
							$results = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$results[] = $row;
			}
			return array(true,$results);
		} else {
			return array(false,"Error");
		}
	}

	/** 																											Session */
	function createSession($user_email, $u_token, $uid, $path) {
		$now = timestamp();
		$token = generateApiKey();
		$activity_table = "sessions";
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `$activity_table`(
											`user_email`, `user_api`,`token`, `uid`, `path`, `session_creation`, `login_datetime`, `status`
											)
						VALUES (?, ?, ?, ?, ?, ?, ?, 2);
			")){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$stmt->bind_param("sssssss",
										$user_email, $u_token, $token, $uid, $path, $now, $now
			)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$result = $stmt->execute()){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$new_user_id = 0;
			$activity_table_id = $new_user_id;

		} else {
			$new_user_id = $stmt->insert_id;
			$retn = array('id' => $new_user_id, 'token' => $token );

			$activity_res = array(true,$retn);

		}
		$stmt->close();
		$activity_type = "user";
		$activity_function = __function__;
		if ($activity_res[0]) {
 			$activity_result = 2;
 		} else {
 			$activity_result = 1;
 		}
		$description = $activity_res[1];

		$this->createUserActivity(
							0, $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;
	}
	function createUser($user_type, $user_email, $passcode, $first_name, $last_name, $level) {
		$now = timestamp();
		$token = generateApiKey();
		$activity_table = "users";
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `$activity_table`(
											`user_type`, `user_email`, `passcode`, `first_name`, `last_name`, `level`, `reg_date`, `status`, `u_api`
											)
						VALUES (?, ?, ?, ?, ?, ?, ?, 2, ?);
		")){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$stmt->bind_param("ssssssss",
										$user_type, $user_email, $passcode, $first_name, $last_name, $level, $now, $token
		)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$result = $stmt->execute()){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$new_user_id = 0;
			$activity_table_id = $new_user_id;

		} else {
			$new_user_id = $stmt->insert_id;
			$retn = array('id' => $new_user_id, 'token' => $token );

			$activity_res = array(true,$retn);
			$activity_table_id = $new_user_id;

		}
		$stmt->close();
		$activity_type = "user";
		$activity_function = __function__;
		if ($activity_res[0]) {
 			$activity_result = 2;
 		} else {
 			$activity_result = 1;
 		}
		$description = $activity_res[1];

		$this->createUserActivity(
							"", $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;
	}

	function createUserPref($user_id, $pref_key, $pref_value) {
		$now = timestamp();
		$token = generateApiKey();
		$activity_table = "user_pref";
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `$activity_table`(
							`user_id`, `pref_key`, `pref_value`, `status`, `updated_on`
							)
						VALUES (?, ?, ?, 2, ?);
		")){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$stmt->bind_param("ssss",
										$user_id, $pref_key, $pref_value, $now
		)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		}
		if (!$result = $stmt->execute()){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		} else {
			$new_user_id = $stmt->insert_id;
			$retn = array('id' => $new_user_id );
			$activity_res = array(true,$retn);
			$activity_table_id = $new_user_id;
		}
		$stmt->close();
		$activity_type = "create";
		$activity_function = __function__;
		if ($activity_res[0]) {
			$activity_result = 2;
		} else {
			$activity_result = 1;
		}

		$description = $activity_res[1];

		$this->createUserActivity(
							$user_id, $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;
	}

	function updateUserPref($user_id, $pref_key, $pref_value) {
		$now = timestamp();
		$token = generateApiKey();

		$activity_table = "user_pref";
		$query = "
			UPDATE
				`$activity_table`
			SET
				`pref_value` = '$pref_value',
				`updated_on` = '$now'
			WHERE
				`user_id` = '$user_id'
			AND
				`pref_key` = '$pref_key';
					;
				";
		if (!$result = $this->conn->query($query)){
			$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
			$activity_table_id = 0;
		} else {
			$activity_res = array(true, array($pref_key, $pref_value));
			$activity_table_id = 0;
		}
		$activity_type = "update";
		$activity_function = __function__;
		if ($activity_res[0]) {
			$activity_result = 2;
		} else {
			$activity_result = 1;
		}

		$description = json_encode($activity_res[1]);

		$this->createUserActivity(
							$user_id, $activity_type, $activity_function, $activity_result,
							$description, $activity_table, $activity_table_id
						);
		return $activity_res;
	}

		function updateUserPasscode($user_id, $passcode) {
			$now = timestamp();
			$token = generateApiKey();

			$activity_table = "users";
			$query = "
				UPDATE
					`$activity_table`
				SET
					`passcode` = '$passcode'
				WHERE
					`user_id` = '$user_id'
						;
					";
			if (!$result = $this->conn->query($query)){
				$activity_res = array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
				$activity_table_id = 0;
			} else {
				$activity_res = array(true, array("Passcode Updated"));
				$activity_table_id = 0;
			}
			$activity_type = "update";
			$activity_function = __function__;
			if ($activity_res[0]) {
				$activity_result = 2;
			} else {
				$activity_result = 1;
			}

			$description = json_encode($activity_res[1]);

			$this->createUserActivity(
								$user_id, $activity_type, $activity_function, $activity_result,
								$description, $activity_table, $activity_table_id
							);
			return $activity_res;
		}

	public function getUserPref($user_id, $pref_key){
		$query = "
				SELECT
					`pref_key`, `pref_value`, `status`, `updated_on`
				FROM
					`user_pref`
				WHERE
					`user_id` = '$user_id'
				AND
					`pref_key` = '$pref_key'
					;
				";
		if (!$result = $this->conn->query($query)){
			return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
		}

		if ($result->num_rows > 0) {
			$results = array();
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

			return array(true,$row);
		} else {
			return array(false,"No record");
		}
	}

	public function getUser($api){
		$query = "
				SELECT
					`user_id`, `user_type`, `username`, `user_email`, `passcode`, `first_name`, `last_name`, `level`, `reg_date`, `status`, `u_api`, `debug`
				FROM
					`users`
				WHERE
					`u_api` = '$api'
					;
				";
				if (!$result = $this->conn->query($query)){
					return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
				}

				if ($result->num_rows > 0) {
				$results = array();
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);


			return array(true,$row);
		} else {
			return array(false,"No record");
		}
	}

	public function createUserActivity(
								$user_id, $activity_type, $activity_function, $activity_result,
								$description, $activity_table, $activity_table_id
												) {
		$now = timestamp();
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `user_activity`(
													`user_id`, `activity_type`, `activity_function`, `activity_result`,
													`description`, `activity_date`, `activity_table`, `activity_table_id`
														)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?)
												")){
								return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
						}
						if (!$stmt->bind_param("ssssssss",
													$user_id, $activity_type, $activity_function, $activity_result,
													$description, $now, $activity_table, $activity_table_id
													)){
												return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
										}
						if (!$result = $stmt->execute()){
							return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
						} else {
							$new_user_id = $stmt->insert_id;
							return array(true,$new_user_id);
						}
						 $stmt->close();
	}
	public function getActivities($type = "", $user_id = 0) {
		$query = "
				SELECT
					`activity_id`, `user_id`, `activity_type`, `activity_function`, `activity_result`, `description`, `activity_date`, `activity_table`, `activity_table_id`
				FROM
					`user_activity`";
		if($type==""&&$user!=0){
			$query .= "
				WHERE
					`user_id` = '$user_id'
			";
		} elseif ($type!=""&&$user==0){
			$query .= "
				WHERE
					`activity_type` = '$type'
			";
		} elseif ($type!=""&&$user!=0){
			$query .= "
				WHERE
					`activity_type` = '$type',
					`user_id` = '$user_id'
			";
		}

    $query .= "
        ORDER BY `user_activity`.`activity_date` DESC;
     ";
		if (!$result = $this->conn->query($query)){
			return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error.", in function \"".__function__."\" at line ".__LINE__);
		}
		if ($result->num_rows > 0) {
			$results = array();
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $results[] = $row;
      }


			return array(true,$results);
		} else {
			return array(false,"No record");
		}
	}

  public function checkToken($token) {
    if ($token == "16c4ac08f29891acf46b51b89e07abf9") {
      return(array(true, "Success"));
    } else {
      return(array(false, "Invalid token"));
    }
  }
}

?>
