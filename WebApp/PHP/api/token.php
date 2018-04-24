<?

  require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
  $base = "https://ei.wevands.com/feed-data/";
  include ROOT_DIR.'include/DbHandler.php';
  $db = new DbHandler();
  echo generateApiKey();

  ?>
