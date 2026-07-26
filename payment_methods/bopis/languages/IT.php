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
$TXT_BAKERY[$payment_method]['NAME'] = 'Ritiro in negozio';

// USED BY FILE bakery/payment_methods/bopis/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Ritiro in negozio';
$TXT_BAKERY[$payment_method]['PICKUP'] = 'Prendete il vostro ordine nel nostro negozio.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Pagare in contanti presso il negozio';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Le invieremo una email di conferma.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = '.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Conferma per il suo ordine su [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Gentile [CUSTOMER_NAME]

Grazie per aver acquistato su [SHOP_NAME].
Qui sotto trover&agrave; il riepilogo del suo ordine:
[ITEM_LIST]

Prendete il vostro ordine presso il nostro negozio "[SHOP_NAME]" e pagare in contanti.


La ringraziamo per la fiducia mostra.

Cordiali Saluti,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nuovo ordine su [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Gentile [SHOP_NAME] Amministratore

NUOVO ORDINE SU [SHOP_NAME]:
	Ordine #: [ORDER_ID]
	Metodo di pagamento: Ritiro in Negozio

Indirizzo di Spedizione:
[ADDRESS]

Indirizzo di Fatturazione:
[CUST_ADDRESS]

Prodotti ordinati: 
[ITEM_LIST]


Nota cliente:
[CUST_MSG]


Distinti Saluti,
[SHOP_NAME]


';




