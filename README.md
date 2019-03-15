===============

PayEx API (PHP)

===============

===============

requirements:
* PHP 7.1.9 (may work with other PHP versions)
* PHP extension curl
* PHP twig - https://twig.symfony.com/
* Composer - https://getcomposer.org/
* jQuery v3.3.1(included in the project => jquery.min.js)

===============

===============

Getting Started:
1. unzip "payex_example_php_code.zip" to your test/production environment
2. edit resources/settings.php
 - AuthorizationBearer (AuthorizationBearer = token)
 - payeeId (Merchant ID)
 - baseuri (environment API)
 - logging (value "true" only for testing purpose, not recommended when having huge transaction volumes)

regarding token please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin/
regarding merchant ID please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin-account-interface-and-search/

3. edit php files in this folder(choose a payment flow), change parameters to your liking.
4. In your root folder of the files from"payex_example_php_code" run command-> composer install
5. ready

===============

Roadmap:
* add all instruments => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-payment-instruments/

===============
