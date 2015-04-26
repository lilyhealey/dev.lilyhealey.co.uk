<?

$list = array();
$d = 0;

function traverse($n)
{
	global $ob;
	global $d;
	global $list;
	$ca = $ob->children_all($n);
	if(empty($ca[0]))
		return;
	else
	{
		$d++;
		foreach($ca as $c)
		{
			$list[] = array($c, $d);
			traverse($c['id']);		
		}
		$d--;
	}
}

function display_nav()
{
	$html = "";
	global $list;
	global $host;
	$urls = array();
	$prevd = 0;
	foreach($list as $o)
	{
		
		//$urls[] = $o[0]['url'];
		$name = $o[0]['name1'];
		$d = $o[1];
		$pops = $prevd - $d + 1;
		for($i = 0; $i < $pops; $i++)
			array_pop($urls);
		$urls[] = $o[0]['url'];
		$url = implode("/", $urls);
		$url = "http://".$host.$url;
		$html.="<div><a href='".$url."'>".$d.". ".$name."</a></div>";
		$prevd = $d;
		
	}	
	return $html;
}