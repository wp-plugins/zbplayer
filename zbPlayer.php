<?php
/*
Plugin Name: zbPlayer
Plugin URI: http://gilevich.com/portfolio/zbplayer
Description: Converts mp3 files links to a small flash player and a link to download file mp3 file. Also you can share your mp3 files with that plugin.
Version: 2.0.5
Author: Vladimir Gilevich
Author URI: http://gilevich.com/
****************************************************
/*
 *	zbPlayer Wordpress Plugin
 *	(c) 2013 Vladimir Gilevich
 *	Dual Licensed under the MIT and GPL licenses
 *  See license.txt, included with this package for more
 *
 *	zbPlayer.php
 *  Release 2.0.5, September 2013
 */

define('ZBPLAYER_VERSION', "2.0.5");
define('ZBPLAYER_DEFAULT_WIDTH', "500");
define('ZBPLAYER_DEFAULT_INITIALVOLUME', "60");
define('ZBPLAYER_DEFAULT_SHOW_NAME', "Y");
define('ZBPLAYER_DEFAULT_ANIMATION', 'true');
define('ZBPLAYER_DEFAULT_COLLECT_FIELD', "[zbplayer]");
define('ZBPLAYER_SHARER_URL', "https://www.facebook.com/sharer/sharer.php?u=");
define('ZBPLAYER_SHARE_SMALL', "small");
define('ZBPLAYER_SHARE_INLINE', "inline");

define('ZBPLAYER_COLOR_BG', "#E5E5E5");
define('ZBPLAYER_COLOR_LEFTBG', "#CCCCCC");
define('ZBPLAYER_COLOR_LEFTICON', "#333333");
define('ZBPLAYER_COLOR_VOLTRACK', "#F2F2F2");
define('ZBPLAYER_COLOR_VOLSLIDER', "#666666");
define('ZBPLAYER_COLOR_RIGHTBG', "#B4B4B4");
define('ZBPLAYER_COLOR_RIGHTBGHOVER', "#999999");
define('ZBPLAYER_COLOR_RIGHTICON', "#333333");
define('ZBPLAYER_COLOR_RIGHTICONHOVER', "#FFFFFF");
define('ZBPLAYER_COLOR_LOADER', "#009900");
define('ZBPLAYER_COLOR_TRACK', "#FFFFFF");
define('ZBPLAYER_COLOR_TRACKER', "#DDDDDD");
define('ZBPLAYER_COLOR_BORDER', "#CCCCCC");
define('ZBPLAYER_COLOR_SKIP', "#666666");
define('ZBPLAYER_COLOR_TEXT', "#333333");

// Hook to add scripts
add_action('admin_menu','zbp_add_pages');
add_filter('the_content', 'zbp_content');
add_action('plugins_loaded', 'zbp_init');

function zbp_init() {
	if (get_option('zbp_width') <= 0) {
		update_option('zbp_width',ZBPLAYER_DEFAULT_WIDTH);
	}
	if (get_option('zbp_show_name') == '') {
		update_option('zbp_show_name',ZBPLAYER_DEFAULT_SHOW_NAME);
	}
	if (get_option('zbp_animation') == '') {
		update_option('zbp_animation',ZBPLAYER_DEFAULT_ANIMATION);
	}
	if (get_option('zbp_collect_field') == '') {
		update_option('zbp_collect_field',ZBPLAYER_DEFAULT_COLLECT_FIELD);
	}
	if (get_option('zbp_share') == '') {
		update_option('zbp_share',ZBPLAYER_SHARE_INLINE);
	}
	if (get_option('zbp_bg_color') == '') {
		update_option('zbp_bg_color',ZBPLAYER_COLOR_BG);
		update_option('zbp_bg_left_color',ZBPLAYER_COLOR_LEFTBG);
		update_option('zbp_icon_left_color',ZBPLAYER_COLOR_LEFTICON);
		update_option('zbp_voltrack_color',ZBPLAYER_COLOR_VOLTRACK);
		update_option('zbp_volslider_color',ZBPLAYER_COLOR_VOLSLIDER);
		update_option('zbp_bg_right_color',ZBPLAYER_COLOR_RIGHTBG);
		update_option('zbp_bg_right_hover_color',ZBPLAYER_COLOR_RIGHTBGHOVER);
		update_option('zbp_icon_right_color',ZBPLAYER_COLOR_RIGHTICON);
		update_option('zbp_icon_right_hover_color',ZBPLAYER_COLOR_RIGHTICONHOVER);
		update_option('zbp_loader_color',ZBPLAYER_COLOR_LOADER);
		update_option('zbp_track_color',ZBPLAYER_COLOR_TRACK);
		update_option('zbp_tracker_color',ZBPLAYER_COLOR_TRACKER);
		update_option('zbp_border_color',ZBPLAYER_COLOR_BORDER);
		update_option('zbp_skip_color',ZBPLAYER_COLOR_SKIP);
		update_option('zbp_text_color',ZBPLAYER_COLOR_TEXT);
	}
	zbp_load_language_file();
}

