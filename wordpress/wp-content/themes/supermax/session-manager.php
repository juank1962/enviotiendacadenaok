<?php
	/*=========================================================================================================
						Verificar Usuario Logueado
	============================================================================================================*/

	add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );

	$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );

if ( $myaccount_page_id ) {

  $logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );

  if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
    $logout_url = str_replace( 'http:', 'https:', $logout_url );
}

	function add_loginout_link( $items, $args ) {
		$myaccount = get_permalink( get_option('woocommerce_myaccount_page_id') );
		$orders = $myaccount.'/orders';
		$profile = $myaccount.'/edit-account';
    if (is_user_logged_in() && $args->theme_location == 'primary') {
		$current_user = wp_get_current_user();

		//----------- Calcular la edad del cliente-----------------
		$fecha_nacimiento = get_user_meta( get_current_user_id(),'billing_fecha_de_nacimiento', TRUE).' 00:00:00';    
		$date = new DateTime($fecha_nacimiento);
		$now = new DateTime();
		$edad = $date->diff($now)->format("%Y");
		//---------------------------------------------------------
		$address1 = '';
		$address2 = '';
		$id = $current_user->ID;
		$nombre =  $current_user->user_firstname;
		//----------- Verificar Direcciones -----------------------
		$address1 = get_user_meta( $id, 'billing_address_1', true );
		$address2 = get_user_meta( $id, 'billing_address_2', true );
		if ($address1 == ''){
			$address1 = get_user_meta( $id, 'shipping_address_1', true );
			$address2 = get_user_meta( $id, 'shipping_address_2', true );
		}
		//--------------------------------------------------------
		$telefono = '</br><small>'.get_user_meta( get_current_user_id(),'billing_telefono', TRUE).'</small>';
		$address = '</br><small>'.descripcion_corta(50, $address1 .' '. $address2).'</small>';
		$edad = '</br><small>'.$edad.'</small>';
		$sexo = '</br><small>'.get_user_meta( get_current_user_id(), 'billing_genero', TRUE) .'</small>';
		$identificacion = '</br><small>'.get_user_meta( get_current_user_id(), 'billing_identificación', TRUE).';</small>';



		$user_info = '<p>Dirección:'.$address.'</p>
						<p>Teléfono:'.$telefono.'</p>
						<p>Edad:'.$edad.'</p>
						<p>Sexo:'.$sexo.'</p>';

		$items .= 
		'<li class="menu-item  menu-item-has-children  has_children"><a href="#">Hola, '.$nombre.'</a><p class="dropdownmenu"></p>
			<ul class="sub-menu">
				<li class="menu-item"><a href="'.$profile.'">Editar Perfíl</a></li>
				<li class="menu-item small">
					'.$user_info.'
				</li>
				<li class="menu-item"><a href="'.$orders.'">Historial de compras</a></li>		
				<li class="menu-item"><a href="'.wp_logout_url().'">Cerrar Sesión</a></li>
			</ul>
		</li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
        $items .= '<li><a href="'. $myaccount .'">Iniciar Sesión</a></li>';
	}
	
	return $items;
	
	// function shipping_zones_shortcode() {

	// 	$delivery_zones = WC_Shipping_Zones::get_zones();
	
	// 	foreach ( (array) $delivery_zones as $key => $the_zone ) {
	// 	  echo ''.$the_zone['zone_name'].', ';
	// 	  //print_r($delivery_zones);
	// 	}
	// }
	//  do_shortcode('shipping_zones_shortcode');
	//add_shortcode( 'list_shipping_zones', 'shipping_zones_shortcode', 10 );

$location = WC_Geolocation::geolocate_ip();
$country = $location['country'];
 $country;
 // Lets use the country to e.g. echo greetings
  
//  switch ($country) {
//      case "AR":
//          $hello = "";
//          break;
//      case "IN":
//          $hello = "Namaste!";
//          break;
//      default:
//          $hello = "Hello!";
//  }
  
//  echo $hello;

     
 }

/*========================================================================================
				Forzar inicio de sesión en frontend
=========================================================================================*/
function redirect_login_page()
{
	// The URL to your login page
	$login_page  = site_url("/mi-cuenta");
	$page_viewed = basename($_SERVER["REQUEST_URI"]);
 
	if($page_viewed == "wp-login.php" AND $_SERVER["REQUEST_METHOD"] == "GET")
	{
		wp_redirect($login_page);
		exit;
	}
}
add_action("init","redirect_login_page");


?>