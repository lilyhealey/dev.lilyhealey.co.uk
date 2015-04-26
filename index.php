<?php
require_once("inc/head.php");
$t = 0;
// function traverse($n)
// {
// 	global $t;
// 	global $ob;
// 	$ca = $ob->children_all($n);
// 	if(empty($ca[0]))
// 		return;
// 	else
// 	{
// 		$t++;
// 		foreach($ca as $child)
// 		{
// 			$cid = $child['id'];
// 			$cname = $child['name1'];
// 			$s = "";
// 			for($i = 1; $i < $t; $i++)
// 				$s.="&nbsp;&nbsp;&nbsp;&nbsp;";
// 			echo "<div>".$s.$cname."</div>";
// 			traverse($cid);
// 		}
// 		$t--;
// 	}
// }
$ca = $ob->children_all(0);
?>hello, world?</br><?
echo $u->id;
echo $u->url;
print_r($u->ids);
print_r($u->urls);
traverse(0);
echo display_nav();
require_once("inc/foot.php");
?>