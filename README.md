===============

PayEx API (PHP)

===============

===============

requirements:
* PHP 7.1.9
(may work with other PHP versions)
* PHP extension curl
* jQuery v3.3.1(included in the project => jquery.min.js)

===============

===============

Getting Started:
1. Copy files to your test/production environment
2. edit resources/settings.php
 - AuthorizationBearer (AuthorizationBearer = token)
 - payeeId (Merchant ID)
 - baseuri (environment API)
 - logging (value "true" only for testing purpose, not recommended when having huge transaction volumes)

regarding token please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin/
regarding merchant ID please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin-account-interface-and-search/

3. edit php files in this folder(choose a payment flow), change parameters to your liking.
4. run php files

===============

Roadmap:
* better response handling
* add all instruments => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-payment-instruments/

===============
