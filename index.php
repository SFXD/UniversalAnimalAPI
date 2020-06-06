<?php
$uri = $_SERVER['REQUEST_URI'];
$base = explode('/', trim($uri, " /"))[0];

if (getenv("PROTOCOL") !== null) {
    $proto = getenv("PROTOCOL");
}
else {
    $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
}
$host = $proto . "://$_SERVER[HTTP_HOST]/";

if (!(file_exists("./animals/$base") && is_dir("./animals/$base")))
{
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	include('404.php');
	die();
}

echo <<< EOT
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
	<p>$base</p>
	</body>
</html>
EOT;
?>
