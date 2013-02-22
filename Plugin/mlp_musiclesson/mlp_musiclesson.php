<?php
/**
 * @package MLP MusicLesson
 */
/*
	Plugin Name: MLP MusicLesson
	Plugin URI: http://musiklararportalen.se
	Description: Music lessons for your wordpress site.
	Version: 0.2
	Author: Musiklärarportalen
	Author URI: http://www.musiklararportalen.se
*/
define('MLP_MUSICLESSON_VERSION', '0.2');
define('MLP_MUSICLESSON_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

include_once MLP_MUSICLESSON_PLUGIN_PATH.'/templates/mlp_musiclesson_templates.php';

include_once MLP_MUSICLESSON_PLUGIN_PATH.'/mlp_musiclesson_functions.php';
include_once MLP_MUSICLESSON_PLUGIN_PATH.'/mlp_musiclesson_shortcodes.php';

function MLP_musiclesson_init() {
	mlp_register_lesson_post_type();
	mlp_create_lesson_taxonomies();
	mlp_add_lesson_details_metabox();
	attach_css_files();
}

register_activation_hook( __FILE__, 'activate' );

add_action('init', 'MLP_musiclesson_init');