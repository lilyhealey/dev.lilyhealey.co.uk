<?
// takes: root node
// returns: a list of associative arrays with depth, o
// function traverse($node)
// {
// 	global $ob;
// 	static $depth = 0;
// 
// 	$o = $ob->get($node);
// 	$children = $ob->children($node);
// 	$list = array();
// 	
// 	if($depth > 0)
// 		$list[] = array('depth'=>$depth, 'o'=>$o);
// 	
// 	if(!empty($children[0]))
// 	{
// 		$depth++;
// 		foreach($children as $c)
// 			$list = array_merge($list, traverse($c['id']));
// 		$depth--;
// 	}
// 	return $list;
// }

// takes: a tree constructed by traverse()
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
?>