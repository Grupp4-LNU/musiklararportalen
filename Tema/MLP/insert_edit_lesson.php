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
			$post_goals = null;
			$post_target_groups = null;
			$post_title = null;
			$post_intro = null;
			$post_execution = null;
			$post_media = null;
			$attachment_id = null;
			$display_form = true;		
		?>
		
		<?php // Är det en editering som ska ske? ?>
		<?php if(isset($_GET['id'])) $post_id = $_GET['id']; ?>
		
		<?php // Är det en ny lektion som ska skapas. ?>
		<?php if(isset($_POST['post_id']) && $_POST['post_id'] > 0) $post_id = $_POST['post_id']; ?>

				
		<?php // Är användaren inloggad ?>
		<?php if( is_user_logged_in() ) : ?>		
		
			<?php // Kontrollerar ifall det är en nu lektion som ska skapas ?>
			<?php if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && 'insert_new_lesson' == $_POST['action'] ) : ?>
								
				<?php
				$errors = array();
				
				
				/**
				 * Validering på serversidan ifall man har javascript avaktiverat.
				 * Detta är alltså inte de röda felmeddelandne som kommer upp.
				 * för att ändra de röda texterna kolla js/external.js
				 */
				 
				// Felmeddelnande för lektionstitlen
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
				
				// Felmeddelnande för lektionsförutsättingar
				if($_POST['lesson_intro'] == "")
				{
					$errors[] = '<p>Du måste ange Lektionsförutsättningar</p>';
				}
				else if(strlen($_POST['lesson_intro']) < 40)
				{
					$errors[] = '<p>Lektionsförutsättningar är för kort (minst 40 tecken)</p>';
				}
				else
				{
					$introduction = esc_html($_POST['lesson_intro']);
				}

				// Felmeddelnande för genomförande
				if($_POST['lesson_execution'] == "") 
				{
					$errors[] = '<p>Du måste ange Lektionsgenomförande</p>';
				}
				else if(strlen($_POST['lesson_execution']) < 40)
				{
					$errors[] = '<p>Lektionsgenomförande är för kort (minst 40 tecken)</p>';
				}
				else 
				{
					$execution = esc_html($_POST['lesson_execution']);
				}
				
				// Felmeddelnande för målgrupp
				if(isset($_POST['target_group']))
				{
					$lesson_target_groups = array();
					foreach( $_POST['target_group'] as $target_group ) {
						$lesson_target_groups[] = $target_group;
					}
				}
				else 
				{
					$errors[] = '<p>Du måste ange minst en målgrupp för lektionen</p>';	
				}			
				
				// Felmeddelnande för syfte & mål							
				if(isset($_POST['goal']))
				{
					$lesson_goals = array();
					foreach( $_POST['goal'] as $goal ) {
						$lesson_goals[] = $goal;
					}
				}
				else
				{
					$errors[] = '<p>Du måste ange minst ett syfte/mål för lektionen</p>';
				}
				
				/**
				 * Ifall det inte var några fel så skapas/redigeras här lektionen.
				 */
				if(count($errors) == 0)
				{

					// Ifall variabalen post_id är större än 0 så är det en redigering av lektionen.
					if($post_id > 0){
						
						// Prepare update lesson
						$update_post = array(
							'post_title'    => $title,
							'ID' 			=> $post_id,
							'post_status'   => 'publish',
							'post_type'     => 'mlp_musiclesson'
						);
						wp_update_post( $update_post );
										
						wp_set_post_terms( $post_id, $lesson_target_groups, 'mlp_target_group', false );
						wp_set_post_terms( $post_id, $lesson_goals, 'mlp_goal', false );
						
						update_post_meta($post_id, 'mlp_intro', $introduction);
						update_post_meta($post_id, 'mlp_execution', $execution);

						// tar bort en fil ifall användaren har valt att klicka på att den ska tabort en fil.
						$attachment_id = isset($_POST['remove_att_id']) ? $_POST['remove_att_id'] : false;
						if($attachment_id)
						{
							$attachment = get_post($attachment_id);
							if(isset($attachment))
							{
								if($attachment->post_author == $current_user->ID)
								{
									wp_delete_attachment($attachment_id);
									echo "<p class='delete_file_success'>Filen har tagits bort!</p>";
								}
								else {
									echo "<p class='delete_file_error'>Du har inte rättigheter att ta bort den här filen!</p>";
								}
							}
							else {
								echo "<p class='delete_file_error'>Det finns ingen fil med detta id!</p>";
							}
						}
					
					
					}
					// Annars så skapas en ny lektion
					else{
					
						//  Förberedelse för att skapa en lektion.
						$new_lesson_post = array(
							'post_title'    => $title,
							'post_status'   => 'publish',
							'post_type'     => 'mlp_musiclesson'
						);
						$post_id = wp_insert_post( $new_lesson_post );
										
						wp_set_post_terms( $post_id, $lesson_target_groups, 'mlp_target_group', false );
						wp_set_post_terms( $post_id, $lesson_goals, 'mlp_goal', false );
						
						add_post_meta($post_id, 'mlp_intro', $introduction);
						add_post_meta($post_id, 'mlp_execution', $execution);
						add_post_meta($post_id, 'mlp_author', $current_user->first_name . $current_user->last_name);
					}
					
					// Ifall det finns eventuella filer som ska laddas upp.
					if ($_FILES['lesson_file']['name'] != "") {
						$wp_upload_dir = wp_upload_dir();
						
						// För varje fil laddas det upp.
						foreach ($_FILES as $file => $array) {
							require_once(ABSPATH . 'wp-admin/includes/admin.php');
							
							$file_return = wp_handle_upload($array, array('test_form' => false));
							
							// Ifall det fanns några frel med filen, eventuellt var filtypen inte tillåten
							if(isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
								$fileUploadError[] = $file_return['error'];
								$fileUploadError['fileType'] = $array['type'];
							}
							
							// Skriv ut felmeddelande om det fanns några fel.
							if(isset($fileUploadError)){
								echo $fileUploadError[0]. "<br />";
								echo "Filtypen som inte tilläts var av typen: ".$fileUploadError['fileType'];
								if(isset($_GET['id'])){
									echo "<br /><br />Sidan laddas automatiskt om och ger dig en ny chans om 5 sekunder";
									echo "<meta http-equiv='refresh' content='5'>";
									die();
								}
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
								
					echo "<p class='lesson_saved' >Din lektion har blivit sparad! <a href='".get_post_permalink($post_id)."'>Se din lektion här.</a></p>";

				}
				else 
				{
					foreach ($errors as $error) {
						echo $error;
					}

				}
				?>
			
			<?php endif; ?>		
			
			
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
								$post_id = get_the_ID();
								$post_goals = array();
								$goal_terms = get_the_terms( $post->ID , 'mlp_goal' );
								foreach($goal_terms as $term){
									$post_goals[] = $term->term_id;
								}
								
								$post_target_groups = array();
								$target_group_terms = get_the_terms( $post->ID , 'mlp_target_group' );
								foreach($target_group_terms as $term){
									$post_target_groups[] = $term->term_id;
								}
								
								$post_title = get_the_title();
								$post_intro = esc_html( get_post_meta( get_the_ID(), 'mlp_intro', true ) );
								$post_execution = esc_html( get_post_meta( get_the_ID(), 'mlp_execution', true ) );
								$attach_args = array(
									'post_type' => 'attachment',
									'post_parent' => get_the_ID(),
									'post_status' => null,
									'numberposts' => null
								);
								$attachments = get_posts($attach_args);
							?>
						
						<?php else: ?>
						<p>Du har ej behörighet att redigera denna lektion</p>
						<h2>Istället kan du här nedan skapa ny lektion</h2>
						<?php endif; ?>	

					<?php endwhile; ?>
				<?php else: ?>
					<p>Hittade ingen lektion med detta id</p>
				<?php endif; ?>	

			<?php else: ?>
				<h2 class="pagetitle">Skapa Lektion</h2>
			<?php endif; ?>		
					
			<?php // Formuläret som visas när man redigerar och skapar en lektion ?>	
			<?php if($display_form) : ?>
					<form name="insert_new_lesson" id='insert_new_lesson' method="post" action="" class="lesson-form" enctype="multipart/form-data">
						<div id="goal_error"></div>
						<div id="target_group_error"></div>
						<div id="lesson_title_error"></div>
						<div id="lesson_intro_error"></div>
						<div id="lesson_execution_error"></div>
						<fieldset id='filter_lesson_container' class='cat_new_lesson_container'>
							<legend><p><strong>Välj Kategorier</strong></p></legend>	

							<!-- Lesson goals -->
							<p>
							Syfte & Mål:
							<?php $lessongoals = get_terms(
								'mlp_goal', 
								array( 
									'orderby'=> 'id', 
									'order'=> 'ASC',
									'hide_empty' => 0 
								)
							);
							
							foreach ($lessongoals as $lessongoal) {
								if(isset($post_goals) && in_array($lessongoal->term_id, $post_goals))
									echo '<label><input class="{goal: true}" type="checkbox" checked name="goal[]" value="'.$lessongoal->term_id.'" id="target_group'.$lessongoal->term_id.'" />'.$lessongoal->name.'</label>';
								else
									echo '<label><input class="{goal: true}" type="checkbox" name="goal[]" value="'.$lessongoal->term_id.'" id="target_group'.$lessongoal->term_id.'" />'.$lessongoal->name.'</label>';
							}
							?>
							</p>

							<p>
							Målgrupp:
							<?php 
							$target_groups = get_terms(
								'mlp_target_group', 
								array( 
									'orderby'=> 'id', 
									'order'=> 'ASC', 
									'hide_empty' => 0 
								)
							);
							foreach ($target_groups as $target_group) {
								if(isset($post_target_groups) && in_array($target_group->term_id, $post_target_groups))
									echo '<label><input class="{target_group: true}" checked type="checkbox" name="target_group[]" value="'.$target_group->term_id.'" id="target_group'.$target_group->term_id.'" />'.$target_group->name.'</label>';
								else
									echo '<label><input class="{target_group: true}" type="checkbox" name="target_group[]" value="'.$target_group->term_id.'" id="target_group'.$target_group->term_id.'" />'.$target_group->name.'</label>';
							}
							?>
							</p>
						</fieldset>
							
						<!-- Lesson Title -->
						<label for="lesson_title"><span>Titel</span></label>
						<p class="help">Här skriver du lektionens titel, sökord tas ifrån den. Välj därför en titel med omtanke.</p>
						<input type="text" id="lesson_title" name="lesson_title" value='<?php echo $post_title; ?>' />
						
						<!-- Lesson Intro -->
						<label for="lesson_intro"><span>Förutsättningar</span></label>
						<p class="help">Här skriver du om vilka resurser som krävs i form av material, utrustning, lokaler och dylikt för att lektionen ska kunna genomföras samt om de eventuella förkunskaper som eleverna måste ha </p>
						<textarea id="lesson_intro" name="lesson_intro" ><?php echo $post_intro; ?></textarea>
						
						<!-- Lesson Execution -->
						<label for="lesson_execution"><span>Genomförande</span></label>
						<p class="help">Här skriver u om lektionens upplägg. Du kan också lämna information om hur lektionen kan kopplas till Lgr 11 samt vilka kunskapskvaliteter som eleverna utvecklar genom lektionen och hur dessa kan bedömas.</p>
						<textarea id="lesson_execution" name="lesson_execution" ><?php echo $post_execution; ?></textarea>
						
						<?php wp_nonce_field( 'insert_new_lesson' ); ?>
						
						
						<label for="lesson_file"><p class='files_label'>Filer</p></label>
						<div class="files">
								<p class="help">Här lägger du in filer (pdf, aiff, ppt, mp4, mp3. m.m.)</p>
								<?php if(isset($attachments)) : ?>
									<?php if(sizeof($attachments) > 0) : ?>
										<ul>
										<?php foreach ($attachments as $attachment) : ?>
											<li class='attachments'>
											<a href='<?php echo $attachment->guid; ?>'><?php echo $attachment->post_name ?></a> <?php echo " [".$attachment->post_mime_type."]"; ?>
											<button type='button' id='<?php echo $attachment->ID ?>' class='delete_attachment delete_file_image'></button>
											</li>
										<?php endforeach ?>
										</ul>
									<?php endif; ?>
								<?php endif; ?>
								
							<input type="file" id="lesson_file" name="lesson_file">
							<a href="#" id="add_file_form">Lägg till ytterligare fil (+)</a>
						</div>
						
						<button type="submit" tabindex="40" id='submit_button'>Spara</button>
						<input type="hidden" name="action" value="insert_new_lesson" />
						<input type="hidden" name="post_id" value="<?php echo $post_id ?>" />
						
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
