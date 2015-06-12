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
							<input type='file' name='upload<?php echo $j; ?>'>
							<textarea name="captions[<? echo $j; ?>]" class="caption"></textarea>
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

			//  Process variables
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
			$valid_url = true;
			foreach($siblings as $s_id)
			{
				$valid_url = ($url != $oo->get($s_id)["url"]);
				if(!$valid_url)
					break;
			}
			
			if($valid_url)
			{
				$dt = date("Y-m-d H:i:s");
				$arr["created"] = "'".$dt."'";
				$arr["modified"] = "'".$dt."'";
	
				foreach($vars as $var)
					if($$var)
						$arr[$var] = "'".$$var."'";

				$toid = $oo->insert($arr);
	
				unset($arr);
	
				/* wires */
				if($uu->id)
					$fromid = $uu->id;
				else
					$fromid = 0;
				$arr["created"] = "'".$dt."'";
				$arr["modified"] = "'".$dt."'";
				$arr["fromid"] = "'".$fromid."'";
				$arr["toid"] = "'".$toid."'";
	
				$ww->insert($arr);

				/* media */
				// this code should be factored
				for ($i = 0; $i < $max_uploads; $i++) 
				{
					if ($imageName = $_FILES["upload".$i]["name"]) 
					{
						$m_rows = $mm->num_rows();

						$nameTemp = $_FILES["upload". $i]['name'];
						$typeTemp = explode(".", $nameTemp);
						$type = $typeTemp[sizeof($typeTemp) - 1];
			
						// $targetFile = str_pad(($m_rows+1), 5, "0", STR_PAD_LEFT) .".". $type;
						$targetFile = m_pad($m_rows+1).".". $type;				

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
		
						if($copy) 
						{
							if($resize)
							{
								include('lib/SimpleImage.php');
								$image = new SimpleImage();
								$image->load($target);
								$image->scale($resizeScale);
								$targetPath = "../media/";
								$target = $targetPath . $targetFile;
								$image->save($target);
				
								echo "Upload $imageName SUCCESSFUL<br />";
								echo "Copy $target SUCCESSFUL<br />";
							}			
							// Add to DB's image list
							$dt = date("Y-m-d H:i:s");

							$m_arr["type"] = "'".$type."'";
							$m_arr["object"] = "'".$toid."'";
							$m_arr["created"] = "'".$dt."'";
							$m_arr["modified"] = "'".$dt."'";
							$m_arr["caption"] = "'".$rr->captions[$i]."'";
							$mm->insert($m_arr);
						}
					}
				}
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