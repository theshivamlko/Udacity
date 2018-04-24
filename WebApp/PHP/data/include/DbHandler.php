<?php
/**
 * Class to handle all db operations
 * This file belongs to Control Panel @ WeVandS
 */

 require_once '/home/wevandsc/link.wevands.com/ei/data/include/var.php';

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
							`state_id`, `state_name`, `state_status`, `state_code`
						FROM
							`location_state`
						ORDER BY
							`state_name`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
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

		public function getCities($state){
			//$state_id = $this->getState($state, "id");
			$query = "
						SELECT
							`city_id`, `city_name`, `city_status`
						FROM
							`location_city`
						WHERE
							`state_code` = '$state'
						ORDER BY
							`city_name`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
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

		public function getPlaces($city){
			$query = "
						SELECT
							`place_id`, `place_name`, `place_local_name`, `place_description`,
							`city_id`, `place_google_place_id`, `place_type`,
							`place_add_by`, `place_add_date`, `place_status`
						FROM
							`location_place`
						WHERE
							`city_id` = '$city'
						ORDER BY
							`place_name`
						;
					";
			if (!$result = $this->conn->query($query)){
				return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
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
						`city_id`, `place_google_place_id`, `place_type`,
						`place_add_by`, `place_add_date`, `place_status`
					FROM
						`location_place`
					WHERE
						`place_id` = $place_id
						;
					";
					if (!$result = $this->conn->query($query)){
						return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
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
							`state_id`, `state_name`, `state_code`, `state_status`
						FROM
							`location_state`
						WHERE
							`state_code` = '".$state_code."'
						;
					";
					if (!$result = $this->conn->query($query)){
						return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
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

	public function getCity($city_id, $type = "all"){
		$query = "
					SELECT
						`city_name`, `state_code`, `city_status`
					FROM
						`location_city`
					WHERE
						`city_id` = '".$city_id."'
					;
				";
				if (!$result = $this->conn->query($query)){
					return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
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

	public function createCity($city_name, $state) {
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `location_city` (
														`city_name`, `state_code`, `city_status`
														)
						VALUES (?, ?, 1)
												")){
								return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
						}
						if (!$stmt->bind_param("ss",
											 $city_name, $state
											 )){
												return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
										}
										if (!$result = $stmt->execute()){
												return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
										}else{
											$new_user_id = $stmt->insert_id;
												return array(true,$new_user_id);
										}
						 $stmt->close();
	}


	public function createImage(
											$place_id, $image_alt, $image_name, $image_discription, $image_add_by
										) {
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `location_city`(
														`place_id`, `image_alt`, `image_name`, `image_discription`, `image_status`, `image_add_by`, `image_add_date`
														)
						VALUES (?, ?, ?, ?, ?, ?, ?)
												")){
								return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
						}
						if (!$stmt->bind_param("sssssss",
											 $place_id, $image_alt, $image_name, $image_discription, $image_status, $image_add_by, $image_add_date
																		 )){
												return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
										}
										if (!$result = $stmt->execute()){
												return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
										}else{
											$new_user_id = $stmt->insert_id;
												return array(true,$new_user_id);
										}
						 $stmt->close();
	}


	public function createPlace(
								$place_name,  $place_local_name, $place_discription,
								$city_id,  $place_google_place_id,  $place_type,
								$place_add_by
												) {
		$now = timestamp();
		if (!$stmt = $this->conn->prepare("
						INSERT INTO `location_place`(
														`place_name`, `place_local_name`, `place_description`,
														`city_id`, `place_google_place_id`, `place_type`,
														`place_add_by`, `place_add_date`, `place_status`
														)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
												")){
								return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
						}
						if (!$stmt->bind_param("ssssssss",
															$place_name,  $place_local_name, $place_discription,
															$city_id,  $place_google_place_id,  $place_type,
															$place_add_by,  $now
															)){
												return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
										}
						if (!$result = $stmt->execute()){
							return array(false,$this->tf.": (".$this->conn->errno.") ".$this->conn->error);
						} else {
							$new_user_id = $stmt->insert_id;
							return array(true,$new_user_id);
						}
						 $stmt->close();
	}

}

?>
