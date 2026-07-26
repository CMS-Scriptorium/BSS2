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
$TXT_BAKERY[$payment_method]['NAME'] = 'SOFORT Banking';
$TXT_BAKERY[$payment_method]['USER_ID'] = 'Klant nummer';
$TXT_BAKERY[$payment_method]['PROJECT_ID'] = 'Project nummer';
$TXT_BAKERY[$payment_method]['PROJECT_PW'] = 'Project wachtwoord';
$TXT_BAKERY[$payment_method]['NOTIFICATION_PW'] = 'Berichten wachtwoord';
$TXT_BAKERY[$payment_method]['NOTICE'] = "
<b>SOFORT Banking Uitgebreide instellingen</b><br />
Login in uw <a href='https://www.sofortueberweisung.de/payment/users/login' target='_blank'>SOFORT Banking</a> account: Ga naar &quot;Mijn projecten&quot; &gt; &quot;Selecteer een project&quot; &gt; &quot;Uitgebreide instellingen &quot;<br /><br />

<b>Shopsysteem interface:</b> Activeer &quot;Automatisch doorlinken&quot; en copy&amp;paste de volledge url hieronder in het veld &quot;Succes link&quot;:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&transaction_id=-TRANSACTION-' readonly='true' onclick='this.select();' style='width: 98%;' />

Copy&amp;paste de volledige url hieronder in het veld &quot;Afbreken link&quot;:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&amp;status=canceled' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Nicht änderbare Eingabeparameter:</b> Activeer &quot;Bedrag&quot; und &quot;Gebruiksdoeleinde&quot;.<br /><br />

<b>Berichten:</b> Voeg email berichten <u>en</u> HTTP berichten to via de <i>POST</i>-methode en voeg de onderstaande url toe:<input type='text' value='".WB_URL."/modules/bakery/payment_methods/payment-network/report.php' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Wachtwoorden en Hash-algoritme:</b> Maak een project wachtwoord en een berichten wachtwoord aan <u>en</u> activeer de input controle door middel van het hash algorithme <i>SHA1</i>.";

// USED BY FILE bakery/payment_methods/payment-network/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'SOFORT Banking';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_1'] = 'Betaal online via SOFORT Banking met uw ebanking account: makkelijk, veilig, gratis... Aanmelden voor een account is niet nodig.';
$TXT_BAKERY[$payment_method]['PAY_ONLINE_2'] = 'Betaal uw bestelling online met uw ebanking account. Geef uw bankrekening, clearing number, PIN en TAN.';
$TXT_BAKERY[$payment_method]['SECURITY'] = 'Lees meer over het veilig betalen op de SOFORT Banking veilig pagina';
$TXT_BAKERY[$payment_method]['SECURE'] = 'De betaling wordt uitgevoerd op de beveligde SOFORT Banking server.';
$TXT_BAKERY[$payment_method]['CONFIRMATION_NOTICE'] = 'Nadat de betaling is gedaan ontvangt u een email met onze orderbevestiging van uw betaling.';
$TXT_BAKERY[$payment_method]['WEBSITE'] = 'SOFORT Banking Website';
$TXT_BAKERY[$payment_method]['PAY'] = 'Ik betaal via SOFORT Banking';
$TXT_BAKERY[$payment_method]['REDIRECT'] = 'Om de betaling uit te voeren wordt u doorgestuurd naar een beveiligde SOFORT Banking server.';
$TXT_BAKERY[$payment_method]['REDIRECT_NOW'] = 'Ga nu naar SOFORT Banking';
$TXT_BAKERY[$payment_method]['AGGREGATED_ITEMS'] = 'Totaal incl BTW + verzendkosten';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Bedankt voor uw online betaling via SOFORT Banking. Uw transactie is geaccepteerd.<br />Onze orderbevestiging van uw betaling zijn naar per email naar u verzonden.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';
$TXT_BAKERY[$payment_method]['ERROR'] = 'Er is een probleem opgetreden. Uw betaling is niet uitgevoerd.<br />Neem contact op met de winkel beheerder.';
$TXT_BAKERY[$payment_method]['CANCELED'] = 'U heeft uw SOFORT Banking betaling afgebroken.<br />Wilt u verder gaan met winkelen?';

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
	Betaal methode: SOFORT Banking

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




