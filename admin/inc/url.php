<?php

class URL
{
	public $urls;
	public $url;
	public $ids;
	public $id;
	
	function __construct()
	{
		global $oo;
		
		$urls = explode('/', $_SERVER['REQUEST_URI']);
		$urls = array_slice($urls, 3);
		$this->urls = $urls;
		$this->url = $urls[count($urls)-1];
		
		$ids = $oo->urls_to_ids($urls);
		$id = $ids[count($ids)-1];
		if(!$id)
			$id = 0;
		if(sizeof($ids) == 1 && empty($ids[0]))
			unset($ids);
		$this->ids = $ids;
		$this->id = $id;
	}
	
	// return a string of the current urls
	// defaults to empty string if none
	public function urls()
	{
		return implode("/", $this->urls);
	}
	
	public function back()
	{
		$urls = $this->urls;
		array_pop($urls);
		return implode("/", $urls);
		// return $url;
	}
	
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
