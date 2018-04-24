 <!DOCTYPE html>
<html lang="en">

	<?php

		require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
		$base = "https://ei.wevands.com/feed-data/";
		include ROOT_DIR.'include/DbHandler.php';
		$db = new DbHandler();
		$form_errors = array();
		$email = "";
		$pass = false;
		require_once '/home/wevandsc/link.wevands.com/ei/feed-data/check_login_status.php';
		$user = array();
		$user_exist = false;
		$state = "";
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		if(isset($_POST['user-email-f'])) {
			if(
				isset($_POST['user-email']) &&
				$_POST['user-email'] != "" &&
				filter_var($_POST['user-email'], FILTER_VALIDATE_EMAIL)
			){
				$email = $_POST['user-email'];

				$temp = $db->isExistingUser($email);
				$user_exist = $temp[0];

				if ($user_exist) {
					$u_token = $temp[1][0]['u_api'];
					if (!$temp[1][0]['passcode']=='0') {
						$pass = true;
					} else {

						$result = $db->createSession($email,$u_token, $ip, "");
						//$form_errors[] = json_encode($result);
						if ($result[0]) {
							$_SESSION['email'] = $email;
							$_SESSION['u_token'] = $u_token;
							$_SESSION['token'] = $result[1]['token'];
							setcookie("email", $email, strtotime( '+30 days' ), "/", "", "", TRUE);
							setcookie("token", $result[1]['token'], strtotime( '+30 days' ), "/", "", "", TRUE);
							setcookie("u_token", $u_token, strtotime( '+30 days' ), "/", "", "", TRUE);
							echo "<meta http-equiv='refresh' content='0'>";
				    	} else {
							$form_errors[] = $result[1];
						}
					}
				}
			} else {
				$form_errors[] = "Plase enter Valid Email";
			}
		}
		if(isset($_POST['user-login'])) {
			if(
				isset($_POST['user-email']) &&
				$_POST['user-email'] != "" &&
				filter_var($_POST['user-email'], FILTER_VALIDATE_EMAIL)
			){
				$email = $_POST['user-email'];
				$passcode = $_POST['user-passcode'];
				$temp = $db->isExistingUser($email);
				$user_exist = $temp[0];
				if ($user_exist) {
					$u_token = $temp[1][0]['u_api'];
					if ($temp[1][0]['passcode']==$passcode) {
						$result = $db->createSession($email, $u_token, $ip, "");
						//$form_errors[] = json_encode($result);
						if ($result[0]) {
							$_SESSION['email'] = $email;
							$_SESSION['u_token'] = $u_token;
							$_SESSION['token'] = $result[1]['token'];
							setcookie("email", $email, strtotime( '+30 days' ), "/", "", "", TRUE);
							setcookie("token", $result[1]['token'], strtotime( '+30 days' ), "/", "", "", TRUE);
							setcookie("u_token", $u_token, strtotime( '+30 days' ), "/", "", "", TRUE);
							echo "<meta http-equiv='refresh' content='0'>";
						}
					}
				}
			} else {
				$form_errors[] = "Plase enter Valid Email";
			}
		}
		if(isset($_POST['user-reg'])) {
			if(
				isset($_POST['user-email']) &&
				$_POST['user-email'] != "" &&
				filter_var($_POST['user-email'], FILTER_VALIDATE_EMAIL)
			){
				$email = $_POST['user-email'];
				$user_type = $_POST['user-type'];
				$first_name = $_POST['user-firstname'];
				$last_name = $_POST['user-lastname'];
				$level = $_POST['user-level'];

				$temp = $db->createUser($user_type, $email, "", $first_name, $last_name, $level);
				$user_exist = $temp[0];
				if ($user_exist) {
					$u_token = $temp[1][0]['u_api'];
					$result = $db->createSession($email, $temp[1]['token'], $ip, "");
					if ($result[0]) {
						$_SESSION['email'] = $email;
						$_SESSION['u_token'] = $u_token;
						$_SESSION['token'] = $result[1]['token'];
						setcookie("email", $email, strtotime( '+30 days' ), "/", "", "", TRUE);
						setcookie("token", $result[1]['token'], strtotime( '+30 days' ), "/", "", "", TRUE);
						setcookie("u_token", $u_token, strtotime( '+30 days' ), "/", "", "", TRUE);
						echo "<meta http-equiv='refresh' content='0'>";
				    } else {
						$form_errors[] =  $result[1];
					}
				} else {
					$form_errors[] = $temp[1];
				}
			} else {
				$form_errors[] = "Plase enter Valid Email";
			}
		}
		function selectState($states){
			$stateHolder = "";
			for ($i = 0; $i < sizeof($states); $i++) {
				$stateHolder .= "<option value=\"".$states[$i]['state_code']."\">".$states[$i]['state_name']."</option>
								";
			}
			return $stateHolder;
		}

