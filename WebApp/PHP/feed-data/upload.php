<?php

 require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
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

				$query = "	INSERT INTO
				 				images (image_path, image_name, place_id, image_alt, image_discription, image_add_date, image_add_by)
							VALUES('".$file_path."', '".$newFileName."', '".$place."', '".$image_name."', '".$image_description."','".timestamp()."', 'test')";

				mysqli_query($conn, $query);
			}
		}

		mysqli_close($conn);

		$count = count($errors);

		if($count != 0){
			foreach($errors as $error){
				echo $error."<br/>";
			}
		}
	}
?>
