<?
// am i using the ternary operator correctly?
// if this url has an id, get the associated object,
// else, get the roote object
$item = $uu->id ? $oo->get($uu->id) : $oo->get(0);
$name = $item ? strip_tags($item["name1"]) : "root";
?><div id="body-container">
	<div id="body" class="centre">
		<div class="actions"><?
			if($item)
			{
			?><span><? echo $name; ?></span>
			<a href="<? echo $admin_path."edit/".$uu->urls(); ?>">edit</a>
			<a href="<? echo $admin_path."delete/".$uu->urls(); ?>">delete</a>
			<?
			}
			?>
			<a href="<? echo $admin_path."add/".$uu->urls(); ?>">add</a>
			<a href="<? echo $admin_path."link/".$uu->urls(); ?>">link</a>
		</div><?
		// object contents
		if($uu->id)
		{
			$item = $oo->get($uu->id);
			$keys = array_keys($item);
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
			foreach($media_ids as $m)
			{
				$m_url = m_url($mm->get($m));
				?><div class='preview'>
					<img src="<? echo $m_url; ?>">
				</div><?
			}
		}
	?></div>
</div>