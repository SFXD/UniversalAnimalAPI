<?php
$files = glob("./animals/**/*");
$file = substr($files[array_rand($files)], 2);

if (getenv("PROTOCOL")) {
    $proto = getenv("PROTOCOL");
}
else {
    $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
}
$host = $proto . "://$_SERVER[HTTP_HOST]/";

echo <<<EOF
<html lang='en'>
	<head>
		<title>Random Animals!</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Galen Guyer">
		<meta name="description" content="Get a random animal picture!" />
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
			<h1>Oops, it looks like that file doesn't exist!</h1>
        </header>
        <h3>To make it up to you, here's a random animal</h3>
        <img src="$host$file"></img>
        <p>Permalink: <a href="$host$file">$host$file</a></p>
		</body>
</html>
EOF;
?>
