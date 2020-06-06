<?php
if (getenv("PROTOCOL") && getenv("PROTOCOL") != "") {
    $proto = getenv("PROTOCOL");
}
else {
    $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
}
$host = $proto."://$_SERVER[HTTP_HOST]/";

$uri = $_SERVER['REQUEST_URI'];
$base = explode('/', trim($uri, " /"))[0];

# If the animal folder doesn't exist, return a nice 404 page
if (!(file_exists("./animals/$uri") && is_dir("./animals/$uri")))
{
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	include('404.php');
	die();
}

# Root url
if ($uri == "/"){
	$cat_count = count(glob("./animals/cats/*"));
	$possum_count = count(glob("./animals/possums/*"));
	$raccoon_count = count(glob("./animals/raccoons/*"));
	
	echo <<<EOT
	<html lang='en'>
	<head>
	<title>Random Animals!</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Galen Guyer">
	<meta name="description" content="Get a bunch of random animal pics!" />
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
		<h1>Hey look! It's a bunch of random animals!</h1>
	</header>
	<h3>Currently serving:</h3>
	<p><a href="/cats/">$cat_count cats!</a></p>
	<p><a href="/possums/">$possum_count possums!</a></p>
	<p><a href="/raccoons/">$raccoon_count raccoons!</a></p>
	</body>
	</html>
	EOT;
	die();
}
# Raw endpoint
else if (trim($uri, "/") == ($base . "/raw")) {
	$files = glob("./animals/$base/*");
	$file = substr($files[array_rand($files)], 2);
	header("Content-Type: text/plain");
	echo $host.$file;
	die();
}
# JSON endpoint
else if (trim($uri, "/") == ($base . "/json")) {
	$files = glob("./animals/$base/*");
	$file = substr($files[array_rand($files)], 2);
	header("Content-Type: application/json");
	echo "{\"link\": \"$host$file\"}";
	die();
}
# Pretty endpoint
else if (trim($uri, "/") == ($base)) {
	$files = glob("./animals/$base/*");
	$file = substr($files[array_rand($files)], 2);
	$singular = rtrim($base, "s");
	$usingular = ucwords($singular);
	header("Content-Type: text/html");
	echo <<< EOT
	<html lang='en'>
	<head>
		<title>Random $usingular!</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Galen Guyer">
		<meta name="description" content="Get a random $singular!" />
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
			<h1>Hey look! It's a random $singular!</h1>
		</header>
		<img src="$host$file"></img>
		<p>Permalink: <a href="$host$file">$host$file</a></p>		
	</body>
	</html>
	EOT;
	die();
} 
?>