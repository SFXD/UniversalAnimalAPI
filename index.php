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
if (!(file_exists("./animals/$base") && is_dir("./animals/$base")))
{
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	include('404.php');
	die();
}

# Root url
if ($uri == "/"){
	$dog_count = count(glob("./animals/dogs/*"));
	$cat_count = count(glob("./animals/cats/*"));
	$wolf_count = count(glob("./animals/wolves/*"));
	$possum_count = count(glob("./animals/possums/*"));
	$raccoon_count = count(glob("./animals/raccoons/*"));
	$bird_count = count(glob("./animals/birds/*"));

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
	<p><a href="/dogs/">$dog_count dogs!</a></p>
	<p><a href="/cats/">$cat_count cats!</a></p>
	<p><a href="/wolves/">$wolf_count wolves!</a></p>
	<p><a href="/possums/">$possum_count possums!</a></p>
	<p><a href="/raccoons/">$raccoon_count raccoons!</a></p>
	<p><a href="/birds/">$bird_count birds!</a></p>
	<script data-goatcounter="https://randomanimals.goatcounter.com/count"
		async src="//gc.zgo.at/count.js"></script>
	</body>
	</html>
	EOT;
	die();
}
# Raw endpoint
else if (trim($uri, "/") == ($base . "/raw")) {
	$files = glob("./animals/$base/*");
	$file = str_replace(" ", "%20", substr($files[array_rand($files)], 2));
	header("Content-Type: text/plain");
	echo $host.$file;
	die();
}
# JSON endpoint
else if (trim($uri, "/") == ($base . "/json")) {
	$files = glob("./animals/$base/*");
	$file = str_replace(" ", "%20", substr($files[array_rand($files)], 2));
	header("Content-Type: application/json");
	echo "{\"link\": \"$host$file\"}";
	die();
}
# Pretty endpoint
else if (trim($uri, "/") == ($base)) {
	$files = glob("./animals/$base/*");
	$file = str_replace(" ", "%20", substr($files[array_rand($files)], 2));
	$singulars = [
		"dogs" => "dog",
		"cats" => "cat",
		"possums" => "possum",
		"raccoons" => "raccoon",
		"birds" => "bird",
		"wolves" => "wolf"
	]
	$singular = $singulars[$base]
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
			html, body {
				margin: 0px;
				padding: 0px;
				height: 100%;
			}
			h1 {
				margin-top: 0px;
				padding-top: 12px;
			}
			#container {
				font-family: Arial, Helvetica, sans-serif;
				text-align: center;
				min-height: 100%;
				position: relative;
			}
			#main {
				padding-bottom: 32px;
			}
			img {
				max-width: 90vw;
				max-height: 70vh;
			}
			#footer {
				position: absolute;
				bottom: 0;
				width: 100%;
				height: 32px;
				text-align: center;
				color: #bebebe;
			}
			#footer a {
				color: #bebebe;
				text-decoration: none;
			}
		</style>
	</head>

	<body>
	<div id="container">
	<div id="header">
		<h1>Hey look! It's a random $singular!</h1>
	</div>
	<div id="main">
			<img src="$host$file"></img>
			<p>Permalink: <a href="$host$file">$host$file</a></p>
	</div>
	<div id="footer">
		<a href="./raw">/raw</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="./json">/json</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="https://github.com/galenguyer/UniversalAnimalApi">source</a>
	</div>
		<script data-goatcounter="https://randomanimals.goatcounter.com/count"
			async src="//gc.zgo.at/count.js"></script>
	</body>
	</html>
	EOT;
	die();
}
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
include('404.php');
die();
?>
