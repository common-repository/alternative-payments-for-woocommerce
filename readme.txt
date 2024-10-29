=== Alternative Payments for WooCommerce ===
Contributors: alternativepayments, molivver
Donate link: 
Tags: alternative payments, woocommerce, ecommerce, e-commerce, local, payment, sepa, ideal, giropay, eps, sofort, bancontact, POLi, przelewy24, unionpay, cashu, trustpay, qiwi, alipay, paysafecard,verkkopankki, boleto bancario, safetypay, teleingreso, tenpay 
Requires at least: 4.4.1
Tested up to: 4.9.1
Stable tag: 1.0.9
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Convert millions of international consumers that don't use credit cards.

== Description ==

The Alternative Payments WooCommerce plugin allows for a seamless integration with our payment gateway. With this plugin, you can add a broad portfolio of local payment methods to your WooCommerce webshop. The following payments methods are supported in this version:

* SEPA EuroDebit
* iDEAL
* Sofort Überweisung
* giropay
* eps
* Przelewy24
* teleingreso
* POLi
* SafetyPay
* Bancontact MisterCash
* TrustPay
* Verkkopankki
* QIWI
* Alipay
* Tenpay
* paysafecard
* CASHU
* BrazilPay Boleto Bancário
* BrazilPay Bank Transfer
* BrazilPay Charge Card

Please go to the Alternative Payments' [Merchant Portal](https://merchant.alternativepayments.com/Account/Register), create an account and start processing international transactions in less than 15 minutes. [Contact us](http://www.alternativepayments.com/support/tech-contact.html) if you have any questions or comments about this plugin.

= Features =

* Edit order for every payment method
* Smart Payment Option filtering by supported country
* Enable/Disable individual payment option
* Custom return and cancel URL
* Extensive webhooks
* SMS Verification for SEPA
* Option to change order status to processing on SEPA transactions 

== Installation ==

= Required =
* You need to have account with [Alternative Payments](https://merchant.alternativepayments.com/Account/Register) and access to the [Merchant portal](https://merchant.alternativepayments.com/Account/Login)
* You need to have Wordpress installed with a supported version of WooCommerce
* You will need to have php5-curl installed on server (sudo apt-get install php5-curl or yum install php-curl) 

= Automatic installation =

* Install the plugin via Plugins -> New plugin. Search for 'Alternative Payments for WooCommerce'.
* Activate the 'Alternative Payments for WooCommerce' plugin through the 'Plugins' menu in WordPress
* Navigate to WooCommerce > Settings > Checkout > Alternative Payments and configure your plugin.
* Enable the plugin
* Enter the Public API Key and Secret API Key obtained in the Website profile in the Alternative Payments [Merchant Portal](https://merchant.alternativepayments.com/Account/Login)
* Insert the API URL: https://api.alternativepayments.com/api
* IMPORTANT: Activate the Payment Options which are active in your account
* Check in your Terms and Conditions for available and active payment methods, or [contact us](http://www.alternativepayments.com/support/tech-contact.html) if you need to activate additional options.
* Save changes by clicking on button “Save changes”

= Please note =
* Don’t change standard WooCommerce status.
* Turn off ‘Hold stock’. Go to ‘WooCommerce > Setting > Products > Inventory’ and in field ‘Hold stock’ (minutes) set it to blank.

= Manual installation =

* Download the Alternative Payments plug-in and upload into the '/wp-content/plugins/' directory.
* Go to WordPress Admin > Plugins and Activate the plugin.
* Go to ‘WooCommerce > Settings > Checkout > Alternative Payments’.
* Check ‘Enable’ under Enable/Disable to enable plug-in.
* Enter the Public API Key and Secret API Key obtained in the Website profile in the Alternative Payments [Merchant Portal](https://merchant.alternativepayments.com/Account/Login)
* Insert the API URL: https://api.alternativepayments.com/api
* Activate the Payment Options which are active in your account
* Check in your Terms and Conditions for available and active payment methods, or [contact us](http://www.alternativepayments.com/support/tech-contact.html) if you need to activate additional options.
* Save changes by clicking on button “Save changes”

= Please note =
* Don’t change standard WooCommerce status.
* Turn off ‘Hold stock’. Go to ‘WooCommerce > Setting > Products > Inventory’ and in field ‘Hold stock’ (minutes) set it to blank.

Please [contact us](http://www.alternativepayments.com/support/tech-contact.html) if you need help installing the Alternative Payments WooCommerce plugin. Please provide your Alternative Payments merchant ID and website URL.


= Important =
Don’t change standard WooCommerce status.
You need to turn off ‘Hold stock’. Go to ‘WooCommerce > Setting > Products > Inventory’ and in field ‘Hold stock’ (minutes) set it to blank.

= Alternative Payments Merchant Tool =

Creating webhooks for response data:

* Go to ‘Merchant Portal > Business Cases > select Business Case ID >  Websites section’
* If no Website is added, click ‘Add new’ and complete the requested information. 
* Once in Website details, click on the tab ‘Webhooks’
* Click ‘Add Webhook’

In field ‘Webhook url’ insert value:
WP4.* => <site name>/?wc-api=wc_gateway_alter
WP4 < <site name>/wc-api/wc_gateway_alter

= Under Webhook Events, activate the following selections: = 
* transaction.pending => Not active
* transaction.approved => Active
* transaction.funded => Active
* transaction.declined => Active
* void.succeeded => Active
* void.declined => Active
* refund.pending => Active
* refund.succeeded => Active
* refund.declined => Active
* transaction.chargeback => Active
* transaction.isf => Active
* transaction.invalid => Active
* subscription.created => Active
* subscription.cancelled => Active
* customer.created => Not active

= Woo Commerce status definitions used when creating module: = 

* Pending payment – Order received (unpaid)
* On-Hold – Awaiting payment – stock is reduced, but need to confirm payment)
* Processing – Payment received and stock has been reduced, order is awaiting fulfillment

* Under WooCommerce > Settings > Checkout > Alternative Payments there is a field:

= Change SEPA order status to processing when =
1. Merchant Funded Status
2. Merchant Approved Status

This allows you to control when the WooCommerce Processing status is triggered.

= If SMS verification is enabled for SEPA payments: = 

When using test API keys you can enter a real number and get pin, or any phone and pin number in the payment form. However, when using live API keys an actual mobile number that can receive the pin needs to be used.

== Frequently asked questions ==

== Screenshots ==

1. Enter the Public API Key and Secret API Key obtained in the Website profile in the Alternative Payments [Merchant Portal](https://merchant.alternativepayments.com/Account/Login). You can change the checkout 'Title', 'Description' and 'SEPA' order status setting.
2. IMPORTANT: Activate the Payment Options which are active in your account. Check in your Terms and Conditions for available and active payment methods, or [contact us](http://www.alternativepayments.com/support/tech-contact.html) if you need to activate additional options.
3. The available payment methods in the checkout filtered by supported country.

== Changelog ==

= 1.0.9 - 24/05/2017 =
* Fix - Add new product

= 1.0.8 - 27/01/2017 =
* Update payment methods list

= 1.0.7 - 19/01/2017 =
* Fix - Displaying payment value
* Tweak - API communication

== Upgrade notice ==

= 1.0.7 =
1.0.7 is a major update.


