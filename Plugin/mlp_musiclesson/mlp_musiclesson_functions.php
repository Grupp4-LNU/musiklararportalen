<?php
/**
 * Skapar Lektions post typen.
 */
function mlp_register_lesson_post_type()
{
	$args = array(
		'labels' => array(
			'name' => 'Lektioner',
			'singular_name' => "Lektion",
			'add_new' => 'Skapa Lektion',
			'add_new_item' => 'Skapa Lektion',
			'edit_item' => 'Redigera Lektion',
			'new_item' => 'Skapa Lektion',
			'view_item' => 'Visa Lektion',
			'search_items' => 'Sök bland lektioner',
			'not_found' => 'Ingen lektion hittad',
			'not_found_in_trash' => 'Ingen lektion hittad i papperskorgen'				
		),
		'query_var' => 'lektion',
		'rewrite' => array( 'slug' => 'lektioner'),
		'public' => true,
		'menu_position' => 5,
        'menu_icon' => plugins_url('/images/icon16.png', __FILE__),
		'supports' => array(
			'title',
			'thumbnail',
			'comments',
			'author'
		),
        'has_archive' => true,			
		'show_in_menu' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true
	);
	register_post_type('mlp_musiclesson', $args);
	
	
	$set = get_option('post_type_rules_flased_mlp_musiclesson');
	if ($set !== true){
	   flush_rewrite_rules(false);
	   update_option('post_type_rules_flased_mlp_musiclesson',true);
	}
}

/**
 * Skapar kategorierna.
 */
function mlp_create_lesson_taxonomies() {
	$taxonomies = array();
	
	$taxonomies['mlp_goal'] = array(
		'hierarchical' => true,
		'query_var' => 'mlp_goal',
		'rewrite' => array(
			'slug' => 'Mål'
		),
		'labels' => array(
			'name' => 'Mål',
			'singular_name' => "Mål",
			'add_new' => 'Lägg till Mål',
			'add_new_item' => 'Lägg till Mål',
			'edit_item' => 'Redigera Mål',
			'new_item' => 'Lägg till Mål',
			'view_item' => 'Visa Mål',
			'search_items' => 'Sök bland Mål',
			'not_found' => 'Inget Mål hittat',
			'not_found_in_trash' => 'Inget Mål hittat i papperskorgen'				
		)
	);
	
	$taxonomies['mlp_target_group'] = array(
		'hierarchical' => true,
		'query_var' => 'mlp_target_group',
		'rewrite' => array(
			'slug' => 'Målgrupp'
		),
		'labels' => array(
			'name' => 'Målgrupper',
			'singular_name' => "Målgrupp",
			'add_new' => 'Lägg till Målgrupp',
			'add_new_item' => 'Lägg till Målgrupp',
			'edit_item' => 'Redigera Målgrupp',
			'new_item' => 'Lägg till Målgrupp',
			'view_item' => 'Visa Målgrupp',
			'search_items' => 'Sök bland Målgrupper',
			'not_found' => 'Inga Målgrupper hittad',
			'not_found_in_trash' => 'Inga Målgrupper hittad i papperskorgen'				
		)
	);
	mlp_register_all_taxonomies($taxonomies);
}

/**
 * Registrerar kategorierna för WordPress.
 */
function mlp_register_all_taxonomies($taxonomies) {
	foreach ($taxonomies as $taxonomy => $array) {
		register_taxonomy($taxonomy, array('mlp_musiclesson'), $array);	
	}
}

/**
 * Lägger till post meta boxarna till adminpanelen samt skapar post metan.
 */
function mlp_add_lesson_details_metabox() {
	add_action('add_meta_boxes', function() {
		add_meta_box('mlp_lesson_details', 'Lektionsdetaljer', 'lesson_details', 'mlp_musiclesson');
	});
	
	function lesson_details($post) {
		$intro = get_post_meta($post->ID, 'mlp_intro', true);
		$execution = get_post_meta($post->ID, 'mlp_execution', true);
		?>
			<h3><label for='mlp_intro'>Förutsättningar</label></h3>
			<textarea class='widefat' id='mlp_intro' name='mlp_intro'><?php echo esc_attr($intro); ?></textarea>

			<h3><label for='mlp_execution'>Genomförande</label></h3>
			<textarea class='widefat' id='mlp_execution' name='mlp_execution'><?php echo esc_attr($execution); ?></textarea>

		<?php
	}

	add_action('save_post', function($id) {
		update_post_meta_data($id, 'mlp_intro');
		update_post_meta_data($id, 'mlp_execution');
	});
}

/**
 * Uppdatering av post_meta datan.
 */
function update_post_meta_data($id, $field) {
	if( isset($_POST[$field]) ) {
		update_post_meta(
			$id, 
			$field, 
			strip_tags($_POST[$field])
		);
	}
}

/**
 * Vad ska ske när pluginet aktiveras
 * Detta bestäms här...
 */
