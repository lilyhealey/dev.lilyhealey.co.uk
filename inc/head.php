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
$r = new Request();

if ($uu->id && is_numeric($uu->id))
{
	$item = $oo->get($uu->id);
	
	if (!$oo->active($uu->id)) 
	{
		$url = "";
		for ($i = 0; $i < sizeof($uu->ids)-1; $i++) 
		{
			if($i == 0)
				$url .= "?object=" . $uu->ids[$i];
			if($i < sizeof($uu->ids)-2) 
				$url .= ",";
		}
		header("location:". $admin_path ."browse.php". $url);
	}
	$name = $item["name1"];
	$title = $name;
}

// parents
$parents = $oo->parents($uu->ids);

// $uu = $oo->objects_to_url($r->objects);
// print_r($u);
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


?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $title; ?></title>
		<link rel="shortcut icon" href="<? echo $host;?>static/icon.png">
	</head>
	<body>
		<div id="page">
		