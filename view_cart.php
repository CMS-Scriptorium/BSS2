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


// Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly"); 

// Get some default values
require_once(WB_PATH.'/modules/bakery/config.php');


$aCart = bxt_cartContents($order_id); 


$bShowShipping = $aCart['show_shipping'];
$sCurrency = $cfg['shop_currency'];

// Assign page filename for tracking with Google Analytics _trackPageview() function
global $ga_page;
$ga_page = '/view_cart.php';

// Compose messages
$sSuccessMsg = '';
if (isset($cart_success)) {
    $sSuccessMsg = $TXT_BAKERY['UPDATE_CART_SUCCESS'];
}
$sErrorMsg = '';
if (isset($cart_error) && is_array($cart_error)) {
    foreach ($cart_error as $error) {
        $sErrorMsg .= "<p>".$error."</p>";
    }
}
include __DIR__.'/templates/cart.tpl.php';
?>
<script>var page = 'view_cart.php';</script>