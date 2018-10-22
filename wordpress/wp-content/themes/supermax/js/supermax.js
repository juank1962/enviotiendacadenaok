jQuery(window).load(function () {
    /*======================================================
            Modificaciones
    =======================================================*/
    //Ejecutarlo 1ms despues de que se hayan cargado todos los procesos JS y jQuery
    setTimeout(function () {
        //Reorganizar la descripción corta del producto
        jQuery('.page .products li, .single .products li, .post-type-archive-product .products li, .tax-product_cat .products li, .related.products .products li, #shop-isle-blog-container .products li, .upsells.products li, .products_shortcode .products li, .cross-sells .products li, .woocommerce.archive .products li').each(function () {
            jQuery(this).find('.product-button-wrap').prepend(jQuery(this).find('.product-excerpt'));
        })
        //Cambiar el texto del personalizador del tema para seleccionat la categoría a mostrar en los banners
        jQuery('#sub-accordion-section-shop_isle_banners_section .customize-control-title').text('Seleccione categoría a mostrar')
        jQuery('nav.navbar').removeClass('navbar-transparent');
        if (jQuery('input[type="radio"][name^="billing_"]').length > 0) {
            jQuery('input[type="radio"][name^="billing_"]').each(function () {
                value = jQuery(this).val();
                jQuery(this).addClass('billing_' + value);
                jQuery('label[for="billing_' + value + '"]').addClass('billing_' + value);
                jQuery('input[value="' + value + '"]').after(jQuery('label[for="billing_' + value + '"]'))
                jQuery('.billing_' + value).wrapAll('<li>');
            })
        }

        if (jQuery('#shop-isle-checkout-coupon').length > 0) {
            jQuery('#shop-isle-checkout-coupon > .woocommerce-info:first-child').after(jQuery('.woocommerce-form.woocommerce-form-login.login'));
        }

        if (jQuery('.wpgmza_sl_main_div').length > 0) {
            jQuery('.wpgmza_sl_main_div').addClass('container');
            jQuery('.wpgmza-form-field').addClass('col-xs-12');
            jQuery('.wpgmza_sl_search_button').addClass('button black');
        }

    }, 1);
});