<?php

/*
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (C) 2016, Christoph Marti

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


// PAYMENT METHOD CASH ON DELIVERY PAYMENT
// ***************************************

// SETTINGS - USED BY BACKEND
$TXT_BAKERY[$payment_method]['NAME'] = 'Cash on Delivery';
$TXT_BAKERY[$payment_method]['CHARGES'] = 'CoD Charges<br />(without currency code)';

// USED BY FILE bakery/payment_methods/cod/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Cash on Delivery';
$TXT_BAKERY[$payment_method]['PAY_CASH_ON_DELIVERY'] = 'Pay cash on delivery.';
$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_1'] = 'Please note additional <b>CoD charges in the amount of ';
$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_2'] = '</b> to be collected.';
$TXT_BAKERY[$payment_method]['PAY'] = 'I will pay cash on delivery';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'We will email you an order confirmation.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'We will ship your order as soon as possible.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation for your order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for your order at [SHOP_NAME] with the ID [ORDER_ID].
Please find below the information about the products you have ordered:
[ITEM_LIST]

We will ship the order to the address below:

[ADDRESS]

Please pay cash on delivery. Please note additional cod charges to be collected.


Please do not delete this message since you might need the order ID above for a revocation.

Kind regards,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Cash on Delivery

Shipping address:
[ADDRESS]

Invoice address:
[CUST_ADDRESS]

List of ordered items: 
[ITEM_LIST]


Customers message:
[CUST_MSG]


Kind regards,
[SHOP_NAME]


';

