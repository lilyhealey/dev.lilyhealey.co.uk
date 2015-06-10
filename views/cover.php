<?
$oarr = $oo->get($uu->id);
$okeys = array("name1", "body", "deck");
$marr = $oo->media($uu->id);
?><section id="body"><?
foreach($okeys as $k)
{
?><div id="<? echo $k; ?>"><? 
	echo $oarr[$k]; 
?></div><?
} 
foreach($marr as $m)
{
	$mfile = m_url($m);
	?><div><img src="<? echo $mfile;?>"></div><?
}
?></section>