function mlp_activate() {
	mlp_create_delete_lesson_page();
	mlp_create_insert_edit_lesson_page();
	mlp_create_my_lesson_archive_page();
}

/**
 * Skapar en sida med template för att ta bort en lektion.
 */
function mlp_create_delete_lesson_page() {
	$post = array(
		'post_type' => 'page',
		'post_title' => 'Radera lektion',
		'post_name' => 'radera-lektion',
		'post_status' => 'publish'
	);
	$post_id = wp_insert_post( $post );
	
	global $wpdb;
	
	add_post_meta($post_id, '_wp_page_template','delete_lesson.php');
}

/**
 * Skapar en sida med template för skapa/redigera lektion.
 */
function mlp_create_insert_edit_lesson_page() {
	$post = array(
		'post_type' => 'page',
		'post_title' => 'Skapa lektion',
		'post_name' => 'skapa-lektion',
		'post_status' => 'publish'
	);
	$post_id = wp_insert_post( $post );
	
	global $wpdb;
	
	add_post_meta($post_id, '_wp_page_template','insert_edit_lesson.php');
}

/**
 * Skapar en sida med template för mina lektioner arkiv.
 */
function mlp_create_my_lesson_archive_page() {
	$post = array(
		'post_type' => 'page',
		'post_title' => 'Mina lektioner',
		'post_name' => 'mina-lektioner',
		'post_status' => 'publish'
	);
	$post_id = wp_insert_post( $post );
	
	global $wpdb;
	
	add_post_meta($post_id, '_wp_page_template','archive-my_lessons.php');
}

/**
 * Tar bort en sida by page title 
 */
function mlp_remove_page_by_title($title) {
	$post = get_page_by_title($title);
	wp_delete_post($post->ID, true);
}


/**
 * Vad ska ske när pluginet inaktiveras
 * Detta bestäms här...
 */
function mlp_deactivate() {
	mlp_remove_page_by_title("Radera Lektion");
	mlp_remove_page_by_title("Skapa Lektion");
	mlp_remove_page_by_title("Mina Lektioner");
}


