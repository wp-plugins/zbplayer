<?php
/**
 *	zbPlayer Wordpress Plugin
 *	(c) 2013-2014 Vladimir Gilevich
 *	Dual Licensed under the MIT and GPL licenses
 *  See license.txt, included with this package for more
 *
 *	zbPlayer.admin.php
 *  Release 2.1.8 August 2014
 */
// connect wordpress color picker
WP_Enqueue_Script('farbtastic');
WP_Enqueue_Style('farbtastic');
WP_Enqueue_Script('zbplayer-options', get_bloginfo('wpurl').'/'.str_replace("\\", '/', substr(realpath(dirname(__FILE__)), strlen(ABSPATH))) . '/js/zbPlayerColors.js');
?>
<div class="wrap">
<div id="icon-plugins" class="icon32"><br/></div>
<h2><?php _e('zbPlayer', 'zbplayer'); ?></h2>
<br class="clear"/>


<div id="poststuff" style="position: relative; margin-top:10px;">
<div style="width:65%; float:left;">
<div class="postbox">
<h3><?php _e('zbPlayer Options', 'zbplayer'); ?></h3>
<div class="inside">
<?php
// Business Logic
if (isset($_POST['action'])) {
    if (!isset($_POST['_wpnonce'])) {
        die("There was a problem authenticating. Please log out and log back in");   
    }
    if (!check_admin_referer('zbp-update_options')) {
        die("There was a problem authenticating. Please log out and log back in");   
    }
    if ($_POST['action'] == 'update') {
        if (isset($_POST['zbp_width'])) {
            update_option('zbp_width',intval($_POST['zbp_width']));
        } else {
            update_option('zbp_width',ZBPLAYER_DEFAULT_WIDTH);
        }
        if (isset($_POST['zbp_autostart'])) {
            update_option('zbp_autostart','true');
        } else {
            update_option('zbp_autostart','false');
        }
        if (isset($_POST['zbp_loop'])) {
            update_option('zbp_loop','true');
        } else {
            update_option('zbp_loop','false');
        }
        if (isset($_POST['zbp_show_name'])) {
            update_option('zbp_show_name','Y');
        } else {
            update_option('zbp_show_name','N');
        }
        if (isset($_POST['zbp_download'])) {
            update_option('zbp_download','true');
        } else {
            update_option('zbp_download','false');
        }
        if (isset($_POST['zbp_id3'])) {
            update_option('zbp_id3','true');
        } else {
            update_option('zbp_id3','false');
        }
        if (isset($_POST['zbp_animation'])) {
            update_option('zbp_animation','true');
        } else {
            update_option('zbp_animation','false');
        }
        if (isset($_POST['zbp_collect_mp3'])) {
            update_option('zbp_collect_mp3','true');
        } else {
            update_option('zbp_collect_mp3','false');
        }
        if (isset($_POST['zbp_collect_field'])) {
            update_option('zbp_collect_field',$_POST['zbp_collect_field']);
        } else {
            update_option('zbp_collect_field',ZBPLAYER_DEFAULT_COLLECT_FIELD);
        }
        if (isset($_POST['zbp_initialvolume'])) {
            update_option('zbp_initialvolume',intval($_POST['zbp_initialvolume']));
        } else {
            update_option('zbp_initialvolume',ZBPLAYER_DEFAULT_INITIALVOLUME);
        }
        if (isset($_POST['zbp_show_share'])) {
            update_option('zbp_show_share','true');

            if (isset($_POST['zbp_share']) && $_POST['zbp_share'] == ZBPLAYER_SHARE_SMALL) {
                update_option('zbp_share',ZBPLAYER_SHARE_SMALL);
            } else {
                update_option('zbp_share',ZBPLAYER_SHARE_INLINE);
            }
        } else {
            update_option('zbp_show_share','false');
        }
        ?><div class="updated"><p><strong>Options Updated</strong></p></div><?php
    } else if ($_POST['action'] == 'updateColor') {
        update_option('zbp_bg_color',$_POST['zbp_bg_color']);
        update_option('zbp_bg_left_color',$_POST['zbp_bg_left_color']);
        update_option('zbp_icon_left_color',$_POST['zbp_icon_left_color']);
        update_option('zbp_voltrack_color',$_POST['zbp_voltrack_color']);
        update_option('zbp_volslider_color',$_POST['zbp_volslider_color']);
        update_option('zbp_bg_right_color',$_POST['zbp_bg_right_color']);
        update_option('zbp_bg_right_hover_color',$_POST['zbp_bg_right_hover_color']);
        update_option('zbp_icon_right_color',$_POST['zbp_icon_right_color']);
        update_option('zbp_icon_right_hover_color',$_POST['zbp_icon_right_hover_color']);
        update_option('zbp_loader_color',$_POST['zbp_loader_color']);
        update_option('zbp_track_color',$_POST['zbp_track_color']);
        update_option('zbp_tracker_color',$_POST['zbp_tracker_color']);
        update_option('zbp_border_color',$_POST['zbp_border_color']);
        update_option('zbp_skip_color',$_POST['zbp_skip_color']);
        update_option('zbp_text_color',$_POST['zbp_text_color']);
        ?><div class="updated"><p><strong>Colors Updated</strong></p></div><?php
    }
}


