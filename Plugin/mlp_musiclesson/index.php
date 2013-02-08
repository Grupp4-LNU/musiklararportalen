<?php
# Silence is golden.
?>
<?php
/*
	Plugin Name: MLP MusicLesson
	Plugin URI: http://musiklararportalen.se
	Description: Music lessons for your wordpress site.
	Version: 0.1
	Author: Musiklärarportalen
	Author URI: http://www.musiklararportalen.se
*/
class MLP_musiclesson {
	public function __construct()
	{
		$this->register_post_type();
		$this->taxonomies();
		$this->add_lesson_details_metabox();
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
			'query_var' => 'lektion',
			'rewrite' => array( 'slug' => 'lektion'),
			'public' => true,
			'menu_position' => 5,
			'menu_icon' => admin_url().'images/media-button-music.gif',
			'supports' => array(
				'title',
				'thumbnail',
				'comments',
				'author'
			),
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
	
	public function taxonomies() {
		$taxonomies = array();
		
		$taxonomies['mlp_grade'] = array(
			'hierarchical' => false,
			'query_var' => 'lektion_arskurs',
			'rewrite' => array(
				'slug' => 'lektion/arskurs'
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
			'hierarchical' => false,
			'query_var' => 'lektion_kategori',
			'rewrite' => array(
				'slug' => 'lektion/kategorier'
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
	
	public function add_lesson_details_metabox() {
		add_action('add_meta_boxes', function() {
			add_meta_box('mlp_lesson_details', 'Lektionsdetaljer', 'lesson_details', 'mlp_musiclesson');
		});
		
		function lesson_details($post) {
			$intro = get_post_meta($post->ID, 'mlp_intro', true);
			$goals = get_post_meta($post->ID, 'mlp_goals', true);
			$execution = get_post_meta($post->ID, 'mlp_execution', true);
			?>
			<p>
				<label for='mlp_intro'>Inledning</label>
				<textarea class='widefat' id='mlp_intro' name='mlp_intro'><?php echo esc_attr($intro); ?></textarea>
			</p>
			<p>
				<label for='mlp_goals'>Mål</label>
				<textarea class='widefat' id='mlp_goals' name='mlp_goals'><?php echo esc_attr($goals); ?></textarea>
			</p>
			<p>
				<label for='mlp_execution'>Utförande</label>
				<textarea class='widefat' id='mlp_execution' name='mlp_execution'><?php echo esc_attr($execution); ?></textarea>
			</p>
			<?php
		}

		add_action('save_post', function($id) {
			$this->update_post_meta_data($id, 'mlp_intro');
			$this->update_post_meta_data($id, 'mlp_goals');
			$this->update_post_meta_data($id, 'mlp_execution');
		});
	}

	// Save post meta data if 
	public function update_post_meta_data($id, $field) {
		if( isset($_POST[$field]) ) {
			update_post_meta(
				$id, 
				$field, 
				strip_tags($_POST[$field])
			);
		}
	}
}

add_action('init', 'MLP_musiclesson_init');

function MLP_musiclesson_init() {
	new MLP_musiclesson();
	include dirname(__FILE__) . '/mlp_musiclesson_shortcode.php';
}