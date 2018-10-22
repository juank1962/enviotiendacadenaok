<?php
/**
 * Front page Latest Products Section
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

$shop_isle_products_hide = get_theme_mod( 'shop_isle_products_hide', false );
if ( ! empty( $shop_isle_products_hide ) && (bool) $shop_isle_products_hide === true ) {
	if ( is_customize_preview() ) {
		echo '<section id="latest" class="module-small si-hidden-in-customizer"></section>';
	}
	return;
}

/* Latest products */

echo '<section id="latest" class="module-small">';

echo '<div class="container">';

if ( current_user_can( 'edit_theme_options' ) ) {
	$shop_isle_products_title = get_theme_mod( 'shop_isle_products_title', __( 'Latest products', 'shop-isle' ) );
} else {
	$shop_isle_products_title = get_theme_mod( 'shop_isle_products_title' );
}


if ( ! empty( $shop_isle_products_title ) ) :
	echo '<div class="row">';
	echo '<div class="col-sm-6 col-sm-offset-3">';
	echo '<h2 class="module-title font-alt product-hide-title">' . $shop_isle_products_title . '</h2>';
	echo '</div>';
	echo '</div>';
elseif ( is_customize_preview() ) :
	echo '<div class="row">';
	echo '<div class="col-sm-6 col-sm-offset-3">';
	echo '<h2 class="module-title font-alt product-hide-title"></h2>';
	echo '</div>';
	echo '</div>';
endif;

$shop_isle_products_shortcode = get_theme_mod( 'shop_isle_products_shortcode' );
$shop_isle_products_category  = get_theme_mod( 'shop_isle_products_category' );

$link = get_term_link( (int)$shop_isle_products_category, 'product_cat' );

$tax_query_item  = array();
$meta_query_item = array();
if ( taxonomy_exists( 'product_visibility' ) ) {
	$tax_query_item = array(
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'exclude-from-catalog',
			'operator' => 'NOT IN',

		),
	);
} else {
	$meta_query_item = array(
		'key'     => '_visibility',
		'value'   => 'hidden',
		'compare' => '!=',
	);

}

$shop_isle_latest_args = array(
	'post_type'      => 'product',
	'posts_per_page' => 8,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( ! empty( $shop_isle_products_category ) && $shop_isle_products_category != '-' ) {
	$shop_isle_latest_args['tax_query'] = array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $shop_isle_products_category,
		),
	);
}

if ( ! empty( $tax_query_item ) ) {
	$shop_isle_latest_args['tax_query']['relation'] = 'AND';
	$shop_isle_latest_args['tax_query']             = array_merge( $shop_isle_latest_args['tax_query'], $tax_query_item );
}

if ( ! empty( $meta_query_item ) ) {
	$shop_isle_latest_args['meta_query'] = $meta_query_item;
}


/* Woocommerce shortcode */
if ( isset( $shop_isle_products_shortcode ) && ! empty( $shop_isle_products_shortcode ) ) :
	echo '<div class="products_shortcode">';
	echo do_shortcode( $shop_isle_products_shortcode );
	echo'<div class="row mt-30"><div class="col-sm-12 align-center"><a href="https://localhost/wordpress/categoria-producto/ofertas-semanales" class="button black" target="_self">Ver todas las Ofertas Semanales</a></div></div>';
	echo '</div>';

	/* Products from category */
elseif ( isset( $shop_isle_products_category ) && ! empty( $shop_isle_products_category ) && ( $shop_isle_products_category != '-' ) ) :

	$shop_isle_latest_loop = new WP_Query( $shop_isle_latest_args );
	if ( $shop_isle_latest_loop->have_posts() ) :

		echo '<div class="row multi-columns-row">';
		echo '<div class="container woocommerce">';
									echo '<ul class="products">';
		while ( $shop_isle_latest_loop->have_posts() ) :

			$shop_isle_latest_loop->the_post();
			global $product;
			echo '<div class="col-sm-6 col-md-3 col-lg-3">';
										add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
										wc_get_template_part( 'content', 'product' );						
										add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
			echo '</div>';

		endwhile;
		echo '</ul>';
		echo '</div>';
		
		echo '</div>';	

		echo '<div class="row mt-30">';
		echo '<div class="col-sm-12 align-center">';
		// if ( function_exists( 'wc_get_page_id' ) ) {
		// 	echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
		// } elseif ( function_exists( 'woocommerce_get_page_id' ) ) {
		// 	echo '<a href="' . esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
		// }
		echo '<a href="'.$link.'" class="button black" target="_self">Ver todas las '. $shop_isle_products_category .'</a>';
		echo '</div>';
		echo '</div>';

	else :

		echo '<div class="row">';
		echo '<div class="col-sm-6 col-sm-offset-3">';
		echo '<p class="">' . __( 'No products found.', 'shop-isle' ) . '</p>';
		echo '</div>';
		echo '</div>';

	endif;

	wp_reset_postdata();

	/* Latest products */
else :

	$shop_isle_latest_loop = new WP_Query( $shop_isle_latest_args );

	if ( $shop_isle_latest_loop->have_posts() ) :

		echo '<div class="row multi-columns-row">';

		while ( $shop_isle_latest_loop->have_posts() ) :

			$shop_isle_latest_loop->the_post();
			global $product;

			echo '<div class="col-sm-6 col-md-3 col-lg-3">';
			
			echo '<div class="shop-item">';
				echo '<div class="woocommerce">';
					echo '<ul class="products">';
						add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
						wc_get_template_part( 'content', 'product' );						
						add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
					echo '</ul>';
				echo '</div>';
			echo '</div>';
			echo '</div>';

		endwhile;

		echo '</div>';

		echo '<div class="row mt-30">';
		echo '<div class="col-sm-12 align-center">';
		if ( function_exists( 'wc_get_page_id' ) ) {
			echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
		} elseif ( function_exists( 'woocommerce_get_page_id' ) ) {
			echo '<a href="' . esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
		}
		echo '</div>';
		echo '</div>';

	elseif ( current_user_can( 'edit_theme_options' ) ) :
		echo '<div class="row">';
		echo '<div class="col-sm-6 col-sm-offset-3">';
		echo '<p class="">' . __( 'For this section to work, you first need to install the WooCommerce plugin , create some products, and insert a WooCommerce shortocode or select a product category in Customize -> Frontpage sections -> Products section', 'shop-isle' ) . '</p>';
		echo '</div>';
		echo '</div>';
	endif;

	wp_reset_postdata();

endif;
if (!isset($link)):
	echo '<a href="'.$link.'" class="button black" target="_self">Ver todas las '. $shop_isle_products_title .'</a>';
endif;
echo '</div><!-- .container -->';

echo '</section>';




