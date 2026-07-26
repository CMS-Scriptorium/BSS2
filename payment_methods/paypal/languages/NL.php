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
$TXT_BAKERY[$payment_method]['AUTH_TOKEN'] = 'DBB Identiteitscode';

$TXT_BAKERY[$payment_method]['NOTICE'] = '
<b>Voorkeuren websitebetalingen</b><br />
Log in in uw PayPal account: Ga naar &quot;Mijn rekening&quot; &gt; &quot;Profiel&quot; &gt; &quot;Voorkeuren websitebetalingen&quot;<br />

<b>De koper wordt automatisch teruggeleid naar webwinkel:</b> Klik de &quot;Automatisch teruggeleid&quot; radio button <i>Aan</i>.<br />
<b>Retour URL:</b> Voer het adres in zoals getoond in het veld &quot;Retour URL&quot;:<input type="text" value="' . WB_URL . '" readonly="true" onclick="this.select();" style="width: 98%;" />

<b>Overdracht van betaalgegevens:</b> Klik de &quot;Overdracht van betaalgegevens&quot; radio button <i>Aan</i> en klik vervolgens op <i>Opslaan</i>.<br />
Uw identiteitscode staat onder de Overdracht van betaalgegevens Aan/Uit radio buttons. Kopieer&amp;plak uw identiteitscode in het geel tekstveld hierboven.<br /><br />

<b>Direct betaalbericht (DBB)</b><br />
Ga naar &quot;Mijn rekening&quot; &gt; &quot;Profiel&quot; &gt; &quot;Voorkeuren Direct betaalbericht&quot;.<br />
Als u klikt op &quot;Instellingen voor DBB&quot; bewerken, wordt u doorgestuurd naar de configuratiepagina.<br />
Kopieer&amp;plak het volledige adres zoals getoond in het veld &quot;Berichtgevings-URL&quot;:<input type="text" value="' . WB_URL . '/modules/bakery/payment_methods/paypal/ipn.php" readonly="true" onclick="this.select();" style="width: 98%;" />
Klik op de  &quot;DBB-berichten ontvangen (ingeschakeld)&quot; radio button en sla de wijzigingen op.<br />';

// USED BY FILE bakery/payment_methods/paypal/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'PayPal (Creditcard)';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_1'] = 'Betaal online via PayPal met uw creditcard: makkelijk, veilig, gratis...';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_2'] = 'Betaal uw bestelling online met uw credit card of uw PayPal rekening.';
$TXT_BAKERY[$payment_method]['SECURITY'] = 'Lees meer over het veilig betalen op de PayPal Veilig Winkelen pagina';
$TXT_BAKERY[$payment_method]['SECURE'] = 'De betaling wordt uitgevoerd op de beveligde PayPal server.';
$TXT_BAKERY[$payment_method]['CONFIRMATION_NOTICE'] = 'Nadat de betaling is gedaan ontvangt u een email met onze orderbevestiging en een Paypal bevestiging van uw betaling.';
$TXT_BAKERY[$payment_method]['WEBSITE'] = 'PayPal Website';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ik betaal via PayPal';
$TXT_BAKERY[$payment_method]['REDIRECT'] = 'Om de betaling uit te voeren wordt u doorgestuurd naar een beveiligde PayPal server.';
$TXT_BAKERY[$payment_method]['REDIRECT_NOW'] = 'Ga nu naar PayPal';
$TXT_BAKERY[$payment_method]['AGGREGATED_ITEMS'] = 'Totaal bedrag incl. BTW en verzendkosten';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Bedankt voor uw online betaling. Uw transactie is geaccepteerd.<br />Onze orderbevestiging en een PayPal bevestiging van uw betaling zijn naar per email naar u verzonden.';
$TXT_BAKERY[$payment_method]['PENDING'] = 'Bedankt voor uw online betaling! Uw transactie wordt zo spoedig mogelijk afgehandeld.<br />Een bevestiging van uw bestelling en een PayPal betalings bevestiging worden per email aan u verzonden.';
$TXT_BAKERY[$payment_method]['TRANSACTION_STATUS'] = "LET OP:\n\tDe transactie status is \"PENDING\".\n\tOm de details te bekijken kunt u inloggen in uw PayPal rekening.";
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';
$TXT_BAKERY[$payment_method]['ERROR'] = 'Er is een probleem opgetreden. Uw betaling is niet uitgevoerd.<br />Neem contact op met de winkel beheerder.';
$TXT_BAKERY[$payment_method]['CANCELED'] = 'U heeft uw PayPal betaling afgebroken.<br />Wilt u verder gaan met winkelen?';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bevestiging van uw [SHOP_NAME] bestelling';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Geachte [CUSTOMER_NAME]

Bedankt voor uw bestelling bij [SHOP_NAME].
Hieronder vind u een overzicht van de door u bestelde produkten:
[ITEM_LIST]

Wij zullen de goederen verzenden naar:

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
	Betaal methode: PayPal
[TRANSACTION_STATUS]

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



