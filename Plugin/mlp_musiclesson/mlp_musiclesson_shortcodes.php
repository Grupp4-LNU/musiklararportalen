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
			$errors = array();
			
			if($_POST['lesson_title'] != "") 
			{
				$title = esc_html($_POST['lesson_title']);
			} 
			else 
			{
				$errors[] = '<p>Du måste ange en lektionstitel</p>';
			}
			
			if($_POST['lesson_intro'] != "")
			{
				$introduction = esc_html($_POST['lesson_intro']);
			} 
			else
			{
				$errors[] = '<p>Du måste ange en lektionsintroduktion</p>';
			}
			
			if($_POST['lesson_goal'] != "") 
			{
				$goal = esc_html($_POST['lesson_goal']);
			} 
			else 
			{
				$errors[] = '<p>Du måste ange ett mål med lektionen</p>';
			}
			
			if($_POST['lesson_execution'] != "") 
			{
				$execution = esc_html($_POST['lesson_execution']);
			} 
			else 
			{
				$errors[] = '<p>Du måste ange en beskrivning på hur man utför lektionen</p>';
			}
			
			
			if(isset($_POST['grade']))
			{
				$lesson_grades = array();
				foreach( $_POST['grade'] as $grade ) {
					$lesson_grades[] = $grade;
				}
			}
			else 
			{
				$errors[] = '<p>Du måste ange minst en årskurs för lektionen</p>';	
			}			
			
			if(isset($_POST['theme']))
			{
				$lesson_themes = array();
				$lesson_themes[] = $_POST['theme'];
			}
			else 
			{
				$errors[] = '<p>Du måste ange ett tema för lektionen</p>';
			}
						
			if(isset($_POST['category']))
			{
				$lesson_categories = array();
				foreach( $_POST['category'] as $category ) {
					$lesson_categories[] = $category;
				}
			}
			else
			{
				$errors[] = '<p>Du måste ange minst ett huvudämne för lektionen</p>';
			}
			
			if(count($errors) == 0)
			{ 
				// Prepare insert new lesson
				$new_lesson_post = array(
					'post_title'    => $title,
					'post_status'   => 'publish',
					'post_type'     => 'mlp_musiclesson'
				);
				$post_id = wp_insert_post( $new_lesson_post );
								
				wp_set_post_terms( $post_id, $lesson_grades, 'mlp_grade', false );
				wp_set_post_terms( $post_id, $lesson_themes, 'mlp_theme', false );
				wp_set_post_terms( $post_id, $lesson_categories, 'mlp_category', false );
				
				add_post_meta($post_id, 'mlp_intro', $introduction);
				add_post_meta($post_id, 'mlp_goals', $goal);
				add_post_meta($post_id, 'mlp_execution', $execution);
				
				if ($_FILES) {
					foreach ($_FILES as $file => $array) {
						$newupload = insert_attachment($file,$post_id);
						// $newupload returns the attachment id of the file that
						// was just uploaded. Do whatever you want with that now.
					}
				}
							
				echo '<p>Tackar! Din lektion har blivit sparad</p>';
				echo '<p>Du blir skickad tillbaka om 5s, ifall det inte fungerar kan du klicka <a href="'.home_url().'">här</a>.</p>';
				echo "<meta http-equiv='refresh' content='5; url='".home_url()."' />";
			}
			else 
			{
				foreach ($errors as $error) {
					echo $error;
				}
				echo '<a href="javascript:history.back()">Tillbaka till formuläret</a>';
			}
		}
		else
		{	
			echo '<div id="lesson_form">';
			echo '<form id="insert_new_lesson" name="insert_new_lesson" method="post" action="" class="lesson-form" enctype="multipart/form-data">';

			echo '<!-- Lesson Categories -->';
			echo '<label for="mlp_musiclesson_categories">Huvudområden</label>';
			$lessonCategories = get_terms('mlp_category', array( 'hide_empty' => 0 ));
			echo '<div class="categories">';
			foreach ($lessonCategories as $lessonCategory) {
				echo '<input class="{category: true}" type="checkbox" name="category[]" value="'.$lessonCategory->term_id.'" id="grade'.$lessonCategory->term_id.'" />';
				echo '<label for="grade'.$lessonCategory->term_id.'">'.$lessonCategory->name.'</label>';
			}
			echo '</div>';
			
			echo '<!-- Grades -->';
			echo '<label for="mlp_musiclesson_grades">Årskurser</label>';
			$grades = get_terms('mlp_grade', array( 'hide_empty' => 0 ));
			echo '<div class="grades">';
			foreach ($grades as $grade) {
				echo '<input class="{grade: true}" type="checkbox" name="grade[]" value="'.$grade->term_id.'" id="grade'.$grade->term_id.'" />';
				echo '<label for="grade'.$grade->term_id.'">'.$grade->name.'</label>';
			}
			echo '</div>';
			
			echo '<!-- Themes -->';
			echo '<label for="mlp_musiclesson_themes">Tema</label>';
			$themes = get_terms('mlp_theme', array( 'hide_empty' => 0 ));
			echo '<div class="themes">';
			foreach ($themes as $theme) {
				echo '<input class="{theme: true}" type="radio" name="theme" value="'.$theme->term_id.'" id="theme'.$theme->term_id.'" />';
				echo '<label for="theme'.$theme->term_id.'">'.$theme->name.'</label>';
			}
			echo '</div>';
			
			echo '<!-- Lesson Title -->';
			echo '<label for="lesson_title">Titel</label>';
			echo '<input type="text" id="lesson_title" name="lesson_title" />';
			
			echo '<!-- Lesson Intro -->';
			echo '<label for="lesson_intro">Inledning / Förutsättningar</label>';
			echo '<textarea id="lesson_intro" name="lesson_intro"></textarea>';
			
			echo '<!-- Lesson Goal -->';
			echo '<label for="lesson_goal">Mål</label>';
			echo '<textarea id="lesson_goal" name="lesson_goal"></textarea>';
			
			echo '<!-- Lesson Execution -->';
			echo '<label for="lesson_execution">Utförande</label>';
			echo '<textarea id="lesson_execution" name="lesson_execution"></textarea>';
			
			echo '<div class="files">';
			echo '<label for="lesson_file">Välj fil</label><br />';
			echo '<input type="file" id="lesson_file" name="lesson_file"><br />';
			echo '<a href="#" id="add_file_form">Lägg till ytterligare fil</a>';
			echo '</div>';
			
			echo '<input type="submit" value="Spara" tabindex="40" id="submit" name="submit" />';
			
			echo '<input type="hidden" name="action" value="insert_new_lesson" />';
			wp_nonce_field( 'insert_new_lesson' );
			
			echo '</form>';		
			echo '</div>';
			echo '
				<script type="text/javascript">
					$(function() {
						$("#add_file_form").on("click", function(e) {
							e.preventDefault();
							var files = $(".files").children();
							var counter = files.length; 
							$(".files").append("<br /><input type=\"file\" id=\"lesson_file" + counter + "\" name=\"lesson_file"+counter+"\">")
						});
						$.validator.addMethod("category", function(value, elem, param) {
						    if($(".category:checkbox:checked").length > 0){
						       return true;
						   }else {
						       return false;
						   }
						},"You must select at least one!");
						$.validator.addMethod("theme", function(value, elem, param) {
						    if($(".theme:checkbox:checked").length > 0){
						       return true;
						   }else {
						       return false;
						   }
						},"You must select at least one!");
						$.validator.addMethod("grade", function(value, elem, param) {
						    if($(".grade:checkbox:checked").length > 0){
						       return true;
						   }else {
						       return false;
						   }
						},"You must select at least one!");
						$("#insert_new_lesson").validate({
							errorPlacement: function (error, element) { 
                                error.insertBefore(element);    
							},
							rules: {
								lesson_title: {
									required: true
								},
								lesson_intro: {
									required: true
								},
								lesson_goal: {
									required: true
								},
								lesson_execution: {
									required: true
								}
							}
						});
					});
				</script>
				';
		}
	}
	else
	{
		echo '<p class="dp-error">Du måste vara inloggad för att skapa en lektion';
	}
}

function insert_attachment($file_handler,$post_id,$setthumb='false') {

	// check to make sure its a successful upload
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$attach_id = media_handle_upload( $file_handler, $post_id );

	if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
	return $attach_id;
}
