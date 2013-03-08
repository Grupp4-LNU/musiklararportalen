<?php if( is_user_logged_in() ) : ?>

	<?php
	//Checking current user
	global $current_user;
	get_currentuserinfo();
	?>
									
	<?php if($current_user->ID == $post->post_author) : ?>
		<form method='POST' action='<?php get_bloginfo('wpurl') ?>/MLP/skapa-lektion/'>
			<input type='hidden' name='post_id' value='<?php get_the_ID(); ?>'/>										
			<button class='edit_lesson' type='submit'>Ändra lektion</button>
		</form>			
	<?php endif; ?>
<?php endif; ?>