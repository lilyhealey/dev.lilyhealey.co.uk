<div id="body-container">
	<div id="body" class="centre"><?
	$c_url = $admin_path."browse";
	$l_url = $admin_path."link";
	if($uu->urls())
	{
		$c_url .= "/".$uu->urls();
		$l_url .= "/".$uu->urls();
	}
	if($rr->submit != "link") 
	{
		echo $l_url;
		print_r($_POST);
		?><div class="self-container">
			<div class="self"><a href="<? echo $c_url; ?>"><? echo $name; ?></a></div>
			<div class="self">
				<p>You are linking to an existing object.</p>
				<p>The object will remain in its original location and also appear here.</p> 
				<p>Please choose from the list of active objects:</p>
			</div>
		</div>
		<div id="form-container">
			<form 
				enctype="multipart/form-data"
				action="<? echo $l_url; ?>"
				method="post" 
			>
				<div class="form">
					<div>
						<select name='wires_toid'><?
							$items = $oo->unlinked_list($uu->id);
							foreach($items as $i)
							{
							?><option value="<? echo $i; ?>"><?
								echo $oo->name($i);
							?></option><?	
							}
						?></select>
					</div>
					<div>
						<input 
							name='cancel' 
							type='button' 
							value='cancel' 
							onClick="javascript:history.back();"
						>
						<input name='submit' type='submit' value="link">
					</div>
				</div>
			</form>
		</div><? 
	} 
	else 
	{
		// create / reactivate wire 
		// TODO:
		// + look for an inactive wire with the same fromid and toid?
		//   to avoid re-creating wires that are just inactive?
		//   is this worth the computation?
		$wires_toid = addslashes($rr->wires_toid);
		$ww->create_wire($uu->id, $wires_toid);
		?><div class="self-container">
			<div class="self">object linked successfully</div>
			<div class="self"><a href="<? echo $c_url; ?>">continue...</a></div>
		</div><?
	}
	?></div>
</div>