/* ------------------------------------------------------------------------------------------------------------------------- If user OK*/
		if($user_ok){
		$form_errors = array();
		$img_arrays = array();
		$temp = $db->getUser($u_token);
		$debug = 1;
		if($temp[0]) {
			$user = $temp[1];
			$email = $temp[1]['user_email'];
			$user_id =  $user['user_id'];
			if ($user['debug'] == 2) {
				$tempDebugPref = $db->getUserPref($user_id, PREF_DEBUG);
				if ($tempDebugPref[0]) {
					$debug = $tempDebugPref[1]['pref_value'];
				}
			}
			//$form_errors[] = $debug;
		} else {
			$form_errors[] = $email.">>".json_encode($temp[1]);
			//header("location: ".$base."logout#Error=UserFetchError");
		}
/* ------------------------------------------------------------------------------------------------------------------------- function */

		function listCities($state){
			$db = new DbHandler();
			global $base;
			global $debug;
			$cityHolder = "";

			$citiesHolder = $db->getCities($state, $debug);
			if($citiesHolder[0]) {
				$holder = $citiesHolder[1];
				for ($i = 0; $i < sizeof($holder); $i++) {
					$status = "Unverified";
					if($holder[$i]['city_status']==2)
					$status = "Verified";

					$cityHolder .= "<a href=\"".$base."state/".$state."/city/".$holder[$i]['city_id']."\">
										<div class=\"a-ll-h list-item\">
											<div>".$holder[$i]['city_name']."</div>
											<div class=\" right\">".$status."</div>
										</div>
									</a>
									";
				}
			}else {
				$cityHolder = $citiesHolder[1];
			}
			return $cityHolder;
		}

		function displayPlaces($city_id){
			$db = new DbHandler();
			global $base;
			global $debug;
			$placeHolder = "";
			$placesHolder = $db->getPlaces($city_id, $debug);
			$state = $db->getCity($city_id,"state");
			if($placesHolder[0]) {
				$holder = $placesHolder[1];
				for ($i = 0; $i < sizeof($holder); $i++) {
					$status = "Unverified";
					if($holder[$i]['place_status']==2)
					$status = "Verified";

					$placeHolder .= "<a href=\"".$base."state/".$state[1]."/city/".$city_id."/".$holder[$i]['place_id']."\">
										<div class=\"a-ll-h list-item\">
											<div>".$holder[$i]['place_name']."</div>
											<div class=\" right\">".$status."</div>
										</div>
									</a>
									";
				}
			}else {
				$placeHolder = "-: No Place Added :-";
			}
			return $placeHolder;
		}

		function displayBudget() {
			?>
			<div class="budget">
				<div class="container-min list-budget">
					<?php echo getStateName($state); ?>
				</div>
			</div>
			<?php
		}
		function displayImages($place_id) {
			$db = new DbHandler();
			global $base;
			global $debug;
			global $img_arrays;
			$Holder = "";
			$sHolder = $db->getImages($place_id, $debug);

			if($sHolder[0]) {
				$holder = $sHolder[1];
				$img_arrays = $holder;
				?>
				<div class="">
					<div class="a-ll-h images a-wrap">
				<?php

				for ($i = 0; $i < sizeof($holder); $i++) {
					$status = "Unverified";
					if($holder[$i]['image_status']==2)
					$status = "Verified";
					?>
						<a href="" data-popup-open="popup-img-v" onclick="v_img(<?php echo $i; ?>)">
							<div class="list-img">
								<img src="<?php echo $base.$holder[$i]['image_path']."/".$holder[$i]['image_name']; ?>" alt="<?php echo $holder[$i]['image_alt']; ?>">
								<div class="sub"><?php echo $holder[$i]['image_alt']; ?></div>
							</div>
						</a>
				<?php }
				?>	</div>
				</div>
				<?php
			}else {
				echo "-: No Images Added :-";
			}?>

			<?php
		}

		function displayStorys($place_id) {
			/*$db = new DbHandler();
			global $base;
			global $img_arrays;
			$Holder = "";
			$sHolder = $db->getImages($place_id);

			if($sHolder[0]) {
				$holder = $sHolder[1];
				$img_arrays = $holder;
				?>
				<div class="">
					<div class="a-ll-h images a-wrap">
				<?php

				for ($i = 0; $i < sizeof($holder); $i++) {
					$status = "Unverified";
					if($holder[$i]['image_status']==2)
					$status = "Verified";
					?>
						<a href="" data-popup-open="popup-img-v" onclick="v_img(<?php echo $i; ?>)">
							<div class="list-img">
								<img src="<?php echo $base.$holder[$i]['image_path']."/".$holder[$i]['image_name']; ?>" alt="<?php echo $holder[$i]['image_alt']; ?>">
								<div class="sub"><?php echo $holder[$i]['image_alt']; ?></div>
							</div>
						</a>
				<?php }
				?>	</div>
				</div>
				<?php
			}else {
				echo "-: No Place Added :-";
			}?>

			<?php*/
			?>** Something Coming Soon **<?
		}

		function getStateName($state_code){
			$db = new DbHandler();
			$stateName = $db->getState($state_code);
			return $stateName[1];
		}

		function getCityName($id){
			$db = new DbHandler();
			$Name = $db->getCity($id, "name");
			return $Name[1];
		}

/* --------------------------------------------------------------------------------- Forms submit */


		if(isset($_POST['user-state'])) {
			if(isset($_POST['input-state'])&&$_POST['input-state']!=""&&$_POST['input-state']!="-: Select State :-"){
				$state = $_POST['input-state'];
					$result = $db->createUserPref( $user_id, PREF_STATE, $state);
					if($result[0]) {
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

						header('location: '.$actual_link);
					} else {
						$form_errors[] = $result[1];
					}
			} else {
				$form_errors[] = "Please Select proper State";
			}
		}

		if(isset($_POST['user-state-update'])) {
			if(isset($_POST['input-state'])&&$_POST['input-state']!=""&&$_POST['input-state']!="-: Select State :-"){
				$state = $_POST['input-state'];
					$result = $db->updateUserPref( $user_id, PREF_STATE, $state);
					if($result[0]) {
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

						header('location: '.$actual_link);
					} else {
						$form_errors[] = $result[1];
					}
			} else {
				$form_errors[] = "Please Select proper State";
			}
		}

		if(isset($_POST['user-debug'])) {
			if(isset($_POST['input-debug'])&&$_POST['input-debug']!=""){
				$deb = $_POST['input-debug'];
					$result = $db->createUserPref( $user_id, PREF_DEBUG, $deb);
					if($result[0]) {
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

						header('location: '.$actual_link);
					} else {
						$form_errors[] = $result[1];
					}
			} else {
				$form_errors[] = "Invalid Request";
			}
		}

		if(isset($_POST['user-debug-update'])) {
			if(isset($_POST['input-debug'])&&$_POST['input-debug']!=""){
				$deb = $_POST['input-debug'];
					$result = $db->updateUserPref( $user_id, PREF_DEBUG, $deb);
					if($result[0]) {
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

						header('location: '.$actual_link);
					} else {
						$form_errors[] = $result[1];
					}
			} else {
				$form_errors[] = "Invalid Request";
			}
		}
		if(isset($_POST['user-passcode-update'])) {
			if(isset($_POST['form-passcode'])&&$_POST['form-passcode']!=""){
				$passcode = $_POST['form-passcode'];
					$result = $db->updateUserPasscode( $user_id, $passcode);
					if($result[0]) {
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

						header('location: '.$actual_link);
					} else {
						$form_errors[] = $result[1];
					}
			} else {
				$form_errors[] = "Please Enater Passcode";
			}
		}

		if(isset($_POST['feed-form-city'])) {
			if(isset($_POST['form-city-name'])&&$_POST['form-city-name']!=""){
				$city = $_POST['form-city-name'];
				$state = $_POST['form-city-state'];

					$cityId = $db->createCity($city,$state,  $user_id, $debug);
					if($cityId[0]) {
						$url = $base."state/".$state."/city/".$cityId[1];
						header('location: '.$url);
					} else {
						echo $cityId[1];
					}

			} else {
				$form_errors[] = "Plase enter Valid city name";
			}

		}

		if(isset($_POST['feed-form-place'])) {
			$isError = false;
			if ($_POST['form-city']=="") {
				$isError = true;
				$form_errors[] = "Invalid Request";
			} else {
				$city = $_POST['form-city'];
			}

			if ($_POST['form-state']=="") {
				$isError = true;
				$form_errors[] = "Invalid Request";
			} else {
				$state = $_POST['form-state'];
			}

			if ($_POST['form-place-name']=="") {
				$isError = true;
				$form_errors[] = "Enter Place name";
			} else {
				$place_name = $_POST['form-place-name'];
			}

			$place_local_name = $_POST['form-place-local-name'];

			if ($_POST['form-place-description']=="") {
				$isError = true;
				$form_errors[] = "Enter description. (eg. Created on MMM'YY, Founded on MMM'YY.)";
			} else {
				$place_name_dis = $_POST['form-place-description'];
			}

			if ($_POST['form-place-gapi']=="") {
				$isError = true;
				$form_errors[] = "Enter Google Place ID";
			} else {
				$place_gapi = $_POST['form-place-gapi'];
			}

			if ($_POST['input-place-type']=="NA") {
				$isError = true;
				$form_errors[] = "";
			} else {
				$place_type = $_POST['input-place-type'];
			}

			$stateId = $db->getState($state,"id");

			if($stateId[0]) {
				$place_id = $db->createPlace(
					$place_name, $place_local_name, $place_name_dis,
					$city, $place_gapi, $place_type,  $user_id, $debug
				);
				if($place_id[0]) {
					$url = $base."state/".$state."/city/".$city."/".$place_id[1];
					header('location: '.$url);
				} else {
					echo $place_id[1];
				}
			} else {
				echo $stateId[1];
			}
		}

		if(isset($_POST["btnSubmit"])){
			$errors = array();

			$extension = array("jpeg","jpg","png","gif");

			$bytes = 1024;
			$allowedMB = 10 * $bytes;
			$totalBytes = $allowedMB * $bytes;
			$state = $_POST['form-state'];
			$city = $_POST['form-city'];
			$place = $_POST['form-place'];
			$image_name = $_POST['image-name'];
			$image_description = $_POST['image-description'];

			if(isset($_FILES["files"])==false)
			{
				echo "<b>Please, Select the files to upload!!!</b>";
				return;
			}

			$conn = mysqli_connect("localhost","wevandsc_ei","explore_i","wevandsc_ei");

			foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name)
			{
				$uploadThisFile = true;

				$file_name=$_FILES["files"]["name"][$key];
				$file_tmp=$_FILES["files"]["tmp_name"][$key];
				$file_path = "Upload/";

				$ext=pathinfo($file_name,PATHINFO_EXTENSION);

				if(!in_array(strtolower($ext),$extension))
				{
					array_push($errors, "File type is invalid. Name:- ".$file_name);
					$uploadThisFile = false;
				}

				if($_FILES["files"]["size"][$key] > $totalBytes){
					array_push($errors, "File size must be less than 100KB. Name:- ".$file_name);
					$uploadThisFile = false;
				}

				if(file_exists($file_path.$_FILES["files"]["name"][$key]))
				{
					array_push($errors, "File is already exist. Name:- ". $file_name);
					$uploadThisFile = false;
				}

				if($uploadThisFile){
					$filename=basename($file_name,$ext);
					//$newFileName=$filename.$ext;
					$newFileName = $state."_".$city."_".$place."_".rand().".".$ext;
					move_uploaded_file($_FILES["files"]["tmp_name"][$key],$file_path.$newFileName);

					$query = "
								INSERT INTO
					 				images (image_path, image_name, place_id, image_alt, image_discription, add_date, add_by, debug)
								VALUES('".$file_path."', '".$newFileName."', '".$place."', '".$image_name."', '".$image_description."','".timestamp()."', '".$user_id."', '".$debug."')";

					mysqli_query($conn, $query);
					$id = mysqli_insert_id($conn);
				}
			}

			mysqli_close($conn);

			$count = count($errors);

			if($count != 0){
				foreach($errors as $error){
					$form_errors[] = $error."<br/>";
				}
				$db->createUserActivity(
									$user_id, "create", "insertImage()", 1,
									json_encode($form_errors), "images", 0
								);
			} else {
				$db->createUserActivity(
								$user_id, "create", "insertImage()", 2,
								"Uploaded", "images", $id
								);
				echo "<meta http-equiv='refresh' content='0'>";
			}
		}

		$uri = $_SERVER['REQUEST_URI'];
		//echo $uri;
		$uri_array = explode( "/", $uri );
		?>
		<head>
			<title>Explore India - Feed data</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="<?php echo $base; ?>css/app.css?v=1">
			<style>

			</style>
			<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<style>
			#yourBtn{
			   font-family: calibri;
			   width: auto;
			   padding: 2px 10px;
			   -webkit-border-radius: 5px;
			   -moz-border-radius: 5px;
			   border: 1px dashed #BBB;
			   text-align: center;
			   background-color: #DDD;
			   cursor:pointer;
			  }
			  <?
			  $img_name = "";
			  if(isset($uri_array[6])) {
			  	$isPlace = true;
			  	$place_id = $uri_array[6];
			  	$placeR = $db->getPlace($place_id);
			  	$placePImg = $placeR[1]['image_id'];
				//$form_errors[] = json_encode($placeR[1]);
				$temp2 = $db->getImage($placePImg);
				//$form_errors[] = json_encode($temp2[1]);
				$img_name = $temp2[1][0]['image_name'];

			  }
			  ?>
			  .cover {
				  background: url("<?php echo $base.'Upload/'.$img_name; ?>") no-repeat;
				  background-position: center;
				  background-size: cover;
				  margin: 0 auto;
			  }

			</style>
			<script type="text/javascript">

			</script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>
			<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
			<script src="<?php echo $base; ?>js/form-validation.js"></script>
		</head>
		<body>

			<header>
				<div class="h-cont no-padding">
					<div id="h-logo">
						<a href="<?php echo $base; ?>"><h3 id="name">EI</h3></a>
					</div>
					<div id="h-title">
						<h3 id="name">
							Explore India
						</h3>
					</div>
					<div class="h-profile">
						<? if ($user['debug'] == 2) {
							$tempDebugPref = $db->getUserPref($user_id, PREF_DEBUG);
							if ($tempDebugPref[0]) {
								if ($tempDebugPref[1]['pref_value'] == 2) {
									$debugPref = 1;
									$debugPrefType = "Off";
									$debugPrefBtn = "user-debug-update";
								} else {
									$debugPref = 2;
									$debugPrefType = "On";
									$debugPrefBtn = "user-debug-update";
								}
							} else {
								$debugPref = 2;
								$debugPrefType = "On";
								$debugPrefBtn = "user-debug";
							}
						?>
						<form action="" method="post" name="user-debug-f" class="">

									<? //<label for="passcode">Debuging (On / Off):</label> ?>
									<input type="hidden" name="input-debug" value="<? echo $debugPref; ?>">

								<input type="submit" class="h-link" value="<? echo "Debuging ".$debugPrefType;?>" name="<? echo $debugPrefBtn;?>">
						</form>

					<? } ?>
						<a href="<?php echo $base; ?>user" >
							<div class="h-link"><? echo $user["first_name"];?></div>
						</a>
						<a href="<?php echo $base; ?>logout">
							<div class="h-link">Logout</div>
						</a>
					</div>
				</div>
			</header>
			<div id="main-container">
				<div class="container no-padding">
					<?php
					$altState = $db->getUserPref( $user_id, PREF_STATE);

					if (!$altState[0]) {
						?>
						<div class="alert">
							<div class="list-alert">
								Set default "State". <a href="<? echo $base; ?>user" class="card-link">Click here</a>
							</div>
						</div>
				 <?php } ?>
				<?php

	 			$altPasscode = $user['passcode'];
				if ($altPasscode == 0) {
					?>
					<div class="alert">
	 					<div class="list-alert">
	 						Set "Passcode". <a href="<? echo $base; ?>user" class="card-link">Click here</a>
	 					</div>
	 				</div>
	 		 <?php } ?>
			</div>
		</div>
			<?
		//echo json_encode($uri_array);
		 switch ( $uri_array[2] ) {
/* --------------------------------------------------------------------------------- first display */
		 	case '':
				$states = $db->getStates();
				$statesHolder = selectState($states[1]);
				?>
					<div id="main-container">
						<div class="container no-padding">
							<div class="container-title">
								<h1 class="h-title">
									Feed form
								</h1>
								<p>
									Fill the details and submit.
								</p>
							</div>
							<div class="a-ll-h a-wrap">
								<div class="a-ll-v form-style" id="side-1">
									<form action="" method="post" name="feed-form-place" class="feed-form-place">
										<fieldset>
											<div class="a-ll-v form-div container">
												<legend><span class="number">1</span> Location</legend>
												<label for="state">State:</label>
												<select id="state" name="input-state">
													<optgroup label="State">
														<option value="">-: Select State :-</option>
														<?php echo $statesHolder; ?>
													</optgroup>
												</select>
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>

				<?php
				break;
			case 'logout':
			session_start();
			// Set Session data to an empty array
			$_SESSION = array();
			// Expire their cookie files
			if(isset($_COOKIE["email"]) && isset($_COOKIE["token"])) {
				setcookie("email", '', strtotime( '-5 days' ), '/');
			    setcookie("token", '', strtotime( '-5 days' ), '/');
			}
			// Destroy the session variables
			session_destroy();
			// Double check to see if their sessions exists
			if(isset($_SESSION['email'])){
				header("location: message.php?msg=Error:_Logout_Failed");
			} else {
				header("location: $base");
				exit();
			}

				break;


				/* ------------------------------------------------------------------ User */
				case 'user':
				$states = $db->getStates();
				$states = $states[1];
				$statesHolder = "";
				for ($i = 0; $i < sizeof($states); $i++) {
					$selected = "";
					if ($altState[0]&&$altState[1]['pref_value']==$states[$i]['state_code']) {
						$selected = "selected";
					}
					$statesHolder .= "<option value=\"".$states[$i]['state_code']."\" $selected>".$states[$i]['state_name']."</option>
									";
				}
				?>
				<div id="main-container">
					<div class="container no-padding">
						<div class="container-title">
							<h1 class="h-title">
								User Preference - Managment
							</h1>
							<p>
								Update Preference.
							</p>
						</div>
						<div class="a-ll-h a-wrap">
							<div class="a-ll-v form-style" id="side-1">
								<form action="" method="post" name="user-state-f" class="">
									<fieldset>
										<div class="a-ll-v form-div container">
											<legend><span class="number">1</span> User</legend>
											<label for="state">Default State:</label>
											<select name="input-state">
												<optgroup label="State">
													<option value="">-: Select State :-</option>
													<?php echo $statesHolder; ?>
												</optgroup>
											</select>
										</div>
									</fieldset>
									<?
									if ($altState[0]) {
										?>
										<input type="submit" value="Update" name="user-state-update">
										<?
									} else {
										?>
										<input type="submit" value="Submit" name="user-state">
										<?
									}?>
								</form>

								<form action="" method="post" name="user-passcode-f" class="">
									<fieldset>
										<div class="a-ll-v form-div container">
											<label for="passcode">Passcode (4 Digits):</label>
											<input type="password" name="form-passcode" maxlength="4" pattern="[0-9]*" inputmode="numeric" placeholder="****" required>
										</div>
									</fieldset>
									<?
									if ($altPasscode!=0) {
										?>
										<input type="submit" value="Update" name="user-passcode-update">
										<?
									} else {
										?>
										<input type="submit" value="Submit" name="user-passcode-update">
										<?
									}?>
								</form>
								<? if ($user['debug'] == 2) {
									$tempDebugPref = $db->getUserPref($user_id, PREF_DEBUG);
									if ($tempDebugPref[0]) {
										if ($tempDebugPref[1]['pref_value'] == 2) {
											$debugPref = 1;
											$debugPrefType = "Off";
											$debugPrefBtn = "user-debug-update";
										} else {
											$debugPref = 2;
											$debugPrefType = "On";
											$debugPrefBtn = "user-debug-update";
										}
									} else {
										$debugPref = 2;
										$debugPrefType = "On";
										$debugPrefBtn = "user-debug";
									}
								?>
								<form action="" method="post" name="user-debug-f" class="">
									<fieldset>
										<div class="a-ll-v form-div container">
											<label for="passcode">Debuging (On / Off):</label>
											<input type="hidden" name="input-debug" value="<? echo $debugPref; ?>">
										</div>
									</fieldset>
										<input type="submit" value="<? echo $debugPrefType;?>" name="<? echo $debugPrefBtn;?>">
								</form>

							<? } ?>
							</div>
						</div>
					</div>
				</div>
				<?
					break;
/* --------------------------------------------------------------------------------- state display */
		    case 'state':
				$state = $uri_array[3];
				$isCity = false;
				$isPlace = false;
				$city_id = 0;
				$place_id = 0;
				$city_name = "";
				$place = array();
				if(isset($uri_array[4]) && $uri_array[4] =="city") {
					$isCity = true;
					$city_id = $uri_array[5];
					$city_name = getCityName($city_id);
					if(isset($uri_array[6])) {
						$isPlace = true;
						$place_id = $uri_array[6];
						$placeR = $db->getPlace($place_id);
						$place = $placeR[1];
					}
				}
				?>


					<div id="main-container">
						<div class="container no-padding">
							<?php
							if ($form_errors) {
								?>
								<div class="errors">
									<?php
									for ($i=0; $i<sizeof($form_errors);$i++) {
										echo "<div class=\"list-error\">".$form_errors[$i]."</div>";
									} ?>

								</div>
								 <?php } ?>
							<div class="container-title">
								<h1 class="h-title">
									Feed form
								</h1>
								<p>
									Fill the details and submit.
								</p>
							</div>

							<div class="a-ll-h a-wrap">

								<div class="a-ll-v form-style" id="side-1">
										 <div class="a-ll-v">
											<?php
											if(!$isCity) {
												?>
												<div class="a-ll-h a-wrap budget">
													<a href="">
														<div class="container-min list-budget">
															<?php echo getStateName($state); ?>
														</div>
													</a>
												</div>
												<legend><span class="number">1</span>Select City</legend>

	   										 <div class="list-view">
	   											 <?php echo listCities($state); ?>
	   										 </div>
	   										 <legend><span class="number">2</span>Not in list</legend>
	   											 <form action="" method="post" class="feed-form-city" name="feed-form-city">
	   							 					<fieldset>
														<label for="form-city-name">City Name</label>
	   													<input type="text" name="form-city-name" id="form-city-name" placeholder="City Name" required>
	   													<input type="hidden" name="form-city-state" id="form-city-state" value="<?php echo $state; ?>" required>
	   												</fieldset>
	   												<input type="submit" name="feed-form-city" value="Submit" />
	   											</form>
												<?php
											} else {
												?>
												<div class="a-ll-h a-wrap budget">
													<a href="<?php echo $base."state/".$state; ?>">
														<div class="container-min list-budget">
															<?php echo getStateName($state); ?>
														</div>
													</a>
													<?php if(!$isPlace){ ?>
													<a href="">
														<div class="container-min list-budget">
															<?php echo $city_name; ?>
														</div>
													</a>
													<?php
												} else {
														?>
														<a href="<?php echo $base."state/".$state."/city/".$city_id; ?>">
															<div class="container-min list-budget">
																<?php echo $city_name; ?>
															</div>
														</a>
														<a href="">
															<div class="container-min list-budget">
																<?php echo $place['place_name']; ?>
															</div>
														</a>
														<?php
														}
													?>
												</div>
												<legend><span class="number">1</span>Select Place </legend>

												<?php
												echo displayPlaces($city_id);

											}
											?>

									 </div>
								</div>


								<?php
								if($isCity) {
									?>
							<div class="a-ll-v form-style" id="side-2">


						<?php
						if(!$isPlace) {
							?>
							<form action="" method="post" class="feed-form-place">
								<fieldset>
									<input type="hidden" id="form-state" value="<?php echo $state; ?>" name="form-state" required>
							<div class="a-ll-h show-field">
								<div class="container-min">
									<?php echo $city_name; ?>
								</div>
							</div>
							<input type="hidden" name="form-city" value="<?php echo $city_id; ?>" required>
							<legend><span class="number">2</span> Add Place</legend>
							<input type="text" name="form-place-name" placeholder="Place name" required>
							<input type="text" name="form-place-local-name" placeholder="Local name">
							<textarea name="form-place-description" placeholder="Place description" required></textarea>
							<label for="place-type">Place Type:</label>
							<select id="place-type" name="input-place-type" required>
								<optgroup label="Type">
									<option value="NA">-: Select Type :-</option>
									<option value="1">Locality</option>
									<option value="2">Place (Shop, Building ..)</option>
								</optgroup>
							</select>

							<input type="text" name="form-place-gapi" placeholder="Google place id" required>
						  </fieldset>
						  <input type="submit" name="feed-form-place" value="Submit" />
	 				</form>
					  <?php
						} else {
							?>
							<div class="cover">
								<div class="title">
									<?php echo $place['place_name'];?>
								</div>
								<div class="sub">
									<a href="" data-popup-open="popup-img">
										<div class="t-link">
											View all images
										</div>
									</a>
									<a href="" data-popup-open="popup-img-add">
										<div class="t-link">
											Add More images
										</div>
									</a>
								</div>
							</div>
							<div class="container-min">

								<?
								echo displayStorys($place_id);
								/*
							<a href="" data-popup-open="popup-img-add">
								<div class="a-ll-h list-item">
									<div>Add Images</div>
								</div>
							</a>
							*/ ?>
						</div>
							<?php
						}
					 ?>
			</div>
			<?php
		}
	 ?>
		</div>
			  </div>
			</div>

				<?php
	    	break;
  		}
