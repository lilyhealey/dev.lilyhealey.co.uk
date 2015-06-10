<?php
// path to config file
$config = __DIR__."/../lib/config.php";
require_once($config);

// specific to this 'app'
require_once("url.php");
require_once("request.php");

$db = db_connect("guest");

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();
// $rr = new Request();

// self
if($uu->id)
	$item = $oo->get($uu->id);
else
	$item = $oo->get(0);
$name = strip_tags($item["name1"]);

// document title
$item = $oo->get($uu->id);
$title = $item["name1"];
$db_name = "leh";
if ($title)
	$title = $db_name ." | ". $title;
else
	$title = $db_name;

$nav = $oo->nav($uu->ids);

?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $title; ?></title>
		<link rel="shortcut icon" href="<? echo $host;?>static/icon.png">
		<link rel="stylesheet" href="<? echo $host; ?>static/main.css">
	</head>
	<body>
		<div id="page">
			<header>
				<h1><a href="<? echo $host; ?>">lily healey</a></h1>
				<ul><?
				$prevd = $nav[0]['depth'];
				foreach($nav as $n)
				{
					$d = $n['depth'];
					if($d > $prevd)
					{
					?><ul class="nav-level"><?
					}
					else
					{
						for($i = 0; $i < $prevd - $d; $i++)
						{ ?></ul><? }
					}
					?><li>
						<a href="<? echo $host.$n['url']; ?>"><?
							echo $n['name'];
						?></a>
					</li><?
					$prevd = $d;
				}
				?></ul>
			</header>