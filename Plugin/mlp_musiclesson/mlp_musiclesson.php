<?php
/**
 * @package MLP MusicLesson
 */
/*
	Plugin Name: MLP MusicLesson
	Plugin URI: http://musiklararportalen.se
	Description: Music lessons for your wordpress site.
	Version: 0.5
	Author: Musiklärarportalen
	Author URI: http://www.musiklararportalen.se
*/
define('MLP_MUSICLESSON_VERSION', '1.0');
define('MLP_MUSICLESSON_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

include_once MLP_MUSICLESSON_PLUGIN_PATH.'/mlp_musiclesson_functions.php';

function MLP_musiclesson_init() {	
	mlp_register_lesson_post_type();
	mlp_create_lesson_taxonomies();
	mlp_add_lesson_details_metabox();
}
add_action('init', 'MLP_musiclesson_init');

register_activation_hook( __FILE__, 'mlp_activate' );
register_deactivation_hook( __FILE__, 'mlp_deactivate' );