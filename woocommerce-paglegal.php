<?php
/** 
 *@package WoocommercePagLegal
*/

/**
 * Plugin Name: WooCommerce PagLegal
 * Plugin URI: http://paglegal.com.br
 * Description: Extensão do WooCommerce para adicionar o gateway de pagamento do PagLegal
 * Author: Pedro Faria
 * Author URI: https://github.com/slainr
 * Version: 1.0
 */

// Abortar o arquivo caso seja acessado diretamente
defined('ABSPATH') or die('Hey, you can\t access this file');

// Constante para a url do plugin
define('PLUGIN_URL', plugin_dir_url(__FILE__));

//verifica se o woocommerce eśta instalado
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// Adiciona a classe ao gateway de pagamento do WooCommerce
	add_action( 'plugins_loaded', 'paglegal_init', 0 );

	add_filter( 'woocommerce_payment_gateways', 'paglegal_gateway' );	
	function paglegal_init() {
		
		include_once( 'inc/Gateway.php' );

		// Adiciona a classe ao WooCommerce
		function paglegal_gateway( $methods ) {
			$methods[] = 'PagLegal';
			return $methods;
		}

	}

	// Adiciona o link de configurações na tela de plugin após ativado
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'settings_link' );	
	 function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=paglegal' ) . '">' . __( 'Configurações', 'paglegal' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}
	
}