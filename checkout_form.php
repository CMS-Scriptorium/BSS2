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

// Include country file depending on the language
if (LANGUAGE_LOADED) {
    if (file_exists($sFile = __DIR__.'/languages/countries/' . LANGUAGE . '.php')) {        
        require_once $sFile;
    }
} else {
    require_once(__DIR__.'/languages/countries/EN.php');
}

// Include state file depending on the shop country
$select_shop_country = '';
$use_states = false;
if (file_exists($sFile = __DIR__.'/languages/states/' . $setting_shop_country . '.php')) {
    require_once($sFile);
    $select_shop_country = $setting_shop_country;
    $use_states = true;
}

// GET CUSTOMER DATA TO PREPOPULATE THE TEXT FIELDS
// ************************************************
// Arrays for all forms and fields

$forms = array('cust', 'ship');
$fields = array(
    'company', 
    'first_name', 
    'last_name', 
    'tax_no', 
    'street', 
    'street_number', 
    'address_addition', 
    'city', 
    'state', 
    'country', 
    'zip', 
    'email', 
    'confirm_email', 
    'phone', 
    'mobile', 
    'message'
);

$aData = array();
$aErrors = array();
// Get customer data and use session var to store it
foreach ($forms as $form) {
    foreach ($fields as $field) {
        $field_var = $form . '_' . $field;
        // Set session var with customer data if not set yet
        if (!isset($_SESSION['bxt'][$form][$field]))
            $_SESSION['bxt'][$form][$field] = '';
        if (!empty($_POST[$field_var]))
            $_SESSION['bxt'][$form][$field] = strip_tags($_POST[$field_var]);
        // Make vars like $cust_company, $cust_first_name,... and $ship_company, $ship_first_name,...
        $$field_var = $_SESSION['bxt'][$form][$field];
        $aData[$field_var] = $$field_var;
    }
}


// For logged in user try to get customer data of a previous order from the db...
if(isset($_SESSION['USER_ID']) ){
    if (        
            empty($aData['cust_first_name']) 
            && empty($aData['cust_last_name']) 
            && empty($aData['cust_street']) 
            && empty($aData['cust_city']) 
            && empty($aData['cust_state']) 
            && empty($aData['cust_zip']) 
            && empty($aData['cust_email']) 
            && empty($aData['cust_phone']) 
        ) {

        $aRetrieveFromDb = array(
            'cust_company', 
            'cust_first_name', 
            'cust_last_name', 
            'cust_tax_no', 
            'cust_street', 
            'cust_street_number', 
            'cust_city', 
            'cust_state', 
            'cust_country', 
            'cust_zip', 
            'cust_email', 
            'cust_phone', 
            'cust_mobile', 
            'ship_company', 
            'ship_first_name', 
            'ship_last_name', 
            'ship_street', 
            'ship_street_number', 
            'ship_city', 
            'ship_state', 
            'ship_country', 
            'ship_zip',
            'ship_phone', 
            'ship_mobile',
        );
        $sSQL = "SELECT ".(implode(', ', $aRetrieveFromDb))." 
                    FROM {BXT}_customer 
                        WHERE user_id = '".$_SESSION['USER_ID']."' 
                        ORDER BY order_id DESC 
                        LIMIT 1";
        if($aDataFromDb = $database->get_array($sSQL)){
            foreach($aDataFromDb[0] as $key=>$val)
                $aData[$key] = $val;
        }
    }
}

// If no country has been selected, preselect the shop country
if (!isset($aData['cust_country']) || $aData['cust_country'] == '') {
    $aData['cust_country'] = $setting_shop_country;
}
if (!isset($aData['ship_country']) || $aData['ship_country'] == '' && $cfg['shipping_form'] != 'none') {
    $aData['ship_country'] = $setting_shop_country;
}
// If no state is selected, preselect the shop state
if (!isset($aData['cust_state']) || $aData['cust_state'] == '') {
    $aData['cust_state'] = $setting_shop_state;
}
if (!isset($aData['ship_state']) || $aData['ship_state'] == '' && $cfg['shipping_form'] != 'none') {
    $aData['ship_state'] = $setting_shop_state;
}


// Assign page filename for tracking with Google Analytics _trackPageview() function
global $ga_page;
$ga_page = '/checkout_form.php';

// CUSTOMER ADDRESS FORM ONLY
// **************************
// Concatenate tax no and optional
$TXT_BAKERY['CUST_TAX_NO'] = $TXT_BAKERY['CUST_TAX_NO'] . ' (' . $TXT_BAKERY['OPTIONAL'] . ')';

// Initialize vars
$cust_country_options = '';
$ship_country_options = '';
$cust_state_options   = '';
$ship_state_options   = '';

