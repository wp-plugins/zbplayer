<?php
/*
 *	zbPlayer Wordpress Plugin
 *	(c) 2013 Vladimir Gilevich
 *	Dual Licensed under the MIT and GPL licenses
 *  See license.txt, included with this package for more
 *
 *	zbPlayer.admin.php
 *  Release 1.6 March 2013
 */
// zbPlayer Admin Page

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
		if (isset($_POST['zbp_download'])) {
			update_option('zbp_download','true');
		} else {
			update_option('zbp_download','false');
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
<div class="wrap">
	<h2>zbPlayer Management Page</h2>
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
            <tr valign="top">
            	<th scope="row">Download Link</th>
                <td>
                	<input type="checkbox" name="zbp_download" id="zbp_download" <?php if (get_option('zbp_download') == 'true') echo "checked"; ?> />
                    <label for="zbp_download">Include a static download link</label>
                    <br />
                    <span call="explanatory-text">Whether to include a link next to the flash player to download the file.</span>
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
    	<input type="submit" name="Submit" value="Configureate >" class="button" />
    </p>
    </form>
</div>
<?php
?>