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
			<div id='preview_edit_control'>
			<?php get_template_part('lessontemplates/edit_button'); ?>
			</div>
		<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
	
	</div>					
		
</div>