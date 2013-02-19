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
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), '70' ); ?>
						<p><?php printf( _x( 'by %s', 'Post written by...', 'buddypress' ), str_replace( '<a href=', '<a rel="author" href=', bp_core_get_userlink( $post->post_author ) ) ); ?></p>
					</div>

					<div class="post-content">
						<h2 class="posttitle"><?php the_title(); ?></h2>

						<p class="date">
							<?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?>
							<span class="post-utility alignright"><?php edit_post_link( __( 'Edit this entry', 'buddypress' ) ); ?></span>
						</p>
						
						<?php
							$terms = get_the_terms( $post->ID , 'mlp_category' );
							if($terms) :
						?>
						<p class="date">
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
						<p class="date">
						Årskurs:									
						<?php
								foreach ( $terms as $term ) {
								echo '<a href="' . esc_attr(get_term_link($term, 'mlp_grade')) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a> ';
								}
						?>
						</p>		
						<?php endif ;?>						

						<div class="entry">
							<h4>Inledning</h3>
							<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_intro', true ) ); ?>
							<h4>Mål</h3>
							<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_goals', true ) ); ?>
							<h4>Utförande</h3>			
							<?php echo esc_html( get_post_meta( get_the_ID(), 'mlp_execution', true ) ); ?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						</div>
						<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> %title' ); ?></div>
						<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
					</div>

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
