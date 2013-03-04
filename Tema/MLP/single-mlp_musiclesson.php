<?php
 /*Template Name: Single MLP Lesson
 */
 ?>

<?php get_header(); ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class("post lesson"); ?>>

					<div class="author-box">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
						<p><?php printf( _x( 'by %s', 'Post written by...', 'buddypress' ), str_replace( '<a href=', '<a rel="author" href=', bp_core_get_userlink( $post->post_author ) ) ); ?></p>
					</div>

					<div class="post-content">
						<?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
						<h2 class="posttitle"><?php the_title(); ?></h2>
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
							<h4>Inledning</h3>
							<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_intro', true ) ); ?>
							<h4>Mål</h3>
							<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_goals', true ) ); ?>
							<h4>Utförande</h3>			
							<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_execution', true ) ); ?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						</div>

					</div>
					<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> %title' ); ?></div>
					<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
				</div>
			
						
			<?php comments_template(); ?>

			<?php endwhile; else: ?>

				<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?></p>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_blog_single_post' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
