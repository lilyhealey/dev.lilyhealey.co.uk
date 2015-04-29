<?php

// int: id, active, rank
// datetime: created, modified, begin, end, date
// tinytext: name1, name2, city, state, zip, country, phone, fax, url, email, head
// text: address1, address2
// blob: deck, body, notes
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
	
	// return the ids of all ancestors of object with id $o
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
	
	// return the ids of all descedants of object with id $o
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

	// returns an array of [depth, o] of objects rooted at $o
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