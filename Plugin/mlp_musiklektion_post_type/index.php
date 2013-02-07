<?php
/*
	Plugin Name: MLP Musiklektion Post Type
	Plugin URI: http://musiklararportalen.se
	Description: Creates music lessons.
	Version: 0.1
	Author: Christoffer Rydberg, Simon Ebeling, Henrik Petersson
	Author URI: http://christoffer.rydberg.me , http://www.joshi.se
*/

class MLP_MusikLektion_Post_Type {
	public function __construct()
	{
		$this->register_post_type();
		$this->taxonomies();
	}
	
	public function register_post_type()
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
			'query_var' => 'lektioner',
			'rewrite' => array(
				'slug' => 'lessons/'
			),
			'public' => true,
			'menu_position' => 5,
			'menu_icon' => admin_url().'images/media-button-music.gif',
			'supports' => array(
				'title',
				'thumbnail',
				'excerpt',
				'comments',
				'author'
			),
			'show_in_menu' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true
		);
		register_post_type('mlp_musiclesson', $args);
	}
	
	public function taxonomies() {
		$taxonomies = array();
		
		$taxonomies['mlp_grade'] = array(
			'hierarchical' => true,
			'query_var' => 'lesson_grades',
			'rewrite' => array(
				'slug' => 'lessons/grades'
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
		
		$taxonomies['mlp_category'] = array(
			'hierarchical' => true,
			'query_var' => 'lesson_categories',
			'rewrite' => array(
				'slug' => 'lessons/mlp_categories'
			),
			'labels' => array(
				'name' => 'Lektionskategorier',
				'singular_name' => "Lektionskategori",
				'add_new' => 'Lägg till Lektionskategori',
				'add_new_item' => 'Lägg till Lektionskategori',
				'edit_item' => 'Redigera Lektionskategori',
				'new_item' => 'Lägg till Lektionskategori',
				'view_item' => 'Visa Lektionkategori',
				'search_items' => 'Sök bland Lektionskategorier',
				'not_found' => 'Inga Lektionskategorier hittad',
				'not_found_in_trash' => 'Inga Lektionskategorier hittad i papperskorgen'				
			)
		);
		
		$this->register_all_taxonomies($taxonomies);
		
		$this->add_taxonomy_data('mlp_grade', '1-3', "Årskurs 1-3");
		$this->add_taxonomy_data('mlp_grade', '4-6', "Årskurs 4-6");
		$this->add_taxonomy_data('mlp_grade', '7-9', "Årskurs 7-9");
		
		$this->add_taxonomy_data('mlp_category', 'Spela och Sjunga', 'Spela och sjunga');
		$this->add_taxonomy_data('mlp_category', 'Musikskapande', 'Musikskapande');
		$this->add_taxonomy_data('mlp_category', 'Musikanalys', 'Musikanalys');
	}

	public function register_all_taxonomies($taxonomies) {
		foreach ($taxonomies as $taxonomy => $array) {
			register_taxonomy($taxonomy, array('mlp_musiclesson'), $array);	
		}
	}

	public function add_taxonomy_data($taxonomy, $value, $description, $parent = null) {
		
		$args = array(
			'slug' => strtolower($value),
			'description' => $description,
		);
		
		$args['parent'] = isset($parent) ? $args['parent'] : 0;
		
		wp_insert_term($value, $taxonomy, $args);
	}
	
	public function metaboxes() {
		add_action('add_meta_boxes', function() {
			// css id, title, cb func, page, priority, cb func arguments
			add_meta_box('mlp_music_lesson_', 'lesson length', 'lesson_length', 'mlp_lesson');
			
			function movie_length($post) {
				?>
				<p>
					<label for=''>Lektionslänmgst</label>
					<input type='text' class='widefat' name='mlp_lesson_length' id='mlp_lesson_length' value='' />
				</p>
				<?php
			}
		});
	}
}

add_action('init', 'MLP_musiklektion_init');

function MLP_musiklektion_init() {
	new MLP_MusikLektion_Post_Type();
}
