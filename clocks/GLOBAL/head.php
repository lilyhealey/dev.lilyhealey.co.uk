<?php 
date_default_timezone_set("America/New_York");
require_once("_Library/systemDatabase.php"); 


        // Parse $id

        $id = $_REQUEST['id'];          // no register globals
        if (!$id) $id = "1";
        $ids = explode(",", $id);
        $idFull = $id;
        $id = $ids[count($ids) - 1];
        $pageName = basename($_SERVER['PHP_SELF'], ".php");
	if (!$pageName) $pageName = "index";

	if ($documentTitle) {
	$documentTitle = "I-n-t-e-r-f-a-c-e? / ". $documentTitle;
	} else {
	$documentTitle = "I-n-t-e-r-f-a-c-e";
	}
	if ($pageName == "voice") $documentTitle = "/";
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>

<!DOCTYPE html
	PUBLIC "-//W3C//Dtd XHTML 1.0 Transitional//EN"
	"http://www.w3.org/tr/xhtml1/Dtd/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title><?php echo $documentTitle; ?></title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8" />
		<meta http-equiv="Title" content="<?php echo $documentTitle; ?>" />
		<link rel="stylesheet" type="text/css" media="all" href="GLOBAL/global.css" />
		<script type="text/javascript" src="GLOBAL/global.js"></script>
		<script src="JS/processing.min.js"></script>
	</head>
	<body<?php 
	if ($pageName == "library.html") { 
		echo " style='background-color:#999;' "; 
	} else { 
		echo " style='background-color:#FFF;' "; 
	} 
	?>
	>
	<!-- WOULD BE VERY GOOD TO MAKE A WINDOW CLOSER IN BODY TAG IF ANY POPUPS OPEN --> 
	<!--<body onload="" onblur='' onunload=''>-->


<?php
if ($pageName && !$stripped) { 
	if ($pageName != "library") { ?>
	
	

		<!--  LIBRARY  -->

		<div class='boxLibrary'>
		<div style='padding: 10px;'>
		<div class='green'>
		<!--<a href='library.html' title='Enter the Library'><img src='MEDIA/library.gif' style='width: 60px; height: 60px;' alt='Library' title='Enter the Library' /></a> -->
		<a href='library.html' title='Enter the Library'>Readings</a>
		</div></div></div>

		<?php 
		} 
		?>





	<!--  ADDRESS  -->

	<div>
	<ul>
	<li><a href="index.html" title="Show">I-n-t-e-r-f-a-c-e</a></li>
	<li>VIS 415, Advanced Graphic Design</li>
	<li>Princeton University</li>
	<li>185 Nassau Room 303</li>
	<li>Tue 1:30-4:20 pm</li>
	<li class='black'><a href='mailto:reinfurt@princeton.edu'>David Reinfurt</a></li>
	<li>&nbsp;</li>
	</ul>




	<!--  MENU  -->

	<ul>

	<?php

	if ($id) {


		// TEST FOR SECOND LEVEL
		
		$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
		$sql   .= "WHERE wires.toid = '$id' AND wires.fromid = objects.id ";
		$sql   .= "AND wires.active=1 AND objects.active=1 LIMIT 1";
		$result =  MYSQL_QUERY($sql);
		$myrow = MYSQL_FETCH_ARRAY($result);
		
		if ($myrow["name1"] != 'News') { 
			$SecondLevel = 'true';
			$PreviousLevel = $myrow["name1"];
			$PreviousLevelId = $myrow['objectsId'];
		}
		
		
		// $ID SELECTED, SHOW ABBREVIATED MENU

		$sql    = "SELECT  *, objects.id AS objectsId FROM objects ";
		$sql   .= "WHERE objects.id = '$id' AND objects.active = 1 ";
		$sql   .= "LIMIT 1";
		$result =  MYSQL_QUERY($sql);
		$myrow = MYSQL_FETCH_ARRAY($result);
	

		// HILITE SELECTION
	
		echo "\n<li><a href='". $pageName .".html?id=". $myrow['objectsId'] ."'>". date("j F Y") ." / </a>";
		if ($SecondLevel) {
			if ($PreviousLevel) echo "<a href='". $pageName .".html?id=". $PreviousLevelId ."'>".$PreviousLevel. " / ";
		}
		
		
		echo "<a href='". $pageName .".html?id=". $myrow['objectsId'] ."'>" . $myrow["name1"] ."</a></li>";
		

		
		

		// GO BACK
	
		echo "\n<li><a href='index.html'>Go back</a></li>";
		echo "\n<li>&nbsp;</li>";
		//echo "\n</ul>";
	}
	else if ($pageName != "sponsors") {

		// NO $ID SELECTED, SHOW FULL MENU
	
		$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
		$sql   .= "WHERE wires.fromid = 8 and wires.toid = objects.id ";
		$sql   .= "AND wires.active = 1 AND objects.active = 1 ";
		$sql   .= "ORDER BY objects.rank DESC";
		$result =  MYSQL_QUERY($sql);

		$i = 0;
		while ($myrow = MYSQL_FETCH_ARRAY($result)) {
			echo "\n<li class='".$myrow["url"]."'><a href='". $pageName .".html?id=". $myrow["objectsId"] ."'>". date("j F Y", strtotime($myrow["begin"])) ." / ";
			echo $myrow["name1"] ."</a></li>";
			$i++;
		}
	
		echo "\n<li>&nbsp;</li>";
	}
	?>
	
	</ul>

<?php 
} 
?>
