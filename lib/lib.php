<?
/* miscellaneous functions */

// THIS NEEDS TO BE TESTED
function slug($name = "untitled")
{
	$pattern = '/(\A\W+|\W+\z)/';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $name);
	
	// replace whitespace with hyphens
	$pattern = '/\s+/';
	$replace = '-';
	$tmp = preg_replace($pattern, $replace, $tmp);
	
	// replace trailing hyphens
	$pattern = '/[^-\w]+/';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $tmp);
	return strtolower($tmp);
}

// why do i need two of these? 
// which would be better to keep? probably the second one.
// maybe the variables should be passed instead of called on globally
function m_pad($m)
{
	global $m_pad;
	return str_pad($m, $m_pad, "0", STR_PAD_LEFT);
}

function m_url($m)
{
	global $media_path;
	return $media_path.m_pad($m['id']).".".$m['type'];
}

function resize($src, $dest, $scale)
{
	include('lib/SimpleImage.php');
	$si = new SimpleImage();
	$si->load($src);
	$si->scale($scale);
	$si->save($dest);
}

function process_media($toid)
{
	global $mm;
	global $rr;
	global $resize;
	global $resize_root;
	global $resize_scale;
	global $media_root;
	
	$dt = date("Y-m-d H:i:s");
	$m_rows = $mm->num_rows();
	$m_old = $m_rows;
	foreach($_FILES["uploads"]["error"] as $key => $error)
	{
		if($error == UPLOAD_ERR_OK)
		{
			$tmp_name = $_FILES["uploads"]["tmp_name"][$key];
			$m_name = $_FILES["uploads"]["name"][$key];
			$m_type = strtolower(end(explode(".", $m_name)));
			$m_file = m_pad(++$m_rows).".".$m_type;
			
			$m_dest = $resize ? $resize_root : $media_root;
			$m_dest.= $m_file;
			
			if(move_uploaded_file($tmp_name, $m_dest))
			{
				if($resize)
					resize($m_dest, $media_root.$m_file, $resize_scale);
				
				// add to db's image list
				$m_arr["type"] = "'".$m_type."'";
				$m_arr["object"] = "'".$toid."'";
				$m_arr["created"] = "'".$dt."'";
				$m_arr["modified"] = "'".$dt."'";
				$m_arr["caption"] = "'".$rr->captions[$key+count($rr->medias)]."'";
				$mm->insert($m_arr);
			}
			else
				$m_rows--;
		}
	}
	return $m_old < $m_rows;
}
?>