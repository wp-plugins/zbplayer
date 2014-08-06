<?php
/**
Plugin Name: zbPlayer
Plugin URI: http://gilevich.com/portfolio/zbplayer
Description: Converts mp3 files links to a small flash player and a link to download file mp3 file. Also you can share your mp3 files with that plugin.
Version: 2.1.8
Author: Vladimir Gilevich
Author URI: http://gilevich.com/
Licence: Dual Licensed under the MIT and GPL licenses. See license.txt, included with this package for more
*/

define('ZBPLAYER_VERSION', "2.1.8");
define('ZBPLAYER_DEFAULT_WIDTH', "500");
define('ZBPLAYER_DEFAULT_INITIALVOLUME', "60");
define('ZBPLAYER_DEFAULT_SHOW_NAME', "Y");
define('ZBPLAYER_DEFAULT_ANIMATION', 'true');
define('ZBPLAYER_DEFAULT_COLLECT_FIELD', "[zbplayer]");
define('ZBPLAYER_SHARER_URL', "https://www.facebook.com/sharer/sharer.php?u=");
define('ZBPLAYER_OGTAGS_URL', "https://apps.gilevich.com/zbplayer/share/link/");
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

WP_Enqueue_Style('zbplayer-style', get_bloginfo('wpurl').'/'.str_replace("\\", '/', substr(realpath(dirname(__FILE__)), strlen(ABSPATH))) . '/css/zbPlayer.css');
WP_Enqueue_Script('zbplayer-flash', get_bloginfo('wpurl').'/'.str_replace("\\", '/', substr(realpath(dirname(__FILE__)), strlen(ABSPATH))) . '/js/zbPlayerFlash.js');

