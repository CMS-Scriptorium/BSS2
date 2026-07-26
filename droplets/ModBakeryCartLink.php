<?php
//:Adds a link to your modul Bakery cart.
//:
//:Displays modul Bakery MiniCart
//:[[ModBakeryMiniCart]]
$template = isset($template) ? $template : 'default';
if(!file_exists($sFunc = WB_PATH.'/modules/bakery/droplets/functions.php')){
    return 'Droplet file not found.';   
} else {
    require_once $sFunc;
    return bakeryCart('cart_link', $template);
}