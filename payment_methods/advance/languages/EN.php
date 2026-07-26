<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org)
  Copyright (C) 2007 - 2016, Christoph Marti

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


// PAYMENT METHOD ADVANCE PAYMENT
// ******************************

// SETTINGS - USED BY BACKEND
$TXT_BAKERY[$payment_method]['NAME'] = 'Advance payment';

// USED BY FILE bakery/payment_methods/advance/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Advance payment';
$TXT_BAKERY[$payment_method]['ACCOUNT'] = 'Please pay the balance due to our bank account in advance.';
$TXT_BAKERY[$payment_method]['PAY'] = 'I will pay in advance';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'We will email you an order confirmation with the required payment information.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'As soon as we receive your payment we will ship the order to you.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation and invoice for your order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for your order at [SHOP_NAME] with the ID [ORDER_ID].
Please find below the information about the products you have ordered:
[ITEM_LIST]

Please pay the balance due in advance to our bank account
[BANK_ACCOUNT]

As soon as we receive your payment we will ship the order to the address below:

[ADDRESS]


Please do not delete this message since you might need the order ID above for a revocation.

Kind regards,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Advance payment

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




