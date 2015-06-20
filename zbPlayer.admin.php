<?php
/**
 *	zbPlayer Wordpress Plugin
 *	(c) 2013-2015 Vladimir Gilevich
 *	Dual Licensed under the MIT and GPL licenses
 *  See license.txt, included with this package for more
 *
 *	zbPlayer.admin.php
 *  Release 2.1.10 June 2015
 */

// connect wordpress color picker
WP_Enqueue_Script('farbtastic');
WP_Enqueue_Style('farbtastic');

$appPath = str_replace("\\", '/', substr(realpath(dirname(__FILE__)), strlen(ABSPATH)));
WP_Enqueue_Script(
    'zbplayer-options',
    get_bloginfo('wpurl') . '/' . $appPath . '/js/zbPlayerColors.js'
);


// Define template variables
$submitMessage = '';
$imgPath = plugin_dir_url(__FILE__) . '/images/';


// Color fields
$colors = array(
    array(
        'title' => __('Background', 'zbplayer'),
        'name' => 'zbp_bg_color',
        'default' => ZBPLAYER_COLOR_BG,
        'desc' => __(
            'Background color inside the player. The environment of the player is always transparent.',
            'zbplayer'
        )
    ),
    array(
        'title' => __('Background left', 'zbplayer'),
        'name' => 'zbp_bg_left_color',
        'default' => ZBPLAYER_COLOR_LEFTBG,
        'desc' => __('Background color of the speaker icon background/volume control.', 'zbplayer')
    ),
    array(
        'title' => __('Icon left', 'zbplayer'),
        'name' => 'zbp_icon_left_color',
        'default' => ZBPLAYER_COLOR_LEFTICON,
        'desc' => __('Color of the speaker icon on the left hand.', 'zbplayer')
    ),
    array(
        'title' => __('Volume track', 'zbplayer'),
        'name' => 'zbp_voltrack_color',
        'default' => ZBPLAYER_COLOR_VOLTRACK,
        'desc' => __('Color of the volume track on the left hand.', 'zbplayer')
    ),
    array(
        'title' => __('Volume slider', 'zbplayer'),
        'name' => 'zbp_volslider_color',
        'default' => ZBPLAYER_COLOR_VOLSLIDER,
        'desc' => __('Color of the volume slider on the left hand.', 'zbplayer')
    ),
    array(
        'title' => __('Background right', 'zbplayer'),
        'name' => 'zbp_bg_right_color',
        'default' => ZBPLAYER_COLOR_RIGHTBG,
        'desc' => __('Background color of the play/pause button.', 'zbplayer')
    ),
    array(
        'title' => __('Background right (hover)', 'zbplayer'),
        'name' => 'zbp_bg_right_hover_color',
        'default' => ZBPLAYER_COLOR_RIGHTBGHOVER,
        'desc' => __('Color of the right background while hovering it with the mouse.', 'zbplayer')
    ),
    array(
        'title' => __('Icon right', 'zbplayer'),
        'name' => 'zbp_icon_right_color',
        'default' => ZBPLAYER_COLOR_RIGHTICON,
        'desc' => __('Color of the play/pause icon.', 'zbplayer')
    ),
    array(
        'title' => __('Icon right (hover)', 'zbplayer'),
        'name' => 'zbp_icon_right_hover_color',
        'default' => ZBPLAYER_COLOR_RIGHTICONHOVER,
        'desc' => __('Color of the right icon while hovering it with the mouse.', 'zbplayer')
    ),
    array(
        'title' => __('Loading bar', 'zbplayer'),
        'name' => 'zbp_loader_color',
        'default' => ZBPLAYER_COLOR_LOADER,
        'desc' => __('Color of the loading bar.', 'zbplayer')
    ),
    array(
        'title' => __('Track bar', 'zbplayer'),
        'name' => 'zbp_track_color',
        'default' => ZBPLAYER_COLOR_TRACK,
        'desc' => __('Color of the track bar.', 'zbplayer')
    ),
    array(
        'title' => __('Tracker', 'zbplayer'),
        'name' => 'zbp_tracker_color',
        'default' => ZBPLAYER_COLOR_TRACKER,
        'desc' => __('Color of the progress track (tracker).', 'zbplayer')
    ),
    array(
        'title' => __('Track bar border', 'zbplayer'),
        'name' => 'zbp_border_color',
        'default' => ZBPLAYER_COLOR_BORDER,
        'desc' => __('Color of the border surrounding the track bar.', 'zbplayer')
    ),
    array(
        'title' => __('Skip', 'zbplayer'),
        'name' => 'zbp_skip_color',
        'default' => ZBPLAYER_COLOR_SKIP,
        'desc' => __('Previous/Next skip buttons.', 'zbplayer')
    ),
    array(
        'title' => __('Text', 'zbplayer'),
        'name' => 'zbp_text_color',
        'default' => ZBPLAYER_COLOR_TEXT,
        'desc' => __('Color of the text in the middle area.', 'zbplayer')
    )
);

