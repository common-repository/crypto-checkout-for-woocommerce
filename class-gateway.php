<?php

class Crypto_Checkout extends WC_Payment_Gateway {

		   public $id;
			  public $icon;
			  public $has_fields;
			  public $method_title;
			  public $method_description;
			  public $title;
			  public $description;
			  public $enabled;
			  public $mid;
			  public $lang;
			  public $usdt;
			  public $btc;
			  public $eth;
			  public $sol;
			  public $ada;
			  public $avax;
			  public $dot;
			  public $xtz;
			  public $xmr;
			  public $btcprice;
			  public $ethprice;
			  public $solprice;
			  public $adaprice;
			  public $avaxprice;
			  public $dotprice;
			  public $xtzprice;
			  public $xmrprice;
			  public $prices;
			  private $transactions;
			  public $mnemonic;


  // Constructor method
	public function __construct() {


				// Other initialization code goes here
				$this->id   = 'crypto_checkout'; // payment gateway plugin ID
				$this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
				/*   $this->has_fields = true; */// in case you need a custom credit card form
				$this->method_title       = __('Crypto currencies', 'ccp');
				$this->method_description = __('Accept crypto payements on your store', 'ccp'); // will be displayed on the options page
				$this->has_fields         = true;
				// gateways can support subscriptions, refunds, saved payment methods,
				// but in this tutorial we begin with simple payments
				$this->supports = array(
					'products'
				);


				$this->title        = $this->get_option('title');
				$this->description  = $this->get_option('description');
				$this->enabled      = $this->get_option('enabled');
				$this->mid          = $this->get_option('mid');
				$this->lang         = $this->get_option('lang');
				$this->usdt         = $this->get_option('usdt');
				$this->btc          = $this->get_option('btc');
				$this->eth          = $this->get_option('eth');
				$this->sol          = $this->get_option('sol');
				$this->avax         = $this->get_option('avax');
				$this->dot          = $this->get_option('dot');
				$this->ada          = $this->get_option('ada');
				$this->xtz          = $this->get_option('xtz');
				$this->xmr          = $this->get_option('xmr');
				$this->btcprice     = $this->get_option('btcprice');
				$this->ethprice     = $this->get_option('ethprice');
				$this->solprice     = $this->get_option('solprice');
				$this->avaxprice    = $this->get_option('avaxprice');
				$this->dotprice     = $this->get_option('dotprice');
				$this->adaprice     = $this->get_option('adaprice');
				$this->xtzprice     = $this->get_option('xtzprice');
				$this->xmrprice     = $this->get_option('xmrprice');
				$this->prices       = $this->get_option('prices');
				$this->transactions = $this->get_option('transactions');
				$this->mnemonic     = $this->get_option('mnemonic');
                $this->update_option('mnemonic', get_option('gpcrypto_mnemonic'));
				$this->update_option('mid', get_option('gpcrypto_mid'));
				$this->init_form_fields();
				$this->init_settings();

				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
				add_action('wp_enqueue_scripts', array( $this, 'payment_scripts' ));
				add_action('admin_print_scripts-woocommerce_page_wc-settings', array( $this, 'print_helper_script' ));
	}
   
