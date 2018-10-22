<?php
	/*==================================================================================
						Configurar Child Theme
	==================================================================================*/

	 add_action( 'wp_enqueue_scripts', 'shop_isle_supermercados_max_enqueue_styles' );
	 function shop_isle_supermercados_max_enqueue_styles() {
		   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
		   wp_enqueue_script( 'slider', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ), '1.0', true ); 
		   wp_enqueue_script( 'supermax', get_stylesheet_directory_uri() . '/js/supermax.js', array( 'jquery' ), '1.0', true );
		   } 
		   require_once dirname( __FILE__ ).'/session-manager.php';
		   require_once dirname( __FILE__ ).'/products.php';
	
	/*=================================================================================
			Incrementar el limite de memoria de Wordpress
	==================================================================================*/
		define( 'WP_MEMORY_LIMIT', '256M' );

	/*==================================================================================================================
								Formulario de Inicio de sesión
	===================================================================================================================*/	  
	//------------------------ Reemplazar la página wp-login por "Mi Cuenta" ----------------------------------------------------
	// add_action('init','redirect_wplogin');
	// 	function redirect_wplogin(){
	// 		$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
	// 		$login_url = wp_logout_url( get_permalink( $myaccount_page_id ) );
	// 		$login_url = str_replace( 'http:', 'https:', $login_url );
	// 		global $pagenow;
	// 		if( 'wp-login.php' == $pagenow && !is_user_logged_in()) {
	// 			wp_redirect($login_url);
	// 			exit();
	// 		}
	// 	}
	//------------------------ Redireccionar a página de inicio luego de cerrar sesión ----------------------------------------------------
		add_action('wp_logout','auto_redirect_after_logout');
		function auto_redirect_after_logout(){
			wp_redirect( home_url() );
			exit();
		}

	//------------------------  Agregar campos de redes sociales al fomulario --------------------------------------
		add_action('woocommerce_login_form', 'NextendSocialLogin::addLoginFormButtons');
		add_action('login_form', 'NextendSocialLogin::addLoginFormButtons');
		remove_action('register_form', 'NextendSocialLogin::addLoginFormButtons');


	/*==================================================================================
						Modificar el estilo del shopPage
	==================================================================================*/
		   function shop_isle_shop_page_wrapper() {
			?>
			<section class="module-large module-large-shop">
					<div class="container">
	
					<?php
					if ( is_shop() || is_product_tag() || is_product_category() ) :
	
							do_action( 'shop_isle_before_shop' );
	
						if ( is_active_sidebar( 'shop-isle-sidebar-shop-archive' ) ) :
						?>
	
								<div class="col-sm-9 shop-with-sidebar" id="shop-isle-blog-container">
	
							<?php endif; ?>
	
					<?php endif; ?>
	
			<?php
		}


	/*==================================================================================================================
				Agregar Rating / Calificaciones al producto
	===================================================================================================================*/
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action('woocommerce_after_shop_loop_item_title', 'add_star_rating', 5	 );

	function add_star_rating()
	{
		global $woocommerce, $product;
		if (!is_front_page() || is_home()):
			$average = $product->get_average_rating();
			echo '<div class="star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
		endif;
	}	


	add_filter('woocommerce_sale_flash', 'show_discount_ammount', 10, 3);
	function show_discount_ammount($content, $post, $product){
		if ($product->is_on_sale() && $product->product_type == 'variable') :
			$available_variations = $product->get_available_variations();								
		   $maximumper = 0;
		   for ($i = 0; $i < count($available_variations); ++$i) :
			   $variation_id=$available_variations[$i]['variation_id'];
			   $variable_product1= new WC_Product_Variation( $variation_id );
			   $regular_price = $variable_product1 ->regular_price;
 			   $sales_price = $variable_product1 ->sale_price;
			   $price= ( $regular_price - $sales_price ) ;
		   endfor;
		elseif($product->is_on_sale() && $product->product_type == 'simple') :
			$price = $product->regular_price - $product->sale_price;
		endif;

   $content = '<span class="onsale">'.__( 'Sale!', 'woocommerce' ). ' <small> - Ahorre ' . $price . ' '. get_woocommerce_currency_symbol() .'</small></span>';
   return $content;
}
?>


