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

// Payment network logo
$logo              = LANGUAGE == 'DE' ? 'logo_de' : 'logo';
// Payment network security info link
$security_info_url = isset($security_info[LANGUAGE]) ? $security_info[LANGUAGE] : $security_info['EN'];
?>


	<div class="grid">
	  <div class="unit whole"><h3 class="mod_bakery_pay_h_f"><?=$TXT_BAKERY[$payment_method]['TITLE']; ?> <img src="<?=WB_URL ?>/modules/bakery/payment_methods/<?=$payment_method ?>/<?=$logo ?>.png" alt="Logo <?=$payment_method_name ?>" width="112" height="30" /></h3></div>
	</div>
	<div class="grid">
	  <div class="unit whole" class="mod_bakery_pay_td_f"><?=$TXT_BAKERY[$payment_method]['PAY_ONLINE_1']; ?><br />
		<?=$TXT_BAKERY[$payment_method]['SECURITY']; ?><a href="<?=$security_info_url ?>" target="_blank"> &raquo; <?=$TXT_BAKERY[$payment_method]['WEBSITE']; ?></a>.</div>
	</div>
	<div class="grid hide-on-mobiles">
	  <div class="unit whole">
		<ol>
			<li><?=$TXT_BAKERY[$payment_method]['PAY_ONLINE_2']; ?></li>
			<li><?=$TXT_BAKERY[$payment_method]['SECURE']; ?></li>
			<li><?=$TXT_BAKERY[$payment_method]['CONFIRMATION_NOTICE']; ?></li>
			<li><?=$TXT_BAKERY[$payment_method]['SHIPMENT']; ?></li>
		</ol>
	  </div>
	</div>
	<div class="grid">
	  <div class="unit whole" class="mod_bakery_pay_submit_f">
		<input type="submit" name="payment_method[<?=$payment_method ?>]" class="mod_bakery_bt_pay_<?=$payment_method ?>_f" value="<?=$TXT_BAKERY[$payment_method]['PAY']; ?>" onclick="javascript: return checkTaC()" />
	  </div>
	</div>
	<div class="grid">
	  <div class="unit whole"><hr class="mod_bakery_hr_f" /></div>
	</div>
