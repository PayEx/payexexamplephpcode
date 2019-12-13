# PayEx API - PHP code example

## requirements
* PHP 7.3.3
(may work with other PHP versions)
* PHP extension curl
* jQuery v3.4.1(included)

## Getting Started
1. unzip "payexexamplephpcode.zip" to your test/production environment
2. edit resources/settings.php
- AuthorizationBearer (AuthorizationBearer = token)
- hosturi (url to your site, used only for hosted view)
- payeeId (Merchant ID)
- baseuri (environment API) - change to production baseuri to go live
- logging (value "true" only for testing purpose, not recommended when having huge transaction volumes because of synchronous blocking)
 
regarding AuthorizationBearer token please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin/
regarding merchant ID please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/resources/admin-account-interface-and-search/

3. edit php files and change request parameters to your liking.
4. ready

## About products

### Checkout
- hostedview_checkout.php 
(paymentmenu with checkin edit paymentmenu parameters at resources/script_checkout_consumerProfileRef.php)
- hostedview_paymentorder.php) 
(paymentmenu without checkin)
- hostedview_creditcard.php
- redirect_creditcard.php

### Payment pages
- hostedview_creditcard.php
- redirect_creditcard.php

## Roadmap
- add all instruments => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-payment-instruments/

# Change Log
All notable changes to this project will be documented in this file.
  
## 2019-10-14

### Added
- none

### Changed
- jQuery v3.3.1 upgrade to jquery-3.4.1
- minor change to api payload
 
### Fixed
- none

## 2019-12-13

### Added
- paymentUrl handling.
- paymentUrl for creditcard is not supported (2019-12-10), but implemented.
- new return file for each hostedview scenario, specified in paymentUrl. This is for reloading view-paymentorder javascript.

### Changed
- moved payex javascript config values to "js/paymentorder.js" to have commen values for checkout and paymentorder.
 
### Fixed
- none
