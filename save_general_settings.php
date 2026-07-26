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


require_once('../../config.php');
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require_once(WB_PATH.'/modules/admin.php');
// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');

require_once __DIR__.'/functions.php';
// Get current general settings
$cfg = bxt_getGlobalCfg();

$reload = $_POST['reload'] == 'true' ? true : false;
if(isset($_POST['save'])) $reload = true;

// Remove any tags and add slashes to POST data
$shop_name       = $database->escapeString(strip_tags($_POST['shop_name']));
$shop_email      = $database->escapeString(strip_tags($_POST['shop_email']));
$pages_directory = $database->escapeString(strip_tags($_POST['pages_directory']));
$tac_url         = $database->escapeString(strip_tags($_POST['tac_url']));
$cancellation_url= $database->escapeString(strip_tags($_POST['cancellation_url']));
$privacy_url     = $database->escapeString(strip_tags($_POST['privacy_url']));
$shop_country    = $database->escapeString(strip_tags($_POST['shop_country']));
$shop_state      = isset($_POST['shop_state']) ? $database->escapeString(strip_tags($_POST['shop_state'])) : '';
$shipping_form   = $database->escapeString(strip_tags($_POST['shipping_form']));
$use_payment     = $database->escapeString(strip_tags($_POST['use_payment']));
$no_revocation   = isset($_POST['no_revocation']) ? 'e-goods' : 'none';

$display_settings    = isset($_POST['display_settings'])    ? 1 : 0;
$skip_cart           = isset($_POST['skip_cart'])           ? 'yes' : 'no';
$out_of_stock_orders = isset($_POST['out_of_stock_orders']) ? 1 : 0;
$use_captcha         = isset($_POST['use_captcha'])         ? 'yes' : 'no';

$definable_field_0 = $database->escapeString(strip_tags($_POST['definable_field_0']));
$definable_field_1 = $database->escapeString(strip_tags($_POST['definable_field_1']));
$definable_field_2 = $database->escapeString(strip_tags($_POST['definable_field_2']));
$stock_mode        = $database->escapeString(strip_tags($_POST['stock_mode']));
$stock_limit       = $database->escapeString(strip_tags($_POST['stock_limit']));

$shop_currency = $database->escapeString(strip_tags($_POST['shop_currency']));
$dec_point     = $database->escapeString(strip_tags($_POST['dec_point']));
$thousands_sep = $database->escapeString(strip_tags($_POST['thousands_sep']));
$tax_rate      = $database->escapeString(strip_tags($_POST['tax_rate']));
$tax_rate1     = $database->escapeString(strip_tags($_POST['tax_rate1']));
$tax_rate2     = $database->escapeString(strip_tags($_POST['tax_rate2']));
$tax_group     = $database->escapeString(strip_tags($_POST['tax_group']));
$tax_included  = isset($_POST['tax_included']) ? 'included' : 'excluded';
$tax_by        = $database->escapeString(strip_tags($_POST['tax_by']));

$tax_rate_shipping = $database->escapeString(strip_tags($_POST['tax_rate_shipping']));
$free_shipping     = $database->escapeString(strip_tags($_POST['free_shipping']));
$free_shipping_msg = isset($_POST['free_shipping_msg']) ? 'show' : 'hide';
$shipping_method   = $database->escapeString(strip_tags($_POST['shipping_method']));
$shipping_domestic = $database->escapeString(strip_tags($_POST['shipping_domestic']));
$shipping_abroad   = $database->escapeString(strip_tags($_POST['shipping_abroad']));
$shipping_zone     = $database->escapeString(strip_tags($_POST['shipping_zone']));
$zone_countries    = isset($_POST['zone_countries']) ? implode(',', $_POST['zone_countries']) : '';

// overwrite some fields based on disabled array        
$aDisabled = bxt_formDisabledArray($use_payment);
foreach($aDisabled as $rec){
    $_POST['show_fields'][$rec] = 1;
    $_POST['required_fields'][$rec] = 1;
}

foreach($_POST['required_fields'] as $key=>$val){
    if(!isset($_POST['show_fields'][$key])){
        // remove all required fields if corresponding show_fields entry is not set
        unset($_POST['required_fields'][$key]); 
    }
}

$aFormConfig = array(
    'show_fields'     => $_POST['show_fields'], 
    'required_fields' => $_POST['required_fields'],
    'option'          => $_POST['option'],
);
$jsonFormConfig = (json_encode($aFormConfig));

$company_field = isset($_POST['show_fields']['cust_company']) ? 'show' : 'hide';
$tax_no_field  = isset($_POST['show_fields']['cust_tax_no'])  ? 'show' : 'hide';
$state_field   = isset($_POST['show_fields']['cust_state'])   ? 'show' : 'hide';
$hide_country  = isset($_POST['show_fields']['cust_country']) ? 'hide' : 'show';
$cust_msg      = isset($_POST['show_fields']['cust_message']) ? 'show' : 'hide';  
$zip_location  = isset($_POST['option']['zip_location'])      ? 'end'  : 'inside';

