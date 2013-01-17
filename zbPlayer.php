<?php
/*
Plugin Name: zbPlayer
Plugin URI: http://gilevich.com/portfolio/zbplayer
Description: Converts mp3 files links to a small flash player and a link to download file mp3 file. Player by outdated plugin <a href="http://wpaudioplayer.com/">WordPress Audio Player</a>.
Version: 1.2
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
 *  Release 1.2, January 2013
 */
?>
<?php

define('ZBPLAYER_VERSION', "1.2");
define('ZBPLAYER_DEFAULT_WIDTH', "500");

// Hook to add scripts
add_action('admin_menu','zbp_add_pages');
add_filter('the_content', 'zbp_content');
add_action("plugins_loaded", "zbp_init");

function zbp_init() {
	if (get_option('zbp_width') <= 0) {
		update_option('zbp_width',ZBPLAYER_DEFAULT_WIDTH);
	}
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
  $name = $matches[5];
	$download = get_option('zbp_download') == 'true' ? '<span> [<a href="'.$link.'" class="zbPlayer-download">Download</a>] </span>' : '';
	$autostart = get_option('zbp_autostart') == 'true' ? 'yes' : 'no';
	$width = get_option('zbp_width') > 0 ? intval(get_option('zbp_width')) : ZBPLAYER_DEFAULT_WIDTH;
  $ret = '<div class="zbPlayer"><a href="'.$link.'" class="zbPlayer">'.$name.'</a>' . $download . '<br/>'
   . '<embed width="'.$width.'" height="26" wmode="transparent" menu="false" quality="high"'
		. ' flashvars="playerID=zbPlayer&amp;titles='.$name.'&amp;soundFile='.urlencode($link)
		. '&amp;autostart='.$autostart.'" type="application/x-shockwave-flash" class="player" src="/wp-content/plugins/zbplayer/data/player.swf" id="zbPlayer"/></div>';
  return $ret;
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