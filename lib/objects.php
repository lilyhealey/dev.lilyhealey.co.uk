<?
/*---------------------------------------------------------
	class for interaction with the OBJECTS table

	fields
		+ blob
			- deck
			- body
			- notes
		+ int
			- id
			- active
			- rank
		+ text
			- address1
			- address2
		+ tinytext
			- name1
			- name2
			- city
			- state
			- zip
			- country
			- phone
			- fax
			- url
			- email
			- head
		+ datetime
			- created
			- modified
			- begin
			- end
			- date
---------------------------------------------------------*/
class Objects extends Model
{
	const table_name = "objects";
	
	// return the name of object with id $o
	public function name($o)
	{
		$item = $this->get($o);
		return $item["name1"];
	}
	
	// return the children of object with id $o
	public function children($o)
	{
		$fields = array("*", "objects.id AS id");
		$tables = array("objects", "wires");
		$where	= array("wires.fromid = '".$o."'",
						"wires.active = 1",
						"wires.toid = objects.id",
						"objects.active = '1'");
		$order 	= array("objects.name1");
		return $this->get_all($fields, $tables, $where, $order);
	}
	
	// returns: the ids of all children of object with id $o
	public function children_ids($o)
	{
		$fields = array("objects.id AS id");
		$tables = array("objects", "wires");
		$where	= array("wires.fromid = '".$o."'",
						"wires.active = 1",
						"wires.toid = objects.id",
						"objects.active = '1'");
		$order 	= array("objects.name1");
		$res = $this->get_all($fields, $tables, $where, $order);
		$ids = array();
		foreach($res as $r)
			$ids[] = $r['id'];

		return $ids;
	}
	
	// check that URL is of valid object here
	// throw 404 exception if not
	public function urls_to_ids($u)
	{
		$fromid = 0;
		$objects = array();
		for($i = 0; $i < count($u); $i++)
		{
			$fields = array("objects.id");
			$tables = array("objects", "wires");
			$where 	= array("wires.fromid = '".$fromid."'",
							"wires.toid = objects.id",
							"objects.url = '".$u[$i]."'",
							"wires.active = '1'",
							"objects.active = '1'");
			$order 	= array("objects.name1");

			$tmp = $this->get_all($fields, $tables, $where, $order);
			$fromid = $tmp[0]['id'];
			if(!$fromid)
				throw new Exception($i);
			$objects[] = $fromid;
		}
		return $objects;
	}
	
	public function ids_to_urls($objects)
	{
		$u = array();
		for($i = 0; $i < count($objects); $i++)
		{
			$o = $this->get($objects[$i]);
			$u[] = $o['url'];
		}
		return $u;
	}
	
	// returns: the ids of all ancestors of object with id $o
	//
	// ancestors are obtained by traversing tree, 
	// going through in-order list of traversals,
	// recording potential parents,
	// breaking when $o is found,
	// reporting the actual parents at the time of finding
	// repeats this process through the entire tree array, in case
	// object is linked elswhere
	public function ancestors($o)
	{
		$all = $this->traverse(0);
		$ancestors = array();
		$a = array();
		for($i = 0; $i < count($all); $i++)
		{
			if(end($all[$i]) == $o)
			{
				$d = count($all[$i]);
				$ancestors = array_merge($ancestors, array_slice($a, 0, $d-1));
			}
			$d = count($all[$i]);
			$a[$d-1] = end($all[$i]);
		}
		return array_unique($ancestors);
	}
	
	// returns: the ids of all descedants of object with id $o
	// children, grandchildren, etc
	public function descendants($o)
	{
		$desc = $this->traverse($o);
		$descendants = array();
		foreach($desc as $d)
			$descendants[] = end($d);
		return $descendants;
	}
	
	// return media attached to this object
	public function media($o)
	{
		$fields = array("*");
		$tables = array("media");
		$where 	= array("object = '".$o."'", 
						"active = '1'");
		$order 	= array("rank", "modified", "created", "id");
		
		return $this->get_all($fields, $tables, $where, $order);
	}
	
	public function media_ids($o)
	{
		$fields = array("id");
		$tables = array("media");
		$where 	= array("object = '".$o."'", 
						"active = '1'");
		$order 	= array("rank", "modified", "created", "id");
		$res = $this->get_all($fields, $tables, $where, $order);
		$ids = array();
		foreach($res as $r)
			$ids[] = $r['id'];

		return $ids;
	}
	
	// returns a list of objects $o can link to
	// $o cannot link to its children 
	// (because it is already linked to them) 
	// or any of its direct ancestors 
	// (because doing so would create a loop)
	public function unlinked_list($o)
	{	
		$all = $this->traverse(0);
		$all_ids = array();
		foreach($all as $a)
			$all_ids[] = end($a);
		
		$exclude_ids = $this->children_ids($o);
		$exclude_ids[] = $o;
		$exclude_ids = array_merge($exclude_ids , $this->ancestors($o));
		$include_ids = array_unique(array_diff($all_ids, $exclude_ids));
		return $include_ids;
	}
	
	// returns an array of [path] of objects rooted at $o
	// depth is equal to the length of each path array
	public function traverse($o)
	{
		static $path = array();
		$children_ids = $this->children_ids($o);
		$paths = array();
		
		if(count($path) > 0)
			$paths[] = $path;
		if(!empty($children_ids)) // make children return an empty array?
		{
			foreach($children_ids as $c)
			{
				$path[] = $c;
				$paths = array_merge($paths, $this->traverse($c));
				array_pop($path);
			}
		}
		return $paths;
	}
	
	// takes: a tree constructed by $oo->traverse()
	// returns; an associative array of depth, name, url
	public function nav_full($paths)
	{
		$urls = array();
		$prevd = 0;
		$nav = array();
		foreach($paths as $path)
		{
			$d = count($path);
			$o = $this->get($path[(count($path)-1)]);
		
			$pops = $prevd - $d + 1;
			$urls = array_slice($urls, 0, count($urls) - $pops);
			$urls[] = $o['url'];
			$url = implode("/", $urls);
		
			$nav[] = array('depth'=>$d, 'name'=>$o['name1'], 'url'=>$url);
			$prevd = $d;
		}
		return $nav;
	}
	
	// takes: 
	// returns: 
	public function nav($ids, $root=0)
	{
		$nav = array();
		$top = $this->children_ids($root);
		$pass = true;
		foreach($top as $t)
		{
			$o = $this->get($t);
			$d = $root+1;
			$nav[] = array('depth'=>$d, 'name'=>$o['name1'], 'url'=>$o['url']);
			if($pass && $t == $ids[$root])
			{
				$urls = array($o['url']);
				$kids = $this->children_ids(end($ids));
				array_shift($ids);
				foreach($ids as $id)
				{
					$d++;
					$o = $this->get($id);
					$urls[] = $o['url'];
					$url = implode("/", $urls);
					$nav[] = array('depth'=>$d, 'name'=>$o['name1'], 'url'=>$url);
				}
				//kids
				$d++;
				foreach($kids as $k)
				{	
					$o = $this->get($k);
					$urls[] = $o['url'];
					$url = implode("/", $urls);
					$nav[] = array('depth'=>$d, 'name'=>$o['name1'], 'url'=>$url);
					array_pop($urls);
				}
				$pass = false; // short-circuit if statement
			}
		}
		return $nav;
	}
}
?>