$imgPath = plugin_dir_url(__FILE__) . '/images/';
?>
    <p>In most cases the way it is configured out of the box is just about right, but feel free to play with it.</p>
    <p>zbPlayer Version: <em><?php echo get_option('zbp_version'); ?></em></p>
    <form id="zbp_options" name="zbp_options" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <table class="form-table">
    	<tbody>
            <tr valign="top">
            	<th scope="row">Autostart</th>
                <td>
                	<input type="checkbox" name="zbp_autostart" id="zbp_autostart" <?php if (get_option('zbp_autostart') == 'true') echo "checked"; ?> />
                    <label for="zbp_autostart">Autostart the player</label>
                    <br />
                    <span call="explanatory-text">Causes the player to start playin immediately on load if enabled.</span>                	
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Loop</th>
                <td>
                	<input type="checkbox" name="zbp_loop" id="zbp_loop" <?php if (get_option('zbp_loop') == 'true') echo "checked"; ?> />
                    <label for="zbp_loop">If checked, player will play in loops</label>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Animation</th>
                <td>
                	<input type="checkbox" name="zbp_animation" id="zbp_animation" <?php if (get_option('zbp_animation') == 'true') echo "checked"; ?> />
                    <label for="zbp_animation">If unchecked, player is always open</label>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Initial volume</th>
                <td>
                	<input type="text" name="zbp_initialvolume" id="zbp_initialvolume" value="<?php echo get_option('zbp_initialvolume'); ?>" size="3" maxlength="3"/>
                    <br />
                    <span call="explanatory-text">Set here default value for volume of sound.</span>                	
                </td>
            </tr>
            <tr valign="top" id="zbp_id3_row">
            	<th scope="row">ID3 tags</th>
                <td>
                	<input type="checkbox" name="zbp_id3" id="zbp_id3" <?php if (get_option('zbp_id3') == 'true') echo "checked"; ?> />
                    <label for="zbp_id3">Use ID3 information from file</label>
                    <br />
                    <span call="explanatory-text">In that case player will always try user ID3 info from file instead link name.</span>
                </td>
            </tr>
            <tr valign="top" id="zbp_show_name_row" <?php if (get_option('zbp_collect_mp3') == 'true') echo "style='display: none;'"; ?>>
            	<th scope="row">Show song name</th>
                <td>
                	<input type="checkbox" name="zbp_show_name" id="zbp_show_name" <?php if (get_option('zbp_show_name') == 'Y') echo "checked";?>/>
                    <label for="zbp_show_name">Show song name above the player</label>
                </td>
            </tr>  
            <tr valign="top" id="zbp_download_row">
            	<th scope="row">Download link</th>
                <td>
                	<input type="checkbox" name="zbp_download" id="zbp_download" <?php if (get_option('zbp_download') == 'true') echo "checked"; ?> />
                    <label for="zbp_download">Include a static download link</label>
                    <br />
                    <span call="explanatory-text">Whether to include a link next to the flash player to download the file.</span>
                </td>
            </tr>
            <tr valign="top" id="zbp_show_share_row" <?php if (get_option('zbp_collect_mp3') == 'true') echo "style='display: none;'"; ?>>
            	<th scope="row"><b>Show Facebook Share Button</b></th>
                <td>
                	<input type="checkbox" name="zbp_show_share" id="zbp_show_share" <?php if (get_option('zbp_show_share') == 'true') echo "checked"; ?> onchange="zbpSwitchShare()"/>
                    <label for="zbp_show_share">Possibility to share mp3 file on Facebook</label>
                </td>
            </tr>  
            <tr valign="top" id="zbp_share_row" <?php if (get_option('zbp_show_share') != 'true' || get_option('zbp_collect_mp3') == 'true') echo "style='display: none;'"; ?>>
            	<th scope="row"></th>
                <td>
                	<input type="radio" name="zbp_share" id="zbp_share_inline" <?php if (get_option('zbp_share') == ZBPLAYER_SHARE_INLINE) echo "checked"; ?> value="<?php echo ZBPLAYER_SHARE_INLINE?>"/>
                    <label for="zbp_share_inline">Include before flash player<br/><img src="<?php echo $imgPath?>zbp_share_inline.png" style="margin-left: 10px;"></label>
                    <br />
                	<input type="radio" name="zbp_share" id="zbp_share_small" <?php if (get_option('zbp_share') == ZBPLAYER_SHARE_SMALL) echo "checked"; ?> value="<?php echo ZBPLAYER_SHARE_SMALL?>"/>
                    <label for="zbp_share_small">Include right after song name<br/><img src="<?php echo $imgPath?>zbp_share_small.png" style="margin-left: 10px;"></label>
                </td>
            </tr>  
            </tr>  
            <tr valign="top">
            	<th scope="row">Multiplayer</th>
                <td>
                	<input type="checkbox" name="zbp_collect_mp3" id="zbp_collect_mp3" <?php if (get_option('zbp_collect_mp3') == 'true') echo "checked"; ?> onchange="zbpSwitchCollectMp3()"/>
                    <label for="zbp_collect_mp3">Collect all mp3 links to one player</label>
                </td>
            </tr>  
            <tr valign="top" id="zbp_collect_row" <?php if (get_option('zbp_collect_mp3') != 'true') echo "style='display: none;'"; ?>>
            	<th scope="row"></th>
                <td>
                	<input type="text" name="zbp_collect_field" id="zbp_collect_field" value="<?php echo get_option('zbp_collect_field'); ?>" size="20" maxlength="20"/>
                    <br />
                    <span call="explanatory-text">Special tag in your post where to put player</span>
                </td>
            </tr>  
            <tr valign="top">
            	<th scope="row">Player Width</th>
                <td>
                	<input type="text" name="zbp_width" id="zbp_width" value="<?php echo get_option('zbp_width'); ?>" size="5" maxlength="5"/>
                    <br />
                    <span call="explanatory-text">Full width of player</span>
                </td>
            </tr>  
        </tbody>
    </table>
    <p class="submit">
    	<input type="hidden" name="action" value="update" />
        <?php wp_nonce_field('zbp-update_options'); ?>
    	<input type="submit" name="Submit" value="Save Changes" class="button-primary" />
    </p>
    </form>
