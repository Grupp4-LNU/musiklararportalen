<?php
 /*Template Name: Archive My Lessons
 */
 ?>
 
<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ); ?>

		<div class="page" id="blog-archives" role="main">
		
		<a href="../skapa-lektion" class='add_lesson'>Skapa ny lektion (+)</a>

			<h2 class="pagetitle">Mina Lektioner</h2>
			
			<?php if( is_user_logged_in() ) : ?>
					
				<form method="GET" id="lesson_filter_form" action='<?php get_bloginfo('wpurl') ?>/mina_lektioner/'>
				<select name='sortera' id='lesson_sort' onchange='submit()' form="lesson_filter_form">
				  <option value='senaste' >Senast inlagda</option>
				  <option value='gillade' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'gillade') echo 'SELECTED' ?>>Mest gillade</option>
				  <option value='diskuterade' <?php if(isset($_GET['sortera']) && $_GET['sortera'] == 'diskuterade') echo 'SELECTED' ?>>Mest diskuterade</option>
				</select>
				</form>
				
				<?php
				
				//Checking current user
				global $current_user;
				get_currentuserinfo();
				
				//Checking Page
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				
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
					'orderby' => $order_by, 'meta_key' => $sort_term,
					'posts_per_page' => 3,
					'paged' => $paged,
					'author' => $current_user->ID 					
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
						<p>Klicka på <strong>Skapa ny lektion</strong> för att lägga till ny.</p>

					<?php endif; ?>
				
				<?php else :?>

				<p class="dp-error">Du måste vara inloggad för att se dina lektioner</p>
					
				<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
