<?php
 /*Template Name: Archive My Lessons
 */
 ?>
 

<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ); ?>

		<div class="page" id="blog-archives" role="main">
		
		<a href="http://mlp.rydberg.me/skapa-lektion/">Skapa Lektion</a>

			<h3 class="pagetitle">Bläddra på Lektioner</h3>
			
			<?php
			global $current_user;
			get_currentuserinfo();
			var_dump($current_user->ID);
			$args = array(
				'post_type' => 'mlp_musiclesson',
				'author' => $current_user->ID 					
			);
			$wp_query = new WP_Query($args);
			?>
			
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
										echo '<a href="' . esc_attr(get_term_link($term, 'mlp_category')) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a> ';
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
										echo '<a href="' . esc_attr(get_term_link($term, 'mlp_grade')) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a> ';
										}
								?>
								</p>		
								<?php endif ;?>									
								
								<?php
									$terms = get_the_terms( $post->ID , 'mlp_theme' );
									if($terms) :
								?>
								<p>
								Tema:									
								<?php
										foreach ( $terms as $term ) {
										echo '<a href="' . esc_attr(get_term_link($term, 'mlp_theme')) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a> ';
										}
								?>
								</p>		
								<?php endif ;?>								
								
							</div>

							<div class="entry">
								<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_intro', true ) ); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="Läs hela <?php the_title_attribute(); ?>"> Läs mer..</a>
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