<?php
	/*=======================================================================================
			Mostrar la descripción del producto en la tienda
	=========================================================================================*/

	function descripcion_corta($limit, $texto) {
		$excerpt = explode(' ', $texto, $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
			$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
			return $excerpt;
		}


	function add_product_description() {
		global $product;
	?>
		<div class="woocommerce-product-details__short-description product-excerpt">
				<p><?php echo descripcion_corta(10, get_the_excerpt()); ?>	</p>
		</div>
	<?php
		}

		add_action( 'woocommerce_after_shop_loop_item_title', 'add_product_description', 4 );
	?>
	
	
	<?php
	/*=======================================================================================
			Mostrar Imagen + Título + Descripción Rápida + Valor del producto en carrito 
	=========================================================================================*/
		add_filter( 'woocommerce_cart_item_name', 'add_excerpt_in_cart_item_name', 10, 3 );
		
		function add_excerpt_in_cart_item_name( $item_name,  $cart_item,  $cart_item_key ){
			$excerpt = wp_strip_all_tags( get_the_excerpt($cart_item['product_id']), true ); 
			$excerpt_html = '<br>
				<p name="short-description">'.$excerpt.'</p>';
			return $item_name . $excerpt_html;
		}
	?>
	

<?php 

		/*==================================================================================================================
							Mostrar ofertas
		===================================================================================================================*/
		add_shortcode('ofertas', 'ofertas');
		function ofertas($atts) {
			//Obtener todos los post que sean de tipo producto
			$args = array(
				'post_type' => 'product',
			);
			$attributos = shortcode_atts( array (
				'categoria' => '',
				'id'=> '',
			), $atts );

			//Crear un na consulta con los agrumentos
			$loop = new WP_Query( $args );
			//Verificar que existan post de tipo productos
			if ( $loop->have_posts() ) :
			?>
			<div class="products_shortcode">
				<div class="woocommerce columns-4">
				<ul class="products columns-4">
				
			<?php 
			//Mientras existan posts
			while ( $loop->have_posts() ) : 
			$loop->the_post();
			global $product;
			$count = 1;
			if ($count < 4) :
			foreach ($product->category_ids as $key => $cat_id) :
				//Obtener las categorías a las que pertenece el producto.
				$category = get_term_by('id', $cat_id, 'product_cat', 'ARRAY_A');
				//Verificar que coincida con la categoría a filtrar
				if ($category['name'] === $attributos['categoria'] || $cat_id['id'] === $attributos['id']):
				//Condiciona que el producto esté en oferta y no se hayan definido períodos de la oferta
					if ( $product->is_on_sale() ) :
						add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
						wc_get_template_part( 'content', 'product' );						
						add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
					endif;
				endif;
				$count++;
				endforeach;
			endif;
							
				endwhile;
				
				?>
				</ul>
				</div>
				</div>
		
				<div class="row">
				<div class="col-sm-12 align-center">
				
				<?php if ( function_exists( 'wc_get_page_id' ) ) {
					echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
				} elseif ( function_exists( 'woocommerce_get_page_id' ) ) {
					echo '<a href="' . esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
				}
				?>
				</div>
			
				</div>
	
		<?php else :
	
			echo '<div class="row">';
			echo '<div class="col-sm-6 col-sm-offset-3">';
			echo '<p class="">' . __( 'No products found.', 'shop-isle' ) . '</p>';
			echo '</div>';
			echo '</div>';
	
		endif;
	
		wp_reset_postdata();

			//return apply_filters( 'woocommerce_get_price', $price );
		}
		
		?>


<?php
  wp_enqueue_script("checkout-js", plugin_dir_url( __FILE__ )."js/checkout.js",array('jquery'),'',true);
  ?>