function zbp_init()
{
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

/**
 * Replace mp3 links in content with player 
 *
 * @param string $content
 * @return string
 */
function zbp_content($content)
{
    // Replace mp3 links (don't do this in feeds and excerpts)
    if ( !is_feed() ) {
        @ini_set('pcre.backtrack_limit', max(10000000, ini_get('pcre.backtrack_limit')));
        $pattern = '#<a.*href=[\'"]((http://|https://).*/.*(\.mp3|\.m4a|\.m4b|\.mp4|\.wav))[\'"].*>.*</a>#imU';
        if (get_option('zbp_collect_mp3') == 'true') {
            preg_match_all( $pattern, $content, $matches );
            $titles = array();
            $links = array();
            if (count($matches) && isset($matches[1]) && count($matches[1])) {
                $patternTitle = '/^<a.*?data-title=(["\'])(.*?)\1.*$/';
                foreach($matches[1] as $key => $link) {
                    preg_match_all( $patternTitle, $matches[0][$key], $matchesTitle );
                    $titles[] = isset($matchesTitle[2][0]) ? $matchesTitle[2][0] : urlencode( str_replace('_', '', strip_tags($matches[0][$key])) );
                    $links[] = $link;
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
                    . '&amp;encode=yes&amp;soundFile='.zbp_encode_source(implode(',',$links))
                    . '&amp;autostart='.$autostart.'" type="application/x-shockwave-flash" class="player" src="'.plugin_dir_url(__FILE__).'data/player.swf" id="zbPlayer"/></div>';
                $content = str_replace(get_option('zbp_collect_field'), $player, $content);
            } else {
                // fix if error occured for preg_match_all()
                $content = str_replace(get_option('zbp_collect_field'), '', $content);
            }
        } else {
            // let's try find exact value or expected replaces to do not have limit problems with preg_replace()
            preg_match_all( $pattern, $content, $matches );
            $expectedReplaces = (is_array($matches) && count($matches)) ? count($matches[0]) : -1;
            $result = preg_replace_callback( $pattern, "zbp_insert_player", $content, $expectedReplaces );
            // fix if error occured for preg_replace_callback()
            $content = empty($result) ? $content : $result;
            // add share popup
            $content .= PHP_EOL . "<script>
function zbpShare(url) {
  var sharer = '".ZBPLAYER_SHARER_URL."'+url;
  window.open(sharer, 'sharer', 'width=626,height=436');
  return false;
}
var zbPregResult = '".preg_last_error()."';
</script>" . PHP_EOL;
        }
        @ini_restore('pcre.backtrack_limit');
    }
    return $content;
}

/**
 * Main code - insert player into content
 *
 * @param array $matches
 * @return string
 */
function zbp_insert_player($matches)
{
    $link = preg_split("/[\|]/", $matches[1]);
    $link = $link[0];
    $name = str_replace('_', ' ', strip_tags($matches[0]));
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
        $shareUrl = ZBPLAYER_OGTAGS_URL . '?mp3='.zbp_urlencode($link);
        $shareImg = plugin_dir_url(__FILE__).'images/fb-share.png';
        $shareInline = get_option('zbp_share') == ZBPLAYER_SHARE_INLINE ? '<span> <a href="'.ZBPLAYER_SHARER_URL.$shareUrl.'" class="zbPlayer-share-inline" target="_blank" onClick="return zbpShare(\''.$shareUrl.'\')" rel="nofollow"><img src="'.$shareImg.'" style="height: 26px;"></a> </span>' : '';
        $shareSmall = get_option('zbp_share') == ZBPLAYER_SHARE_SMALL ? '<span> <a href="'.ZBPLAYER_SHARER_URL.$shareUrl.'" class="zbPlayer-share-small" target="_blank" onClick="return zbpShare(\''.$shareUrl.'\')" rel="nofollow"><img src="'.$shareImg.'" style="height: 21px;margin-bottom: -5px;"></a> </span>' : '';
    } else {
        $shareInline = $shareSmall = '';
    }

    $songname = get_option('zbp_show_name') == 'Y' ? $name . $download . $shareSmall: $download . $shareSmall;
    $songname .= !empty($songname) ? '<br/>' : '';
    $titles = (get_option('zbp_id3') == 'true') ? '' : '&amp;titles='.urlencode($titles);

    $ret = '<div class="zbPlayer">' . $songname
        . $shareInline
        . '<embed width="'.$width.'" height="26" wmode="transparent" menu="false" quality="high"'
        . ' flashvars="loop='.$loop.'&animation='.$animation.'&amp;playerID=zbPlayer&amp;initialvolume='.$initialvolume . zbp_get_color_srt()
        . $titles.'&amp;encode=yes&amp;soundFile='.zbp_encode_source($link)
        . '&amp;autostart='.$autostart.'" type="application/x-shockwave-flash" class="zbPlayerFlash" src="'.plugin_dir_url(__FILE__).'data/player.swf" id="zbPlayer"/>';
    $ret .= '<audio class="zbPlayerNative" src="'.$link.'" controls preload="none"></audio>';
    $ret .= '</div>';
    return $ret;
}

/**
 * Encodes the given string for flash player
 *
 * @param string $string String to encode
 * @return the encoded string
 */
function zbp_encode_source($string)
{
    $source = utf8_decode($string);
    $ntexto = "";
    $codekey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";
    for ($i = 0; $i < strlen($string); $i++) {
				$ntexto .= substr("0000".base_convert(ord($string{$i}), 10, 2), -8);
    }
    $ntexto .= substr("00000", 0, 6-strlen($ntexto)%6);
    $string = "";
    for ($i = 0; $i < strlen($ntexto)-1; $i = $i + 6) {
				$string .= $codekey{intval(substr($ntexto, $i, 6), 2)};
    }
    
    return $string;
}

/**
 * own ulrencode method - need to convert to utf8 filename if it is not in utf8
 *
 * @param string $link
 * @return stting
 */
function zbp_urlencode($link)
{
    $url = parse_url($link);
    $file = zbp_mb_pathinfo($url['path']);

    // prepare filename and encode if need
    $filename = function_exists('mb_detect_encoding') && mb_detect_encoding($file['basename']) != "UTF-8" ? utf8_encode($file['basename']) : $file['basename']; 

    $link = $url['scheme'] . '://' . $url['host'] . $file['dirname'] . '/' . zbp_flash_entities(urlencode($filename));
    return $link;
}

/**
 * pathinfo with UTF-8 encoded file names too. Special thanks Pietro Baricco
 *
 * @param string $filepath
 * @return array
 */
function zbp_mb_pathinfo($filepath)
{
    preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $m);
    if ($m[1]) $ret['dirname'] = $m[1];
    if ($m[2]) $ret['basename'] = $m[2];
    if ($m[5]) $ret['extension'] = $m[5];
    if ($m[3]) $ret['filename'] = $m[3];
    return $ret;
}

/**
 * replace special symbols to do not destoy flash vars
 *
 * @param string $string
 * @return string
 */
function zbp_flash_entities($string)
{ 
    return str_replace(array("%", "&","'"), array("%25","%26","%27"), $string); 
} 

// See if we need to install/update
if (get_option('zbp_version') != ZBPLAYER_VERSION) {
    zbp_setup(ZBPLAYER_VERSION);
}

/**
 * Add the script
 */
function zbp_add_pages()
{
    // Add a new submenu under options
    add_options_page('zbPlayer', 'zbPlayer', 'manage_options', 'zbplayer', 'zbp_manage_page');
}

/**
 * Management Page
 */
function zbp_manage_page()
{
    include_once('zbPlayer.admin.php');
}


/**
 * Setup Function
 *
 * @param string $ZBPLAYER_VERSION
 */
function zbp_setup($ZBPLAYER_VERSION)
{
    update_option('zbp_version', $ZBPLAYER_VERSION);
}

/**
 * Loads language files according to locale (only does this once per request)
 */
function zbp_load_language_file()
{
    if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain('zbplayer', false, dirname( plugin_basename(__FILE__) ) . '/languages');
    }
}

function zbp_get_color_srt($delim='&amp;')
{
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