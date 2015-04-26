<?php
require_once("config.php");
require_once("url.php");

$org_path = __DIR__."/../admin/inc/";
require_once($org_path."model.php");
require_once($org_path."objects.php");
require_once($org_path."wires.php");
require_once($org_path."media.php");
// require_once($org_path."url.php");
require_once($org_path."request.php");

require_once("lib.php");

$db = db_connect();

$ob = new Objects();
$mm = new Media();
$ww = new Wires();
$u = new URL();
$r = new Request();

if ($u->id && is_numeric($u->id))
{
	$item = $ob->get($u->id);
	
	if (!$ob->active($u->id)) 
	{
		$url = "";
		for ($i = 0; $i < sizeof($u->ids)-1; $i++) 
		{
			if($i == 0)
				$url .= "?object=" . $u->ids[$i];
			if($i < sizeof($u->ids)-2) 
				$url .= ",";
		}
		header("location:". $admin_path ."browse.php". $url);
	}
	$name = $item["name1"];
	$title = $name;
}

// parents
$parents = $ob->parents($u->ids);

// $u = $ob->objects_to_url($r->objects);
// print_r($u);
// self
if($u->id)
	$item = $ob->get($u->id);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);

// document title
$item = $ob->get($u->id);
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
	</head>
	<body>
		<div id="page">
		