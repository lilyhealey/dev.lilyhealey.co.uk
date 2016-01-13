<?php
$uri = explode('/', $_SERVER['REQUEST_URI']);
if($uri[1] == "video")
	require_once("video.php");
elseif($uri[1] == "clock")
	require_once("clock.php");
else
{
	require_once("inc/head.php");
	require_once("views/cover.php");
	require_once("inc/foot.php");
}
?>