</div>
</div>
</div>


<div style="width:34%; float:right;">

<div class="postbox" style="min-width:200px; padding-bottom: 0px;">
<h3><?php _e('Our ads', 'zbplayer'); ?></h3>
<div class="inside" style="padding-bottom: 0px;">
<center>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- music-publisher-lr -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-3706080085678049"
     data-ad-slot="9238930360"></ins>
<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</center>
</div>
</div>


<div class="postbox" style="min-width:200px;">
<h3><?php _e('Colour scheme options', 'zbplayer'); ?></h3>
<div class="inside">
<p><?php _e('All colour codes must be 6-digit HEX codes without ‘#’ or ’0x’ in front.', 'zbplayer'); ?></p>
<form id="zbp_color" name="zbp_color" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<table class="form-table">
<?php 
foreach(
    array(
        array( 'title' => __('Background', 'zbplayer'),
               'name' => 'zbp_bg_color',
               'default' => ZBPLAYER_COLOR_BG,
               'desc' => __('Background color inside the player. The environment of the player is always transparent.', 'zbplayer') ),

        array( 'title' => __('Background left', 'zbplayer'),
               'name' => 'zbp_bg_left_color',
               'default' => ZBPLAYER_COLOR_LEFTBG,
               'desc' => __('Background color of the speaker icon background/volume control.', 'zbplayer') ),

        array( 'title' => __('Icon left', 'zbplayer'),
               'name' => 'zbp_icon_left_color',
               'default' => ZBPLAYER_COLOR_LEFTICON,
               'desc' => __('Color of the speaker icon on the left hand.', 'zbplayer') ),

        array( 'title' => __('Volume track', 'zbplayer'),
               'name' => 'zbp_voltrack_color',
               'default' => ZBPLAYER_COLOR_VOLTRACK,
               'desc' => __('Color of the volume track on the left hand.', 'zbplayer') ),

        array( 'title' => __('Volume slider', 'zbplayer'),
               'name' => 'zbp_volslider_color',
               'default' => ZBPLAYER_COLOR_VOLSLIDER,
               'desc' => __('Color of the volume slider on the left hand.', 'zbplayer') ),

        array( 'title' => __('Background right', 'zbplayer'),
               'name' => 'zbp_bg_right_color',
               'default' => ZBPLAYER_COLOR_RIGHTBG,
               'desc' => __('Background color of the play/pause button.', 'zbplayer') ),

        array( 'title' => __('Background right (hover)', 'zbplayer'),
               'name' => 'zbp_bg_right_hover_color',
               'default' => ZBPLAYER_COLOR_RIGHTBGHOVER,
               'desc' => __('Color of the right background while hovering it with the mouse.', 'zbplayer') ),

        array( 'title' => __('Icon right', 'zbplayer'),
               'name' => 'zbp_icon_right_color',
               'default' => ZBPLAYER_COLOR_RIGHTICON,
               'desc' => __('Color of the play/pause icon.', 'zbplayer') ),

        array( 'title' => __('Icon right (hover)', 'zbplayer'),
               'name' => 'zbp_icon_right_hover_color',
               'default' => ZBPLAYER_COLOR_RIGHTICONHOVER,
               'desc' => __('Color of the right icon while hovering it with the mouse.', 'zbplayer') ),

        array( 'title' => __('Loading bar', 'zbplayer'),
               'name' => 'zbp_loader_color',
               'default' => ZBPLAYER_COLOR_LOADER,
               'desc' => __('Color of the loading bar.', 'zbplayer') ),

        array( 'title' => __('Track bar', 'zbplayer'),
               'name' => 'zbp_track_color',
               'default' => ZBPLAYER_COLOR_TRACK,
               'desc' => __('Color of the track bar.', 'zbplayer') ),

        array( 'title' => __('Tracker', 'zbplayer'),
               'name' => 'zbp_tracker_color',
               'default' => ZBPLAYER_COLOR_TRACKER,
               'desc' => __('Color of the progress track (tracker).', 'zbplayer') ),

        array( 'title' => __('Track bar border', 'zbplayer'),
               'name' => 'zbp_border_color',
               'default' => ZBPLAYER_COLOR_BORDER,
               'desc' => __('Color of the border surrounding the track bar.', 'zbplayer') ),

        array( 'title' => __('Skip', 'zbplayer'),
               'name' => 'zbp_skip_color',
               'default' => ZBPLAYER_COLOR_SKIP,
               'desc' => __('Previous/Next skip buttons.', 'zbplayer') ),

        array( 'title' => __('Text', 'zbplayer'),
               'name' => 'zbp_text_color',
               'default' => ZBPLAYER_COLOR_TEXT,
               'desc' => __('Color of the text in the middle area.', 'zbplayer') )
    ) as $color) : ?>
