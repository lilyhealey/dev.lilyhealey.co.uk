<?
$b_url = $admin_path.'browse/'.$uu->urls();
$vars = array("name1", "deck", "body", "notes", "begin", "end", "url", "rank");
?><div id="body-container">
	<div id="body" class="centre"><?
		// show form
		if($rr->submit != "add") 
		{
			$form_url = $admin_path."add";
			if($uu->urls())
				$form_url.="/".$uu->urls();
		?><div class="self-container">
			<div class="self">You are adding a new object.</div>
		</div>
		<div id="form-container">
			<form 
				enctype="multipart/form-data" 
				action="<? echo $form_url; ?>" 
				method="post"
			>
				<div id="form"><?
				// object data
				foreach($vars as $var)
				{
					?><div>
						<div><? echo $var; ?></div>
						<div><textarea name='<? echo $var; ?>'></textarea></div>
					</div><?
				}
				//  upload new images
				for ($j = 0; $j < $max_uploads; $j++)
				{
					?><div>
						<div>image <? echo $j+1; ?></div>
						<div>
							<input type='file' name='uploads[]'>
							<textarea name="captions[]" class="caption"></textarea>
						</div>
					</div><?
				}
				?></div>
				<div>
					<input 
						name='cancel' 
						value='cancel'
						type='button' 
						onClick="javascript:history.back();"
					>
					<input name='submit' value='add' type='submit'>
				</div>
			</form>
		</div><?
		}
		// process form
		else
		{
			// objects
			foreach($vars as $var)
				$$var = addslashes($rr->$var);

			//  process variables
			if (!$name1) 
				$name1 = "untitled";
			$begin = ($begin) ? date("Y-m-d H:i:s", strToTime($begin)) : NULL;
			$end = ($end) ? date("Y-m-d H:i:s", strToTime($end)) : NULL;
			if(!$url)
				$url = slug($name1);
			
			// check that the desired URL is valid
			// URL is valid if it is not the same as any of its siblings
			// siblings are all the children of the record to which
			// this new record is being added
			$siblings = $oo->children_ids($uu->id);
			$url_is_valid = true;
			foreach($siblings as $s_id)
			{
				$url_is_valid = ($url != $oo->get($s_id)["url"]);
				if(!$url_is_valid)
					break;
			}
			
			if($url_is_valid)
			{
				$dt = date("Y-m-d H:i:s");
				$arr["created"] = "'".$dt."'";
				$arr["modified"] = "'".$dt."'";
	
				foreach($vars as $var)
					if($$var)
						$arr[$var] = "'".$$var."'";

				$toid = $oo->insert($arr);
	
				// wires
				$ww->create_wire($uu->id, $toid);

				// media
				process_media($toid);

			?><div class="self-container">
				<div class="self">
					<p>record added successfully</p>
					<p><a href="<? echo $b_url; ?>">continue... </a></p>
				</div>
			</div><?
			}
			else
			{
			?><p>record not created, <a href="javascript:history.back();">try again</a></p><?
			}
		} 
	?></div>
</div>