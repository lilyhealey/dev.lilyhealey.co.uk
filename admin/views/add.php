<?
$b_url = $admin_path.'browse/'.$uu->urls();
$vars = array("name1", "deck", "body", "notes", "begin", "end", "url", "rank");
$f = array();
$dt_fmt = "Y-m-d H:i:s";
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
						onClick="<? echo $js_back; ?>"
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
				$f[$var] = addslashes($rr->$var);

			$siblings = $oo->children_ids($uu->id);
			$toid = insert_object($f, $siblings);
			if($toid)
			{
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
			?><p>record not created, <a href="<? echo $js_back; ?>">try again</a></p><?
			}
		} 
	?></div>
</div>