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


// Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly"); 

// Include info file
include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/info.php');

// Look for payment method language file
if (LANGUAGE_LOADED) {
    include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
        include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php');
    }
}

// Get the payment method settings from db
$query_payment_methods = $database->query("SELECT value_1 FROM {BXT}_payment_methods WHERE directory = '$payment_method'");
if ($query_payment_methods->numRows() > 0) {
	$payment_methods = $query_payment_methods->fetchRow();
	// value_1 to value_6 correspond to the payment method settings field_1 to field_6 in the info.php file
	$value_1 = stripslashes($payment_methods['value_1']);  // Charges
}
?>




<div class="grid">
   <div class="unit whole">
      <h3 class="mod_bakery_pay_h_f"><?=$TXT_BAKERY[$payment_method]['TITLE']; ?></h3>
   </div>
</div>
<div class="grid hide-on-mobiles">
   <div class="unit whole">
      <ol>
         <li><?=$TXT_BAKERY[$payment_method]['SUCCESS']; ?></li>
         <li><?=$TXT_BAKERY[$payment_method]['SHIPMENT']; ?></li>
         <li><?=$TXT_BAKERY[$payment_method]['PAY_CASH_ON_DELIVERY']; ?></li>
      </ol>
   </div>
</div>
<div class="grid">
   <div class="unit whole">
      <p><?=$TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_1'] . $setting_shop_currency . ' ' . $value_1 . $TXT_BAKERY[$payment_method]['ADDITIONAL_CHARGES_2']; ?></p>
   </div>
</div>
<div class="grid">
   <div class="unit whole">
      <input type="submit" name="payment_method[<?=$payment_method ?>]" class="mod_bakery_bt_pay_<?=$payment_method ?>_f" value="<?=$TXT_BAKERY[$payment_method]['PAY']; ?>" onclick="javascript: return checkTaC()" />
   </div>
</div>
<div class="grid">
   <div class="unit whole">
      <hr class="mod_bakery_hr_f" />
   </div>
</div>


