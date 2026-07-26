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
$TXT_BAKERY[$payment_method]['NAME'] = 'Nachnahme';
$TXT_BAKERY[$payment_method]['CHARGES'] = 'Nachnahmegeb&uuml;hren<br />(ohne W&auml;hrungscode)';

// USED BY FILE bakery/payment_methods/cod/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Nachnahme';
$TXT_BAKERY[$payment_method]['PAY_CASH_ON_DELIVERY'] = 'Bezahlen Sie direkt bei Zustellung der Lieferung an den Mitarbeiter der Logistikfirma.';
$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_1'] = 'Bitte beachten Sie, dass zus&auml;tzliche <b>Nachnahmegeb&uuml;hren im Betrag von ';
$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_2'] = '</b> anfallen.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ich bezahle per Nachnahme';

// USED BY FILE bakery/view_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbest&auml;tigung.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Best&auml;tigung f&uuml;r Ihre [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank f&uuml;r Ihren Einkauf bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Die gew&uuml;nschten Artikel werden wir Ihnen unverz&uuml;glich an folgende Adresse senden:

[ADDRESS]

Bitte bezahlen Sie direkt bei Zustellung der Lieferung an den Mitarbeiter der Logistikfirma. Beachten Sie, dass zus&auml;tzliche Nachnahmegeb&uuml;hren anfallen k&ouml;nnen.


Wir danken f&uuml;r das uns entgegengebrachte Vertrauen.

Mit freundlichen Gr&uuml;ssen
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Nachnahme

Lieferadresse:
[ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Kundenbemerkung:
[CUST_MSG]


Mit freundlichen Gr&uuml;ssen
[SHOP_NAME]


';


