===============

PayEx API (PHP)

===============

===============

Getting Started:
1. unzip "payex_example_php_code.zip" to your test/production environment
2. edit resources/settings.php
 - AuthorizationBearer (AuthorizationBearer = token)
 - payeeId (Merchant ID)
 - baseuri (environment API) - change to production baseuri to go live
 - logging (value "true" only for testing purpose, not recommended when having huge transaction volumes because of synchronous blocking)

regarding AuthorizationBearer token please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin/
regarding merchant ID please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin-account-interface-and-search/

3. edit php files and change request parameters to your liking.
4. ready

===============

Roadmap:
* add all instruments => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-payment-instruments/

===============