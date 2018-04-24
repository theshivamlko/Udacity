<?php
define('ROOT_DIR','/home/wevandsc/link.wevands.com/ei/feed-data/');
define('PREF_STATE','state');
define('PREF_DEBUG', 'debug');

$base = "https://ei.wevands.com/feed-data/";


function timestamp(){
	$tz = 'Asia/Calcutta';
	$timestamp = time();
	$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
	$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
	return $dt->format('Y.m.d H:i:s');
}
function generateApiKey() {
	return md5(uniqid(rand(), true));
}
 ?>
