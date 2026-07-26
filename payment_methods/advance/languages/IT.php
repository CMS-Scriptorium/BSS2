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
$TXT_BAKERY[$payment_method]['NAME'] = 'Pagamento Anticipato';

// USED BY FILE bakery/payment_methods/advance/gateway.php
$TXT_BAKERY[$payment_method]['TITLE'] = 'Pagamento Anticipato';
$TXT_BAKERY[$payment_method]['ACCOUNT'] = 'La preghiamo di effettuare il pagamento dovuto al nostro conto bancario in anticipo.';
$TXT_BAKERY[$payment_method]['PAY'] = 'Pagher&ograve; in anticipo';

// USED BY FILE bakery/checkout_confirmation.php
$TXT_BAKERY[$payment_method]['SUCCESS'] = 'Le invieremo una email di conferma con le informazioni per il pagamento.';
$TXT_BAKERY[$payment_method]['SHIPMENT'] = 'Appena riceveremo il suo pagamento, le spediremo l\'ordine.';

// EMAIL CUSTOMER
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Conferma e fattura per il suo ordine su [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Gentile [CUSTOMER_NAME]

Grazie per aver acquistato su [SHOP_NAME].
Qui sotto trover&agrave; il riepilogo del suo ordine:
[ITEM_LIST]

La preghiamo di effettuare il pagamento dovuto al nostro conto bancario in anticipo
[BANK_ACCOUNT]

Non appena il pagamento risulter&agrave; effettuato le spediremo l\'ordine al pi&ugrave; presto all\'indirizzo qui sotto:

[ADDRESS]


La ringraziamo per la fiducia mostra.

Cordiali Saluti,
[SHOP_NAME]


';

// EMAIL SHOP
$TXT_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nuovo ordine su [SHOP_NAME]';
$TXT_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Gentile [SHOP_NAME] Amministratore

NUOVO ORDINE SU [SHOP_NAME]:
	Ordine #: [ORDER_ID]
	Metodo di pagamento: Pagamento Anticipato

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




