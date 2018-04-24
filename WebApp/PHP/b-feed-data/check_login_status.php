<?php
session_start();
include_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/Config.php';
include_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
$db_conx = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_CONTROL);
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}
// Files that inculde this file at the very top would NOT require
// connection to database or session_start(), be careful.
// Initialize some vars
$user_ok = false;
$email = "";
$u_token = "";
$token = "";
$log_password = "";
// User Verify function
function evalLoggedUser($conx,$u_token,$u){
	$sql = "SELECT session_id FROM sessions WHERE user_api='$u_token' AND token='$u' AND status='2' LIMIT 1";
    $query = mysqli_query($conx, $sql);
    $numrows = mysqli_num_rows($query);
	if($numrows > 0){
		return true;
	}
}
if(isset($_SESSION["email"]) && isset($_SESSION["token"]) && isset($_SESSION["u_token"])) {
	$email = $_SESSION['email'];
	$token = $_SESSION['token'];
	$u_token = $_SESSION['u_token'];
	// Verify the user
	$user_ok = evalLoggedUser($db_conx,$u_token,$token);
} else if(isset($_COOKIE["email"]) && isset($_COOKIE["token"]) && isset($_COOKIE["u_token"])){
	$_SESSION['email'] = $_COOKIE['email'];
    $_SESSION['token'] = $_COOKIE['token'];
	$_SESSION['u_token'] = $_COOKIE['u_token'];
	$email = $_SESSION['email'];
	$token = $_SESSION['token'];
	$u_token = $_SESSION['u_token'];
	// Verify the user
	$user_ok = evalLoggedUser($db_conx,$u_token,$token);
	if($user_ok == true){
		// Update their lastlogin datetime field
		$now = timestamp();
		$sql = "UPDATE `sessions` SET `login_datetime` = '$now' WHERE token='$token' AND `status` = '2' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
	}
}
?>
