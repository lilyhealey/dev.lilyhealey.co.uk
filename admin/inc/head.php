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
$rr = new Request();

// parents
$parents = $uu->parents();

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

//$flat = $oo->traverse(0);
//$nav = nav($flat, $uu->ids);
$nav = $oo->nav($uu->ids);
?><!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8">
		<meta name="description" content="anglophile">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link rel="shortcut icon" href="<? echo $admin_path;?>media/icon.png">
		<link rel="apple-touch-icon-precomposed" href="<? echo $admin_path;?>media/icon.png">
		<link rel="stylesheet" href="<? echo $admin_path; ?>static/main.css">
	</head>
	<body>
		<div id="page">
			<div id="header-container">
				<header class="centre">
					<div>
						<span id="date"><?php 
							echo strtolower(date("l, d M Y H:i (T)")); 
						?></span>
					</div>
					<div id="nav">
						<a href="<?php echo $admin_path; ?>browse"><?php 
						echo $db_name ?> db</a>
						<div class="nav-level"><?
						$prevd = $nav[0]['depth'];
						foreach($nav as $n)
						{
							$d = $n['depth'];
							if($d > $prevd)
							{
							?><div class="nav-level"><?
							}
							else
							{
								for($i = 0; $i < $prevd - $d; $i++)
								{
								?></div><?
								}
							}
							?><div>
								<a href="<? echo $admin_path.'browse/'.$n['url']; ?>"><?
									echo $d.". ".$n['name'];
								?></a>
							</div><?
							$prevd = $d;
						}
						?></div>
					</div>
				</header>
			</div>