<?php

/** 
 *@package WoocommercePagLegal
*/

class PagLegal extends WC_Payment_Gateway {

	// Configuração do gateway
	function __construct() {

		$this->id = "paglegal";

		$this->method_title = __( "PagLegal", 'paglegal' );

		$this->method_description = __( "Modo de pagamento do PagLegal.", 'paglegal' );

		$this->title = __( "PagLegal", 'paglegal' );

		$this->icon = apply_filters('woocommerce_paglegal_icon', PLUGIN_URL . 'assets/img/pagseguro.png');

		$this->has_fields = true;

		$this->init_form_fields();

		$this->init_settings();
		

		// Requisição do filtro de Ajax para adicionar o campo de CPF no checkout
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		{
			$cpf = '<input type="text" placeholder="CPF"/>';

			$this->description = $cpf;
		}
		
		
		// Checa se usuário é administrador para poder salvar as configurações
		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}		
	}

	// Cria campos na área administrativa
	public function init_form_fields() {
		$this->form_fields = [
			'enabled' => [
				'title'		=> __( 'Ativar / Desativar', 'paglegal' ),
				'label'		=> __( 'Utilizar o método de pagamento PagLegal.', 'paglegal' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
			],
			'email' => [
				'title'		=> __( 'E-mail de cadastro', 'paglegal' ),
				'type'		=> 'email',
				'desc_tip'	=> __( 'E-mail de sua conta do PagLegal.', 'paglegal' ),
			]
		];		
	}
	
	// Método de pagamento
	public function process_payment( $order_id ) {
		global $woocommerce;
		
		$customer_order = new WC_Order( $order_id );
		
		// Adiciona nota no painel de pedidos
		$customer_order->add_order_note( __( 'Pagamento com PagLegal feito com sucesso', 'paglegal' ) );
												 
		// Deixa pedido como pago
		$customer_order->payment_complete();

		// Esvazia o carrinho
		$woocommerce->cart->empty_cart();

		// Redireciona para a página de pedidos
		return [
			'result'   => 'success',
			'redirect' => $this->get_return_url( $customer_order ),
		];
		
	}
	
}