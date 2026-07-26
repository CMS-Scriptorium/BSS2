<?php

/*
  
  Copyright (C) 2007 - 2021, Christoph Marti
Copyleft 2021- Christian M. Stefan, Florian Meerwinck

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


//Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly"); 

// Look for language File
if (LANGUAGE_LOADED) {
    require_once __DIR__.'/languages/EN.php';
    if (file_exists($sFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
        require_once $sFile;
    }
}


// Set default values for page settings

// Shop
$aInsert = [];
$aInsert['page_id']      = $page_id;
$aInsert['section_id']   = $section_id;
$aInsert['page_offline'] = 'no';
$aInsert['offline_text'] = $TXT_BAKERY['ERR_OFFLINE_TEXT'];
$aInsert['continue_url'] = $page_id;

// Layout
$aInsert['header'] = $database->escapeString('<div class="mod_bakery_main_div_cart_bt_f">
<form action="[SHOP_URL]" method="post">
<input type="submit" name="view_cart" class="mod_bakery_bt_cart_f" value="[VIEW_CART]" />
</form>
</div>
<div class="wrap">
<div class="grid">
');
$aInsert['item_loop'] = $database->escapeString('<div class="unit half mod_bakery_main_td_f">
[THUMB]
<br />
<a href="[LINK]"><span class="mod_bakery_main_title_f">[TITLE]</span></a>
<br />
[DESCRIPTION]
<br />
[TXT_PRICE]: [CURRENCY] [PRICE]
<br />
<div[DISPLAY:STOCK]>
[TXT_STOCK]: [STOCK]
</div>
<br />
<form action="[SHOP_URL]" method="post">
[OPTION]
<br />
<input type="number" name="item[ITEM_ID]" class="mod_bakery_main_input_f" value="1" size="2" />
<input type="submit" name="add_to_cart" class="mod_bakery_bt_add_f" value="[ADD_TO_CART]" />
</form>
</div>');
$aInsert['footer'] = $database->escapeString('</div><br clear="all">
</div>
<div class="wrap" style="display: [DISPLAY_PREVIOUS_NEXT_LINKS]">
<div class="grid">
<div class="unit one-third">[PREVIOUS_PAGE_LINK]</div>
<div class="unit one-third align-center">[TXT_ITEM] [OF] </div>
<div class="unit one-third align-right">[NEXT_PAGE_LINK]</div>
</div>
</div>');
$aInsert['item_header'] = $database->escapeString('<div class="wrap">');
$aInsert['item_footer'] = $database->escapeString('[IMAGE]
<form action="[SHOP_URL]" method="post">
<div class="grid">
<div class="unit whole"><h2 class="mod_bakery_item_title_f">[TITLE]</h2></div>
</div>
<div class="grid">
<div class="unit one-fifth"><span class="mod_bakery_item_sku_f">[TXT_SKU]:</span></div>
<div class="unit four-fifths">[SKU]</div>
</div>
<div class="grid">
<div class="unit one-fifth"><span class="mod_bakery_item_price_f">[TXT_PRICE]:</span></div>
<div class="unit four-fifths">[CURRENCY] [PRICE]</div>
</div>
<div class="grid">
<div class="unit one-fifth"><span class="mod_bakery_item_shipping_f">[TXT_SHIPPING]:</span></div>
<div class="unit four-fifths">[CURRENCY] [SHIPPING] </div>
</div>
<div class="grid"[DISPLAY:STOCK]>
<div class="unit one-fifth"><span class="mod_bakery_item_stock_f">[TXT_STOCK]:</span></div>
<div class="unit four-fifths">[STOCK]</div>
</div>
<div class="grid">   	    
<div class="unit one-fifth"><span class="mod_bakery_item_full_desc_f"><p>[TXT_FULL_DESC]:</p></span></div>
<div class="unit four-fifths">[FULL_DESC]</div>
</div>
<div class="grid">   	    
<div class="unit one-fifth"><span class="mod_bakery_shipping_cost_f">[TXT_SHIPPING_COST]:</span></div>
<div class="unit four-fifths">
[TXT_DOMESTIC]: [CURRENCY] [SHIPPING_DOMESTIC]<br />
[TXT_ABROAD]: [CURRENCY] [SHIPPING_ABROAD]</div>
</div>
<div class="grid">   	  
<div class="unit one-fifth"> </div>
<div class="unit four-fifths">
[OPTION]
</div>
</div>
<div class="grid">   	  
<div class="unit one-fifth"> </div>
<div class="unit four-fifths">
<input type="number" name="item[ITEM_ID]"  class="mod_bakery_item_input_f" value="1" size="2" />
<input type="submit" name="add_to_cart" class="mod_bakery_bt_add_f" value="[ADD_TO_CART]" />
</div>
</div>
<div class="grid">
<div class="unit one-third">[PREVIOUS]</div>
<div class="unit one-third align-center"><a href="[BACK]">[TXT_BACK]</a> </div>
<div class="unit one-third align-right"> [NEXT]</div>
</div>
</form>
</div>
');

// Insert default values into table page_settings 
$database->insertRow("{BXT}_page_settings", $aInsert);