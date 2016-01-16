<?
$d = getdate();
$h = $d['hours'];
$m = $d['minutes'];
$s = $d['seconds'];

$is_morning = $h < 12 ? true : false;

$n = (((($s / 60.0) + $m) / 60.0) + $h) / 24.0;
$seconds_left = (60*60*24) - ($s + ($m + ($h * 60) * 60));

if($is_morning)
{
	$seconds_left -= 60*60*12;
	$to = 255;
}
else
	$to = 0;

$from = floor($seconds_left / (60*60*12) * 256);
$from = "rgba($from, $from, $from, 1)";
$to = "rgba($to, $to, $to, 1)";

?><!DOCTYPE html>
<html>
	<head>
		<title>clock</title>
		<style>
			body {
				margin: 0;
				
				position: fixed;
				width: 100%;
				height: 100%;
				
				/*
				animation-name: bg;
				-webkit-animation-name: bg;
				animation-duration: <? echo $seconds_left; ?>s;
				-webkit-animation-duration: <? echo $seconds_left; ?>s;
				animation-timing-function: linear;
				-webkit-animation-timing-function: linear;	
				*/
			}
			#clock-canvas {
				position: absolute;
				top: 50%;
				
				transform: translateY(-50%);
				-webkit-transform: translateY(-50%);
				-ms-transform: translateY(-50%);
			}
			
			@keyframes bg {
				from {
					background-color: <? echo $from; ?>;
				}
				to {
					background-color: <? echo $to; ?>;
				}
			}
			
			@-webkit-keyframes bg {
				from {
					background-color: <? echo $from; ?>;
				}
				to {
					background-color: <? echo $to; ?>;
				}
			}
		</style>
		<script type="text/javascript" src="static/js/clock.js"></script>
	</head>
	<body>
		<canvas id="clock-canvas"></canvas>
		<script>init_clock("clock-canvas");</script>
	</body>
</html>