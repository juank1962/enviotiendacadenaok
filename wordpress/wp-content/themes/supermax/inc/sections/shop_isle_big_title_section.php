<?php

/**
 * Big title section
 *
 * @package ShopIsle
 * @since 1.0.0
 */

$shop_isle_homepage_slider_shortcode = get_theme_mod( 'shop_isle_homepage_slider_shortcode' );
$shop_isle_big_title_hide            = get_theme_mod( 'shop_isle_big_title_hide' );

if ( isset( $shop_isle_big_title_hide ) && $shop_isle_big_title_hide != 1 ) {
	echo '<section id="home" class="home-section home-parallax home-fade' . ( empty( $shop_isle_homepage_slider_shortcode ) ? ' home-full-height' : ' home-slider-plugin' ) . '">';
} elseif ( is_customize_preview() ) {
	echo '<section id="home" class="home-section home-parallax home-fade shop_isle_hidden_if_not_customizer' . ( empty( $shop_isle_homepage_slider_shortcode ) ? ' home-full-height' : ' home-slider-plugin' ) . '">';
}

if ( ( isset( $shop_isle_big_title_hide ) && $shop_isle_big_title_hide != 1 ) || is_customize_preview() ) {

	if ( ! empty( $shop_isle_homepage_slider_shortcode ) ) {
		echo do_shortcode( $shop_isle_homepage_slider_shortcode );
	} else {

		$shop_isle_big_title_image    = get_theme_mod( 'shop_isle_big_title_image', get_template_directory_uri() . '/assets/images/slide1.jpg' );
		$shop_isle_big_title_title    = get_theme_mod( 'shop_isle_big_title_title', 'Shop Isle' );
		$shop_isle_big_title_subtitle = get_theme_mod( 'shop_isle_big_title_subtitle', __( 'WooCommerce Theme', 'shop-isle' ) );
		
		echo '<div class="hero-slider">';

		echo '<ul class="slides">';
		
		echo '<li class="bg-dark" style="background-image:url(' . esc_url( $shop_isle_big_title_image ) . ')">';

			echo '<div class="home-slider-overlay"></div>';
			echo '<div class="hs-caption">';
			echo '<div class="caption-content">';

			if ( ! empty( $shop_isle_big_title_title ) ) {
				echo '<h1 class="hs-title-size-4 font-alt mb-30">' . $shop_isle_big_title_title . '</h1>';
			} elseif ( is_customize_preview() ) {
				echo '<h1 class="hs-title-size-4 font-alt mb-30"></h1>';
			}

			if ( ! empty( $shop_isle_big_title_subtitle ) ) {
				echo '<div class="hs-title-size-1 font-alt mb-40">' . $shop_isle_big_title_subtitle . '</div>';
			} elseif ( is_customize_preview() ) {
				echo '<div class="hs-title-size-1 font-alt mb-40"></div>';
			}

			shop_isle_big_title_section_display_button();
			echo '</div><!-- .caption-content -->';
			echo '</div><!-- .hs-caption -->';

			echo '</li><!-- .bg-dark -->';
			//Enviar como argumento la categoría que coincida con el slug "Ofertas-diarias" y limitar a 4 productos de manera aleatoria
			$args = array(
				'posts_per_page' => 4,
				'product_cat' => 'ofertas-diarias',
				'post_type' => 'product',
				'orderby'   => 'rand',
			);
			$the_query = new WP_Query( $args );
			while ( $the_query->have_posts() ) : $the_query->the_post();
			global $product;
				$category = get_term_by('id', $cat_id, 'product_cat', 'ARRAY_A');
				//Condiciona que el producto esté en oferta y no se hayan definido períodos de la oferta
					if ( $product->is_on_sale() ) :
						$product->name;
						$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($product->id), 'single-post-thumbnail' );
						$subtext = descripcion_corta(10, get_the_excerpt());
						$url = get_permalink($product->id);
						$label = 'Ver Oferta';
					endif;

					$image_url = ! empty( $image_url[0] ) ? apply_filters( 'shop_isle_translate_single_string', $image_url[0], 'Slider section' ) : '';
					$text      = ! empty( $product->name ) ? apply_filters( 'shop_isle_translate_single_string', $product->name, 'Slider section' ) : '';
					$subtext   = ! empty( $subtext ) ? apply_filters( 'shop_isle_translate_single_string', $subtext, 'Slider section' ) : '';
					$link      = ! empty( $url ) ? apply_filters( 'shop_isle_translate_single_string', $url, 'Slider section' ) : '';
					$label     = ! empty( $label ) ? apply_filters( 'shop_isle_translate_single_string', $label, 'Slider section' ) : '';

					if ( ! empty( $image_url ) ) {

						echo '<li class="bg-dark-30 bg-dark" style="background-image:url(' . esc_url( $image_url ) . ')">';
						echo '<div class="home-slider-overlay"></div>';
						echo '<div class="hs-caption">';
						echo '<div class="caption-content">';
	
						if ( ! empty( $text ) ) {
							if ( ! $has_h1_tag ) {
								echo '<h1 class="hs-title-size-4 font-alt mb-30">' . wp_kses_post( $text ) . '</h1>';
								$has_h1_tag = 1;
							} else {
								echo '<div class="hs-title-size-4 font-alt mb-30">' . wp_kses_post( $text ) . '</div>';
							}
						}
	
						if ( ! empty( $subtext ) ) {
							echo '<div class="hs-title-size-1 font-alt mb-40">' . wp_kses_post( $subtext ) . '</div>';
						}
	
						if ( ! empty( $link ) && ! empty( $label ) ) {
							echo '<a href="' . esc_url( $link ) . '" class="section-scroll btn btn-border-w btn-round">' . wp_kses_post( $label ) . '</a>';
						}
	
						echo '</div>';
						echo '</div>';
						echo '</li>';
	
					}// End if().
				$count++;
			
			endwhile;

			echo '</ul><!-- .slides -->';

			echo '</div><!-- .hero-slider -->';


	}// End if().
}// End if().

echo '</section >';
echo '<section class="col-lg-12">';
echo '<div class="container">';
echo do_shortcode('[aps-social id="1"]');
echo '</div>';
echo '</section>';