/**
 * Uppladdning av fil till Lektioner.
 * Vilka filtyper är tillåtna, vilka är det inte. 
 * Detta bestäms här.
 */
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes = array() ) {

	// add your extension to the array
	$existing_mimes['3gp'] = 'audio/3gpp';
	$existing_mimes['3gp'] = 'video/3gpp';
	$existing_mimes['ppt'] = 'application/vnd.ms-powerpoint';
	$existing_mimes['au'] = 'audio/basic';
	$existing_mimes['au'] = 'audio/x-au';
	$existing_mimes['avi'] = 'application/x-troff-msvideo';
	$existing_mimes['avi'] = 'video/avi';
	$existing_mimes['avi'] = 'video/msvideo';
	$existing_mimes['avi'] = 'video/x-msvideo';
	$existing_mimes['bmp'] = 'image/bmp';
	$existing_mimes['bmp'] = 'image/x-windows-bmp';
	$existing_mimes['doc'] = 'application/msword';
	$existing_mimes['gif'] = 'image/gif';
	$existing_mimes['jpeg'] = 'image/jpeg';
	$existing_mimes['jpeg'] = 'image/pjpeg';
	$existing_mimes['jpg'] = 'image/jpeg';
	$existing_mimes['jpg'] = 'image/pjpeg';
	$existing_mimes['m2v'] = 'video/mpeg';
	$existing_mimes['mid'] = 'application/x-midi';
	$existing_mimes['mid'] = 'audio/midi';
	$existing_mimes['mid'] = 'audio/x-mid';
	$existing_mimes['mid'] = 'audio/x-midi';
	$existing_mimes['mid'] = 'music/crescendo';
	$existing_mimes['mid'] = 'x-music/x-midi';
	$existing_mimes['midi'] = 'audio/x-mid';
	$existing_mimes['midi'] = 'audio/x-midi';
	$existing_mimes['midi'] = 'music/crescendo';
	$existing_mimes['midi'] = 'x-music/x-midi';
	$existing_mimes['midi'] = 'audio/midi';
	$existing_mimes['midi'] = 'application/x-midi';
	$existing_mimes['mov'] = 'video/quicktime';
	$existing_mimes['moov'] = 'video/quicktime';
	$existing_mimes['midi'] = 'application/x-midi';
	$existing_mimes['mp2'] = 'audio/x-mpeg';
	$existing_mimes['mp2'] = 'video/mpeg';
	$existing_mimes['mp2v'] = 'video/mpeg';
	$existing_mimes['mp2'] = 'video/x-mpeg';
	$existing_mimes['mp2'] = 'video/x-mpeq2a';
	$existing_mimes['mp2v'] = 'video/mpeg';
	$existing_mimes['mp3'] = 'audio/mpeg3';
	$existing_mimes['mp3'] = 'audio/x-mpeg-3';
	$existing_mimes['mp3'] = 'video/mpeg';
	$existing_mimes['mp3'] = 'video/x-mpeg';
	$existing_mimes['mpa'] = 'audio/mpeg';
	$existing_mimes['mpa'] = 'video/mpeg';
	$existing_mimes['mpe'] = 'video/mpeg';
	$existing_mimes['mpeg'] = 'video/mpeg';
	$existing_mimes['mpg'] = 'audio/mpeg';
	$existing_mimes['mpg'] = 'video/mpeg';
	$existing_mimes['mpga'] = 'audio/mpeg';
	$existing_mimes['mpv2'] = 'video/mpeg';
	$existing_mimes['mov'] = 'video/quicktime';
	$existing_mimes['nif'] = 'image/x-niff';
	$existing_mimes['niff'] = 'image/x-niff';
	$existing_mimes['pbm'] = 'image/x-portable-bitmap';
	$existing_mimes['pct'] = 'image/x-pict';
	$existing_mimes['pcx'] = 'image/x-pcx';
	$existing_mimes['pdf'] = 'application/pdf';
	$existing_mimes['pic'] = 'image/pict';
	$existing_mimes['pict'] = 'image/pict';
	$existing_mimes['png'] = 'image/png';
	$existing_mimes['pps'] = 'application/mspowerpoint';
	$existing_mimes['pps'] = 'application/vnd.ms-powerpoint';
	$existing_mimes['ppt'] = 'application/vnd.ms-powerpoint';
	$existing_mimes['ppt'] = 'application/powerpoint';
	$existing_mimes['ppt'] = 'application/vnd.ms-powerpoint';
	$existing_mimes['ppt'] = 'application/x-mspowerpoint';
	$existing_mimes['qif'] = 'image/x-quicktime';
	$existing_mimes['qt'] = 'video/quicktime';
	$existing_mimes['qtc'] = 'video/x-qtc';
	$existing_mimes['qti'] = 'image/x-quicktime';
	$existing_mimes['qtif'] = 'image/x-quicktime';
	$existing_mimes['ra'] = 'audio/x-pn-realaudio';
	$existing_mimes['ra'] = 'audio/x-pn-realaudio-plugin';
	$existing_mimes['ra'] = 'audio/x-realaudio';
	$existing_mimes['rgb'] = 'image/x-rgb';
	$existing_mimes['rm'] = 'application/vnd.rn-realmedia';
	$existing_mimes['rm'] = 'audio/x-pn-realaudio';
	$existing_mimes['tif'] = 'image/tiff';
	$existing_mimes['tif'] = 'image/x-tiff';
	$existing_mimes['tiff'] = 'image/tiff';
	$existing_mimes['tiff'] = 'image/x-tiff';
	$existing_mimes['txt'] = 'text/plain';
	$existing_mimes['xls'] = 'application/excel';
	$existing_mimes['xls'] = 'application/vnd.ms-excel';
	$existing_mimes['xls'] = 'application/x-excel';
	$existing_mimes['xls'] = 'application/x-msexcel';
	$existing_mimes['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	$existing_mimes['xltx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
	$existing_mimes['potx'] = 'application/vnd.openxmlformats-officedocument.presentationml.template';
	$existing_mimes['ppsx'] = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
	$existing_mimes['pptx'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
	$existing_mimes['sldx'] = 'application/vnd.openxmlformats-officedocument.presentationml.slide';
	$existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	$existing_mimes['dotx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
	$existing_mimes['xlam'] = 'application/vnd.ms-excel.addin.macroEnabled.12';
	$existing_mimes['xlsb'] = 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
	$existing_mimes['webm'] = 'audio/webm';
	$existing_mimes['webm'] = 'video/webm';
	// or: $existing_mimes['ppt|pot|pps'] = 'application/vnd.ms-powerpoint';
	// to add multiple extensions for the same mime type
	// add as many as you like
	// removing existing file types
	unset( $existing_mimes['exe'] );
	unset( $existing_mimes['dmg'] );
	unset( $existing_mimes['bin'] );
	unset( $existing_mimes['bat'] );
	unset( $existing_mimes['app'] );
	unset( $existing_mimes['php'] );
	unset( $existing_mimes['zip'] );
	unset( $existing_mimes['rar'] );
	unset( $existing_mimes['7z'] );
	unset( $existing_mimes['xz'] );
	unset( $existing_mimes['bzip2'] );
	unset( $existing_mimes['gzip'] );
	unset( $existing_mimes['tar'] );
	unset( $existing_mimes['wim'] );
	unset( $existing_mimes['tgz'] );
	unset( $existing_mimes['pkg'] );
	unset( $existing_mimes['iso'] );
	unset( $existing_mimes['cdr'] );
	unset( $existing_mimes['bin'] );
	unset( $existing_mimes['cue'] );
	// add as many as you like
	// and return the new full result
	return $existing_mimes;
}