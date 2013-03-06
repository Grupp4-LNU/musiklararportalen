<?php
 /*Template Name: Insert Edit Lesson
 */
 ?> 
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
					
					if($_POST['lesson_title'] == "") 
					{
						$errors[] = '<p>Du måste ange en lektionstitel</p>';
					} 
					else if(strlen($_POST['lesson_title']) < 4)
					{
						$errors[] = '<p>Lektionstiteln är för kort.</p>';
					}
					else 
					{
						$title = esc_html($_POST['lesson_title']);
					}
					
					if($_POST['lesson_intro'] == "")
					{
						$errors[] = '<p>Du måste ange en lektionsintroduktion</p>';
					}
					else if(strlen($_POST['lesson_intro']) < 20)
					{
						$errors[] = '<p>Lektionsintroduktionen är för kort.(Minst 20tecken)</p>';
					}
					else
					{
						$introduction = esc_html($_POST['lesson_intro']);
					}
					
					if($_POST['lesson_goal'] == "") 
					{
						$errors[] = '<p>Du måste ange ett mål med lektionen</p>';
					}
					else if(strlen($_POST['lesson_goal']) < 20)
					{
						$errors[] = '<p>Lektionsmål är för kort.(Minst 20tecken)</p>';
					}
					else 
					{
						$goal = esc_html($_POST['lesson_goal']);
					}
					
					if($_POST['lesson_execution'] == "") 
					{
						$errors[] = '<p>Du måste ange en beskrivning på hur man utför lektionen</p>';
					}
					else if(strlen($_POST['lesson_execution']) < 20)
					{
						$errors[] = '<p>Lektionsutförande är för kort.(Minst 20tecken)</p>';
					}
					else 
					{
						$execution = esc_html($_POST['lesson_execution']);
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
							$wp_upload_dir = wp_upload_dir();
							foreach ($_FILES as $file => $array) {
								// $newupload returns the attachment id of the file that
								// was just uploaded. Do whatever you want with that now.
								require_once(ABSPATH . 'wp-admin/includes/admin.php');
								
						        $file_return = wp_handle_upload($array, array('test_form' => false));
						
						        if(isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
						            echo $file_return['error'];
						        }
								
								$wp_filetype = wp_check_filetype(basename($array['name']), null );
								$filename = basename($array['name']);
								$args = array(
									'guid' => $wp_upload_dir['url'] . '/' . basename($file_return['url']),
									'post_mime_type' => $wp_filetype['type'],
									'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
									'post_status' => 'publish',
									'post_parent' => $post_id
								);
								$newupload = wp_insert_attachment($args);
							}
						}
									
						echo '<p>Tackar! Din lektion har blivit sparad</p>';
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
								echo '<label><input class="{category: true}" type="checkbox" name="category[]" value="'.$lessonCategory->term_id.'" id="grade'.$lessonCategory->term_id.'" />'.$lessonCategory->name.'</label>';
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
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#add_file_form").on("click", function(e) {
				e.preventDefault();
				var files = $(".files").children();
				var counter = files.length; 
				$(".files").append("<br /><input type=\"file\" id=\"lesson_file" + counter + "\" name=\"lesson_file"+counter+"\">")
			});
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
	<?php get_sidebar(); ?>

<?php get_footer(); ?>