// Loop through all fields and generate the form
foreach ($fields as $field) {

    $field = 'cust_'.$field;
    // Generate country dropdown menu...
    if ($field == "cust_country") {
        // Prepare cust country options
        for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
            $country = $TXT_BAKERY['COUNTRY_NAME'][$n];
            $sCC = $TXT_BAKERY['COUNTRY_CODE'][$n];
            $sSelected = ($sCC == @$_POST['cust_country'] || $sCC == @$aData['cust_country']) ? ' selected' : '';
            $cust_country_options .= "\n\t\t\t".'<option value="'.$sCC.'"'.$sSelected.'>'.$country.'</option>';
        }
    } else {
        // Generate state dropdown menu...
        if ($use_states && $field == 'cust_state') {
            // Prepare cust state options
            for ($n = 1; $n <= count($TXT_BAKERY['STATE_NAME']); $n++) {
                $state = $TXT_BAKERY['STATE_NAME'][$n];
                $state_code = $TXT_BAKERY['STATE_CODE'][$n];
                $selected_state = ($state_code == @$_POST['cust_state'] || $state_code == @$cust_state) ? ' selected="selected"' : '';
                $cust_state_options .= "\n\t\t\t<option value='$state_code'$selected_state>$state</option>";
            }
        }
        // Generate all other fields
        // Add css class (red background) if the textfield is blank or incorrect
        $css_error_class = isset($aErrorBG) && in_array($field, $aErrorBG) ? 'mod_bakery_errorbg_f ' : '';
        // Show cust textfields block using template file
        $aErrors[$field] = $css_error_class;
    }
}


// CHECK IF WE HAVE TO SHOW THE SHIP FORM
// **************************************
$show_add_shipment_button = false;
$toggle_shipform_checked = false;
if ($cfg['shipping_form'] == 'hideable' || $cfg['shipping_form'] == 'always') {
    $toggle_shipform_checked = true;
}
if(isset($_SESSION['bxt']['shipform_checked'])){
    $toggle_shipform_checked = $_SESSION['bxt']['shipform_checked'];
} 
if($cfg['shipping_form'] == 'always'){
    $toggle_shipform_checked = true;
}

// Initialize session var ship_form depending on general settings
if (!isset($_SESSION['bxt']['ship_form'])) {
    $_SESSION['bxt']['ship_form'] = null;
    if ($cfg['shipping_form'] == 'request') {
        $_SESSION['bxt']['ship_form'] = false;
    } elseif ($cfg['shipping_form'] == 'hideable' || $cfg['shipping_form'] == 'always') {
        $_SESSION['bxt']['ship_form'] = true;
    }
}

// Toogle session var depending on address form buttons "add" or "hide ship form"
if (!empty($_POST['add_ship_form'])) {
    $_SESSION['bxt']['ship_form'] = true;
} elseif (!empty($_POST['hide_ship_form'])) {
    $_SESSION['bxt']['ship_form'] = false;
}

// Check if we have to show ship form
$show_ship_form = false;
if ($cfg['shipping_form'] != 'none') {
    if ($cfg['shipping_form'] == 'request' && $_SESSION['bxt']['ship_form']) {
        $show_ship_form = true;
    }
    if ($cfg['shipping_form'] == 'hideable' && $_SESSION['bxt']['ship_form']) {
        $show_ship_form = true;
    }
    if ($cfg['shipping_form'] == 'always') {
        $show_ship_form = true;
    }
    // we will use JS to toggle shipping form 
    $show_ship_form = true;
}

$sendButtonTxt =  $TXT_BAKERY['SEND_REQUEST'];
if($cfg['use_payment']){
    $sendButtonTxt =  $TXT_BAKERY['SELECT_PAYMENT_METHOD'];
}
$sFormActionURL = $setting_continue_url;

// ADD SHIPPING ADDRESS FORM IF REQUIRED
// *************************************

if ($show_ship_form) {

    $_SESSION['bxt']['ship_data'] = true;
    // Loop through all fields and generate the shipping address form
    foreach ($fields as $field) {
        $field = 'ship_'.$field;
        // Generate country dropdown menu...
        if ($field == 'ship_country') {
            // Prepare ship country options
            for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
                $country = $TXT_BAKERY['COUNTRY_NAME'][$n];
                $sCC = $TXT_BAKERY['COUNTRY_CODE'][$n];
                $sSelected = ($sCC == @$_POST['ship_country'] || $sCC == @$aData['ship_country']) ? ' selected="selected"' : '';
                $ship_country_options .= "\n\t\t\t<option value='$sCC'$sSelected>$country</option>";
            }
        } else {
            // Generate state dropdown menu...
            if ($use_states && $field == 'ship_state') {
                // Prepare ship state options
                for ($n = 1; $n <= count($TXT_BAKERY['STATE_NAME']); $n++) {
                    $state = $TXT_BAKERY['STATE_NAME'][$n];
                    $state_code = $TXT_BAKERY['STATE_CODE'][$n];
                    $selected_state = ($state_code == @$_POST['cust_state'] || $state_code == @$ship_state) ? ' selected="selected"' : '';
                    $ship_state_options .= "\n\t\t\t<option value='$state_code'$selected_state>$state</option>";
                }
            }

            // Generate all other fields
            // Add css class (red background) if the textfield is blank or incorrect
            $css_error_class = isset($aErrorBG) && in_array($field, $aErrorBG) ? 'mod_bakery_errorbg_f ' : '';
            $aErrors[$field] = $css_error_class;
        }
    }
} else {
    // Delete ship data if ship form has not been completed
    unset($_SESSION['bxt']['ship']);
    $_SESSION['bxt']['ship_data'] = false;
}

// INCLUDE FORM TEMPLATE
include __DIR__ . '/templates/checkout_form.tpl.php';
?><script>var page = 'checkout_form.php';</script>