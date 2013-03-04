<?php

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


function mlp_create_lesson_taxonomies() {
	$taxonomies = array();
	
	$taxonomies['mlp_category'] = array(
		'hierarchical' => true,
		'query_var' => 'mlp_category',
		'rewrite' => array(
			'slug' => 'Huvudämne'
		),
		'labels' => array(
			'name' => 'Huvudämne',
			'singular_name' => "Huvudämne",
			'add_new' => 'Lägg till Huvudämne',
			'add_new_item' => 'Lägg till Huvudämne',
			'edit_item' => 'Redigera Huvudämne',
			'new_item' => 'Lägg till Huvudämne',
			'view_item' => 'Visa Huvudämne',
			'search_items' => 'Sök bland Huvudämnen',
			'not_found' => 'Inget Huvudämne hittat',
			'not_found_in_trash' => 'Inget Huvudämne hittat i papperskorgen'				
		)
	);
	
	$taxonomies['mlp_grade'] = array(
		'hierarchical' => true,
		'query_var' => 'mlp_grade',
		'rewrite' => array(
			'slug' => 'Årskurs'
		),
		'labels' => array(
			'name' => 'Årskurser',
			'singular_name' => "Årskurs",
			'add_new' => 'Lägg till årskurs',
			'add_new_item' => 'Lägg till årskurs',
			'edit_item' => 'Redigera årskurs',
			'new_item' => 'Lägg till årskurs',
			'view_item' => 'Visa Årskurs',
			'search_items' => 'Sök bland årskurser',
			'not_found' => 'Inga årskurser hittad',
			'not_found_in_trash' => 'Inga årskurser hittad i papperskorgen'				
		)
	);
	
	mlp_register_all_taxonomies($taxonomies);
	
	mlp_add_taxonomy_data('mlp_grade', '1-3', "Årskurs 1-3");
	mlp_add_taxonomy_data('mlp_grade', '4-6', "Årskurs 4-6");
	mlp_add_taxonomy_data('mlp_grade', '7-9', "Årskurs 7-9");
	mlp_add_taxonomy_data('mlp_grade', 'Gymn', "Årskurs Gymnasiet.");		
	
	mlp_add_taxonomy_data('mlp_category', 'Musicerande', 'Musicerande');
	mlp_add_taxonomy_data('mlp_category', 'Musikens verktyg', 'Musikens verktyg');
	mlp_add_taxonomy_data('mlp_category', 'Musikens sammanhang', 'Musikens sammanhang');
}

function mlp_register_all_taxonomies($taxonomies) {
	foreach ($taxonomies as $taxonomy => $array) {
		register_taxonomy($taxonomy, array('mlp_musiclesson'), $array);	
	}
}

function mlp_add_taxonomy_data($taxonomy, $value, $description, $parent = null) {
	
	$args = array(
		'slug' => strtolower($value),
		'description' => $description,
	);
	
	$args['parent'] = isset($parent) ? $args['parent'] : 0;
	
	wp_insert_term($value, $taxonomy, $args);
}

function mlp_add_lesson_details_metabox() {
	add_action('add_meta_boxes', function() {
		add_meta_box('mlp_lesson_details', 'Lektionsdetaljer', 'lesson_details', 'mlp_musiclesson');
	});
	
	function lesson_details($post) {
		$intro = get_post_meta($post->ID, 'mlp_intro', true);
		$goals = get_post_meta($post->ID, 'mlp_goals', true);
		$execution = get_post_meta($post->ID, 'mlp_execution', true);
		?>
			<h3><label for='mlp_intro'>Inledning</label></h3>
			<textarea class='widefat' id='mlp_intro' name='mlp_intro'><?php echo esc_attr($intro); ?></textarea>

			<h3><label for='mlp_goals'>Mål</label></h3>
			<textarea class='widefat' id='mlp_goals' name='mlp_goals'><?php echo esc_attr($goals); ?></textarea>

			<h3><label for='mlp_execution'>Undervisning</label></h3>
			<textarea class='widefat' id='mlp_execution' name='mlp_execution'><?php echo esc_attr($execution); ?></textarea>

		<?php
	}

	add_action('save_post', function($id) {
		update_post_meta_data($id, 'mlp_intro');
		update_post_meta_data($id, 'mlp_goals');
		update_post_meta_data($id, 'mlp_execution');
	});
}

// Save post meta data if 
function update_post_meta_data($id, $field) {
	if( isset($_POST[$field]) ) {
		update_post_meta(
			$id, 
			$field, 
			strip_tags($_POST[$field])
		);
	}
}

function attach_css_files() {
	wp_register_style( 'mlp_musiclesson', plugins_url('css/mlp_musiclesson.css', __FILE__));
	wp_enqueue_style( 'mlp_musiclesson' );
}

function activate() {
	mlp_create_my_lesson_archive_page();
}

function mlp_create_my_lesson_archive_page() {
	$post = array(
		'post_type' => 'page',
		'post_title' => 'Mina lektioner',
		'post_name' => 'mina_lektioner',
		'post_status' => 'publish'
	);
	$post_id = wp_insert_post( $post );
	
	global $wpdb;
	
	$post_name = 'mina_lektioner';
	
	$query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_name = %s", $post_name);	
	$post_id = $wpdb->get_var($query);	
	
	update_post_meta($post_id, '_wp_page_template','archive-my_lessons.php');
}
