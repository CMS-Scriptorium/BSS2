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
$TXT_BAKERY[$payment_method]['NAME'] = 'Rechnung';
$TXT_BAKERY[$payment_method]['BANK_ACCOUNT'] = 'Shop Bankkonto';
$TXT_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'] = 'Rechnungstemplate';
$TXT_BAKERY[$payment_method]['INVOICE_ALERT'] = '1. Zahlungserinnerung nach';
$TXT_BAKERY[$payment_method]['REMINDER_ALERT'] = '2. Zahlungserinnerung nach';

// USED BY FILE bakery/payment_methods/invoice/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Rechnung';
$TXT_BAKERY[$payment_method]['ACCOUNT'] = 'Bitte bezahlen Sie den Rechnungsbetrag innerhalb der in der Rechnung angegebenen Zahlungsfrist.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ich bezahle per Rechnung';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbest&auml;tigung.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu, sofern die Bezahlung per Rechnung f&uuml;r den konkreten Bestellvorgang zul&auml;ssig ist.';

// INVOICE TEMPLATE
$TXT_BAKERY[$payment_method]['INVOICE_TEMPLATE'] = '<img src="[WB_URL]/modules/bakery/images/logo.gif" width="690" height="75" alt="[SHOP_NAME] Logo" class="mod_bakery_logo_b" />
<br />
<p class="mod_bakery_shop_address_b">[SHOP_NAME] | Firma | Strasse Nummer | PLZ Stadt | Land</p>
<br /><br /><br />
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_INVOICE]">[CUST_ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_DELIVERY_NOTE]">[ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_REMINDER]">[CUST_ADDRESS]</p>
<br /><br /><br /><br /><br /><br />
<h2>[TITLE]</h2>
<table class="mod_bakery_invoice_no_b" cellspacing="0" cellpadding="0">
<tr>
<td align="right">Datum:</td>
<td>[CURRENT_DATE]</td>
</tr>
<tr>
<td align="right">Rechnung:</td>
<td>[INVOICE_ID]</td>
</tr>
<tr>
<td align="right">Bestellung:</td>
<td>[ORDER_ID] | [ORDER_DATE]</td>
</tr>
<tr>
<td align="right">Ihre USt-ID:</td>
<td>[CUST_TAX_NO]</td>
</tr>
</table>
<br />
[ITEM_LIST]
<br /><br /><br />

<div style="display: [DISPLAY_INVOICE]">
<p class="mod_bakery_thank_you_b">Vielen Dank f&uuml;r Ihre Bestellung.</p>
<p class="mod_bakery_pay_invoice_b">Bitte bezahlen Sie die Rechnung innert 30 Tagen auf unser Konto:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<div style="display: [DISPLAY_DELIVERY_NOTE]">
<p class="mod_bakery_thank_you_b">Vielen Dank f&uuml;r Ihre Bestellung.</p>
</div>

<div style="display: [DISPLAY_REMINDER]">
<p class="mod_bakery_pay_invoice_b">Sollten Sie die Rechnung zwischenzeitlich beglichen haben, erachten sie dieses Schreiben als gegenstandslos. Ansonsten bitten wir Sie, den Rechnungsbetrag innert 10 Tagen auf unser Konto zu &uuml;berweisen:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<br /><br />';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung für Ihre [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf mit der ID [ORDER_ID] bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Die gewünschten Artikel werden wir Ihnen unverzüglich an folgende Adresse senden:

[ADDRESS]

Die Rechnung werden wir Ihnen an folgende Adresse senden:

[CUST_ADDRESS]


Bitte bewahren Sie diese E-Mail auf, da Sie für einen eventuellen Widerruf die o.g. ID benötigen.

Mit freundlichen Grüssen
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Rechnung

Lieferadresse:
[ADDRESS]

Rechnungsadresse:
[CUST_ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Kundenbemerkung:
[CUST_MSG]


Mit freundlichen Grüssen
[SHOP_NAME]


';