// Business Logic
if (isset($_POST['action'])) {
    if (!isset($_POST['_wpnonce'])) {
        die("There was a problem authenticating. Please log out and log back in");
    }

    if (!check_admin_referer('zbp-update_options')) {
        die("There was a problem authenticating. Please log out and log back in");
    }

    if ($_POST['action'] == 'update') {
        update_option(
            'zbp_width',
            isset($_POST['zbp_width']) ? intval($_POST['zbp_width']) : ZBPLAYER_DEFAULT_WIDTH
        );

        update_option(
            'zbp_autostart',
            isset($_POST['zbp_autostart']) ? 'true' : 'false'
        );

        update_option(
            'zbp_loop',
            isset($_POST['zbp_loop']) ? 'true' : 'false'
        );

        update_option(
            'zbp_show_name',
            isset($_POST['zbp_show_name']) ? 'Y' : 'N'
        );

        update_option(
            'zbp_download',
            isset($_POST['zbp_download']) ? 'true' : 'false'
        );

        update_option(
            'zbp_id3',
            isset($_POST['zbp_id3']) ? 'true' : 'false'
        );

        update_option(
            'zbp_animation',
            isset($_POST['zbp_animation']) ? 'true' : 'false'
        );

        update_option(
            'zbp_collect_mp3',
            isset($_POST['zbp_collect_mp3']) ? 'true' : 'false'
        );

        update_option(
            'zbp_collect_field',
            isset($_POST['zbp_collect_field']) ? $_POST['zbp_collect_field'] : ZBPLAYER_DEFAULT_COLLECT_FIELD
        );

        update_option(
            'zbp_initialvolume',
            isset($_POST['zbp_initialvolume']) ? intval($_POST['zbp_initialvolume']) : ZBPLAYER_DEFAULT_INITIALVOLUME
        );

        if (isset($_POST['zbp_show_share'])) {
            update_option('zbp_show_share', 'true');

            if (isset($_POST['zbp_share']) && $_POST['zbp_share'] == ZBPLAYER_SHARE_SMALL) {
                update_option('zbp_share', ZBPLAYER_SHARE_SMALL);
            } else {
                update_option('zbp_share', ZBPLAYER_SHARE_INLINE);
            }
        } else {
            update_option('zbp_show_share', 'false');
        }

        $submitMessage = 'Options Updated';
    } elseif ($_POST['action'] == 'updateColor') {
        update_option('zbp_bg_color', $_POST['zbp_bg_color']);
        update_option('zbp_bg_left_color', $_POST['zbp_bg_left_color']);
        update_option('zbp_icon_left_color', $_POST['zbp_icon_left_color']);
        update_option('zbp_voltrack_color', $_POST['zbp_voltrack_color']);
        update_option('zbp_volslider_color', $_POST['zbp_volslider_color']);
        update_option('zbp_bg_right_color', $_POST['zbp_bg_right_color']);
        update_option('zbp_bg_right_hover_color', $_POST['zbp_bg_right_hover_color']);
        update_option('zbp_icon_right_color', $_POST['zbp_icon_right_color']);
        update_option('zbp_icon_right_hover_color', $_POST['zbp_icon_right_hover_color']);
        update_option('zbp_loader_color', $_POST['zbp_loader_color']);
        update_option('zbp_track_color', $_POST['zbp_track_color']);
        update_option('zbp_tracker_color', $_POST['zbp_tracker_color']);
        update_option('zbp_border_color', $_POST['zbp_border_color']);
        update_option('zbp_skip_color', $_POST['zbp_skip_color']);
        update_option('zbp_text_color', $_POST['zbp_text_color']);
        $submitMessage = 'Colors Updated';
    }
}



require_once 'templates/zbPlayer.admin.tpl';
