<?php if( is_user_logged_in() ) : ?>

	<?php
	//Checking current user
	global $current_user;
	get_currentuserinfo();
	?>
									
	<?php if($current_user->ID == $post->post_author) : ?>
		<form method='GET' action='<?php get_bloginfo('wpurl') ?>/MLP/skapa-lektion/'>
			<input type='hidden' name='id' value='<?php the_ID(); ?>'/>										
			<button type='submit'>Redigera</button>
		</form>
		<form method='GET' action='<?php get_bloginfo('wpurl') ?>/MLP/radera-lektion/'>
			<input type='hidden' name='id' value='<?php the_ID(); ?>'/>										
			<button type='submit'>Radera</button>
		</form>		
	<?php endif; ?>
<?php endif; ?>