/* --------------------------------------------------------------------------------- End switch*/
		?>
		<div class="cover-footer">
		</div>
		<footer>
		  <div class="container">

			<div class="vrow">
			  <div class="vcol">
				Made with Love
			  </div>
			</div>
		  </div>
		</footer>
		<? if ($state != "") {
			?>
		<div class="popup" data-popup="popup-img">
		<div class="popup-inner">
			<?php
			if($isPlace) {
				echo displayImages($place_id);
				} ?>
				<p><a data-popup-close="popup-img" href="#">Close</a></p>
				<a class="popup-close" data-popup-close="popup-img" href="#">x</a>
		</div>
		</div>
		<div class="popup" data-popup="popup-img-v">
		<div class="popup-inner">
			<div id="view_img">

			</div>
				<p><a data-popup-close="popup-img-v" href="#">Close</a></p>
				<a class="popup-close" data-popup-close="popup-img-v" href="#">x</a>
		</div>
		</div>
		<div class="popup" data-popup="popup-img-add">
		<div class="popup-inner form-style">
			<div class="v-scroll">
			<legend><span class="number">2</span> Place Addon</legend>

			<form method="post" enctype="multipart/form-data" name="formUploadFile" id="uploadForm" action="">
				<fieldset>
				<input type="hidden" id="form-state" value="<?php echo $state; ?>" name="form-state" required>
				<input type="hidden" name="form-city" value="<?php echo $city_id; ?>" required>
				<input type="hidden" name="form-place" value="<?php echo $place_id; ?>" required>
				<div class="a-ll-h a-wrap">
					<div class="container-min">
						<label for="InputFile">Select files to upload:</label>
					</div>
					<div class="container-min">

						<input id="upfile" type="file"  name="files[]" multiple="multiple" required/>
						<div class="progress hide" id="progress"><div class="bar"></div><div class="percent">0%</div></div>
						<div id="image_preview" class="hide" class="container-min"></div>
					</div>


					<div id="status"></div>
					<div class="container-min">
						<p class="help-block"><span class="label label-info">Note:</span> Please, Select the only images (.jpg, .jpeg, .png, .gif) to upload with the size below 10 MB only.</p>
					</div>
				</div>
					<input type="text" name="image-name" placeholder="Name" required>
				<textarea name="image-description" placeholder="Image description" required></textarea>
			  </fieldset>
				<input type="submit" value='submit' name="btnSubmit" onclick="progress()" >
			</form>
		</div>
		<p><a data-popup-close="popup-img-add" href="#">Close</a></p>
		<a class="popup-close" data-popup-close="popup-img-add" href="#">x</a>
		</div>
		</div>
	<? } ?>
