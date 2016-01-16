<?php
$uri = explode('/', $_SERVER['REQUEST_URI']);
if($uri[1] == "video")
	require_once("views/video.php");
elseif($uri[1] == "clock")
	require_once("views/clock.php");
else
{
	require_once("views/head.php");
	require_once("views/cover.php");
	require_once("views/foot.php");
}
?>