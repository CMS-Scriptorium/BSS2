<?php
//:Displays modul Bakery MiniCart
//:
//:Displays modul Bakery MiniCart
//:[[ModBakeryMiniCart]]
$template = isset($template) ? $template : 'default';
if(!file_exists($sFunc = WB_PATH.'/modules/bakery/droplets/functions.php')){
    return 'Droplet file not found.';   
} else {
    require_once $sFunc;
    return bakeryCart('mini_cart', $template);
}