// Replace mp3 links in content with player 
function zbp_content($content)
{
  // Replace mp3 links (don't do this in feeds and excerpts)
  if ( !is_feed() ) {
    $pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+(\.mp3|\.m4a|\.m4b|\.mp4|\.wav)))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
		if (get_option('zbp_collect_mp3') == 'true') {
			preg_match_all( $pattern, $content, $matches );
			$titles = array();
			$links = array();
			if (count($matches) && isset($matches[3]) && count($matches[3])) {
				$patternTitle = '/^<a.*?data-title=(["\'])(.*?)\1.*$/';
				foreach($matches[3] as $key => $link) {
					preg_match_all( $patternTitle, $matches[0][$key], $matchesTitle );
					$titles[] = isset($matchesTitle[2][0]) ? $matchesTitle[2][0] : urlencode( str_replace('_', '', $matches[6][$key]) );
					$links[] = zbp_urlencode($link);
				}
			}
			if (count($links)) {
				$loop = get_option('zbp_loop') == 'true' ? 'yes' : 'no';
				$autostart = get_option('zbp_autostart') == 'true' ? 'yes' : 'no';
				$animation = get_option('zbp_animation') == 'true' ? 'yes' : 'no';
				$initialvolume = intval(get_option('zbp_initialvolume')) ? intval(get_option('zbp_initialvolume')) : ZBPLAYER_DEFAULT_INITIALVOLUME;
				$width = get_option('zbp_width') > 0 ? intval(get_option('zbp_width')) : ZBPLAYER_DEFAULT_WIDTH;
				$titles = (get_option('zbp_id3') == 'true') ? '' : '&amp;titles='.implode(',',$titles);
				$player = '<div class="zbPlayer">'
					. '<embed width="'.$width.'" height="26" wmode="transparent" menu="false" quality="high"'
					. ' flashvars="loop='.$loop.'&animation='.$animation.'&amp;playerID=zbPlayer&amp;initialvolume='.$initialvolume . zbp_get_color_srt()
					. $titles
					. '&amp;soundFile='.implode(',',$links)
					. '&amp;autostart='.$autostart.'" type="application/x-shockwave-flash" class="player" src="'.plugin_dir_url(__FILE__).'data/player.swf" id="zbPlayer"/></div>';
				$content = str_replace(get_option('zbp_collect_field'), $player, $content);
			}
		} else {
			$content = preg_replace_callback( $pattern, "zbp_insert_player", $content );
			// add share popup
			$content .= PHP_EOL . "<script>
function zbpShare(url) {
  var sharer = '".ZBPLAYER_SHARER_URL."'+url;
  window.open(sharer, 'sharer', 'width=626,height=436');
  return false;
}
</script>" . PHP_EOL;
		}
  }
  return $content;
}

