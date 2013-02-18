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
			
			if ( isset( $_POST['lesson_intro'] ) ) {
				// Set the lesson title
				$introduction = $_POST['lesson_intro'];
			} else {
				// No title were entered
				echo '<p>'. __( 'Please enter a lesson introduction.', 'mlp_musiclesson' ) .'</p>';
			}
			
			if ( isset( $_POST['lesson_goal'] ) ) {
				// Set the lesson title
				$goal = $_POST['lesson_goal'];
			} else {
				// No title were entered
				echo '<p>'. __( 'Please enter a goal for the lesson.', 'mlp_musiclesson' ) .'</p>';
			}
			
			if ( isset( $_POST['lesson_execution'] ) ) {
				// Set the lesson title
				$execution = $_POST['lesson_goal'];
			} else {
				// No title were entered
				echo '<p>'. __( 'Please enter a description for the execution of the lesson.', 'mlp_musiclesson' ) .'</p>';
			}
			
			$lesson_grades = array();
			foreach( $_POST['grade'] as $grade ) {
				$lesson_grades[] = $grade;
			}
			
			$lesson_themes = array();
			foreach( $_POST['theme'] as $themes ) {
				$lesson_themes[] = $themes;
			}
			
			$lesson_categories = array();
			foreach( $_POST['category'] as $category ) {
				$lesson_categories[] = $category;
			}
			
			// Prepare insert new lesson
			$new_lesson_post = array(
				'post_title'    => $title,
				'post_status'   => 'publish',
				'post_type'     => 'mlp_musiclesson'
			);

			// Insert the new lesson post
			$post_id = wp_insert_post( $new_lesson_post );
							
			wp_set_post_terms( $post_id, $lesson_grades, 'mlp_grade', false );
			wp_set_post_terms( $post_id, $lesson_themes, 'mlp_theme', false );
			wp_set_post_terms( $post_id, $lesson_categories, 'mlp_category', false );
			
			add_post_meta($post_id, 'mlp_intro', $introduction);
			add_post_meta($post_id, 'mlp_goals', $goal);
			add_post_meta($post_id, 'mlp_execution', $execution);
			
			/*
			if ( isset( $_FILES['file']['tmp_name'] ) ) {
				foreach( $_FILES as $file_handler => $data ) {
					$attachment_id = $this->insert_attachment( $file_handler, $recipe_id );
				}
			}
			*/
			
			//set_post_thumbnail( $post_id, $attachment_id );
		
			_e( '<p>Thank you! Your lesson has been submitted to our database. You will be notified when it has been reviewed by an administrator!</p>', 'dp-recipes' );
			_e( '<p>You will be redirected in 5 seconds, if not click <a href="'.home_url().'">here</a>.</p>', 'dp-recipes' );
			
			echo "<meta http-equiv='refresh' content='5;url='".home_url()."' />";
		}
		else
		{
			echo '<div id="lesson_form">'."\n";
			echo '<form id="insert_new_lesson" name="insert_new_lesson" method="post" action="" class="lesson-form" enctype="multipart/form-data">'."\n";
			
			echo '<!-- Lesson Title -->'."\n";
			echo '<label for="lesson_title">'. __( 'Lesson Name', 'mlp_musiclesson' ) .':</label>';
			echo '<input type="text" id="lesson_title" value="" tabindex="1" name="lesson_title" /><br />';
			
			echo '<!-- Grades -->';
			echo '<label for="mlp_musiclesson_grades">'. __( 'Grade', 'mlp_musiclesson' ) .'</label><br />';
			$grades = get_terms('mlp_grade');
			foreach ($grades as $grade) {
				echo '<input type="checkbox" name="grade[]" value="'.$grade->term_id.'" id="grade'.$grade->term_id.'" />';
				echo '<label for="grade'.$grade->term_id.'">'.$grade->name.'</label>';
			}
			
			echo '<!-- Themes -->';
			echo '<br /><label for="mlp_musiclesson_themes">'. __( 'Theme', 'mlp_musiclesson' ) .'</label><br />';
			$themes = get_terms('mlp_theme');
			foreach ($themes as $theme) {
				echo '<input type="checkbox" name="theme[]" value="'.$theme->term_id.'" id="theme'.$theme->term_id.'" />';
				echo '<label for="theme'.$theme->term_id.'">'.$theme->name.'</label>';
			}
			
			echo '<!-- Lesson Categories -->';
			echo '<br /><label for="mlp_musiclesson_categories">'. __( 'Category', 'mlp_musiclesson' ) .'</label><br />';
			$lessonCategories = get_terms('mlp_category');
			foreach ($lessonCategories as $lessonCategory) {
				echo '<input type="checkbox" name="category[]" value="'.$lessonCategory->term_id.'" id="grade'.$lessonCategory->term_id.'" />';
				echo '<label for="grade'.$lessonCategory->term_id.'">'.$lessonCategory->name.'</label>';
			}
			
			echo '<!-- Lesson Intro -->'."\n";
			echo '<br /><label for="lesson_intro">'. __( 'Introduction', 'mlp_musiclesson' ) .':</label><br />'."\n";
			echo '<textarea id="lesson_intro" value="" tabindex="1" name="lesson_intro"></textarea><br />'."\n";
			
			echo '<!-- Lesson Goal -->'."\n";
			echo '<label for="lesson_goal">'. __( 'Goal', 'mlp_musiclesson' ) .':</label><br />'."\n";
			echo '<textarea id="lesson_goal" value="" tabindex="1" name="lesson_goal"></textarea><br />'."\n";
			
			echo '<!-- Lesson Execution -->'."\n";
			echo '<label for="lesson_execution">'. __( 'Execution', 'mlp_musiclesson' ) .':</label><br />'."\n";
			echo '<textarea id="lesson_execution" value="" tabindex="1" name="lesson_execution"></textarea><br />'."\n";
			
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