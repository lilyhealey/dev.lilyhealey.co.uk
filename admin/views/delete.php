<div id="body-container">
	<div id="body" class="centre"><?
		// the current object is linked elsewhere if (and only if?) it 
		// exists in the tree (returned by $oo->traverse(0)) multiple times
		$all_paths = $oo->traverse(0);
		$l = 0;
		$is_linked = false;
		foreach($all_paths as $p)
		{
			if(end($p) == $uu->id)
			{
				// break when second link is found
				// no need to cycle through entire tree
				if($l)
				{
					$is_linked = true;
					break;
				}
				else
					$l++; 
			}
		}
		if(strtolower($rr->submit) != "delete") 
		{
			// if this object does not exist elsewhere in the tree,
			// check to see if its descendents are linked elsewhere
			// (or will be deleted with the deletion of this object)
			if(!$is_linked || !empty($dep_paths))
			{
				$all_paths = $oo->traverse(0);
				$dep_paths = $oo->traverse($uu->id);
				$dep_prefix = implode("/", $uu->ids)."/";
				$dp_len = strlen($dep_prefix);
				$dep = array(); // ids only
				$all = array(); // ids only
				
				foreach($dep_paths as $p)
					$dep[] = end($p);
				
				// compare the beginning of $each path $p to $dep_prefix
				// will that work?
				foreach($all_paths as $p)
					if(!(substr(implode("/", $p), 0, $dp_len) == $dep_prefix))
						$all[] = end($p);
				
				$dependents = array_diff($dep, $all);
				$k = count($dependents);
			}
		// display warning
		?><div class="self-container"><?
			if($is_linked)
			{ 
			?><p>this object is linked elsewhere, so the original will not be deleted.</p><?
			}
			else
			{
			?><p>warning! you are about to permanently delete this object.</p><?
				if($k) 
				{ 
			?><p>The following <? 
					if($k > 1)
						echo $k." objects";
					else
						echo "object"; 
					?> will also be deleted as a result:</p><?	
					$padout = floor(log10($k)) + 1;
					if ($padout < 2) 
						$padout = 2;
			?><div class="children-container"><?		
					foreach($dependents as $d) 
					{
						$j++;
						$child = $oo->get($d);
						$n = STR_PAD($j, $padout, "0", STR_PAD_LEFT);
						$url = $admin_path."browse/".$uu->urls()."/".$child["url"];
						$child_name = strip_tags($child["name1"]);
						?><div class="child">
							<span><? echo $n; ?></span>
							<a href="<? echo $url; ?>"><? echo $child_name; ?></a>
						</div><?
					}
			?></div><?
				}
			}
		?></div>
		<div id="form-container">
			<form 
				action="<? echo $admin_path.'delete/'.$uu->urls(); ?>" 
				method="post"
			>
				<div class="form">
					<input 
						name='cancel' 
						type='button' 
						value='cancel' 
						onClick="javascript:history.back();"
					> 
					<input name='submit' type='submit' value='delete'>
				</div>
			</form>
		</div><?
		} 
		else
		{
			//  get wire that goes to this object to be deleted
			if (sizeof($uu->ids) < 2) 	
				$fromid = 0;
			else
				$fromid = $uu->ids[sizeof($uu->ids) - 2];
			$message = $ww->delete_wire($fromid, $uu->id);
			// if object doesn't exist anywhere else, deactivate it
			if(!$is_linked)
				$oo->deactivate($uu->id);
		?><div class="self-container">
			<div class="self"><? echo $message; ?></div>
			<div class="self">
				<a href="<? echo $admin_path.'browse/'.$uu->back(); ?>">continue...</a>
			</div>
		</div><?
		}
	?></div>
</div>