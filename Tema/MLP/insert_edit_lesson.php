<?php
 /*Template Name: Insert Edit Lesson
 */
 ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
 
<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ); ?>

		<div class="page" id="lesson-archives" role="main">
		
			<h2 class="pagetitle">Skapa Lektion</h2>
		
			<?php if( is_user_logged_in() ) : ?>
			
				<?php // Check if the form has been submitted ?>
				<?php if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && 'insert_new_lesson' == $_POST['action'] ) : ?>
					
					<?php
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
					?>
				
				<?php else : ?>
					<form name="insert_new_lesson" id='insert_new_lesson' method="post" action="" class="lesson-form" enctype="multipart/form-data">
						<fieldset id='filter_lesson_container' class='cat_new_lesson_container'>
							<legend><p><strong>Välj Kategorier</strong></p></legend>	

							<!-- Lesson Categories -->
							<p>
							Huvudområden:
							<?php $lessonCategories = get_terms('mlp_category', array( 'hide_empty' => 0 ));
							foreach ($lessonCategories as $lessonCategory) {
								echo '<label><input class="{category: true}" type="radio" name="category[]" value="'.$lessonCategory->term_id.'" id="grade'.$lessonCategory->term_id.'" />'.$lessonCategory->name.'</label>';
							}
							?>
							</p>

							<p>
							Årskurs:
							<?php $grades = get_terms('mlp_grade', array( 'hide_empty' => 0 ));
							foreach ($grades as $grade) {
								echo '<label><input class="{grade: true}" type="checkbox" name="grade[]" value="'.$grade->term_id.'" id="grade'.$grade->term_id.'" />'.$grade->name.'</label>';
							}
							?>
							</p>
						</fieldset>
							
						<!-- Lesson Title -->
						<label for="lesson_title"><p>Titel</p></label>
						<input type="text" id="lesson_title" name="lesson_title" />
						
						<!-- Lesson Intro -->
						<label for="lesson_intro"><p>Inledning / Förutsättningar</p></label>
						<textarea id="lesson_intro" name="lesson_intro"></textarea>
						
						<!-- Lesson Goal -->
						<label for="lesson_goal"><p>Mål</p></label>
						<textarea id="lesson_goal" name="lesson_goal"></textarea>
						
						<!-- Lesson Execution -->
						<label for="lesson_execution"><p>Utförande</p></label>
						<textarea id="lesson_execution" name="lesson_execution"></textarea>
						
						<label for="lesson_file"><p>Media</p></label>
						<div class="files">
						<input type="file" id="lesson_file" name="lesson_file">
						<a href="#" id="add_file_form">Lägg till ytterligare fil (+)</a>
						</div>
						
						<button type="submit" tabindex="40" id="submit" name="submit">Spara</button>
						<input type="hidden" name="action" value="insert_new_lesson" />
						
						<?php wp_nonce_field( 'insert_new_lesson' ); ?>
						
					</form>		
				
				<?php endif; ?>
				
			<?php else :?>

			<p class="dp-error">Du måste vara inloggad för att skapa en lektion'</p>
				
			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>

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
					required: true,
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
			},
			messages: {
				lesson_title: {
					required: "Detta fält måste fyllas i"
				},
				lesson_intro: {
					required: "Detta fält måste fyllas i"
				},
				lesson_goal: {
					required: "Detta fält måste fyllas i"
				},
				lesson_execution: {
					required: "Detta fält måste fyllas i"
				}				
			}
		});
	});
</script>
