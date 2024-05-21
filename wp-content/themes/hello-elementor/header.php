<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' );
$enable_skip_link = apply_filters( 'hello_elementor_enable_skip_link', true );
$skip_link_url = apply_filters( 'hello_elementor_skip_link_url', '#content' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<meta property="og:site_name" content="EsCard"/>
  <meta property="og:title" content="Home"/>
  <meta property="og:url" content="https://escard.ge"/>
  <meta property="og:type" content="website"/>
  <meta property="og:description" content="Sign up																Log in																																																								What is ESCARD?				Digital card, providing wide range of lifestyle benefits as well as exclusive offers and up to 40% discounts of more than 250 partner companies from all over Georgia, including the enter"/>
  <meta property="og:image" content="https://escard.ge/wp-content/uploads/2024/01/cover.png"/>
  <meta property="og:image:url" content="https://escard.ge/wp-content/uploads/2024/01/cover.png"/>
  <meta property="og:image:secure_url" content="https://escard.ge/wp-content/uploads/2024/01/cover.png"/>
  <meta property="og:image:width" content="1200"/>
  <meta property="og:image:height" content="630"/>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php if ( $enable_skip_link ) { ?>
<a class="skip-link screen-reader-text" href="<?php echo esc_url( $skip_link_url ); ?>"><?php echo esc_html__( 'Skip to content', 'hello-elementor' ); ?></a>
<?php } ?>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-header' );
	} else {
		get_template_part( 'template-parts/header' );
	}
}
