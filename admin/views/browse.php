<div id="body-container">
	<div id="body" class="centre">
		<span><? echo $name; ?></span>
		<div class="actions"><?
			if($item)
			{
			?>
			<a href="<? echo $admin_path."edit/".$uu->urls(); ?>">edit</a>
			<a href="<? echo $admin_path."delete/".$uu->urls(); ?>">delete</a>
			<?
			}
			?>
			<a href="<? echo $admin_path."add/".$uu->urls(); ?>">add</a>
			<a href="<? echo $admin_path."link/".$uu->urls(); ?>">link</a>
		</div><?
		// object contents
		if($item)
		{
			//$keys = array_keys($item);
			$keys = ["id", "name1", "url", "deck"];
			foreach($keys as $k)
			{
				if($item[$k])
				{
				?><div>
					<span><? echo $k; ?>: </span>
					<span><? echo $item[$k]; ?></span>
				</div><?
				}
			}
			$media_ids = $oo->media_ids($uu->id);
			foreach($media_ids as $m_id)
			{
				$m = $mm->get($m_id);
				$m_url = m_url($m);
				?><div class='preview'>
					<img src="<? echo $m_url; ?>">
					<div class='caption'><? echo $m['caption']; ?></div>
				</div><?
			}
		}
	?></div>
</div>