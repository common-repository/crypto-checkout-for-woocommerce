=== Crypto Checkout for Woocommerce ===
 
Contributors: heroo
Tags: usdt, woocommerce, payment gateway, payments, payment, checkout, ecommerce, e-commerce
Requires at least: 5.6
Tested up to: 6.5.3
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
  
Secure, simple plugin compatible with the latest WooCommerce Blocks Checkout experience & TrustWallet/MetaMask apps/extensions.

== Description ==
  

Secure simple crypto payments gateway enabling you to receive the USDT Binance Smart Chain payments (lowest network fee) & managing your revenues with TrustWallet/MetaMask apps/extensions.
This plugin is relying on a 3rd party CryptoCheckout (crypto payment processor) as a service
<ul><strong>Features:</strong>
<li>Start in seconds: start receiving crypto by activation click/tap, no signup required, no ID verification required</li>
<li>Universal: target any client who has crypto wallet not only clients on such platform</li>
<li>Setup isolated prices: if you don't need to follow the market prices, so no need to update the prices of all the products</li></ul>
Crypto currencies supported are: USDT(BEP20), BTC, ETH, SOL, AVAX, ADA, DOT, XTZ, XMR.
Languages supported: ar, bn, zh, cs, en, da, nl, fr, de, he, hi, id, it, ja, ko, pl, pt, ru, sv, es, tr, uk, vi (ISO 639‑1).
<strong>Fee: 1% on receiving</strong>
<strong>If you would like to use our decentralized version (no private keys stored/mnemonic phrase required) <a href="https://downloads.wordpress.org/plugin/crypto-checkout-for-woocommerce.1.2.1.zip" target="_blank">1.2.1</a> for a small flat fee 2$/mo</strong>
Live preview: <a href="https://myawesome.shop/wp/checkout" target="_blank">https://myawesome.shop/wp/checkout</a>
Important: This version works only with latest WooCommerce releases (blocks checkout).
CryptoCheckout homepage: <a href="https://cryptocheckout.co" target="_blank">https://cryptocheckout.co</a>
How it's secure: <a href="https://cryptocheckout.co/how-it's-secure" target="_blank">https://cryptocheckout.co/how-it's-secure</a>
Terms: <a href="https://cryptocheckout.co/terms" target="_blank">https://cryptocheckout.co/terms</a>
Privacy Policy: <a href="https://cryptocheckout.co/privacy-policy" target="_blank">https://cryptocheckout.co/privacy-policy</a>
Changelog: <a href="https://cryptocheckout.co/changelog" target="_blank">https://cryptocheckout.co/changelog</a>	
Any issues contact: <a href="mailto:support@cryptocheckout.co" target="_blank">support@cryptocheckout.co</a>

== Installation ==
  
1. Upload the plugin folder to your /wp-content/plugins/ folder.
1. Go to the **Plugins** page and activate the plugin.
  
== Frequently Asked Questions ==
  
= How do I use this plugin? =
  
You will need to create/generate new wallet from our site (you will get addresses, mnemonic phrase & ID)
Paste your id in the ID input form inside the woocommerce get payed crypto payment settings, the payment button will add automatically after enable the gateway/payment method, the prices will set depending to you: market price or isolated
Manage your wallet by paste the mnemonic phrase on trust wallet or any other wallet
  
= How to uninstall the plugin? =
  
Simply deactivate and delete the plugin. 
  
== Screenshots ==
1. Crypto checkout as a payment method.
2. Select a crypto currency.
3. Collecting the user address, it's used for confirmation process (We ignore other/side confirmations from other locations comes from hackers that listen to the merchant address).
4. QR & the merchant address.
5. Copy address clicked.
6. Validating transaction using crypto explorers.
7. Admin dashboard (listed with the woo's payment methods).
8. Admin dashboard (options main page).
9. Admin dashboard (setup isolated prices).



== Changelog ==
= 1.0.0 (2023-06-02) =
* Plugin released. 

= 1.0.1 (2023-06-19) =
* Fix stuck/block/ when activating without Woocommerce is activated
* Update plugin options, prices menu drop down instead checkbox

= 1.0.3 (2023-09-07) =
* Fix admin notices
* Add default values of language (en) & prices (market)

= 1.0.5 (2023-10-14) =
* Enhance transaction validation, the issue before the fix is the rate may change at the moment of validating the transaction that cause failing, but now we've attache the rate at the moment when the user pay to the transaction object, so the problem goes away

= 1.0.6 (2024-01-10) =
* Update links after moving from getpaidcrypto.online to cryptocheckout.co

= 1.0.7 (2024-01-11) =
* Update the code to comply with php8.3

= 1.0.9 (2024-01-29) =
* Add WooCommerce blocks checkout support

= 1.1.0 (2024-01-30) =
* Add USDT Binance Smart Chain support

= 1.1.1 (2024-01-30) =
* Fixes & enhacements