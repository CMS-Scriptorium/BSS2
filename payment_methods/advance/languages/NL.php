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
$TXT_BAKERY[$payment_method]['NAME'] = 'Vooruitbetaling';

// USED BY FILE bakery/payment_methods/advance/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Vooruitbetaling';
$TXT_BAKERY[$payment_method]['ACCOUNT'] = 'Maak het bedrag over op onze bankrekening.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ik betaal vooruit';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'U ontvangt een bevestigings email met betaal instructies.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Zodra het bedrag is bijgeschreven op onze bankrekening verzenden wij de bestelde artikelen.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bevestiging en factuur voor uw [SHOP_NAME] order';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Geachte [CUSTOMER_NAME]

Bedankt voor uw bestelling bij [SHOP_NAME].
Hieronder vind u een overzicht van de door u bestelde produkten:
[ITEM_LIST]

U wordt verzocht het totaalbedrag over te maken op bankrekening 
[BANK_ACCOUNT]

Zodra wij het bedrag hebben ontvangen zullen wij de goederen verzenden naar:

[ADDRESS]


Bedankt voor het in ons gestelde vertrouwen.

Met vriendelijke groeten,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nieuwe bestelling bij [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Geachte [SHOP_NAME] Administrator

NIEUWE BESTELLING BIJ [SHOP_NAME]:
	Bestelling #: [ORDER_ID]
	Betaal methode: Advance payment

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




