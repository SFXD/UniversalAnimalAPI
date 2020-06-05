<?php
	$uri = $_SERVER['REQUEST_URI'];
	$base = explode('/', trim($uri, " /"))[0];
?>

<html lang='en'>

<head>
	<title>Random Shitposts!</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Galen Guyer">
	<meta name="description" content="Get a random shitpost!" />
	<style>
		body {
			font-family: Arial, Helvetica, sans-serif;
			text-align: center;
		}
		img {
			max-width: 90vw;
			max-height: 70vh;
		}
	</style>
</head>

<body>
	<header>
		<h1>Hey look! It's a random shitpost!</h1>
	</header>
	<p></p>
	</body>
	<?php echo $base ?>
</html>