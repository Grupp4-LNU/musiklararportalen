<?php

// [insert_lesson]
add_shortcode( 'insert_lesson', 'shortcode_insert_lesson' );

/**
 * Shortcode to use for the front-end to display and handle the insert post form
 *
 * NOTE: wp_insert_post() passes data through sanitize_post(), which itself handles 
 *       all necessary sanitization and validation (kses, etc.).
 *
 * @access public
 * @since 0.2
 *
 * @return void
 */
function shortcode_insert_lesson() {
	if( is_user_logged_in() ) {	
		
		// Check if the form has been submitted
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && 'insert_new_lesson' == $_POST['action'] ) {	
			
			if ( isset( $_POST['lesson_title'] ) ) {
				// Set the lesson title
				$title = $_POST['lesson_title'];
			} else {
				// No title were entered
				echo '<p>'. __( 'Please enter a lesson name.', 'mlp_musiclesson' ) .'</p>';
			}
			
			/*
			$lesson_grades = array();
			foreach( $_POST['mlp_grade'] as $grade ) {
				$lesson_grades[] = $grade;
			}
			*/
			
			// Prepare insert new lesson
			$new_lesson_post = array(
				'post_title'    => $title,
				'post_status'   => 'pending',
				'post_type'     => 'mlp_musiclesson'
			);

			// Insert the new lesson post
			$post_id = wp_insert_post( $new_lesson_post );
							
			//wp_set_post_terms( $post_id, $lesson_grades, 'mlp_grade', false );
			
			/*
			if ( isset( $_FILES['file']['tmp_name'] ) ) {
				foreach( $_FILES as $file_handler => $data ) {
					$attachment_id = $this->insert_attachment( $file_handler, $recipe_id );
				}
			}
			*/
			
			//set_post_thumbnail( $post_id, $attachment_id );
		
			_e( '<p>Thank you! Your recipe has been submitted to our database. You will be notified when it has been reviewed by an administrator!</p>', 'dp-recipes' );
			_e( '<p>You will be redirected in 5 seconds, if not click <a href="'.home_url().'">here</a>.</p>', 'dp-recipes' );
			
			echo "<meta http-equiv='refresh' content='5;url='".home_url()."' />";
		}
		else
		{
			echo '<div id="recipe_form">'."\n";
			echo '<form id="insert_new_recipe" name="insert_new_recipe" method="post" action="" class="recipe-form" enctype="multipart/form-data">'."\n";
			
			echo '<!-- Recipe Title -->'."\n";
			echo '<label for="lesson_title">'. __( 'Lesson Name', 'mlp_musiclesson' ) .':</label>'."\n";
			echo '<input type="text" id="lesson_title" value="" tabindex="1" name="lesson_title" />'."\n";
			
			/*
			echo '<!-- Category -->'."\n";
			echo '<!-- Recipe Title -->'."\n";
			echo '<label for="recipe_category">'. __( 'Grade', 'mlp_musiclesson' ) .':</label>'."\n";
			$select_grades = wp_dropdown_categories( array( 'echo' => 0, 'taxonomy' => 'mlp_grade', 'hide_empty' => 0 ) );
			$select_grades = str_replace( "name='mlp_grade' id=", "name='grade[]' multiple='multiple' id=", $select_grades );
			echo $select_grades;
			*/
			
			/*
			echo '<label for="file">'. __( 'Select image', 'mlp_musiclesson' ) .':</label>'."\n";
			echo '<input type="file" id="files" name="file">'."\n";
			*/
			
			echo '<input type="submit" value="'.__( 'Submit Lesson', 'mlp_musiclesson' ).'" tabindex="40" id="submit" name="submit" />';
			
			echo '<input type="hidden" name="action" value="insert_new_lesson" />'."\n";
			wp_nonce_field( 'insert_new_lesson' );
			
			echo '</form>'."\n";				
			echo '</div>';
		}
	}
	else
	{
		_e( '<p class="dp-error">You must be logged in to post a new recipe.</p>', 'dp-recipes' );
	}
}