// Clean out protocol names if added to the shop name
// to prevent problems with the php mail() function
$shop_name = str_replace('http://',  '', $shop_name);
$shop_name = str_replace('https://', '', $shop_name);

// If no state file exists for the selected country...
if (!file_exists(WB_PATH.'/modules/bakery/languages/states/'.$shop_country.'.php')) {
	// ...set shop state to blank
	$shop_state = '';
	// ...change tax by from state to country
	if ($tax_by == 'state') $tax_by = 'country';
}

// If a tax rate setting has changed => update items tax rate 
if ($cfg['tax_rate'] != $tax_rate) {
    $database->query("UPDATE {BXT}_items SET tax_rate = $tax_rate WHERE tax_rate = {$cfg['tax_rate']}");
}
if ($cfg['tax_rate1'] != $tax_rate1) {
    $database->query("UPDATE {BXT}_items SET tax_rate = $tax_rate1 WHERE tax_rate = {$cfg['tax_rate1']}");
}
if ($cfg['tax_rate2'] != $tax_rate2) {
    $database->query("UPDATE {BXT}_items SET tax_rate = $tax_rate2 WHERE tax_rate = {$cfg['tax_rate2']}");
}

// Rename Bakery pages directory
// Old and new directory pathes
$old_pages_dir = WB_PATH.PAGES_DIRECTORY.'/'.$cfg['pages_directory'].'/';
$new_pages_dir = WB_PATH.PAGES_DIRECTORY.'/'.$pages_directory.'/';

// Make sure the old directory exists
make_dir($old_pages_dir);

// Rename if the pages directory has changed
if ($cfg['pages_directory'] != $pages_directory) {
    // Check if the pages directory name does not exist yet
    if (is_dir($new_pages_dir)) {
            $admin->print_error($MESSAGE['MEDIA']['DIR_EXISTS'], WB_URL.'/modules/bakery/modify_general_settings.php?page_id='.$page_id.'&section_id='.$section_id);
    }
    // Rename directory
    if (rename($old_pages_dir, $new_pages_dir)) {
        // Update item links
        $database->query("UPDATE {BXT}_items SET link = REPLACE(link, '/{$cfg['pages_directory']}/', '/$pages_directory/')");
    }
    else {
        $admin->print_error($MESSAGE['MEDIA']['CANNOT_RENAME'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }	
}

// Update general settings
    $aUpdate = array(
        'shop_id' => 0,
        'shop_name' => $shop_name,
        'shop_email' => $shop_email,
        'pages_directory' => $pages_directory,
        'tac_url' => $tac_url,
        'cancellation_url' => $cancellation_url,
        'privacy_url' => $privacy_url,
        'shop_country' => $shop_country,
        'shop_state' => $shop_state,
        'shipping_form' => $shipping_form,
        'company_field' => $company_field,
        'state_field' => $state_field,
        'tax_no_field' => $tax_no_field,
        'tax_group' => $tax_group,
        'zip_location' => $zip_location,
        'no_revocation' => $no_revocation,
        'hide_country' => $hide_country,
        'cust_msg' => $cust_msg, 
        'display_settings' => $display_settings,
        'skip_cart' => $skip_cart,
        'out_of_stock_orders' => $out_of_stock_orders,
        'use_captcha' => $use_captcha,
        'definable_field_0' => $definable_field_0,
        'definable_field_1' => $definable_field_1,
        'definable_field_2' => $definable_field_2,
        'stock_mode' => $stock_mode,
        'stock_limit' => $stock_limit,
        'shop_currency' => $shop_currency,
        'dec_point' => $dec_point,
        'thousands_sep' => $thousands_sep,
        'tax_rate' => $tax_rate,
        'tax_rate1' => $tax_rate1,
        'tax_rate2' => $tax_rate2,
        'tax_included' => $tax_included,
        'tax_by' => $tax_by,
        'tax_rate_shipping' => $tax_rate_shipping,
        'free_shipping' => $free_shipping,
        'free_shipping_msg' => $free_shipping_msg,
        'shipping_method' => $shipping_method,
        'shipping_domestic' => $shipping_domestic,
        'shipping_abroad' => $shipping_abroad,
        'shipping_zone' => $shipping_zone,
        'zone_countries' => $zone_countries, 
        'use_payment' => $use_payment,
        'form_config' => $jsonFormConfig
    );
    $database->updateRow("{BXT}_general_settings",  "shop_id", $aUpdate);

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error().' '.__FILE__.' ('.__LINE__.')', ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
    // If a country has been selected go back to the general settings page
    if ($reload) {
        $admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_general_settings.php?page_id='.$page_id.'&section_id='.$section_id);
    } else {
        $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
}

// Print admin footer
$admin->print_footer();