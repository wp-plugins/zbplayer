<?php
/*
 *	zbPlayer Wordpress Plugin
 *	(c) 2013 Vladimir Gilevich
 *	Dual Licensed under the MIT and GPL licenses
 *  See license.txt, included with this package for more
 *
 *	zbPlayer.admin.php
 *  Release 1.8.1 May 2013
 */
?>
<div class="wrap">
<div id="icon-plugins" class="icon32"><br/></div>
<h2><?php _e('zbPlayer', 'zbplayer'); ?></h2>
<br class="clear"/>


<div id="poststuff" style="position: relative; margin-top:10px;">
<div style="width:75%; float:left;">
<div class="postbox">
<h3><?php _e('zbPlayer Options', 'zbplayer'); ?></h3>
<div class="inside">
<?php
// Business Logic
if (isset($_POST['action'])) {
	if (!isset($_POST['_wpnonce'])) die("There was a problem authenticating. Please log out and log back in");
	if (!check_admin_referer('zbp-update_options')) die("There was a problem authenticating. Please log out and log back in");
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
		?><div class="updated"><p><strong>Options Updated</strong></p></div><?php
	}
}


?>
    <p>In most cases the way it's configured out of the box is just about right, but feel free to play with it.</p>
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
            	<th scope="row">Initial volume</th>
                <td>
                	<input type="text" name="zbp_initialvolume" id="zbp_initialvolume" value="<?php echo get_option('zbp_initialvolume'); ?>" size="3" maxlength="3"/>
                    <br />
                    <span call="explanatory-text">Set here default value for volume of sound.</span>                	
                </td>
            </tr>
            <tr valign="top" id="zbp_show_name_row" <?php if (get_option('zbp_collect_mp3') == 'true') echo "style='display: none;'"; ?>>
            	<th scope="row">Show song name</th>
                <td>
                	<input type="checkbox" name="zbp_show_name" id="zbp_show_name" <?php if (get_option('zbp_show_name') == 'Y') echo "checked"; ?> onchange="zbpSwitchDownload()"/>
                    <label for="zbp_show_name">Show song name above the player</label>
                </td>
            </tr>  
            <tr valign="top" id="zbp_download_row" <?php if (get_option('zbp_show_name') != 'Y' || get_option('zbp_collect_mp3') == 'true') echo "style='display: none;'"; ?>>
            	<th scope="row"></th>
                <td>
                	<input type="checkbox" name="zbp_download" id="zbp_download" <?php if (get_option('zbp_download') == 'true') echo "checked"; ?> />
                    <label for="zbp_download">Include a static download link</label>
                    <br />
                    <span call="explanatory-text">Whether to include a link next to the flash player to download the file.</span>
                </td>
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


<div style="width:24%; float:right;">

<div class="postbox" style="min-width:200px;">
<h3><?php _e('Donation', 'zbplayer'); ?></h3>
<div class="inside">
<p><?php _e('If you liked this plugin, please make a donation via skrill! Any amount is welcome. Your support is much appreciated.', 'zbplayer'); ?></p>
<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
<input type="hidden" name="pay_to_email" value="vladimir@gilevich.com">
<input type="hidden" name="return_url" value="http://gilevich.com/thankyou/">
<input type="hidden" name="language" value="EN">
<input type="hidden" name="currency" value="USD">
<input type="hidden" name="detail1_description" value="Assistance in the development of plugin">
<input type="hidden" name="detail1_text" value="zbPlayer plugin">
<p><img src="http://gilevich.com/images/skrill-chkout_cc_110x52.gif" align="left" style="margin-right: 20px;"/>
Amount: <input type="text" name="amount" value="5.00" size="10">$<br/>
<input type="submit" alt="click to make a donation to zbPlayer plugin" class="button-primary" style="width: 160px" value="Donate"></p>
</form>

</div>
</div>

</div>

</div>
<script>
function zbpSwitchDownload()
{
  var newStatus = (document.getElementById('zbp_download_row').style.display == 'none') ? '' : 'none';
  document.getElementById('zbp_download_row').style.display = newStatus;
}

function zbpSwitchCollectMp3()
{
  var newStatus = (document.getElementById('zbp_collect_row').style.display == 'none') ? '' : 'none';
  document.getElementById('zbp_collect_row').style.display = newStatus;

  nameStatus = newStatus == 'none' ? '' : 'none';
  document.getElementById('zbp_show_name_row').style.display = nameStatus;

  var newStatus = (nameStatus == '' && document.getElementById('zbp_show_name').checked) ? '' : 'none';
  document.getElementById('zbp_download_row').style.display = newStatus;
}
</script>
