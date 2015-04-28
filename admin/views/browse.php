<?php

if($u->id)
	$item = $ob->get($u->id);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);

$full = traverse(0);
$traversed = traverse($u->id);
$url_root = $admin_path.'browse';
if($u->urls())
	$url_root.="/".$u->urls();
//$nav = nav($traversed, $url_root."/");
$nav = nav($traversed, "");
//$fullnav = nav($full, $admin_path."browse/");
$fullnav = nav($full, "");

?>
<div id="body-container">
	<div id="nav" class="centre"><?
		$t = "&nbsp;&nbsp;&nbsp;&nbsp;";
		$prevd = $fullnav[0]['depth'];
		foreach($fullnav as $n)
		{
			$d = $n['depth'];
// 			if($d > $prevd)
// 				continue;
			$tab = "";
			for($i = 1; $i < $d; $i++)
				$tab.= $t;
			?><div><?
				echo $tab;
				?><a href="<? echo $n['url']; ?>"><?
					echo $n['name'];
				?></a>
			</div><?
			$prevd = $d;
		}
	?></div>
	<div id="body" class="centre">
		<div class="parent-container"><?php 
			for($i = 0; $i < count($parents); $i++) 
			{ 
			?><div class="parent">
				<a href="<?php echo $parents[$i]['url']; ?>"><? 
					echo $parents[$i]['name'];
				?></a>
			</div><?php 
			} 
		?></div>
		<div class="self-container">
			<div class="self"><?php 
				if($u->id) { ?>
				<span><?php echo $name; ?></span>
				<span>
					<a href="<? echo $admin_path; ?>edit/<?php echo $u->urls(); ?>">edit</a>
				</span>
				<span>
					<a href="<? echo $admin_path; ?>delete/<?php echo $u->urls(); ?>">delete</a>
				</span><?php } 
			?></div>
		</div>
		<div class="children-container"><?php
			$t = "&nbsp;&nbsp;&nbsp;&nbsp;";
			$prevd = $nav[0]['depth'];
			$count = 0;
			for($i = 0; $i < count($nav); $i++)
			{
				$d = $nav[$i]['depth'];
				if($d > $prevd)
					continue;
				$tab = "";
				for($j = 1; $j < $d; $j++)
					$tab.= $t;
			?><div class="child">
				<span><? echo ++$count; ?></span>
				<span><?
					echo $tab;
					$url_root = $admin_path."browse";
					if($u->urls())
						$url_root.="/".$u->urls();
					?><a href="<? echo $url_root."/".$nav[$i]['url']; ?>"><?
						echo $nav[$i]['name'];
					?></a>
				</span>
				<span><?
					$url_root = $admin_path."edit";
					if($u->urls())
						$url_root.="/".$u->urls();
					?><a href="<? echo $url_root."/".$nav[$i]['url']; ?>">edit</a>
				</span>
				<span><?
					$url_root = $admin_path."delete";
					if($u->urls())
						$url_root.="/".$u->urls();
					?><a href="<? echo $url_root."/".$nav[$i]['url']; ?>">delete</a>
				</span>
			</div><?
				$prevd = $d;
			}
		?></div>
		<div class="actions">
			<a href="<? echo $admin_path; ?>add/<?php echo $u->urls(); ?>">add object</a>
			<a href="<? echo $admin_path; ?>link/<?php echo $u->urls(); ?>">link</a>
		</div>
	</div>
</div>