<tr valign="top">
  <th scope="row"><?php echo $color['title'] ?></th>
  <td>
    <input type="text" name="<?php echo $color['name'] ?>" value="<?php echo get_option($color['name'], $color['default']) ?>" class="color" /><br />
    <div class="colorpicker"></div>
    <small><?php echo $color['desc'] ?></small>
  </td>
</tr>
<?php endforeach; ?>
<tr valign="top">
  <th scope="row">&nbsp;</th>
  <td>
<p class="submit">
 	<input type="hidden" name="action" value="updateColor" />
     <?php wp_nonce_field('zbp-update_options'); ?>
 	<input type="submit" name="Submit" value="Save Changes" class="button-primary" />
</p>
  </td>
</tr>
</table>
</form>
</div>
</div>



</div>

</div>
<script>
function zbpSwitchShare()
{
  var newStatus = (document.getElementById('zbp_share_row').style.display == 'none') ? '' : 'none';
  document.getElementById('zbp_share_row').style.display = newStatus;
}

function zbpSwitchCollectMp3()
{
  var newStatus = (document.getElementById('zbp_collect_row').style.display == 'none') ? '' : 'none';
  document.getElementById('zbp_collect_row').style.display = newStatus;

  nameStatus = newStatus == 'none' ? '' : 'none';
  document.getElementById('zbp_show_name_row').style.display = nameStatus;
  document.getElementById('zbp_show_share_row').style.display = nameStatus;

  var newStatus = (nameStatus == '' && document.getElementById('zbp_show_name').checked) ? '' : 'none';
  document.getElementById('zbp_download_row').style.display = newStatus;

  var newStatus = (nameStatus == '' && document.getElementById('zbp_show_share').checked) ? '' : 'none';
  document.getElementById('zbp_share_row').style.display = newStatus;
}
</script>
