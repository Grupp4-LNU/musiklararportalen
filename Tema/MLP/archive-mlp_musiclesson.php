<?php
 /*Template Name: MLP Archive Lessons
 */
 ?>
 

<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ); ?>

		<div class="page" id="lesson-archives" role="main">
			
			<h2 class="pagetitle">Lektioner</h2>
				<form method="GET" id="lesson_filter_form" action='<?php get_bloginfo('wpurl') ?>/lektioner/'>
					<fieldset id='filter_lesson_container'>
						<legend><p><strong>Filter</strong></p></legend>				
						<?php 
						$args = array(
						  'public'   => true,
						  '_builtin' => false
						); 
						$output = 'objects'; // or objects
						$operator = 'and'; // 'and' or 'or'
						$taxonomies = get_taxonomies( $args, $output, $operator ); 
						if ( $taxonomies ) {
							$filter_terms = array();
							foreach ( $taxonomies  as $taxonomy ) {
								$filter_terms [$taxonomy->name] = array ();
								echo "<p>";
								echo $taxonomy->labels->singular_name.":";
								
								$terms = get_terms(
									$taxonomy->name, 
									array(
										'orderby'=> 'id', 
										'order'=> 'ASC',
										'hide_empty' => 0
									)
								);
								
								$count = count($terms);
								if ( $count > 0 ){
									foreach($terms as $term) {
										$post_name = str_replace(' ', '_', $term->name);
										if(isset($_GET[$post_name]) && $_GET[$post_name] == '1'){
											$filter_terms [$taxonomy->name][] = $term->name;
											echo "<label class='selected'><input type='checkbox' checked='yes' name='".$post_name."' id='".$post_name."' value='1' onclick='submit()'></input>" . $term->name . "</label>";
										}								
										else {
											echo "<label><input type='checkbox' name='".$post_name."' id='".$post_name."' value='1' onclick='submit()'></input>" . $term->name . "</label>";
										}
									}
								echo "</p>";

								}
							}
						}					
						?>
						<p>Sökord: <input type='text' name='sokord' value='<?php if(isset($_GET['sokord'])) echo $_GET['sokord']; ?>'><button type='submit'>Filtrera</button></p>
						<button type='submit' form='clear_filter' id='clear_button'>Rensa filter</button>						
					</fieldset>
				</form>
				<form id='clear_filter' method='GET' action='<?php get_bloginfo('wpurl') ?>/lektioner/'></form>
				<select name='sortera' id='lesson_sort' onchange='submit()' form="lesson_filter_form">
				  <option value='senaste' >Senast inlagda</option>
				  <option value='gillade' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'gillade') echo 'SELECTED' ?>>Mest gillade</option>
				  <option value='diskuterade' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'diskuterade') echo 'SELECTED' ?>>Mest diskuterade</option>
				  <option value='bokstavsordning' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'bokstavsordning') echo 'SELECTED' ?>>Bokstavsordning</option>
				  <option value='skribent' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'skribent') echo 'SELECTED' ?>>Författare</option>				  
				</select>
			
			<?php
			
			//Checking Page
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			//Building Filter-query
			$category = isset($filter_terms['mlp_category']) ? $filter_terms['mlp_category'] : false;
			$grades = isset($filter_terms['mlp_grade']) ? $filter_terms['mlp_grade'] : false;
			$category_setting = null;
			$grades_setting = null;
			$keyword = null;
			
			if($category){
				$category_setting = array(
							'taxonomy' => 'mlp_category',
							'field' => 'slug',
							'terms' => $category
						);			
			};
			
			if($grades){
				$grades_setting = array(
							'taxonomy' => 'mlp_grade',
							'field' => 'slug',
							'terms' => $grades
						);	
			};
			
			//Building keyword-query
			if(isset($_GET['sokord'])){
				$keyword = $_GET['sokord'];
			}

			//Building Sorting-query
			$sort_term = null;
			$order = null;
			$order_by = 'date';
			if(isset($_GET['sortera'])){
						
				if($_GET['sortera'] == 'gillade'){
					$order_by = 'meta_value';				
					$sort_term = '_zilla_likes';
				}
				
				if($_GET['sortera'] == 'diskuterade'){
					$order_by = 'comment_count';
				}
				
				if($_GET['sortera'] == 'bokstavsordning'){
					$order_by = 'title';
					$order = ASC;
				}
				
				if($_GET['sortera'] == 'skribent'){
					$order_by = 'name';
					$order = ASC;
				}				
			
			}
						
			//Building complete WP-Query
			$args = array(
				'post_type' => 'mlp_musiclesson',
				'tax_query' => array('relation' => 'AND', $category_setting, $grades_setting),
				'orderby' => $order_by, 
				'meta_key' => $sort_term,
				'order' => $order,
				's' => $keyword,
				'posts_per_page' => 10,
				'paged' => $paged				
			);

			$wp_query = new WP_Query($args);
			?>
			
			<p class='found_lessons'><strong>Antal funna lektioner: <?php echo $wp_query->found_posts; ?></strong></p>
			
			<?php if ( $wp_query->have_posts() ) : ?>

				<?php bp_dtheme_content_nav( 'nav-above' ); ?>

				<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ); ?>

					<?php get_template_part('lessontemplates/lesson_preview'); ?>
					
					<?php do_action( 'bp_after_blog_post' ); ?>

				<?php endwhile; ?>

				<?php bp_dtheme_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<h3>Inga lektioner kunde hittas</h3>
				<p>Välj andra filterinställningar för att hitta lektioner</p>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
