<?

$t = 0;

$oarr = $ob->get($u->id);

?>
<div>leh</div>
<div><? print_r($oarr); ?></div>
<div><? 
$list = $ob->traverse(0);
$list2 = nav($list, "");
//print_r($list);
$urls = array();
$prevd = 0;
$t = "&nbsp;&nbsp;&nbsp;&nbsp;";
foreach($list2 as $o)
{
	$d = $o['depth'];
	$tab = "";
	for($i = 1; $i < $d; $i++)
		$tab.= $t;
?><div><?
	//echo $d.". ";
	echo $tab;
?><a href="<? echo $host.$o['url']; ?>"><?
	echo $o['name']." ";
?></a><?
?></div><?
	$prevd = $d;
}
?></div>