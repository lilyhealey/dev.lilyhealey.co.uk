<!DOCTYPE html>
<html>
	<head>
		<title>seven test</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			html, body {
				height: 100%;
				margin: 0px;
				background-color: #000;
			}
			#wyoscan {
				max-width: 500px;
				margin-left: auto;
				margin-right: auto;
				display: block;
				position: relative;
				top: 50%;
				-webkit-transform: translateY(-50%);
 				-ms-transform: translateY(-50%);
  				transform: translateY(-50%);
			}
		</style>
	</head>
	<body>
		<object id="wyoscan" data="wyoscan.svg" type="image/svg+xml"></object>
		<script src="wyoscan.js"></script>
		<script>setup('wyoscan');</script>
	</body>
</html>