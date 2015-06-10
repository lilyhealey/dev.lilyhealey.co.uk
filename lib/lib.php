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
?>