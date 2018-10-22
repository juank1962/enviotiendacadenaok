<?php
/**
 * The front-page.php
 *
 * @package ShopIsle
 */

get_header();
/* Wrapper start */

echo '<div class="main">';
$big_title = dirname( __FILE__ ) . '/inc/sections/shop_isle_big_title_section.php';
load_template( apply_filters( 'shop-isle-subheader', $big_title ) );

/* Wrapper start */
$shop_isle_bg = get_theme_mod( 'background_color' );

if ( isset( $shop_isle_bg ) && $shop_isle_bg != '' ) {
	echo '<div class="main front-page-main" style="background-color: #' . $shop_isle_bg . '">';
} else {

	echo '<div class="main front-page-main" style="background-color: #FFF">';

}

if ( defined( 'WCCM_VERISON' ) ) {

	/* Woocommerce compare list plugin */
	echo '<section class="module-small wccm-frontpage-compare-list">';
	echo '<div class="container">';
	do_action( 'shop_isle_wccm_compare_list' );
	echo '</div>';
	echo '</section>';

}

/******* Products Slider Section - Ofertas Diarias*/
// $products_slider = get_template_directory() . '/inc/sections/shop_isle_products_slider_section.php';
$products_slider = dirname( __FILE__ ) .'/inc/sections/shop_isle_products_slider_section.php';
require_once( $products_slider );


/******  Banners Section - Ofertas Mensuales*/
$banners_section = get_template_directory() . '\inc\sections\shop_isle_banners_section.php';
//require_once( $banners_section );
include_once(dirname( __FILE__ ) .'\inc\sections\shop_isle_banners_section.php');
// echo do_shortcode( '[ofertas categoria="Ofertas Mensuales"]' );

/******* Products Section - Ofertas Semanales*/
$latest_products = dirname( __FILE__ ) . '/inc/sections/shop_isle_products_section.php';
require_once( $latest_products );

/******* Video Section */
$video = get_template_directory() . '/inc/sections/shop_isle_video_section.php';
require_once( $video );
?>

<?php 
// Cargamos todos los plugins activos
$plugins = get_option('active_plugins');
// Plugin que deseamos comprobar
$required_plugin = 'easy-testimonials/easy-testimonials.php';
if ( in_array( $required_plugin , $plugins ) ) {
?>
<section class="module-small" style="background-color:lightgray">
	<div class="container">
		<div class="col-sm-6 col-sm-offset-3">
			<h2 class="module-title font-alt home-prod-title"> Testimonios de nuestros clientes </h2>
		 </div>
		<div class="col-xs-12">	
			<?php echo do_shortcode('[testimonials_cycle theme="clean_style" count="-1" order_by="date" order="ASC" show_title="1" use_excerpt="1" show_thumbs="1" show_date="0" show_other="1" hide_view_more="1" output_schema_markup="0" show_rating="stars" testimonials_per_slide="1" transition="fade" timer="5000" pause_on_hover="true" auto_height="calc" show_pager_icons="1" prev_next="1" display_pagers_above="0" paused="0"]');?>
		</div>
	</div>
</section>


<?php
};

// Plugin que deseamos comprobar
$required_plugin = 'wp-google-maps/wpGoogleMaps.php';
if ( in_array( $required_plugin , $plugins ) ) {
	?>
	<h2 class="module-title font-alt home-prod-title">Nuestros Almacenes </h2>
	<div class="module-subtitle font-serif home-prod-subtitle">Ubique nuestras tiendas en la ciudad de Bariloche</div>
	<?php echo '<row>';
	 	echo do_shortcode( '[wpgmza id="1"]' );
	 echo '</row>';
	}?>

<?php
get_footer();

