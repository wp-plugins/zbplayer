<?php
/*
Plugin Name: zbPlayer
Plugin URI: http://gilevich.com/portfolio/zbplayer
Description: Converts mp3 files links to a small flash player and a link to download file mp3 file. Player by outdated plugin <a href="http://wpaudioplayer.com/">WordPress Audio Player</a>.
Version: 1.7
Author: Vladimir Gilevich
Author URI: http://gilevich.com/
****************************************************
/*
 *	zbPlayer Wordpress Plugin
 *	(c) 20013 Vladimir Gilevich
 *	Dual Licensed under the MIT and GPL licenses
 *  See license.txt, included with this package for more
 *
 *	zbPlayer.php
 *  Release 1.7, March 2013
 */

define('ZBPLAYER_VERSION', "1.7");
define('ZBPLAYER_DEFAULT_WIDTH', "500");
define('ZBPLAYER_DEFAULT_INITIALVOLUME', "60");

// Hook to add scripts
add_action('admin_menu','zbp_add_pages');
add_filter('the_content', 'zbp_content');
add_action("plugins_loaded", "zbp_init");

function zbp_init() {
	if (get_option('zbp_width') <= 0) {
		update_option('zbp_width',ZBPLAYER_DEFAULT_WIDTH);
	}
	zbp_load_language_file();
}

// Replace mp3 links in content with player 
function zbp_content($content)
{
  // Replace mp3 links (don't do this in feeds and excerpts)
  if ( !is_feed() ) {
    $pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.mp3))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
    $content = preg_replace_callback( $pattern, "zbp_insert_player", $content );
  }
  return $content;
}

// Main code - insert player into content
function zbp_insert_player($matches)
{
  $link = preg_split("/[\|]/", $matches[3]);
  $link = $link[0];
  $name = str_replace('_', ' ', $matches[5]);
	$download = get_option('zbp_download') == 'true' ? '<span> [<a href="'.$link.'" class="zbPlayer-download">'.__("Download", 'zbplayer').'</a>] </span>' : '';
	$autostart = get_option('zbp_autostart') == 'true' ? 'yes' : 'no';
	$initialvolume = intval(get_option('zbp_initialvolume')) ? intval(get_option('zbp_initialvolume')) : ZBPLAYER_DEFAULT_INITIALVOLUME;
	$width = get_option('zbp_width') > 0 ? intval(get_option('zbp_width')) : ZBPLAYER_DEFAULT_WIDTH;
  $ret = '<div class="zbPlayer">' . $name . $download . '<br/>'
   . '<embed width="'.$width.'" height="26" wmode="transparent" menu="false" quality="high"'
		. ' flashvars="playerID=zbPlayer&amp;initialvolume='.$initialvolume.'&amp;titles='.urlencode($name).'&amp;soundFile='.zbp_urlencode($link)
		. '&amp;autostart='.$autostart.'" type="application/x-shockwave-flash" class="player" src="'.plugin_dir_url(__FILE__).'data/player.swf" id="zbPlayer"/></div>';
  return $ret;
}

// own ulrencode method - need to convert to utf8 filename if it is not in utf8
function zbp_urlencode($link)
{
	$url = parse_url($link);
	$file = zbp_mb_pathinfo($url['path']);

	// prepare filename and encode if need
	$filename = function_exists('mb_detect_encoding') && mb_detect_encoding($file['basename']) != "UTF-8" ? utf8_encode($file['basename']) : $file['basename']; 

	$link = $url['scheme'] . '://' . $url['host'] . $file['dirname'] . '/' . zbp_flash_entities(urlencode($filename));
	return $link;
}

// pathinfo with UTF-8 encoded file names too. Special thanks Pietro Baricco
function zbp_mb_pathinfo($filepath)
{
	preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',$filepath,$m);
	if($m[1]) $ret['dirname']=$m[1];
	if($m[2]) $ret['basename']=$m[2];
	if($m[5]) $ret['extension']=$m[5];
	if($m[3]) $ret['filename']=$m[3];
	return $ret;
}

// replace special symbols to do not destoy flash vars
function zbp_flash_entities($string)
{ 
	return str_replace(array("%", "&","'"),array("%25","%26","%27"),$string); 
} 

// See if we need to install/update
if (get_option('zbp_version') != ZBPLAYER_VERSION) zbp_setup(ZBPLAYER_VERSION);

// Add the script
function zbp_add_pages() {
	// Add a new submenu under options
	add_options_page('zbPlayer','zbPlayer',6,'zbplayer','zbp_manage_page');
}

// Management Page
function zbp_manage_page() {
	include_once('zbPlayer.admin.php');
}


// Setup Function
function zbp_setup($ZBPLAYER_VERSION) {
	update_option('zbp_version',$ZBPLAYER_VERSION);
}

// Loads language files according to locale (only does this once per request)
function zbp_load_language_file() {
	if (function_exists('load_plugin_textdomain')) {
		load_plugin_textdomain('zbplayer', false, dirname( plugin_basename(__FILE__) ) . '/languages');
	}
}
