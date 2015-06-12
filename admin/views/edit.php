<div id="body-container">
	<div id="body" class="centre"><?
	$vars = array("name1", "deck", "body", "notes", "begin", "end", "url", "rank");
if ($rr->action != "update" && $uu->id)
{
	//  get existing image data
	$medias = $oo->media($uu->id);
	$num_medias = count($medias);
	
	/*
	 * add associations to media arrays:
	 * $medias[$i]["file"] is url of media file
	 * $medias[$i]["display"] is url of display file (diff for pdfs)
	 * $medias[$i]["type"] is type of media (jpg, 
	 */
	for($i = 0; $i < $num_medias; $i++)
	{
		//$m_padded = "" . str_pad($medias[$i]["id"], 5, "0", STR_PAD_LEFT);
		$m_padded = "".m_pad($medias[$i]['id']);
		$medias[$i]["file"] = $media_path . $m_padded . "." . $medias[$i]["type"];
		if ($medias[$i]["type"] == "pdf")
			$medias[$i]["display"] = $admin_path."media/pdf.png";
		else
			$medias[$i]["display"] = $medias[$i]["file"];
	}

	// object contents
	$name = strip_tags($item["name1"]);
	?><div class="self-container">
		<div class="self">
			<a href="<? echo $admin_path.'browse/'.$uu->urls(); ?>"><?php 
				echo $name; 
			?></a>
		</div>
	</div>
	<div id="form-container">
		<form
			method="post"
			enctype="multipart/form-data" 
			action="<?php echo htmlspecialchars($admin_path.'edit/'.$uu->urls()); ?>" 
		>
			<div class="form"><?php
				// show object data
				foreach($vars as $var)
				{
				?><div>
					<div><? echo $var; ?></div>
					<div><textarea name='<? echo $var; ?>'><?php
						echo $item[$var];
					?></textarea></div>
				</div><?
				}
				// show existing images
				for($i = 0; $i < $num_medias; $i++)
				{
				?><div>
					<div>Image <? echo str_pad($i+1, 2, "0", STR_PAD_LEFT);?></div>
					<div class='preview'>
						<a href="<? echo $medias[$i]['file']; ?>" target="_blank">
							<img src="<?php echo $medias[$i]['display']; ?>">
						</a>
					</div>
					<textarea name="captions[<? echo $i; ?>]"><?php
						echo $medias[$i]["caption"];
					?></textarea>
					<div>Rank</div>
					<select name="ranks[<? echo $i; ?>]"><?php
						for($j = 1; $j <= $num_medias; $j++)
						{
							if($j == $medias[$i]["rank"])
							{
							?><option selected value="<? echo $j; ?>"><? 
								echo $j; 
							?></option><?php
							}
							else
							{
							?><option value="<? echo $j; ?>"><? 
								echo $j; 
							?></option><?php
							}
						}
					?></select>
					<input
						type="checkbox"
						name="deletes[<? echo $i; ?>]"
					>
					<div>delete image</div>
					<input 
						type="hidden"
						name="medias[<? echo $i; ?>]"
						value="<? echo $medias[$i]['id']; ?>"
					>
					<input 
						type="hidden"
						name="types[<? echo $i; ?>]"
						value="<? echo $medias[$i]['type']; ?>"
					>
				</div><?php
				}
				// upload new images
				for($j = 0; $j < $max_uploads; $j++)
				{
				?><div>
					<div>Image <?php echo str_pad(++$i, 2, "0", STR_PAD_LEFT); ?></div>
					<div><input type="file" name="upload<? echo $j; ?>"></div>
					<textarea name="captions[<? echo $i-1; ?>]"><?php
							echo $medias[$i]["caption"];
					?></textarea>
				</div><?php
				} ?>
				<div>	
					<input 
						name='cancel' 
						type='button' 
						value='cancel' 
						onClick="javascript:history.back();" 
					>
				</div>
				<div>
					<input name='submit' type='submit' value='update'>
					<input name='action' type='hidden' value='update'>
				</div>
			</div>
		</form>
	</div>
<?php
}

