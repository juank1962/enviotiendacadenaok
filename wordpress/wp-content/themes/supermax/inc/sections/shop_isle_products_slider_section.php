<?php
/**
 * Front page Product Slider Section
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

$shop_isle_products_slider_hide = get_theme_mod( 'shop_isle_products_slider_hide', false );
if ( ! empty( $shop_isle_products_slider_hide ) && (bool) $shop_isle_products_slider_hide === true ) {
	return;
}
echo '<section class="home-product-slider">';
echo '<div class="container">';


if ( current_user_can( 'edit_theme_options' ) ) {
	$shop_isle_products_slider_title = get_theme_mod( 'shop_isle_products_slider_title', __( 'Exclusive products', 'shop-isle' ) );
	if ( ! class_exists( 'WC_Product' ) ) {
		$shop_isle_products_slider_subtitle = get_theme_mod( 'shop_isle_products_slider_subtitle', __( 'For this section to work, you first need to install the WooCommerce plugin , create some products, and select a product category in Customize -> Frontpage sections -> Products slider section', 'shop-isle' ) );
	} else {
		$shop_isle_products_slider_subtitle = get_theme_mod( 'shop_isle_products_slider_subtitle' );
	}
} else {
	$shop_isle_products_slider_title    = get_theme_mod( 'shop_isle_products_slider_title' );
	$shop_isle_products_slider_subtitle = get_theme_mod( 'shop_isle_products_slider_subtitle' );
}


	echo '<div class="row">';
	echo '<div class="col-sm-6 col-sm-offset-3">';
if ( ! empty( $shop_isle_products_slider_title ) ) :
	echo '<h2 class="module-title font-alt home-prod-title">' . $shop_isle_products_slider_title . '</h2>';
	elseif ( is_customize_preview() ) :
		echo '<h2 class="module-title font-alt home-prod-title"></h2>';
	endif;
	if ( ! empty( $shop_isle_products_slider_subtitle ) ) :
		echo '<div class="module-subtitle font-serif home-prod-subtitle">' . $shop_isle_products_slider_subtitle . '</div>';
	elseif ( is_customize_preview() ) :
		echo '<div class="module-subtitle font-serif home-prod-subtitle"></div>';
	endif;
	echo '</div>';
	echo '</div><!-- .row -->';

	$shop_isle_products_slider_category = get_theme_mod( 'shop_isle_products_slider_category' );
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

	$shop_isle_products_slider_args = array(
		'post_type'      => 'product',
		'posts_per_page' => 10,
	);

	if ( ! empty( $shop_isle_products_slider_category ) && ( $shop_isle_products_slider_category != '-' ) ) {
		$shop_isle_products_slider_args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $shop_isle_products_slider_category,
			),
		);
	}
	

	if ( ! empty( $tax_query_item ) ) {
		$shop_isle_products_slider_args['tax_query']['relation'] = 'AND';
		$shop_isle_products_slider_args['tax_query']             = array_merge( $shop_isle_products_slider_args['tax_query'], $tax_query_item );
	}

	if ( ! empty( $meta_query_item ) ) {
		$shop_isle_products_slider_args['meta_query'] = $meta_query_item;
	}



	if ( ! empty( $shop_isle_products_slider_category ) && ( $shop_isle_products_slider_category != '-' ) ) :

		$shop_isle_products_slider_loop = new WP_Query( $shop_isle_products_slider_args );

		if ( $shop_isle_products_slider_loop->have_posts() ) :

			$rtl_slider      = apply_filters( 'shop_isle_products_slider_section_rtl', 'false' );
			$number_of_items = apply_filters( 'shop_isle_products_slider_section_items', 4 );
			$pagination      = apply_filters( 'shop_isle_products_slider_section_pagination', 'true' );
			$navigation      = apply_filters( 'shop_isle_products_slider_section_navigation', 'true' );
			$link = get_term_link( (int)$shop_isle_products_slider_category, 'product_cat' );
			echo '<div class="row">';
			echo '<div class="container">';
			echo '<div class="supermax-carousel owl-carousel text-center" data-loop="false" data-items="' . esc_attr( $number_of_items ) . '" data-pagination="' . esc_attr( $pagination ) . '" data-navigation="' . esc_attr( $navigation ) . '" data-rtl="' . esc_attr( $rtl_slider ) . '" >';

			while ( $shop_isle_products_slider_loop->have_posts() ) :

				$shop_isle_products_slider_loop->the_post();
				global $product;
				echo '<div class="owl-item">';
					echo '<div class="col-sm-12 products">';
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

			wp_reset_postdata();
			echo '</div>';
			echo '<a href="'.$link.'" class="button black" target="_self">Ver todas las '. $shop_isle_products_slider_title .'</a>';
			echo '</div>';
			echo '</div>';

		endif;

else :

	$shop_isle_products_slider_loop = new WP_Query( $shop_isle_products_slider_args );

	if ( $shop_isle_products_slider_loop->have_posts() ) :

		$rtl_slider      = apply_filters( 'shop_isle_products_slider_section_rtl', 'false' );
		$number_of_items = apply_filters( 'shop_isle_products_slider_section_items', 4 );
		$pagination      = apply_filters( 'shop_isle_products_slider_section_pagination', 'true' );
		$navigation      = apply_filters( 'shop_isle_products_slider_section_navigation', 'true' );
		echo '<div class="row">';

		echo '<div class="supermax-carousel text-center" data-loop="false" data-items="' . esc_attr( $number_of_items ) . '" data-pagination="' . esc_attr( $pagination ) . '" data-navigation="' . esc_attr( $navigation ) . '" data-rtl="' . esc_attr( $rtl_slider ) . '">';

		while ( $shop_isle_products_slider_loop->have_posts() ) :

			$shop_isle_products_slider_loop->the_post();
			global $product;
			echo '<div class="owl-item">';
					echo '<div class="col-sm-12 products">';
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
		echo '<a href="" class="button" target="_self">Ver todas las '. $shop_isle_products_slider_title .'</a>';
		echo '</div>';
		wp_reset_postdata();
	endif;

endif;


echo '</div>';

echo '</section>';




