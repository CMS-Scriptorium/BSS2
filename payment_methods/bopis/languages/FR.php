<?php

/*
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
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
$TXT_BAKERY[$payment_method]['NAME'] = 'L&apos;enl&eacute;vement d&eacute;part boutique';

// USED BY FILE bakery/payment_methods/bopis/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'L&apos;enl&eacute;vement d&eacute;part boutique';
$TXT_BAKERY[$payment_method]['PICKUP'] = 'Veuillez venir chercher votre commande &agrave; la boutique.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Payer comptant &agrave; la boutique';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Vous allez recevoir un email de confirmation de votre commande.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Veuillez venir chercher votre commande &agrave; la boutique en payant comptant.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation pour votre commande sur [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Cher [CUSTOMER_NAME]

Merci beaucoup pour votre commande sur [SHOP_NAME].
Veuillez trouver ci-dessous les informations concernant les article command&eacute;s:
[ITEM_LIST]

Veuillez venir chercher votre commande à la boutique "[SHOP_NAME]" en payant comptant.


Nous vous remercions d&apos;avoir fait vos achats sur notre site.

[SHOP_NAME] vous remercie pour votre commande.

';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nouvelle commande sur [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Cher administrateur de [SHOP_NAME] 

NOUVELLE COMMANDE SUR [SHOP_NAME]:
	Commande #: [ORDER_ID]
	M&eacute;thode de paiement: L&apos;enl&eacute;vement d&eacute;part boutique

Adresse de Livraison:
[ADDRESS]

Adresse de Facturation:
[CUST_ADDRESS]

Liste des articles command&eacute;s:
[ITEM_LIST]


Note du client:
[CUST_MSG]


Meilleures consid&eacute;rations,
[SHOP_NAME]


';




