<?php
 /*Template Name: Startpage
 */
 ?>

<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry">

						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

					</div>

				</div>

			<?php endwhile; endif; ?>
						
				<?php

				//News Query arguments				
				$news_args = array(
				'post_type'=> 'post',
				'category_name'=> 'Nyheter',
				'order'    => 'DESC',
				'posts_per_page' => 4			
				);
				
				//Article Query arguments
				$article_args = array(
				'post_type'=> 'post',
				'category_name'=> 'Artiklar',
				'order'    => 'DESC',
				'posts_per_page' => 5			
				);

				//Lesson Query arguments
				$lesson_args = array(
				'post_type'=> 'mlp_musiclesson',
				'order'    => 'DESC',
				'posts_per_page' => 5			
				);  				

				$news_query = new WP_Query( $news_args );
				$article_query = new WP_Query( $article_args );
				$lesson_query = new WP_Query( $lesson_args );
				
				?>
			
			<div class='preview_container'>
			
			<?php //News Container ?>
			<div class="preview_post_container">				

				<h3>Senaste Nyheter</h3>				

				<?php if ( $news_query->have_posts() ) : ?>

					<?php bp_dtheme_content_nav( 'nav-above' ); ?>
					
					<?php while ($news_query->have_posts()) : $news_query->the_post(); ?>

						<?php do_action( 'bp_before_blog_post' ); ?>

						<div id="post-<?php the_ID(); ?>" <?php post_class('preview_news'); ?>>

							<div class="post-content">
								<h4 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>

								<div class="entry">
									<p class="small_text"><?php echo excerpt(20); ?></p>
									<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
								</div>

							</div>

						</div>

						<?php do_action( 'bp_after_blog_post' ); ?>

					<?php endwhile; ?>

					<?php bp_dtheme_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<p class="center"><?php _e( 'Not Found', 'buddypress' ); ?></p>

				<?php endif; ?>
			</div>

			<?php //Article Container ?>			
			<div class="preview_post_container">
						
				<h3>Senaste Artiklar</h3>	

				<?php if ( $article_query->have_posts() ) : ?>

					<?php bp_dtheme_content_nav( 'nav-above' ); ?>
					
					<?php while ($article_query->have_posts()) : $article_query->the_post(); ?>

						<?php do_action( 'bp_before_blog_post' ); ?>

						<div id="post-<?php the_ID(); ?>" <?php post_class('preview_article'); ?>>

							<div class="author-box">
								<?php echo get_avatar( get_the_author_meta( 'user_email' ), '40' ); ?>
							</div>

							<div class="post-content">
								<h4 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>

								<p class="date"><?php printf( __( '%1$s', 'buddypress' ), get_the_date()); ?></p>

							</div>

						</div>

						<?php do_action( 'bp_after_blog_post' ); ?>

					<?php endwhile; ?>

					<?php bp_dtheme_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<p class="center"><?php _e( 'Not Found', 'buddypress' ); ?></p>

				<?php endif; ?>

			</div>
			
			<?php //Lesson Container ?>			
			<div class='preview_post_container'>
						
				<h3>Senaste Lektioner</h3>	
				
				<div>

					<?php if ( $lesson_query->have_posts() ) : ?>

					<?php bp_dtheme_content_nav( 'nav-above' ); ?>

					<?php while ($lesson_query->have_posts()) : $lesson_query->the_post(); ?>

						<?php do_action( 'bp_before_blog_post' ); ?>

						<div id="post-<?php the_ID(); ?>" <?php post_class('post preview_lesson'); ?>>
							
							<h4 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
							
							<div class="author-box">
								<?php echo get_avatar( get_the_author_meta( 'user_email' ), '40' ); ?>
							</div>
							
							<div class="post-content">
								<p class="date">
								<?php
									$terms = get_the_terms( $post->ID , 'mlp_category' );
									if($terms) :
								?>
								Huvudämne:									
								<?php
										foreach ( $terms as $term ) {
										echo '<span class="lesson_meta_text">'.$term->name.' </span>';
										}
								?>
								</p>		
								<?php endif ;?>
								
								<?php
									$terms = get_the_terms( $post->ID , 'mlp_target_group' );
									if($terms) :
								?>
								<p class="date">
								Målgrupp:									
								<?php
										foreach ( $terms as $term ) {
										echo '<span class="lesson_meta_text">'.$term->name.' </span>';
										}
								?>
								</p>		
								<?php endif ;?>
								
							</div>

						</div>

						<?php do_action( 'bp_after_blog_post' ); ?>

					<?php endwhile; ?>

					<?php bp_dtheme_content_nav( 'nav-below' ); ?>

					<?php else : ?>

						<p class="center"><?php _e( 'Not Found', 'buddypress' ); ?></p>

					<?php endif; ?>
			
				</div>

			</div>			
			
			</div>
			
		</div><!-- .page -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
