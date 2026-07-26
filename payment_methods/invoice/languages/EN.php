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


// PAYMENT METHOD INVOICE
// **********************

// SETTINGS - USED BY BACKEND
$TXT_BAKERY[$payment_method]['NAME'] = 'Invoice';
$TXT_BAKERY[$payment_method]['BANK_ACCOUNT'] = 'Shop Bank Account';
$TXT_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'] = 'Invoice Template';
$TXT_BAKERY[$payment_method]['INVOICE_ALERT'] = '1. Reminder Alert after';
$TXT_BAKERY[$payment_method]['REMINDER_ALERT'] = '2. Reminder Alert after';

// USED BY FILE bakery/payment_methods/invoice/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Invoice';
$TXT_BAKERY[$payment_method]['ACCOUNT'] = 'Please pay the balance due to our bank account in compliance with the term of payment.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Charge to my Account';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'We will email you an order confirmation.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'We will ship your order as soon as possible.';

// INVOICE TEMPLATE
$TXT_BAKERY[$payment_method]['INVOICE_TEMPLATE'] = '<img src="[WB_URL]/modules/bakery/images/logo.gif" width="690" height="75" alt="[SHOP_NAME] Logo" class="mod_bakery_logo_b" />
<br />
<p class="mod_bakery_shop_address_b">[SHOP_NAME] | Company | No Street | City ZIP | COUNTRY</p>
<br /><br /><br />
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_INVOICE]">[CUST_ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_DELIVERY_NOTE]">[ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_REMINDER]">[CUST_ADDRESS]</p>
<br /><br /><br /><br /><br /><br />
<h2>[TITLE]</h2>
<table class="mod_bakery_invoice_no_b" cellspacing="0" cellpadding="0">
<tr>
<td align="right">Date:</td>
<td>[CURRENT_DATE]</td>
</tr>
<tr>
<td align="right">Invoice-No:</td>
<td>[INVOICE_ID]</td>
</tr>
<tr>
<td align="right">Order:</td>
<td>[ORDER_ID] | [ORDER_DATE]</td>
</tr>
<tr>
<td align="right">Your VAT-No:</td>
<td>[CUST_TAX_NO]</td>
</tr>
</table>
<br />
[ITEM_LIST]
<br /><br /><br />

<div style="display: [DISPLAY_INVOICE]">
<p class="mod_bakery_thank_you_b">Thank you for your order at [SHOP_NAME] with the ID [ORDER_ID].</p>
<p class="mod_bakery_pay_invoice_b">Please pay the balance due within 30 days into account below:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<div style="display: [DISPLAY_DELIVERY_NOTE]">
<p class="mod_bakery_thank_you_b">Thank you for your order at [SHOP_NAME] with the ID [ORDER_ID].</p>
</div>

<div style="display: [DISPLAY_REMINDER]">
<p class="mod_bakery_pay_invoice_b">Please disregard this letter if you have already made payment. Otherwise please pay the balance due within 10 days into account below:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<br /><br />';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation for your order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for your order at [SHOP_NAME] with the ID [ORDER_ID].
Please find below the information about the products you have ordered:
[ITEM_LIST]

We will ship the order to the address below:

[ADDRESS]

We will send you the invoice to the address below:

[CUST_ADDRESS]


Please do not delete this message since you might need the order ID above for a revocation.

Kind regards,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Invoice

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




