<?php
 /*Template Name: Archive MLP Lesson
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
						<button type='submit' form='clear_filter' id='clear_button'>Rensa filter</button>						
					</fieldset>
				</form>
				<form id='clear_filter' method='GET' action='<?php get_bloginfo('wpurl') ?>/lektioner/'></form>
				<select name='sortera' id='lesson_sort' onchange='submit()' form="lesson_filter_form">
				  <option value='senaste' >Senast inlagda</option>
				  <option value='gillade' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'gillade') echo 'SELECTED' ?>>Mest gillade</option>
				  <option value='diskuterade' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'diskuterade') echo 'SELECTED' ?>>Mest diskuterade</option>
				</select>
			
			<?php
			
			//Checking Page
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			//Building Filter-query
			$category = isset($filter_terms['mlp_category']) ? $filter_terms['mlp_category'] : false;
			$grades = isset($filter_terms['mlp_grade']) ? $filter_terms['mlp_grade'] : false;
			$category_setting = null;
			$grades_setting = null;
			
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

			//Building Sorting-query
			$sort_term;
			$order_by = 'date';
			if(isset($_GET['sortera'])){
						
				if($_GET['sortera'] == 'gillade'){
					$order_by = 'meta_value';				
					$sort_term = '_zilla_likes';
				}
				
				if($_GET['sortera'] == 'diskuterade'){
					$order_by = 'comment_count';
				}
			
			}
						
			//Building complete WP-Query
			$args = array(
				'post_type' => 'mlp_musiclesson',
				'tax_query' => array('relation' => 'AND', $category_setting, $grades_setting),
				'orderby' => $order_by, 'meta_key' => $sort_term,
				'posts_per_page' => 3,
				'paged' => $paged				
			);
			$wp_query = new WP_Query($args);
			?>
			
			<p class='found_lessons'><strong>Antal funna lektioner: <?php echo $wp_query->found_posts; ?></strong></p>
			
			<?php if ( $wp_query->have_posts() ) : ?>

				<?php bp_dtheme_content_nav( 'nav-above' ); ?>

				<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class('post lesson'); ?>>

						<div class="author-box">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
							<p><?php printf( _x( 'by %s', 'Post written by...', 'buddypress' ), bp_core_get_userlink( $post->post_author ) ); ?></p>
						</div>

						<div class="post-content">
							<?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
							<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

							<div class="lesson_meta">
								<p><?php printf( __( '%1$s', 'buddypress' ), get_the_date() ); ?></p>
													
								<?php
									$terms = get_the_terms( $post->ID , 'mlp_category' );
									if($terms) :
								?>
								<p>
								Huvudämne:									
								<?php
										foreach ( $terms as $term ) {
										echo '<span class="lesson_meta_text">'.$term->name.' </span>';
										}
								?>
								</p>		
								<?php endif ;?>
								
								<?php
									$terms = get_the_terms( $post->ID , 'mlp_grade' );
									if($terms) :
								?>
								<p>
								Årskurs:									
								<?php
										foreach ( $terms as $term ) {
										echo '<span class="lesson_meta_text">'.$term->name.' </span>';
										}
								?>
								</p>		
								<?php endif ;?>																					
							</div>

							<div class="entry">
								<?php
								$intro = esc_html( get_post_meta( get_the_ID(), 'mlp_intro', true ) );
								echo substr($intro, 0, 250);
								?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="Läs hela <?php the_title_attribute(); ?>"> Läs hela..</a>
							</div>

							<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
						</div>

					</div>

					<?php do_action( 'bp_after_blog_post' ); ?>

				<?php endwhile; ?>

				<?php bp_dtheme_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ); ?></h2>
				<?php get_search_form(); ?>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
