<?php
 /*Template Name: MLP Delete Lesson
 */
 ?> 
<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<div class="page" role="main">

		<h2 class="pagetitle">Radera Lektion</h2>
		
		<?php
			//Checking current user
			global $current_user;
			get_currentuserinfo();
		?>
		
		<?php // Check if the form has been submitted ?>
		<?php if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['id'])) : ?>
		
		<?php $post_id = $_POST['id']; ?>
		
			<?php
				//Building complete WP-Query
				$args = array(
					'post_type' => 'mlp_musiclesson',
					'p' => $post_id
				);
				$wp_query = new WP_Query($args);
			?>
			
			<?php if ( $wp_query->have_posts() ) : ?>

				<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
				
					<?php if($current_user->ID == $post->post_author) : ?>
					
						<?php
						$attach_args = array(
							'post_type' => 'attachment',
							'post_parent' => get_the_ID(),
							'post_status' => null,
							'numberposts' => null
						);
						$attachments = get_posts($attach_args);
						if(isset($attachments))
						{
							foreach ($attachments as $attachment){
								wp_delete_attachment($attachment->ID);
							}
						}
						?>

						<?php if(wp_delete_post($post_id)) : ?>
						<p>Lektionen <strong><?php echo get_the_title(); ?></strong> har raderats! <a href='<?php get_bloginfo('wpurl') ?>/lektioner/'>Återgå till Lektioner.</a></p>
						<?php else: ?>
						<p>Det gick ej att radera lektion. <a href='<?php get_bloginfo('wpurl') ?>/lektioner/'>Återgå till Lektioner.</a></p>
						<?php endif; ?>
															
					<?php else: ?>
					<p>Du har ej behörighet att radera denna lektion. <a href='<?php get_bloginfo('wpurl') ?>/lektioner/'>Återgå till Lektioner.</a></p>
					<?php endif; ?>

				<?php endwhile; ?>
			
			<?php else: ?>
			<p>Hittar ingen lektion att radera. <a href='<?php get_bloginfo('wpurl') ?>/lektioner/'>Återgå till Lektioner.</a></p>
			<?php endif; ?>		
		
		<?php else: ?>

			<?php $get_id = isset($_GET['id']) ? $_GET['id'] : false ?>	
			
			<?php if($get_id) : ?>
								
			<?php
				//Building complete WP-Query
				$args = array(
					'post_type' => 'mlp_musiclesson',
					'p' => $_GET['id']
				);
				$wp_query = new WP_Query($args);
			?>
			
				<?php if ( $wp_query->have_posts() ) : ?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
					
						<?php if($current_user->ID == $post->post_author) : ?>
						
						<p>Vill du radera lektionen <strong><?php echo get_the_title(); ?></strong>?</p>
						
						<form method='POST' name='delete_lesson'>
						<input type='hidden' name='id' value='<?php the_ID(); ?>'/>	
						<button type='submit'>Ja, radera</button> <a href="javascript:history.back()">Nej, återgå till Lektioner.</a>
						</form>
										
						<?php else: ?>
						<p>Du har ej behörighet att radera denna lektion. <a href="javascript:history.back()">Återgå till Lektioner.</a></p>
						<?php endif; ?>

					<?php endwhile; ?>
				
				<?php endif; ?>
			
			<?php else: ?>
			<p>Hittar ingen lektion att radera. <a href="javascript:history.back()">Återgå till Lektioner.</a></p>
			<?php endif; ?>

		<?php endif; ?>	

		</div>

		</div><!-- .padder -->
	</div><!-- #content -->
	<?php get_sidebar(); ?>

<?php get_footer(); ?>