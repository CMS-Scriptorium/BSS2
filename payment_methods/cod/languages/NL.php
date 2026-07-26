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
$TXT_BAKERY[$payment_method]['NAME'] = 'Rembours';
$TXT_BAKERY[$payment_method]['CHARGES'] = 'Rembours kosten<br />(zonder valuta code)';

// USED BY FILE bakery/payment_methods/cod/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Rembours';
$TXT_BAKERY[$payment_method]['PAY_CASH_ON_DELIVERY'] = 'Betaal bij aflevering.';
$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_1'] = 'Let op, er zijn extra kosten aan het verzenden onder rembours van <b>';
$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_2'] = '</b> bovenop de verzendkosten.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ik betaal contant bij aflevering';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'U ontvangt per email een bevestiging van uw bestelling.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bevestiging van uw [SHOP_NAME] bestelling';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Geachte [CUSTOMER_NAME]

Bedankt voor uw bestelling bij [SHOP_NAME].
Hieronder vind u een overzicht van de door u bestelde produkten:
[ITEM_LIST]

Wij zullen de goederen verzenden naar:

[ADDRESS]

Betaal contant bij aflevering. Let op, er zijn extra kosten voor verzenden onder rembours.


Bedankt voor het in ons gestelde vertrouwen.

Met vriendelijke groeten,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nieuwe bestelling bij [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Geachte [SHOP_NAME] Administrator

NIEUWE BESTELLING BIJ [SHOP_NAME]:
	Bestelling #: [ORDER_ID]
	Betaal methode: Rembours

Aflever adres:
[ADDRESS]

Factuur adres:
[CUST_ADDRESS]

Bestellijst: 
[ITEM_LIST]


Klant opmerking:
[CUST_MSG]


Met vriendelijke groet,
[SHOP_NAME]


';


