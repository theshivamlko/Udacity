<!DOCTYPE html>
<html lang="en">

	<?

		require_once '/home/wevandsc/link.wevands.com/ei/feed-data/include/var.php';
		$base = "https://ei.wevands.com/feed-data/";
		include ROOT_DIR.'include/DbHandler.php';
		$db = new DbHandler();

    function showActivities() {
      global $db;
      $act = "";
      $result = $db->getActivities();

      if ($result[0]) {
        $holder = $result[1];
				for ($i = 0; $i < sizeof($holder); $i++) {

					switch ($holder[$i]['activity_type']) {
            case 'user':
            $subHolder .= "
                      <div class=\"a-ll-h list-item\">
                        <div>User registered</div>
                        <div>".$holder[$i]['city_name']."</div>
                        <div class=\" right\">".$status."</div>
                      </div>
                    ";
            break;

            case 'user':
            $subHolder .= "<a href=\"".$base."state/".$state."/city/".$holder[$i]['city_id']."\">
                      <div class=\"a-ll-h list-item\">
                        <div>".$holder[$i]['city_name']."</div>
                        <div class=\" right\">".$status."</div>
                      </div>
                    </a>
                    ";
            break;

            default:
              $subHolder .= "";
              break;
          }


				}

      } else {
				$subHolder = $result[1];
			}
			return $subHolder;
    }
    ?>
    <head>
      <title>Explore India - Feed data</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="<?php echo $base; ?>css/app.css?v=1">

      <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
        </div>
      </header>
      <div id="main-container">

  				<div class="container no-padding t-white">
            <? echo showActivities(); ?>
          </div>
  		</div>
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
    </body>
  </html>
