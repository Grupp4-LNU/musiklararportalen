<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?><meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php 
			function mlp_remove_default_responsive() {
				remove_theme_support( 'bp-default-responsive' );
			}
			add_action( 'wp_enqueue_scripts', 'mlp_remove_default_responsive', 5 );
		?>
		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<div id="header">
			<div id="search-bar" role="search">
				<div class="padder">
					<h1 id="logo" role="banner"><a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'Home page banner link title', 'buddypress' ); ?>"><?php bp_site_name(); ?></a></h1>
				</div><!-- .padder -->
			</div><!-- #search-bar -->

			<div id="navigation" role="navigation">
				<?php wp_nav_menu( array(
					'container' => false,
					'menu_id' => 'nav',
					'theme_location' => 'primary',
					'fallback_cb' => 'bp_dtheme_main_nav' )
				);

				wp_nav_menu(array(
					'container' => false,
					'menu_id' => 'nav',
					'theme_location' => 'primary', // your theme location here
					'walker'         => new Walker_Nav_Menu_Dropdown(),
					'items_wrap'     => '<select>%3$s</select>',
					'fallback_cb'    => 'bp_dtheme_main_nav'
				));


				class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu{
				    function start_lvl(&$output, $depth){
				      $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
				    }

				    function end_lvl(&$output, $depth){
				      $indent = str_repeat("\t", $depth); // don't output children closing tag
				    }

				    function start_el(&$output, $item, $depth, $args){
					  // add spacing to the title based on the depth
					  $item->title = str_repeat("&nbsp;- ", $depth).$item->title;

					  parent::start_el($output, $item, $depth, $args);

					  $href = ! empty( $item->url ) ? ' value="'   . esc_attr( $item->url ) .'"' : '#';

					  // no point redefining this method too, we just replace the li tag...
					  $output = str_replace('<li', '<option '.$href, $output);
					}

				    function end_el(&$output, $item, $depth){
				      $output .= "</option>\n"; // replace closing </li> with the option tag
				    }
				} ?>

			</div>

			<?php do_action( 'bp_header' ); ?>

		</div><!-- #header -->

		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div id="container">