	public function init_form_fields() {
		$this->form_fields = array(
			        'mnemonic' => array(
				        'title'       => __('Mnemonic phrase', 'ccp'),
				        'type'        => 'textarea',
				        'description' => __('Manage your revenues using <a href="https://trustwallet.com/" target="_blank">TrustWallet</a> or <a href="https://metamask.io/" target="_blank">MetaMask</a> apps/extensions', 'ccp'),
				        'disabled'    => true
			        ),
					'enabled' => array(
						'title'       => __('Enable/Disable', 'ccp'),
						'label'       => __('Enable Crypto Checkout', 'ccp'),
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'no'
					),
					'title' => array(
						'title'       => __('Title', 'ccp'),
						'type'        => 'text',
						'description' => __('This controls the title which the user sees during checkout.', 'ccp'),
						'default'     => __('Pay using crypto currencies', 'ccp'),
						'desc_tip'    => true,
					),
					'description' => array(
						'title'       => __('Description', 'ccp'),
						'type'        => 'textarea',
						'description' => __('This controls the description which the user sees during checkout.', 'ccp'),
						'default'     => __('Pay using your crypto funds via our super-cool payment gateway.', 'ccp'),
					),					
					/*'mid' => array(
						'title'       => __('ID', 'ccp'),
						'type'        => 'text',
						'description' => __('Generate your wallet here: <a href="https://cryptocheckout.co/" target="_blank">https://cryptocheckout.co</a> & paste your ID', 'ccp'),
					),*/
					'lang' => array(
						'title'       => __('Language', 'ccp'),
						'type'        => 'text',
						'description' => __('ISO 639‑1 language code (two charcters)', 'ccp'),
						'default'     => 'en',
						'desc_tip'    => true
					),
					'prices' => array(
						'title'       => __('Prices', 'ccp'),
						'label'       => __('Market price', 'ccp'),
						'type'        => 'select',
						'class'       => 'prices',
						'options'     => array('market' => 'Market','isolated' => 'Isolated'),
						'description' => 'Market or Isolated price',
						'default'     => 'market'
					),
										'usdt' => array(
						'title'       => 'Tether',
						'label'       => 'Enable Tether',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'btc' => array(
						'title'       => 'Bitcoin',
						'label'       => 'Enable Bitcoin',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'eth' => array(
						'title'       => 'Ethereum',
						'label'       => 'Enable Ethereum',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'sol' => array(
						'title'       => 'Solana',
						'label'       => 'Enable Solana',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'avax' => array(
						'title'       => 'Avalanche',
						'label'       => 'Enable Avalanche',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'ada' => array(
						'title'       => 'Cardano',
						'label'       => 'Enable Cardano',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'dot' => array(
						'title'       => 'Polkadot',
						'label'       => 'Enable Polkadot',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'xtz' => array(
						'title'       => 'Tezos',
						'label'       => 'Enable Tezos',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'xmr' => array(
						'title'       => 'Monero',
						'label'       => 'Enable Monero',
						'type'        => 'checkbox',
						'description' => '',
						'default'     => 'yes'
					),
					'btcprice' => array(
						'title'       => 'Bitcoin Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
					),
					'ethprice' => array(
						'title'       => 'Ethereum Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					),
					'solprice' => array(
						'title'       => 'Solana Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					),
					'avaxprice' => array(
						'title'       => 'Avalanche Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					),
					'dotprice' => array(
						'title'       => 'Polkadot Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					),
					'adaprice' => array(
						'title'       => 'Cardano Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					),
					'xtzprice' => array(
						'title'       => 'Tezos Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					),
					'xmrprice' => array(
						'title'       => 'Monero Price',
						'type'        => 'text',
						'default'     => '',
						'class'       => 'cprice',
						'desc_tip'    => true,
					)
				);
	}
	public function process_payment($order_id) {
		global $woocommerce;
		   $pds           = $woocommerce->cart->get_cart();
		   $total         = 0;
		   $download_urls = array();
		foreach ($pds as $p => $v) {
				  $product   = wc_get_product($v['product_id']);
				  $downloads = $product->get_downloads();
			foreach ($downloads as $key => $each_download) {
				$download_urls[] = $each_download['file'];
			}
				  $total += get_post_meta($v['product_id'], '_price', true) * $v['quantity'];
		}
		   $r     = json_decode(wp_remote_get('https://rates.cryptocheckout.co/rate.php?from=' . get_option('woocommerce_currency') . '&to=USD&token=6fd8404714f243391d3f125910b4338a')['body'])->rate;
		   $total = $total * floatval($r);
		if ('market'===$this->prices) {
					 $r1 = json_decode(wp_remote_get('https://cryptocheckout.co/back.php?transaction=' . sanitize_text_field($_POST['transactionId']))['body']);
					 $r  = json_decode(wp_remote_get('https://rates.cryptocheckout.co/rate.php?from=USD&to=' . $r1->curr . '&token=6fd8404714f243391d3f125910b4338a')['body'])->rate;

			if ($r1->completed == true &&  abs(floatval($r1->amount) - ( $total * floatval($r1->rate) )) < 0.0000001) {
				$transactions = get_option('transactions');
				if (empty($transactions)) {
						  $transactions   = array();
						  $transactions[] = sanitize_text_field($_POST['transactionId']);
						  update_option('transactions', $transactions);
						  return $this->completeorder($order_id, $download_urls);
				} else {
					foreach ($transactions as $transaction => $hash) {
						if ($hash == sanitize_text_field($_POST['transactionId'])) {
							$exist = true;
							break;
						} elseif ($transaction == count($transactions) - 1 && $exist == false) {
							$transactions[] = sanitize_text_field($_POST['transactionId']);
							update_option('transactions', $transactions);
							return $this->completeorder($order_id, $download_urls);
						}
					}
				}
			}
		} else {
			$r1 = json_decode(wp_remote_get('https://cryptocheckout.co/?transaction=' . sanitize_text_field($_POST['transactionId']))['body']);
			if ($r1->completed == true && abs(floatval($r1->amount) - floatval($this->unitfromamount($r1->curr, $total * $this->getprice($r1->curr)))) < 1000) {
				$transactions = get_option('transactions');
				if (empty($transactions)) {
					$transactions   = array();
					$transactions[] = sanitize_text_field($_POST['transactionId']);
					update_option('transactions', $transactions);
					return $this->completeorder($order_id, $download_urls);
				} else {
					foreach ($transactions as $transaction => $hash) {
						if ($hash == sanitize_text_field($_POST['transactionId'])) {
							$exist = true;
							break;
						} elseif ($transaction == count($transactions) - 1 && $exist == false) {
							$transactions[] = sanitize_text_field($_POST['transactionId']);
							update_option('transactions', $transactions);
							return $this->completeorder($order_id, $download_urls);
						}
					}
				}
			}
		}
	}
	public function payment_scripts() {
	}
	public function print_helper_script() {
		?>
				<script src='/wp-includes/js/jquery/jquery.min.js'></script>
				<script>
					(function($){
						$(document).ready(function(){
							$('.prices').val("<?php echo wp_kses_post($this->get_option('prices')); ?>");
							$('.prices').on('change',function(){
								//$(this).val(($(this).val()));
								if('market'===$(this).val()){
									$('label[for="woocommerce_ccp_btcprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_ethprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_solprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_avaxprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_dotprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_adaprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_xtzprice"]').parent().parent().hide();
									$('label[for="woocommerce_ccp_xmrprice"]').parent().parent().hide();
								}else{
									$('label[for="woocommerce_ccp_btcprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_ethprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_solprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_avaxprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_dotprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_adaprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_xtzprice"]').parent().parent().show();
									$('label[for="woocommerce_ccp_xmrprice"]').parent().parent().show();
									alert('Now you must setup values below')
								}
								
							})
							if("<?php echo wp_kses_post($this->get_option('prices')); ?>"=="market"){           
								$('label[for="woocommerce_ccp_btcprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_ethprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_solprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_avaxprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_dotprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_adaprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_xtzprice"]').parent().parent().hide();
								$('label[for="woocommerce_ccp_xmrprice"]').parent().parent().hide();
							}

						})                        
					})(jQuery)
				</script>
				<?php
	}
}
