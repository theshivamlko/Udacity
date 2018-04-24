 <!DOCTYPE html>
<html lang="en">
	<?php
		require_once '/home/wevandsc/link.wevands.com/ei/data/include/var.php';
		$base = "https://ei.wevands.com/data/";
		include ROOT_DIR.'include/DbHandler.php';
		$db = new DbHandler();
		?>
		<head>
			<title>Explore India - Feed data</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="<?php echo $base; ?>css/app.css">
			<style>
				html {
					background: url("<?php echo $base; ?>img/adventure-2178442_mini.jpg") no-repeat center center fixed;
					-webkit-background-size: cover;
					  -moz-background-size: cover;
					  -o-background-size: cover;
					  background-size: cover;
				}
				#main-container{
					height: 100%;
				}
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

			</div>

			<footer>
	  			<div class="container-min">
					<div class="vrow">
						<div class="vcol">
							Made with Love
						</div>
					</div>
				</div>
			</footer>
			<script>
				
			</script>
		</body>
</html>
