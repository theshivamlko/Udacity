<?

  require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
  $base = "https://ei.wevands.com/feed-data/";
  include ROOT_DIR.'include/DbHandler.php';
  $db = new DbHandler();

if(isset($_GET['token'])&&isset($_GET['state_code'])) {
  $token = $_GET['token'];
  $tokenStatus = $db->checkToken($token);
  if ($tokenStatus[0]){
    $state_code = $_GET['state_code'];
    $result = array();
    $result = $db->getCities($state_code);
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
