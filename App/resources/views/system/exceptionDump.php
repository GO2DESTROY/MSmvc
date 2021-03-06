<!DOCTYPE HTML>
<html>
<head>
	<title>MSmvc Exception</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<style>
		body {
			background-color: #dbdbdb;
			font-family: 'Open Sans', sans-serif;
		}

		.container {
			margin-top: 10px;
			margin-left: auto;
			margin-right: auto;
			width: 600px;
			border: 1px solid black;
			border-radius: 25px;
			padding: 20px;
		}

		.title {
			background-color: whitesmoke;
			width: 100%;
			display: block;
			border-bottom: 1px solid black;
			margin-left: -20px;
			margin-top: -20px;
			padding-left: 20px;
			padding-right: 20px;
			padding-top: 1px;
			border-top-right-radius: 25px;
			border-top-left-radius: 25px;
		}

		.content {
			background-color: ghostwhite;
			margin-left: -20px;
			padding-top: 10px;
			margin-right: -20px;
			padding-left: 20px;
			padding-right: 20px;
			padding-bottom: 20px;
			margin-bottom: -20px;
			border-bottom-right-radius: 25px;
			border-bottom-left-radius: 25px;
			font-size: 12px;
		}

		.highlight {
			font-size: 14px;
			color: coral;
		}

		li {
			display: block;
		}

		ul {
			padding-left: 15px;
		}
	</style>
</head>
<body>
<div class="container">
	<div class="title"><h2>Exception thrown..</h2></div>
	<div class="content">

		<span class="highlight">Message:</span> <span><?php echo $message; ?></span><br/>
		<span class="highlight">Code:</span> <span><?php echo $code; ?></span><br/>
		<span class="highlight">Line:</span> <span><?php echo $line; ?></span><br/>
		<span class="highlight">File:</span> <span><?php echo $location; ?></span><br/>
		<span class="highlight">Date:</span> <span><?php echo $date; ?></span><br/>
		<span class="highlight">Backtrace:</span>
		<span>
		<?php echo dumpArray($backtrace); ?>
			</span>
	</div>
</div>
</body>
</html>