// THIS CODE NEEDS TO BE FACTORED OUT SO HARD
// basically the same as what is happening in add.php
else 
{
	$item = $oo->get($uu->id);
	
	// objects
	foreach($vars as $var)
		$$var = addslashes($rr->$var);

	//  process variables
	if (!$name1) 
		$name1 = "Untitled";
	$begin = ($begin) ? date("Y-m-d H:i:s", strToTime($begin)) : NULL;
	$end = ($end) ? date("Y-m-d H:i:s", strToTime($end)) : NULL;
	if(!$url)
		$url = slug($name1);
	
	// check that the desired URL is valid
	// URL is valid if it is not the same as any of its siblings
	// siblings are all the children of any of this object's direct
	// parents (object could be linked elsehwere -- any updated
	// URL cannot conflict with those siblings, either)
	$siblings = $oo->siblings($uu->id);
	$valid_url = true;
	foreach($siblings as $s_id)
	{
		$valid_url = ($url != $oo->get($s_id)["url"]);
		if(!$valid_url)
			break;
	}
	
	if($valid_url)
	{
		//  check for differences
		foreach($vars as $var)
			if($item[$var] != $$var)
			{
				if($$var)
					$arr[$var] = "'".$$var."'";
				else
					$arr[$var] = "null";
			}
	
		// update if modified
		if($arr)
		{
			$arr["modified"] = "'".date("Y-m-d H:i:s")."'";
			$z = TRUE;
			$sqlA = $oo->update($uu->id, $arr);
		}

		$mflag = FALSE;
		// upload media
		for ($i = 0; $i < $max_uploads; $i++) 
		{
			if ($imageName = $_FILES["upload".$i]["name"])
			{
				$m_rows = $mm->num_rows();

				$nameTemp = $_FILES["upload". $i]['name'];
				$typeTemp = explode(".", $nameTemp);
				$type = $typeTemp[sizeof($typeTemp) - 1];

				// $targetFile = str_pad(($m_rows+1), 5, "0", STR_PAD_LEFT) .".". $type;
				$targetFile = m_pad($m_rows+1).".".$type;
			
				// ** Image Resizing **
				// Only if folder ../media/hi exists
				// First upload the raw image to ../media/hi/ folder
				// If upload works, then resize and copy to main ../media/ folder
				// To turn on set $resize = TRUE; FALSE by default
				$resize = FALSE; 
				$resizeScale = 65;
				$targetPath = ($resize) ? "../media/hi/" : "../media/";
				$target = $targetPath . $targetFile;
				$copy = copy($_FILES["upload".$i]['tmp_name'], $target);
			
				if ($copy)
				{
					if ($resize)
					{
						include('lib/SimpleImage.php');
						$image = new SimpleImage();
						$image->load($target);
						$image->scale($resizeScale);
						$targetPath = "../media/"; //$media_path;
						$target = $targetPath . $targetFile;
						$image->save($target);
					
						echo "Upload $imageName SUCCESSFUL<br />";
						echo "Copy $target SUCCESSFUL<br />";
					}			
					// Add to DB's image list
					$dt = date("Y-m-d H:i:s");

					$m_arr["type"] = "'".$type."'";
					$m_arr["object"] = "'".$uu->id."'";
					$m_arr["created"] = "'".$dt."'";
					$m_arr["modified"] = "'".$dt."'";
					$m_arr["caption"] = "'".$rr->captions[count($rr->medias)+$i]."'";
					$mm->insert($m_arr);
				}
				$mflag = TRUE;
			}
		}
	
		// delete media
		for ($i = 0; $i < sizeof($rr->types); $i++)
		{
			/* 
				Use sizeof($rr->types) because if checkbox is unchecked 
				that variable "doesn't exist" although the expected behavior is 
				for it to exist but be null.
			*/
			if ($rr->deletes[$i]) 
			{
				$m = $rr->medias[$i];
				$mm->deactivate($m);
				$mflag = TRUE;
			}
		}
	
		// update caption, weight, rank  
		$num_captions = sizeof($rr->captions);
		if (sizeof($rr->medias) < $num_captions)
			$num_captions = sizeof($rr->medias);
	
		for ($i = 0; $i < $num_captions; $i++) 
		{
			unset($m_arr);
			$m = $rr->medias[$i];
			$item = $mm->get($m);
		
			$caption = addslashes($rr->captions[$i]);
			$rank = addslashes($rr->ranks[$i]);

			$z2 = NULL;
			if ($item["caption"] != $caption)
				$m_arr["caption"] = "'".$caption."'";
			if ($item["rank"] != $rank)
				$m_arr["rank"] = "'".$rank."'";

			if ($m_arr)		
				$mm->update($m, $m_arr);
		}
		?><div class="self-container">
			<div class="self"><?php
			// Job well done?
			if ($z || $mflag || $m_arr)
				echo "Record successfully updated."; 
			else 
				echo "Nothing was edited, therefore update not required.";
			?></div>
			<div class="self">
				<a href="<?php echo $admin_path.'edit/'.$uu->urls(); ?>">refresh object</a>
			</div>
		</div><?
		}
		else
		{
			?><p>record not updated, <a href="javascript:history.back();">try again</a></p><?
		}
	} 
	?></div>
</div>