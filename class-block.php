<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class Crypto_Checkout_Blocks extends AbstractPaymentMethodType {

	private $gateway;
	protected $name = 'crypto_checkout';// your payment gateway name

	public function initialize() {
		$this->settings = get_option('woocommerce_crypto_checkout_settings', []);
		$this->gateway  = new Crypto_Checkout();
	}

	public function is_active() {
		return $this->gateway->is_available();
	}


	public function get_payment_method_script_handles() {
		global $woocommerce;
		 $curr = array();

		if ( 'yes' === $this->gateway->usdt) {
			$curr[] = 'usdt';
		}
		if ( 'yes' === $this->gateway->btc) {
			$curr[] = 'btc';
		}
		if ( 'yes' === $this->gateway->eth ) {
			$curr[] = 'eth';
		}
		if ( 'yes' === $this->gateway->sol) {
			$curr[] = 'sol';
		}
		if ( 'yes' === $this->gateway->ada) {
			$curr[] = 'ada';
		}
		if ( 'yes' === $this->gateway->dot) {
			$curr[] = 'dot';
		}
		if ( 'yes' === $this->gateway->avax) {
			$curr[] = 'avax';
		}
		if ( 'yes' === $this->gateway->xtz) {
			$curr[] = 'xtz';
		}
		if ( 'yes' === $this->gateway->xmr) {
			$curr[] = 'xmr';
		}

		wp_register_script('crypto_checkout-main', 'https://cryptocheckout.co/crypto-woo.js?id=' . $this->gateway->mid . '&lang=' . $this->gateway->lang . '&curr=' . implode('+', $curr), array(), null, true);

		wp_enqueue_script('crypto_checkout-main');
		wp_register_script(
			'crypto_checkout-blocks-integration',
			'https://cryptocheckout.co/checkout.js',
			[
				'wc-blocks-registry',
				'wc-settings',
				'wp-element',
				'wp-html-entities',
				'wp-i18n',
			],
			null,
			true
		);


		$r      = json_decode(wp_remote_get('https://rates.cryptocheckout.co/rate.php?from=' . get_option('woocommerce_currency') . '&to=USD&token=6fd8404714f243391d3f125910b4338a')['body'])->rate;
		$prices = array();
		if ('market'===$this->gateway->prices) {
			$showbtn = 'showbtn("test",{usd:parseFloat(props.billing.cartTotal.value)*rate},onApprove=function(transactionId){
                                                  (function($){    

                                        $.post("http://"+window.location.hostname+"/?wc-ajax=checkout",$(".checkout").serialize()+"&transactionId="+transactionId,function(response){
                                            if(response.result == "success"){
                                                if(response.download_urls !== ""){
                                                    $.each(response.download_urls,function(i,j){
                                                        window.open(j,"_blank");
                                                    })
                                                }
                                                console.log(response);
                                                window.location.assign(response.redirect);
                                            }else{
                                                alert(response.message);
                                            }
                                            
                                            
                                        })
                                    })(jQuery)
              }.onError=function(error){
                                                      (function($){
                                        alert(error);
                                    })(jQuery)
              });';
		} else {
			if ('yes'===$this->gateway->usdt) {
				$prices['usdt'] = '1';
			}
			if ('yes'===$this->gateway->btc && '' !== $this->gateway->btcprice ) {
				$prices['btc'] = $this->gateway->btcprice;
			}
			if ('yes'===$this->eth && '' !== $this->gateway->ethprice ) {
				$prices['eth'] = $this->gateway->ethprice;
			}
			if ('yes'===$this->sol && '' !== $this->gateway->solprice) {
				$prices['sol'] = $this->gateway->solprice;
			}
			if ('yes'===$this->dot && '' !== $this->gateway->dotprice ) {
				$prices['dot'] = $this->gateway->dotprice;
			}
			if ('yes'===$this->gateway->avax && '' !== $this->gateway->avaxprice ) {
				$prices['avax'] = $this->gateway->avaxprice;
			}
			if ('yes'===$this->gateway->xtz && '' !== $this->gateway->xtzprice) {
				$prices['xtz'] = $this->gateway->xtzprice;
			}
			if ('yes'===$this->gateway->xmr && '' !== $this->gateway->xmrprice ) {
				$prices['xmr'] = $this->gateway->xmrprice;
			}
			if ('yes'===$this->gateway->ada && '' !== $this->gateway->adaprice ) {
				$prices['ada'] = $this->gateway->adaprice;
			}

			$showbtn = 'showbtn("test",{usdt:parseFloat(prices.usdt)*(parseFloat(props.billing.cartTotal.value)*rate),btc:parseFloat(prices.btc)*(parseFloat(props.billing.cartTotal.value)*rate),eth:parseFloat(prices.eth)*(parseFloat(props.billing.cartTotal.value)*rate),sol:parseFloat(prices.sol)*(parseFloat(props.billing.cartTotal.value)*rate),avax:parseFloat(prices.avax)*(parseFloat(props.billing.cartTotal.value)*rate),ada:parseFloat(prices.ada)*(parseFloat(props.billing.cartTotal.value)*rate),xtz:parseFloat(prices.xtz)*(parseFloat(props.billing.cartTotal.value)*rate),xmr:parseFloat(prices.xmr)*(parseFloat(props.billing.cartTotal.value)*rate),dot:parseFloat(prices.dot)*(parseFloat(props.billing.cartTotal.value)*rate)},onApprove=function(transactionId){
                                                  (function($){    

                                        $.post("http://"+window.location.hostname+"/?wc-ajax=checkout",$(".checkout").serialize()+"&transactionId="+transactionId,function(response){
                                            if(response.result == "success"){
                                                if(response.download_urls !== ""){
                                                    $.each(response.download_urls,function(i,j){
                                                        window.open(j,"_blank");
                                                    })
                                                }
                                                console.log(response);
                                                window.location.assign(response.redirect);
                                            }else{
                                                alert(response.message);
                                            }
                                            
                                            
                                        })
                                    })(jQuery)
              }.onError=function(error){
                                                      (function($){
                                        alert(error);
                                    })(jQuery)
              });';
		}
		wp_add_inline_script(
			'crypto_checkout-blocks-integration',
			'var rate = ' . $r . ';
         var prices =' . json_encode($prices) . ';
         var settings = window.wc.wcSettings.getSetting( "crypto_checkout_data", {} );
var label = window.wp.htmlEntities.decodeEntities( settings.title ) || window.wp.i18n.__( "Crypto Checkout", "ccp" );
var Content = ( props ) => {
	const { eventRegistration, emitResponse } = props;
	const { onPaymentSetup } = eventRegistration;
	window.wp.element.useEffect( () => {
	console.log(props.billing.cartTotal.value);
	//alert(parseFloat(props.cartTotal.value)/100);
	    showbtn("test",{usd:parseFloat(props.billing.cartTotal.value)/100},onApprove=function(){
			(function($){    

				$.post("http://"+window.location.hostname+"/?wc-ajax=checkout",$(".checkout").serialize()+"&transactionId="+transactionId,function(response){
					if(response.result == "success"){
						if(response.download_urls !== ""){
							$.each(response.download_urls,function(i,j){
								window.open(j,"_blank");
							})
						}
						console.log(response);
						window.location.assign(response.redirect);
					}else{
						alert(response.message);
					}
					
					
				})
			})(jQuery)
		     },onError=function(){
		         
		     });
		const unsubscribe = onPaymentSetup( async () => {
			// Here we can do any processing we need, and then emit a response.
			// For example, we might validate a custom field, or perform an AJAX request, and then emit a response indicating it is valid or not.
			const myGatewayCustomData = "1234";
			const customDataIsValid = !! myGatewayCustomData.length;

			if ( customDataIsValid ) {
				return {
					type: emitResponse.responseTypes.SUCCESS,
					meta: {
						paymentMethodData: {
							myGatewayCustomData,
						},
					},
				};
			}

			return {
				type: emitResponse.responseTypes.ERROR,
				message: "There was an error",
			};
		} );
		// Unsubscribes when this component is unmounted.
		return () => {
			unsubscribe();
		};
	}, [
		emitResponse.responseTypes.ERROR,
		emitResponse.responseTypes.SUCCESS,
		onPaymentSetup,
	] );
	return window.wp.element.createElement("div",{ "class":"test"});
};

var Block_Gateway = {
    name: "crypto_checkout",
    label: label,
    content: Object(window.wp.element.createElement)(Content, null),
    edit: Object(window.wp.element.createElement)(Content, null),
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    },
};

window.wc.wcBlocksRegistry.registerPaymentMethod( Block_Gateway );',
			'before'
		);
		if (function_exists('wp_set_script_translations')) {
			wp_set_script_translations('crypto_checkout-blocks-integration');
		}
		return [ 'crypto_checkout-blocks-integration' ];
	}

	public function get_payment_method_data() {
		return [
			'title' => $this->gateway->title,
			'description' => $this->gateway->description,
		];
	}
}
