<?php

remove_action( 'woocommerce_after_single_product', 'shop_isle_products_slider_on_single_page', 10, 0 );
add_action( 'woocommerce_after_single_product', 'supermax_products_slider_on_single_page', 10, 0 );

function supermax_products_slider_on_single_page() {

$shop_isle_products_slider_single_hide = get_theme_mod( 'shop_isle_products_slider_single_hide' );

if ( isset( $shop_isle_products_slider_single_hide ) && $shop_isle_products_slider_single_hide != 1 ) :
    echo '<hr class="divider-w">';
    echo '<section class="module module-small-bottom aya">';
elseif ( is_customize_preview() ) :
    echo '<hr class="divider-w">';
    echo '<section class="module module-small-bottom shop_isle_hidden_if_not_customizer">';
endif;

if ( ( isset( $shop_isle_products_slider_single_hide ) && $shop_isle_products_slider_single_hide != 1 ) || is_customize_preview() ) :

        echo '<div class="container">';

            $shop_isle_products_slider_title    = get_theme_mod( 'shop_isle_products_slider_title', __( 'Exclusive products', 'shop-isle' ) );
            $shop_isle_products_slider_subtitle = get_theme_mod( 'shop_isle_products_slider_subtitle', __( 'Special category of products', 'shop-isle' ) );

    if ( ! empty( $shop_isle_products_slider_title ) || ! empty( $shop_isle_products_slider_subtitle ) ) :
        echo '<div class="row">';
        echo '<div class="col-sm-6 col-sm-offset-3">';
        if ( ! empty( $shop_isle_products_slider_title ) ) :
            echo '<h2 class="module-title font-alt">' . $shop_isle_products_slider_title . '</h2>';
        endif;
        if ( ! empty( $shop_isle_products_slider_subtitle ) ) :
            echo '<div class="module-subtitle font-serif">' . $shop_isle_products_slider_subtitle . '</div>';
        endif;
        echo '</div>';
        echo '</div><!-- .row -->';
            endif;

            $shop_isle_products_slider_category = get_theme_mod( 'shop_isle_products_slider_category' );

    $tax_query_item  = array();
    $meta_query_item = array();
    if ( taxonomy_exists( 'product_visibility' ) ) {
        $tax_query_item = array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_id',
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

            $rtl_slider      = apply_filters( 'shop_isle_products_slider_single_rtl', 'false' );
            $number_of_items = apply_filters( 'shop_isle_products_slider_single_items', 4 );
            $pagination      = apply_filters( 'shop_isle_products_slider_single_pagination', 'false' );
            $navigation      = apply_filters( 'shop_isle_products_slider_single_navigation', 'false' );

            echo '<div class="row">';

            echo '<div class="owl-carousel text-center" data-loop="false" data-items="' . esc_attr( $number_of_items ) . '" data-pagination="' . esc_attr( $pagination ) . '" data-navigation="' . esc_attr( $navigation ) . '" data-rtl="' . esc_attr( $rtl_slider ) . '" >';

            while ( $shop_isle_products_slider_loop->have_posts() ) :

                $shop_isle_products_slider_loop->the_post();

                echo '<div class="owl-item">';
                echo '<div class="col-sm-12">';
                add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
					wc_get_template_part( 'content', 'product' );						
					add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
                echo '</div>';
                echo '</div>';

                endwhile;

                wp_reset_postdata();
            echo '</div>';

                echo '</div>';

        endif;

            else :

                $shop_isle_products_slider_loop = new WP_Query( $shop_isle_products_slider_args );

                if ( $shop_isle_products_slider_loop->have_posts() ) :

                    $rtl_slider      = apply_filters( 'shop_isle_products_slider_single_rtl', 'false' );
                    $number_of_items = apply_filters( 'shop_isle_products_slider_single_items', 4 );
                    $pagination      = apply_filters( 'shop_isle_products_slider_single_pagination', 'false' );
                    $navigation      = apply_filters( 'shop_isle_products_slider_single_navigation', 'false' );

                    echo '<div class="row">';

                    echo '<div class="owl-carousel text-center" data-loop="false" data-items="' . esc_attr( $number_of_items ) . '" data-pagination="' . esc_attr( $pagination ) . '" data-navigation="' . esc_attr( $navigation ) . '" data-rtl="' . esc_attr( $rtl_slider ) . '" >';

                    while ( $shop_isle_products_slider_loop->have_posts() ) :

                        $shop_isle_products_slider_loop->the_post();

                        echo '<div class="owl-item">';
                        echo '<div class="col-sm-12">';
                        add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
					wc_get_template_part( 'content', 'product' );						
					add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
                            echo '</div>';
                            echo '</div>';

                                endwhile;

                                wp_reset_postdata();
                            echo '</div>';

                        echo '</div>';

                endif;

            endif;

            echo '</div>';

            echo '</section>';

endif;
}
?>