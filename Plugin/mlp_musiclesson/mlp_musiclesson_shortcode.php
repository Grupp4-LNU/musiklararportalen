<?php

add_shortcode('mlp_musiclesson', function() {
	$loop = new WP_Query(
		array(
			'post_type' => 'mlp_musiclesson',
			'orderby' => 'title'
		)
	);
	
	if ( $loop->have_posts() ) {
		$output = '<ul class="mlp_musiclesson_list">';
		
		while ( $loop->have_posts() ) {
			$loop->the_post();
			$meta = get_post_meta(get_the_id()."");
			$output .= "
				<li>
					<a href='".get_permalink()."'>
						". get_the_title() ." | ". $meta['mlp_intro'][0] ."
					</a>
					<div>
						". get_the_excerpt() ."
					</div>
				</li>
			";
		}
	} else {
		$output = "Det finns inga lektioner";
	}
	
	return $output;
	
});
