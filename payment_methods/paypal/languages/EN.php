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


// PAYMENT METHOD PAYPAL
// *********************

// SETTINGS - USED BY BACKEND
$TXT_BAKERY[$payment_method]['NAME'] = 'PayPal';
$TXT_BAKERY[$payment_method]['EMAIL'] = 'PayPal Email';
$TXT_BAKERY[$payment_method]['PAGE'] = 'PayPal Page';
$TXT_BAKERY[$payment_method]['AUTH_TOKEN'] = 'PDT Identity Token';

$TXT_BAKERY[$payment_method]['NOTICE'] = '
<b>Website Payment Preferences</b><br />
Log in to your PayPal account: Go to &quot;My Account&quot; &gt; &quot;Profile&quot; &gt; &quot;My selling tools&quot; &gt; &quot;Website preferences&quot;.<br />

<b>Auto Return:</b> Click the &quot;Auto Return&quot; radio button <i>On</i>.<br />
<b>Return URL:</b> Enter the url as shown below in the field &quot;Return URL&quot;:<input type="text" value="' . WB_URL . '" readonly="true" onclick="this.select();" style="width: 98%;" /><br /><br />

<b>Payment Data Transfer:</b> Click the &quot;Payment Data Transfer (PDT)&quot; radio button <i>On</i> and then click <i>Save</i>.<br />
Your Identity Token is shown below the PDT On/Off radio buttons. Copy&amp;paste your Identity Token to the textfield right above this yellow box.<br /><br />

<b>Instant Payment Notification Preferences</b><br />
Go to &quot;My Account&quot; &gt; &quot;Profile&quot; &gt; &quot;My selling tools&quot; &gt; &quot;Instant payment notifications&quot;.<br />
Click the &quot;Choose IPN Settings&quot; button and you will be taken to the configuration page.<br />
Copy&amp;paste the full url as shown below to the field &quot;Notification URL&quot;:<input type="text" value="' . WB_URL . '/modules/bakery/payment_methods/paypal/ipn.php" readonly="true" onclick="this.select();" style="width: 98%;" />
Click the &quot;Receive IPN messages (Enabled)&quot; radio button and save your changes.<br />';

// USED BY FILE bakery/payment_methods/paypal/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'PayPal (Credit card)';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_1'] = 'Pay online with PayPal using your credit card: easy, safe, free...';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_2'] = 'Pay your order online using your credit card or PayPal payment.';
$TXT_BAKERY[$payment_method]['SECURITY'] = 'Learn more about buying safely on the PayPal Security Center page';
$TXT_BAKERY[$payment_method]['SECURE'] = 'The payment processing is handled by the secure PayPal server.';
$TXT_BAKERY[$payment_method]['CONFIRMATION_NOTICE'] = 'After completion of the transaction, our order confirmation and a PayPal receipt for your purchase will be emailed to you.';
$TXT_BAKERY[$payment_method]['WEBSITE'] = 'PayPal Website';
$TXT_BAKERY[$payment_method]['PAY'] = 'I will pay with PayPal';
$TXT_BAKERY[$payment_method]['REDIRECT'] = 'To handle the payment processing you will be redirected to a secure PayPal server.';
$TXT_BAKERY[$payment_method]['REDIRECT_NOW'] = 'Go to PayPal now';
$TXT_BAKERY[$payment_method]['AGGREGATED_ITEMS'] = 'Total incl. tax and shipping';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Thank you for your online payment! Your transaction has been completed.<br />Our order confirmation and a PayPal receipt for your purchase has been emailed to you.';
$TXT_BAKERY[$payment_method]['PENDING'] = 'Thank you for your online payment! Your transaction will be processed shortly.<br />Our order confirmation and a PayPal receipt for your purchase will be emailed to you.';
$TXT_BAKERY[$payment_method]['TRANSACTION_STATUS'] = "PLEASE NOTE:\n\tThe transaction status is \"PENDING\".\n\tTo see all the transaction details, please log in to your PayPal account.";
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'We will ship your order as soon as possible.';
$TXT_BAKERY[$payment_method]['ERROR'] = 'A problem has occurred. The transaction has not been completed.<br />Please contact the shop admin.';
$TXT_BAKERY[$payment_method]['CANCELED'] = 'You have canceled your PayPal payment.<br />Do you like to continue shopping?';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation for your order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for your order at [SHOP_NAME] with the ID [ORDER_ID].
Please find below the information about the products you have ordered:
[ITEM_LIST]

We will ship the order to the address below:

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
	Payment method: PayPal
[TRANSACTION_STATUS]

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




