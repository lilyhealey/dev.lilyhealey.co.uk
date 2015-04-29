<?php
// path to config file
$config = __DIR__."/../../lib/config.php";
require_once($config);

// specific to this 'app'
require_once("url.php");
require_once("request.php");

// logged in user via .htaccess, .htpasswd
$user = $_SERVER['REDIRECT_REMOTE_USER'];
$db = db_connect($user);

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();
$r = new Request();

// Check that selected object exists
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
if ($title)
	$title = $db_name ." | ". $title;
else
	$title = $db_name;
	
function slug($name = "untitled")
{
	$pattern = '/(\A\W+|\W+\z)/';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $name);
	
	$pattern = '/\s+/';
	$replace = '-';
	$tmp = preg_replace($pattern, $replace, $tmp);
	
	$pattern = '/[^-\w]+/';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $tmp);
	return strtolower($tmp);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8">
		<meta http-equiv="Title" content="<?php echo $documentTitle; ?>">
		<meta name="description" content="Open Records Generator 2.0">
		<link rel="shortcut icon" href="<? echo $admin_path;?>media/icon.png">
		<link rel="apple-touch-icon-precomposed" href="<? echo $admin_path;?>media/icon.png">
		<link rel="stylesheet" href="<? echo $admin_path; ?>static/global.css">
	</head>
	<body>
		<div id="page">
			<div id="header-container">
				<div id="header" class="centre">
					<a href="<?php echo $admin_path; ?>browse"><?php 
						echo $db_name ?> db</a>
				</div>
			</div>