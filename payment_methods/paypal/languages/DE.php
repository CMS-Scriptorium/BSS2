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
$TXT_BAKERY[$payment_method]['EMAIL'] = 'PayPal E-Mail';
$TXT_BAKERY[$payment_method]['PAGE'] = 'Benutzerdefinierte Zahlungsseite';
$TXT_BAKERY[$payment_method]['AUTH_TOKEN'] = 'Identit&auml;tstoken';

$TXT_BAKERY[$payment_method]['NOTICE'] = '
<b>Website-Zahlungsoptionen</b><br />
<a href="'.WB_URL.'/modules/bakery/payment_methods/paypal/bakery-paypal.pdf" target="_blank"><strong><span class="fa fa-file-pdf-o fa-fw"></span>Anleitung (PDF, deutschsprachig) zur Einrichtung von Paypal</strong></a><br />

<b>Automatische R&uuml;ckleitung:</b> Aktivieren Sie &quot;Automatische R&uuml;ckleitung&quot;.<br />
<b>R&uuml;ckleitungs-URL:</b> Geben Sie folgende URL als &quot;R&uuml;ckleitungs-URL&quot; an:<input type="text" value="' . WB_URL . '" readonly="true" onclick="this.select();" style="width: 98%;" /><br /><br />

<b>&Uuml;bertragung der Zahlungsdaten:</b> Aktivieren Sie &quot;&Uuml;bertragung der Zahlungsdaten&quot; und speichern Ihre Einstellung. Ihr Identit&auml;ts-Token wird unterhalb der &quot;&Uuml;bertragung der Zahlungsdaten&quot; Radio-Buttons angezeigt. Kopieren Sie Ihr Identit&auml;ts-Token ins Feld direkt oberhalb dieser gelben Box.<br /><br />

<b>Sofortige Zahlungsbest&auml;tigung (IPN)</b><br />
Kopieren Sie die unten stehende URL und f&uuml;gen Sie sie vollst&auml;ndig ins Feld &quot;Benachrichtigungs-URL&quot; auf der Konfigurationsseite ein:<input type="text" value="' . WB_URL . '/modules/bakery/payment_methods/paypal/ipn.php" readonly="true" onclick="this.select();" style="width: 98%;" />
Aktivieren Sie &quot;Sofortige Zahlungsbest&auml;tigungen erhalten (aktiviert)&quot; und speichern Ihre Einstellung.<br />';

// USED BY FILE bakery/payment_methods/paypal/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'PayPal (Kreditkarte)';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_1'] = 'Bezahlen Sie online mit allen g&auml;ngigen Kreditkarten per PayPal: schnell, sicher, problemlos...';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_2'] = 'Bezahlen Sie Ihre Bestellung online mit allen g&auml;ngigen Kreditkarten per PayPal oder auch per PayPal-Zahlung.';
$TXT_BAKERY[$payment_method]['SECURITY'] = 'Mehr Informationen zur Zahlungssicherheit finden Sie auf der';
$TXT_BAKERY[$payment_method]['SECURE'] = 'Die Zahlungsabwicklung l&auml;uft &uuml;ber einen sicheren PayPal Server.';
$TXT_BAKERY[$payment_method]['CONFIRMATION_NOTICE'] = 'Nach Ihrer Transaktion erhalten Sie per E-Mail unsere Auftragsbest&auml;tigung sowie eine Zahlungsbest&auml;tigung von PayPal.';
$TXT_BAKERY[$payment_method]['WEBSITE'] = 'PayPal Website';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ich bezahle per PayPal';
$TXT_BAKERY[$payment_method]['REDIRECT'] = 'Zur Zahlungsabwicklung werden Sie zu einem sicheren PayPal Server weitergeleitet.';
$TXT_BAKERY[$payment_method]['REDIRECT_NOW'] = 'Jetzt zu PayPal wechseln';
$TXT_BAKERY[$payment_method]['AGGREGATED_ITEMS'] = 'Gesamtsumme inkl. Mwst und Versand';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Besten Dank f&uuml;r Ihre Online-Zahlung. Ihre Transaktion wurde abgeschlossen.<br />Unsere Auftragsbest&auml;tigung und eine Zahlungsbest&auml;tigung von PayPal wurden Ihnen per E-Mail zugesandt.';
$TXT_BAKERY[$payment_method]['PENDING'] = 'Beste Dank f&uuml;r Ihre Online-Zahlung. Ihre Transaktion wird in K&uuml;rze bearbeitet.<br />Unsere Auftragsbest&auml;tigung und eine Zahlungsbest&auml;tigung von PayPal wird Ihnen per E-Mail zugesandt.';
$TXT_BAKERY[$payment_method]['TRANSACTION_STATUS'] = "ACHTUNG:\n\tDie Transaktion ist noch \"OFFEN\".\n\tAlle Details zu dieser Zahlung finden Sie in Ihrer PayPal-Kontoübersicht.";
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen in der angegebenen Lieferzeit zu.';
$TXT_BAKERY[$payment_method]['ERROR'] = 'Es ist ein Problem aufgetreten. Ihre Transaktion konnte nicht abgeschlossen werden.<br />Bitte wenden Sie sich an den Shop-Betreiber.';
$TXT_BAKERY[$payment_method]['CANCELED'] = 'Sie haben Ihre Zahlung bei PayPal abgebrochen.<br />M&ouml;chten Sie Ihren Einkauf trotzdem fortsetzen?';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung für Ihre [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf mit der ID [ORDER_ID] bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Die gewünschten Artikel werden wir Ihnen unverzüglich an folgende Adresse senden:

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
	Zahlungsart: PayPal
[TRANSACTION_STATUS]

Lieferadresse:
[ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Kundenbemerkung:
[CUST_MSG]


Mit freundlichen Grüssen
[SHOP_NAME]


';




