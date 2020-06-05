<html lang='en'>

<head>
	<title><?php echo ucwords("Random Animals!")?></title>
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
	<p><a href="/cats/"><?php echo count(array_diff(glob("./cats/*"), glob("./cats/*php"))) ?> cats!</a></p>
</body>

</html>