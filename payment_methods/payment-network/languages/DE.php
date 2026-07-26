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


// PAYMENT METHOD PAYMENT-NETWORK
// ******************************

// Get the current url scheme
$url = parse_url(WB_URL);

// SETTINGS - USED BY BACKEND
$TXT_BAKERY[$payment_method]['NAME'] = 'SOFORT &Uuml;berweisung';
$TXT_BAKERY[$payment_method]['USER_ID'] = 'Kundennummer';
$TXT_BAKERY[$payment_method]['PROJECT_ID'] = 'Projektnummer';
$TXT_BAKERY[$payment_method]['PROJECT_PW'] = 'Projekt Passwort';
$TXT_BAKERY[$payment_method]['NOTIFICATION_PW'] = 'Benachrichtigungspasswort';
$TXT_BAKERY[$payment_method]['NOTICE'] = "
<b>SOFORT &Uuml;berweisung erweiterte Einstellungen</b><br />
Loggen Sie sich in Ihr <a href='https://www.sofortueberweisung.de/payment/users/login' target='_blank'>SOFORT &Uuml;berweisung</a> Konto ein: Gehen Sie zu &quot;Meine Projekte&quot; &gt; &quot;Projekt ausw&auml;hlen&quot; &gt; &quot;Erweiterte Einstellungen&quot;:<br /><br />

<b>Shopsystem-Schnittstelle:</b> Aktivieren Sie &quot;Automatische Weiterleitung&quot; und geben Sie unter &quot;Erfolgslink&quot; folgende vollst&auml;ndige URL ein:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&transaction_id=-TRANSACTION-' readonly='true' onclick='this.select();' style='width: 98%;' />

Geben Sie unter &quot;Abbruch-Link&quot; die folgende vollst&auml;ndige URL ein:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&amp;status=canceled' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Nicht änderbare Eingabeparameter:</b> Aktivieren Sie &quot;Betrag&quot; und &quot;Verwendungszweck&quot;.<br /><br />

<b>Benachrichtigungen:</b> Erstellen Sie eine E-Mail Benachrichtigung <u>und</u> eine HTTP Benachrichtigung mit der <i>POST</i>-Methode an die folgende vollst&auml;ndige URL:<input type='text' value='".WB_URL."/modules/bakery/payment_methods/payment-network/report.php' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Passw&ouml;rter und Hash-Algorithmus:</b> Legen Sie ein Projekt-Passwort und ein Benachrichtigungspasswort fest <u>und</u> aktivieren Sie die Input-Pr&uuml;fung mit dem Hash-Algorithmus <i>SHA1</i>.";

// USED BY FILE bakery/payment_methods/payment-network/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'SOFORT &Uuml;berweisung';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_1'] = 'Mit SOFORT &Uuml;berweisung k&ouml;nnen Sie bequem, einfach und sicher ohne Registrierung mit Ihrem Online-Banking Konto bezahlen.';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_2'] = 'Bezahlen Sie Ihre Bestellung online &uuml;ber Ihr Online-Banking Konto. Sie ben&ouml;tigen lediglich Bankkontonummer, Bankleitzahl, PIN und TAN.';
$TXT_BAKERY[$payment_method]['SECURITY'] = 'Mehr Informationen zur Zahlungssicherheit finden Sie auf der';
$TXT_BAKERY[$payment_method]['SECURE'] = 'Die Zahlungsabwicklung l&auml;uft &uuml;ber einen sicheren Server von SOFORT &Uuml;berweisung.';
$TXT_BAKERY[$payment_method]['CONFIRMATION_NOTICE'] = 'Nach Ihrer Transaktion erhalten Sie per E-Mail unsere Auftragsbest&auml;tigung.';
$TXT_BAKERY[$payment_method]['WEBSITE'] = 'SOFORT &Uuml;berweisung Website';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ich bezahle per SOFORT &Uuml;berweisung';
$TXT_BAKERY[$payment_method]['REDIRECT'] = 'Zur Zahlungsabwicklung werden Sie zu einen sicheren Server von SOFORT &Uuml;berweisung weitergeleitet.';
$TXT_BAKERY[$payment_method]['REDIRECT_NOW'] = 'Jetzt zu SOFORT &Uuml;berweisung wechseln';
$TXT_BAKERY[$payment_method]['AGGREGATED_ITEMS'] = 'Summe inkl Mwst + Versand';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Besten Danke f&uuml;r Ihre online Zahlung. Ihre Transaktion bei SOFORT &Uuml;berweisung wurde abgeschlossen.<br />Unsere Auftragsbest&auml;tigung wurde Ihnen per E-Mail zugesandt.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu.';
$TXT_BAKERY[$payment_method]['ERROR'] = 'Es ist ein Problem aufgetreten. Ihre Transaktion konnte nicht abgeschlossen werden.<br />Bitte wenden Sie sich an den Shop-Betreiber.';
$TXT_BAKERY[$payment_method]['CANCELED'] = 'Sie haben Ihre Zahlung bei SOFORT &Uuml;berweisung abgebrochen.<br />M&ouml;chten Sie Ihren Einkauf trotzdem fortsetzen?';

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
	Zahlungsart: SOFORT Überweisung

Lieferadresse:
[ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Kundenbemerkung:
[CUST_MSG]


Mit freundlichen Grüssen
[SHOP_NAME]


';




