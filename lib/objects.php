<?php

// int: id, active, rank
// datetime: created, modified, begin, end, date
// tinytext: name1, name2, city, state, zip, country, phone, fax, url, email, head
// text: address1, address2
// blob: deck, body, notes
class Objects extends Model
{
	const table_name = "objects";
	
	// parents
	// children
	// ancestors
	// descendants
	
	// return the name of this object
	public function name($o)
	{
		$item = $this->get($o);
		return $item["name1"];
	}
	
	// return the parents of this object
	public function parents($objects)
	{
		global $admin_path;
		$parents[] = ""; // is this necessary?

		$u = $this->ids_to_urls($objects);

		for ($i = 0; $i < count($objects) - 1; $i++) 
		{
			$item = $this->get($objects[$i]);
			$name = strip_tags($item["name1"]);

			// Each panel expands on title click
			$parents[$i]["url"] = $admin_path."browse/";
			for ($j = 0; $j < $i + 1; $j++)
			{
				$parents[$i]["url"] .= $u[$j];
				if ($j < $i)
					$parents[$i]["url"] .= "/";
			}
			$parents[$i]["name"] = $name;
		}
		if($parents[0] == "")
			unset($parents);
		return $parents;
	}
	
	// return the children of this object
	public function children_x($o)
	{
		$fields = array("objects.name1", "objects.url AS o");
		$tables = array("objects", "wires");
		$where 	= array("wires.fromid = '".$o."'",
						"wires.toid = objects.id",
						"wires.active = '1'",
						"objects.active = '1'");
		$order 	= array("objects.rank", "name1");
		
		return $this->get_all($fields, $tables, $where, $order);	
	}
	
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
			if($all[$i]['o']['id'] == $o)
			{
				$d = $all[$i]['depth'];
				$ancestors = array_merge($ancestors, array_slice($a, 0, $d-1));
			}
			$d = $all[$i]['depth'];
			$a[$d-1] = $all[$i]['o']['id'];
		}
		return array_unique($ancestors);
	}
	
	// return all descedants of this object
	// children, grandchildren, etc
	public function descendants($o)
	{
		$desc = $this->traverse($o);
		$descendants = array();
		foreach($desc as $d)
			$descendants[] = $d['o']['id'];
		
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
	
	// returns a list of objects $o can link to
	// $o cannot link to its children 
	// (because it is already linked to them) 
	// or any of its direct ancestors 
	// (because doing so would create a loop)
	public function unlinked_list($o)
	{
		$fields = array("objects.id", "objects.name1");
		$tables = array("objects", "wires");
		$where 	= array("objects.active = 1",
						"wires.active = 1",
						"wires.toid = objects.id",
						"wires.fromid != ".$o,
						"objects.id != ".$o);
		$order 	= array("objects.name1");
		$limit = '';
		
		$all = $this->traverse(0);
		$all_ids = array();
		foreach($all as $a)
			$all_ids[] = $a['o']['id'];
		
		$exclude = $this->children($o);
		$exclude_ids = array($o);
		foreach($exclude as $e)
			$exclude_ids[] = $e['id'];
		$exclude_ids = array_merge($exclude_ids , $this->ancestors($o));
		
		$include_ids = array_diff($all_ids, $exclude_ids);
		
		return $include_ids;
	}
	
	public function url_data($o)
	{
		
	}
	
	public function traverse($o)
	{
		static $depth = 0;
		$object = $this->get($o);
		$children = $this->children($o);
		$list = array();
		
		if($depth > 0)
			$list[] = array('depth'=>$depth, 'o'=>$object);
		if(!empty($children[0]))
		{
			$depth++;
			foreach($children as $c)
				$list = array_merge($list, $this->traverse($c['id']));
			$depth--;
		}
		return $list;
	}
}
?>