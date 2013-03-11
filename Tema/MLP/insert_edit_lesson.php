<?php
 /*Template Name: MLP Insert Edit Lesson
 */
 ?> 
<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ); ?>

		<div class="page" id="lesson-archives" role="main">
		
		<?php
			$post_id = null;
			$post_categories = null;
			$post_grades = null;
			$post_title = null;
			$post_intro = null;
			$post_goal = null;
			$post_execution = null;
			$post_media = null;
			$attachment_id = null;
			$display_form = true;

		?>
		
		
		<?php if(isset($_GET['id'])) $post_id = $_GET['id']; else $post_id = false;?>
		<?php if(isset($_POST['id'])) $post_id = $_POST['id']; ?>
		<?php if($post_id) : ?>
			<h2 class="pagetitle">Redigera Lektion</h2>
			
			<?php
				//Checking current user
				global $current_user;
				get_currentuserinfo();
			?>
							
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
							// Delete attachment
							$attachment_id = isset($_POST['att_id']) ? $_POST['att_id'] : null;
							if($attachment_id)
							{
								$attachment = get_post($attachment_id);
								if(isset($attachment))
								{
									if($attachment->post_author == $current_user->ID)
									{
										wp_delete_attachment($attachment_id);
										echo "<p class='delete_file_success'>Filen togs bort!</p>";
									}
									else {
										echo "<p class='delete_file_error'>Du har inte rättigheter att ta bort den här filen!</p>";
									}
								}
								else {
									echo "<p class='delete_file_error'>Det finns ingen fil med de id't!</p>";
								}
							}
						?>
						<?php
							$post_id = get_the_ID();
							$post_categories = array();
							$category_terms = get_the_terms( $post->ID , 'mlp_category' );
							foreach($category_terms as $term){
								$post_categories[] = $term->term_id;
							}
							
							$post_grades = array();
							$grade_terms = get_the_terms( $post->ID , 'mlp_grade' );
							foreach($grade_terms as $term){
								$post_grades[] = $term->term_id;
							}
							
							$post_title = get_the_title();
							$post_intro = esc_html( get_post_meta( get_the_ID(), 'mlp_intro', true ) );
							$post_goal = esc_html( get_post_meta( get_the_ID(), 'mlp_goals', true ) );
							$post_execution = esc_html( get_post_meta( get_the_ID(), 'mlp_execution', true ) );
							$attach_args = array(
								'post_type' => 'attachment',
								'post_parent' => get_the_ID(),
								'post_status' => null,
								'numberposts' => null
							);
							$attachments = get_posts($attach_args);
							$post_media = $attachments;
						?>
					
					<?php else: ?>
					<p>Du har ej behörighet att redigera denna lektion</p>
					<h2>Istället kan du här nedan skapa ny lektion</h2>
					<?php endif; ?>	

				<?php endwhile; ?>
			<?php else: ?>
				<p>Hittade ingen lektion med detta id</p>
				<?php $display_form = false; ?>
			<?php endif; ?>	

		<?php else: ?>
			<h2 class="pagetitle">Skapa Lektion</h2>
		<?php endif; ?>		
		
			<?php if( is_user_logged_in() ) : ?>
			
				<?php // Check if the form has been submitted ?>
				<?php if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && 'insert_new_lesson' == $_POST['action'] ) : ?>
					
					<?php
					$errors = array();
					
					if($_POST['lesson_title'] == "") 
					{
						$errors[] = '<p>Du måste ange en lektionstitel</p>';
					} 
					else if(strlen($_POST['lesson_title']) < 10)
					{
						$errors[] = '<p>Lektionstiteln är för kort (minst 10 tecken)</p>';
					}
					else 
					{
						$title = esc_html($_POST['lesson_title']);
					}
					
					if($_POST['lesson_intro'] == "")
					{
						$errors[] = '<p>Du måste ange en lektionsintroduktion</p>';
					}
					else if(strlen($_POST['lesson_intro']) < 40)
					{
						$errors[] = '<p>Lektionsintroduktionen är för kort (minst 40 tecken)</p>';
					}
					else
					{
						$introduction = esc_html($_POST['lesson_intro']);
					}
					
					if($_POST['lesson_goal'] == "") 
					{
						$errors[] = '<p>Du måste ange ett mål med lektionen</p>';
					}
					else if(strlen($_POST['lesson_goal']) < 40)
					{
						$errors[] = '<p>Lektionsmål är för kort (minst 40 tecken)</p>';
					}
					else 
					{
						$goal = esc_html($_POST['lesson_goal']);
					}
					
					if($_POST['lesson_execution'] == "") 
					{
						$errors[] = '<p>Du måste ange en beskrivning på hur man utför lektionen</p>';
					}
					else if(strlen($_POST['lesson_execution']) < 40)
					{
						$errors[] = '<p>Lektionsutförande är för kort (minst 40 tecken)</p>';
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

						if($post_id > 0){
							
							// Prepare update lesson
							$update_post = array(
								'post_title'    => $title,
								'ID' 			=> $post_id,
								'post_status'   => 'publish',
								'post_type'     => 'mlp_musiclesson'
							);
							wp_update_post( $update_post );
											
							wp_set_post_terms( $post_id, $lesson_grades, 'mlp_grade', false );
							wp_set_post_terms( $post_id, $lesson_categories, 'mlp_category', false );
							
							update_post_meta($post_id, 'mlp_intro', $introduction);
							update_post_meta($post_id, 'mlp_goals', $goal);
							update_post_meta($post_id, 'mlp_execution', $execution);						
						
						
						}
						else{
											
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
						}
							
						if ($_FILES['lesson_file']['name'] != "") {
							$wp_upload_dir = wp_upload_dir();
							foreach ($_FILES as $file => $array) {
								// $newupload returns the attachment id of the file that
								// was just uploaded. Do whatever you want with that now.
								require_once(ABSPATH . 'wp-admin/includes/admin.php');
								
								$file_return = wp_handle_upload($array, array('test_form' => false));
						
								if(isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
									$fileUploadError = $file_return['error'];
								}
								if(isset($fileUploadError)){
									echo $fileUploadError;
									echo '<a href="javascript:history.back()">Tillbaka till formuläret</a>';
									wp_delete_post($post_id);
									die();
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
									
						echo "<p>Tackar! Din lektion har blivit sparad. <a href='".get_post_permalink($post_id)."'>Se din lektion här.</a></p>";

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
					<?php if($display_form) : ?>
						<form name="insert_new_lesson" id='insert_new_lesson' method="post" action="" class="lesson-form" enctype="multipart/form-data">
							<fieldset id='filter_lesson_container' class='cat_new_lesson_container'>
								<legend><p><strong>Välj Kategorier</strong></p></legend>	

								<!-- Lesson Categories -->
								<p>
								Huvudämnen:
								<?php $lessonCategories = get_terms('mlp_category', array( 'hide_empty' => 0 ));
								
								foreach ($lessonCategories as $lessonCategory) {
									if(isset($post_categories) && in_array($lessonCategory->term_id, $post_categories))
										echo '<label><input class="{category: true}" type="checkbox" checked name="category[]" value="'.$lessonCategory->term_id.'" id="grade'.$lessonCategory->term_id.'" />'.$lessonCategory->name.'</label>';
									else
										echo '<label><input class="{category: true}" type="checkbox" name="category[]" value="'.$lessonCategory->term_id.'" id="grade'.$lessonCategory->term_id.'" />'.$lessonCategory->name.'</label>';
								}
								?>
								</p>

								<p>
								Årskurs:
								<?php $grades = get_terms('mlp_grade', array( 'hide_empty' => 0 ));
								foreach ($grades as $grade) {
									if(isset($post_grades) && in_array($grade->term_id, $post_grades))
										echo '<label><input class="{grade: true}" checked type="checkbox" name="grade[]" value="'.$grade->term_id.'" id="grade'.$grade->term_id.'" />'.$grade->name.'</label>';
									else
										echo '<label><input class="{grade: true}" type="checkbox" name="grade[]" value="'.$grade->term_id.'" id="grade'.$grade->term_id.'" />'.$grade->name.'</label>';
								}
								?>
								</p>
							<div id="category_error"></div>
							<div id="grade_error"></div>
							</fieldset>
								
							<!-- Lesson Title -->
							<label for="lesson_title"><p>Titel</p></label>
							<input type="text" id="lesson_title" name="lesson_title" value='<?php echo $post_title; ?>' />
							
							<!-- Lesson Intro -->
							<label for="lesson_intro"><p>Inledning / Förutsättningar</p></label>
							<textarea id="lesson_intro" name="lesson_intro" ><?php echo $post_intro; ?></textarea>
							
							<!-- Lesson Goal -->
							<label for="lesson_goal"><p>Mål</p></label>
							<textarea id="lesson_goal" name="lesson_goal" ><?php echo $post_goal; ?></textarea>
							
							<!-- Lesson Execution -->
							<label for="lesson_execution"><p>Utförande</p></label>
							<textarea id="lesson_execution" name="lesson_execution" ><?php echo $post_execution; ?></textarea>
							
							<?php wp_nonce_field( 'insert_new_lesson' ); ?>
							</form>
							
							<label for="lesson_file"><p>Filer</p></label>
							<div class="files">
							
									<?php if(isset($attachments)) : ?>
										<?php if(sizeof($attachments) > 0) : ?>
											<ul>
											<?php foreach ($attachments as $attachment) : ?>
												<li class='attachments'>
												<form method='POST'>
												<a href='<?php echo $attachment->guid; ?>'><?php echo $attachment->post_name ?></a>
												<input type='hidden' name='id' value='<?php the_ID(); ?>'/>
												<input type='hidden' name='att_id' value='<?php echo $attachment->ID ?>'/>
												<button type='submit' class='delete_attachment delete_file_image'></button>
												</form>
												</li>
											<?php endforeach ?>
											</ul>
										<?php endif; ?>
									<?php endif; ?>
									
								<input type="file" id="lesson_file" form="insert_new_lesson" name="lesson_file">
								<a href="#" id="add_file_form">Lägg till ytterligare fil (+)</a>
							</div>
							
							<button type="submit" form="insert_new_lesson" tabindex="40" id="submit" name="submit">Spara</button>
							<input type="hidden" form="insert_new_lesson" name="action" value="insert_new_lesson" />
							<input type="hidden" form="insert_new_lesson" name="post_id" value= />
														
							
					<?php endif; ?>
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
