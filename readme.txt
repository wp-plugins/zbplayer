===zbPlayer ===
Contributors: zubaka
Donate link: http://gilevich.com/
Tags: mp3, flash player, audio, easy control, noJS, small mp3 player, embed, media player, music player, mp3 player, cyrillic mp3 player
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.7.1
License: Dual Licensed under the MIT and GPLv2 or later

zbPlayer is a small and very easy plugin. It does one thing: capture mp3 links and insert a small flash player instead.

== Description ==

zbPlayer is a very easy audio plugin - you can select some options like: include Download link or no, enable autoplay or no and setup width of player. Other things will be done by zbPlayer plugun automatically. One nice feature - player support cyrillic filenames without problem.

== Installation ==

The most basic installation is a simple two step:

1. Upload the `zbplayer` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

That's it, you're done.

== Frequently Asked Questions ==

= Do I need to do anything special in my posts? =

No, zbPlayer automatically converts any link to an mp3 file into player. So, if you put in &lt;a href="audio_file.mp3"&gt;A Link&lt;/a&gt; zbPlayer will automatically add a flash player on page.

= Does player support cyrillic names for mp3 files? =

Yes, here no problems with cyrillic filenames.

== Screenshots ==

1.  The minimized player.
2.  The expanded player.


== Known Issues ==

== Changelog ==

= 1.8 =
*    Added option in admin to show/hide song name above player.
*    Added "Multiplayer" option in admin to setup only one player on page with all mp3's

= 1.7.1 =
*    Fix for admin part in debug mode.

= 1.7 =
*    Added localization for 'Download' link

= 1.6 =
*    Added initial volume control for player.

= 1.5 =
*    Replaced characters from '_' to ' ' in song names.

= 1.4 =
*    Fixed problem with player path if wordpress works not from default directory
*    Fixed pathinfo function - now it works with UTF-8 encoded file names too

= 1.3 =
*    Allow to play files with cyrillic filenames.

= 1.2 =
*    Increased player width. Now player looks better.

= 1.1 =
*    Fix constants namings

= 1.0 =
*    Init version

== Upgrade Notice ==

= 1.0 =
*    Just Init version