// Main code - insert player into content
function zbp_insert_player($matches)
{
  $link = preg_split("/[\|]/", $matches[3]);
  $link = $link[0];
  $name = str_replace('_', ' ', $matches[6]);
	$titles = str_replace('&#8211;', '-', $name);
	$titles = str_replace('&#8212;', '-', $name);
	$titles = str_replace('&#038;', '&', $titles);
	$titles = str_replace('&amp;', '&', $titles);
	if (get_option('zbp_download') == 'true') {
		$linkInfo = zbp_mb_pathinfo($link);
		$downloadName = isset($linkInfo['basename']) && $linkInfo['basename'] ? $linkInfo['basename'] : 'song.mp3';
	} 
	$download = get_option('zbp_download') == 'true' ? '<span> [<a href="'.$link.'" class="zbPlayer-download" download="'.$downloadName.'">'.__("Download", 'zbplayer').'</a>] </span>' : '';
	$loop = get_option('zbp_loop') == 'true' ? 'yes' : 'no';
	$autostart = get_option('zbp_autostart') == 'true' ? 'yes' : 'no';
	$animation = get_option('zbp_animation') == 'true' ? 'yes' : 'no';
	$initialvolume = intval(get_option('zbp_initialvolume')) ? intval(get_option('zbp_initialvolume')) : ZBPLAYER_DEFAULT_INITIALVOLUME;
	$width = get_option('zbp_width') > 0 ? intval(get_option('zbp_width')) : ZBPLAYER_DEFAULT_WIDTH;

	// share functionality
	if (get_option('zbp_show_share') == 'true') {
		$shareUrl = urlencode('https://ssl.perfora.net/mp3.gilevich.com/share/link/?mp3='.zbp_urlencode($link).'&title='.urlencode($name));
		$shareImg = plugin_dir_url(__FILE__).'images/fb-share.png';
		$shareInline = get_option('zbp_share') == ZBPLAYER_SHARE_INLINE ? '<span> <a href="'.ZBPLAYER_SHARER_URL.$shareUrl.'" class="zbPlayer-share-inline" target="_blank" onClick="return zbpShare(\''.$shareUrl.'\')"><img src="'.$shareImg.'" style="height: 26px;"></a> </span>' : '';
		$shareSmall = get_option('zbp_share') == ZBPLAYER_SHARE_SMALL ? '<span> <a href="'.ZBPLAYER_SHARER_URL.$shareUrl.'" class="zbPlayer-share-small" target="_blank" onClick="return zbpShare(\''.$shareUrl.'\')"><img src="'.$shareImg.'" style="height: 21px;margin-bottom: -5px;"></a> </span>' : '';
	} else {
		$shareInline = $shareSmall = '';
	}

	$songname = get_option('zbp_show_name') == 'Y' ? $name . $download . $shareSmall . '<br/>' : '';
	$titles = (get_option('zbp_id3') == 'true') ? '' : '&amp;titles='.urlencode($titles);

  $ret = '<div class="zbPlayer">' . $songname
		. $shareInline
		. '<embed width="'.$width.'" height="26" wmode="transparent" menu="false" quality="high"'
		. ' flashvars="loop='.$loop.'&animation='.$animation.'&amp;playerID=zbPlayer&amp;initialvolume='.$initialvolume . zbp_get_color_srt()
		. $titles.'&amp;soundFile='.zbp_urlencode($link)
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
	add_options_page('zbPlayer','zbPlayer','manage_options','zbplayer','zbp_manage_page');
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

function zbp_get_color_srt($delim='&amp;') {
	$color = $delim . 'bg=' .	get_option('zbp_bg_color');
	$color .= $delim . 'leftbg=' . get_option('zbp_bg_left_color');
	$color .= $delim . 'lefticon=' . get_option('zbp_icon_left_color');
	$color .= $delim . 'voltrack=' . get_option('zbp_voltrack_color');
	$color .= $delim . 'volslider=' . get_option('zbp_volslider_color');
	$color .= $delim . 'rightbg=' . get_option('zbp_bg_right_color');
	$color .= $delim . 'rightbghover=' . get_option('zbp_bg_right_hover_color');
	$color .= $delim . 'righticon=' . get_option('zbp_icon_right_color');
	$color .= $delim . 'righticonhover=' . get_option('zbp_icon_right_hover_color');
	$color .= $delim . 'loader=' . get_option('zbp_loader_color');
	$color .= $delim . 'track=' . get_option('zbp_track_color');
	$color .= $delim . 'tracker=' . get_option('zbp_tracker_color');
	$color .= $delim . 'border=' . get_option('zbp_border_color');
	$color .= $delim . 'skip=' . get_option('zbp_skip_color');
	$color .= $delim . 'text=' . get_option('zbp_text_color');
	return str_replace('#', '', $color);
}