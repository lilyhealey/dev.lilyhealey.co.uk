<?php

class URL extends URL_Base
{	
	function __construct()
	{
		global $oo;
		
		$urls = explode('/', $_SERVER['REQUEST_URI']);
		$base = array_slice($urls, 0, 3); // == ["admin", [view]]
		$urls = array_slice($urls, 3);
		
		try 
		{
			$ids = $oo->urls_to_ids($urls);
		}
		// check that the object that this URL refers to exists
		// FIX THIS CODE
		catch(Exception $e)
		{
			$urls = array_slice($urls, 0, $e->getMessage());
			$ids = $oo->urls_to_ids($urls);
			$loc = $host.implode("/".$base)."/".implode("/", $urls);
			header("Location: ".$loc);
		}
		$id = $ids[count($ids)-1];
		if(!$id)
			$id = 0;
		if(sizeof($ids) == 1 && empty($ids[0]))
			unset($ids);
		
		$this->urls = $urls;
		$this->url = $urls[count($urls)-1];
		$this->ids = $ids;
		$this->id = $id;
	}
	
	// FIX THIS CODE
	public function parents()
	{
		global $oo;
		global $admin_path;
		$urls = $this->urls;
		$ids = $this->ids;
		$parents[] = "";
		
		for($i = 0; $i < count($urls)-1; $i++)
		{
			$parents[$i]['url'] = $admin_path."browse/";
			for($j = 0; $j < $i + 1; $j++)
			{
				$parents[$i]['url'].= $urls[$j];
				if($j < $i)
					$parents[$i]['url'].= "/";
			}
			$parents[$i]["name"] = $oo->name($ids[$i]);
		}
		if($parents[0] == "")
			unset($parents);
		return $parents;
	}
}

?>
