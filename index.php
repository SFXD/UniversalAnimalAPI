<?php
	$files = array_diff(glob("./*"), glob("./*php"));
	$file = substr($files[array_rand($files)], 2);
	$animal = end(explode('/', trim(getcwd(), " /")));
	$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . $animal . "/";
?>

<html lang='en'>

<head>
	<title><?php echo ucwords("Random " . $animal . "!")?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Galen Guyer">
	<meta name="description" content="Get a random <?php echo substr($animal, 0, -1) ?> pic!" />
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
		<h1>Hey look! It's a random <?php echo substr($animal, 0, -1) ?>!</h1>
	</header>
	<img src="<?php echo $file ?>"></img>
	<p>Permalink: <a href="<?php echo ($base . $file)?>"><?php echo ($base . $file)?></a></p>
</body>

</html>