<script>
$(document).ready(function() {
  $('input').blur(function() {
    // check if the input has any value (if we've typed into it)
    if ($(this).val())
      $(this).addClass('used');
    else
      $(this).removeClass('used');
  });

});
$(function() {
//----- OPEN
$('[data-popup-open]').on('click', function(e)  {
var targeted_popup_class = jQuery(this).attr('data-popup-open');
$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
e.preventDefault();
});
//----- CLOSE
$('[data-popup-close]').on('click', function(e)  {
var targeted_popup_class = jQuery(this).attr('data-popup-close');
$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
e.preventDefault();
});
});
$('#state').change(function(){
window.location.href = "<?php  echo $base."state/";?>"+($(this).val());
});


	function v_img(pos) {
		var js_array =<?php echo json_encode($img_arrays );?>;
		  //  alert(js_array[pos]['image_path']);
		document.getElementById("view_img").innerHTML = "<img src=\"<?php echo $base;?>"+js_array[pos]['image_path']+js_array[pos]['image_name']+"\">";
	}

$(function() {
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');

	$('form').ajaxForm({
		beforeSend: function() {
			$('#progress').removeClass('hide');
	        status.empty();
	        var percentVal = '0%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal = percentComplete + '%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    success: function() {
	        var percentVal = '100%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
		complete: function(xhr) {
			window.location.reload();
		}
	});

});

$(document).ready(function()
{
	$('input[type=file]').each(function()
	{
		$(this).attr('onchange',"sub(this)");
		$('<div id="yourBtn" onclick="getFile()">click to upload a file</div>').insertBefore(this);
		$(this).wrapAll('<div style="height: 0px;width: 0px; overflow:hidden;"></div>');
	});
});
 function getFile(){
   $('input[type=file]').click();
 }
 function sub(obj){
	 $('#image_preview').removeClass('hide');
	var file = obj.value;
	var fileName = file.split("\\");
	document.getElementById("yourBtn").innerHTML = "Selected";
 }

$("#upfile").change(function(){
     $('#image_preview').html("");
     var total_file=document.getElementById("upfile").files.length;


     for(var i=0;i<total_file;i++)
     {
      $('#image_preview').append("<img src='"+URL.createObjectURL(event.target.files[i])+"'>");
     }


  });
</script>
<? } else {
	?>
	<head>
		<title>Explore India - Feed data</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="<?php echo $base; ?>css/app.css">
		<style>

		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	</head>
	<body>
		<header>
			<div class="h-cont">
				<div id="h-logo">
					<a href="<?php echo $base; ?>"><h3 id="name">EI</h3></a>
				</div>
				<div id="h-title">
					<h3 id="name">
						Explore India
					</h3>
				</div>
			</div>
		</header>
		<div id="main-container">
			<div class="container no-padding">
			<?php
							if ($form_errors) {
								?>
								<div class="errors">
									<?php
									for ($i=0; $i<sizeof($form_errors);$i++) {
										echo "<div class=\"list-error\">".$form_errors[$i]."</div>";
									} ?>

								</div>
								 <?php } ?>
				<div class="container-title">
					<h1 class="h-title">
						Feed form
					</h1>
					<p>
						Fill the details and submit.
					</p>
				</div>
				<div class="a-ll-h a-wrap">
					<div class="a-ll-v form-style" id="side-1">
						<?
						$uri = $_SERVER['REQUEST_URI'];
						//echo $uri;
						$uri_array = explode( "/", $uri );
						//echo json_encode($uri_array);
						 if ( $email == "" ) {
							 ?>
							 <form action="" method="post">
	 							<fieldset>
	 								<div class="a-ll-v form-div container">
	 									<legend><span class="number">1</span> Login</legend>
	 									<label for="email">Email (Same which is used on Slack and Udacity):</label>
	 									<input type="email" name="user-email" placeholder="Email" required>
	 								</div>
	 							</fieldset>
								<input type="submit" value="Submit" name="user-email-f">
	 						</form>
						<? } elseif ($email != "") {
							if ($user_exist){
								if ($pass) {
									?>
									<form action="" method="post" name="user-login" class="user-reg">
										<fieldset>
											<div class="a-ll-v form-div container">
												<legend><span class="number">1</span> Login</legend>
												<input type="hidden" name="user-email" placeholder="Email" value="<? echo $email; ?>" required>
												<label for="passcode">Passcode:</label>
												<input type="password" name="user-passcode" placeholder="****" required>
											</div>
										</fieldset>
										<input type="submit" value="Submit" name="user-login">
									</form>
									<?
								}
							} else {
								?>
						<form action="" method="post" name="user-reg" class="user-reg">
							<fieldset>
								<div class="a-ll-v form-div container">
									<legend><span class="number">1</span> Login</legend>
									<label for="user-from">User from:</label>
									<input type="hidden" name="user-type" value="udacity">
									<select id="type" name="" disabled>
										<optgroup label="Type">
											<option value="udacity" selected>Udacity</option>
										</optgroup>
									</select>
									<label for="email">Email (Same which is used on Slack and Udacity):</label>
									<input type="email" name="" placeholder="Email" value="<? echo $email; ?>" required>
									<input type="hidden" name="user-email" placeholder="Email" value="<? echo $email; ?>">
									<label for="firstname">First Name:</label>
									<input type="text" name="user-firstname" placeholder="First Name" required>
									<label for="lastname">Last Name:</label>
									<input type="text" name="user-lastname" placeholder="Last Name" required>
									<input type="hidden" value="data_feed" name="user-level">
								</div>
							</fieldset>
							<input type="submit" value="Submit" name="user-reg">
						</form>
					<? } } ?>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<div class="container">
				<div class="vrow">
					<div class="vcol">
						Made with Love
					</div>
				</div>
			</div>
		</footer>

	<?}?>
</body>
</html>
