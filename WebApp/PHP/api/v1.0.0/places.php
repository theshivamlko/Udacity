<?

  require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
  $base = "https://ei.wevands.com/feed-data/";
  include ROOT_DIR.'include/DbHandler.php';
  $db = new DbHandler();

if(isset($_GET['token'])&&isset($_GET['city_id'])) {
  $token = $_GET['token'];
  $tokenStatus = $db->checkToken($token);
  if ($tokenStatus[0]){
    $city_id = $_GET['city_id'];
    $result = array();
    $result = $db->getPlaces($city_id);
    if ($result[0]) {
      echo json_encode(array('success' => true, 'message' => "Success", 'response' => $result[1]));
    } else {
      echo json_encode(array('success' => false, 'message' => "Oops, error"));
    }
  } else {
    echo json_encode(array('success' => false, 'message' => "Invalid token"));
  }
} else {
  echo json_encode(array('success' => false, 'message' => "Invalide Token or Request"));
}


  ?>
