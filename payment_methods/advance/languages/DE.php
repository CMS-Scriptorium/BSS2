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
$TXT_BAKERY[$payment_method]['NAME'] = 'Vorauszahlung';

// USED BY FILE bakery/payment_methods/advance/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Vorauszahlung';
$TXT_BAKERY[$payment_method]['ACCOUNT'] = 'Bezahlen Sie Ihre Bestellung im Voraus auf unser Konto.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ich bezahle per Vorauszahlung';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbest&auml;tigung und den Zahlungsinformationen.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Sobald Ihre Zahlung bei uns eingegangen ist, werden wir Ihnen die gew&uuml;nschten Artikel unverz&uuml;glich zusenden.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung und Rechnung für Ihre [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf mit der ID [ORDER_ID] bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Bitte bezahlen Sie die Gesamtsumme im Voraus auf unser Konto:
[BANK_ACCOUNT]

Sobald Ihre Zahlung bei uns eingegangen ist, werden wir Ihnen die gewünschten Artikel unverzüglich an folgende Adresse senden:

[ADDRESS]


Bitte bewahren Sie diese E-Mail auf, da Sie für einen eventuellen Widerruf die o.g. ID benötigen.

Mit freundlichen Grüssen
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Vorauszahlung

Lieferadresse:
[ADDRESS]

Folgende Artikel wurden bestellt:
[ITEM_LIST]


Kundenbemerkung:
[CUST_MSG]


Mit freundlichen Grüssen
[SHOP_NAME]


';




