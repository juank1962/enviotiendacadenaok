<?php
/**
 * The Banners Section
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

		/* BANNERS */


		$shop_isle_banners_hide  = get_theme_mod( 'shop_isle_banners_hide' );
		$shop_isle_banners_title = get_theme_mod( 'shop_isle_banners_title' );

if ( isset( $shop_isle_banners_hide ) && $shop_isle_banners_hide != 1 ) :
	echo '<section class="module-small home-banners">';
		elseif ( is_customize_preview() ) :
			echo '<section class="module-small home-banners si-hidden-in-customizer"></section>';
			return;
		endif;

		if ( ( isset( $shop_isle_banners_hide ) && $shop_isle_banners_hide != 1 ) || is_customize_preview() ) :

			$shop_isle_banners = get_theme_mod(
				'shop_isle_banners', json_encode(
					array(
						array(
							'image_url' => get_template_directory_uri() . '/assets/images/banner1.jpg',
							'link'      => '#',
						),
						array(
							'image_url' => get_template_directory_uri() . '/assets/images/banner2.jpg',
							'link'      => '#',
						),
						array(
							'image_url' => get_template_directory_uri() . '/assets/images/banner3.jpg',
							'link'      => '#',
						),
					)
				)
			);

			if ( ! empty( $shop_isle_banners ) ) :

				$shop_isle_banners_decoded = json_decode( $shop_isle_banners );
				
				if ( ! empty( $shop_isle_banners_decoded ) ) :

					echo '<div class="container">';

					if ( ! empty( $shop_isle_banners_title ) ) {
						echo '<div class="row">';
						echo '<div class="col-sm-6 col-sm-offset-3">';
						echo '<h2 class="module-title font-alt product-banners-title">' . $shop_isle_banners_title . '</h2>';
						echo '</div>';
						echo '</div>';

					} elseif ( is_customize_preview() ) {
						echo '<div class="row">';
						echo '<div class="col-sm-6 col-sm-offset-3">';
						echo '<h2 class="module-title font-alt product-banners-title"></h2>';
						echo '</div>';
						echo '</div>';
					}

						echo '<div class="row shop_isle_bannerss_section">';
						echo do_shortcode( '[products per_page="4" columns="4" category="'.$shop_isle_banners_title.'" orderby="rand" order="rand" on_sale="true"]' );						
						echo '</div>';

					echo '</div>';
				endif;

			endif;
			
			echo '</section>';

endif;  /* END BANNERS */
