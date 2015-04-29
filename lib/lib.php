<?

// takes: a tree constructed by $oo->traverse()
// returns; an associative array of name1, urls
function nav($tree, $url_root)
{
	$urls = array();
	$prevd = 0;
	$nav = array();
	foreach($tree as $node)
	{
		$d = $node['depth'];
		$o = $node['o'];
		
		$pops = $prevd - $d + 1;
		$urls = array_slice($urls, 0, count($urls) - $pops);
		$urls[] = $o['url'];
		$url = $url_root.implode("/", $urls);

		$nav[] = array(	'depth'=>$d, 
						'name'=>$o['name1'], 
						'url'=>$url);
		$prevd = $d;
	}
	return $nav;
}

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