<?

  require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
  $base = "https://ei.wevands.com/feed-data/";
  include ROOT_DIR.'include/DbHandler.php';
  $db = new DbHandler();
// 16c4ac08f29891acf46b51b89e07abf9
if(isset($_GET['token'])) {
  $token = $_GET['token'];
  $tokenStatus = $db->checkToken($token);
  if ($tokenStatus[0]){
    $result = array();
    //echo json_encode($db->getState2("UP"));
    $result = $db->getStates();
    if ($result[0]) {
      $holder = $result[1];
      for ($i = 0; $i < sizeof($holder); $i++) {
        $temp_123 = $holder[$i]['img'];

        $holder[$i]['img'] = "https://ei.wevands.com/uploads/".$temp_123;
      }
      echo json_encode(array('success' => true, 'message' => "Success", 'response' => $holder));
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
