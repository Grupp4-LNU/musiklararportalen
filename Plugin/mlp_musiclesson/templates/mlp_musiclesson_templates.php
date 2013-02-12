<?php
/**
 * @package MLP MusicLesson
 */
 
add_filter( 'template_include', 'include_template_function', 1);

function include_template_function( $template_path ) {
    if ( get_post_type() == 'mlp_musiclesson' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-mlp_musiclesson.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = MLP_MUSICLESSON_PLUGIN_PATH.'templates/single-mlp_musiclesson.php';
            }
        }
        elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array ( 'archive-mlp_musiclesson.php' ) ) ) {
                $template_path = $theme_file;
            } else {
            	 $template_path = MLP_MUSICLESSON_PLUGIN_PATH.'templates/archive-mlp_musiclesson.php'; 
            }
        }		
    }
    return $template_path;
}