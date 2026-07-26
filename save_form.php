<?php
/**
  
  Copyright (C) 2007 - 2021, Christoph Marti
Copyleft 2021- Christian M. Stefan, Florian Meerwinck

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License  - version 2 or later, 
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
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$iOrderID = $_SESSION['bxt']['order_id'];
$aErrMsg = []; // collect errors in this Array

// Look for language file
if (LANGUAGE_LOADED) {
    include_once __DIR__ . '/languages/EN.php';
    if (file_exists($sFile = __DIR__ . '/languages/' . LANGUAGE . '.php')) {
        include_once $sFile;
    }
}

// Clean post array
$_POST = array_map('strip_tags', $_POST);
$aFields = bxt_formConfig();
$bUseShipform = false;

$_SESSION['bxt']['send_copy'] = false;
if(isset($_POST['send_copy'])){
    $_SESSION['bxt']['send_copy'] = true;
}
if(isset($_POST['use_shipform']) || $cfg['shipping_form'] == 'always'){
    $bUseShipform = true;
}

// *********************************************
// * Check if all REQUIRED Fields are filled  *
// *********************************************
$aReqBlanks = []; // required blank array
$_SESSION['bxt']['shipform_checked'] = false;
$_SESSION['bxt']['ship_data'] = false;
if ($bUseShipform) {
    $_SESSION['bxt']['shipform_checked'] = true;    
    $_SESSION['bxt']['ship_data'] = true;
}
foreach ($aFields['required_fields'] as $field => $val) {
    if($val == 0) continue;
    if ($bUseShipform == false) {
        // skip ship_ fields if not in use
        if (strpos($field, 'ship_') !== false)
            continue;
    }
    if (!isset($_POST[$field]) || $_POST[$field] == '') {
        if($cfg['use_payment'] == 1 && $field == 'cust_message'){        
            continue;
        }
        // if empty, add to the required blank array
        $aReqBlanks[] = $field;
    }
}

function isReq($sField){
    $aFields = $aFields = bxt_formConfig();
    return $aFields['required_fields'][$sField] == '1';
}



// If blank fields show error message
if (!empty($aReqBlanks)) {
    $form_error = $TXT_BAKERY['ERR_FIELD_BLANK'];
    $aErrorBG = $aReqBlanks;
    extract($_POST);
    include __DIR__ . '/checkout_form.php';
    return;
}

$aFieldsToCheck = [];
foreach ($aFields['show_fields'] as $field => $val) {
    if ($bUseShipform == false) {
        // skip ship_ fields if not in use
        if (strpos($field, 'ship_') !== false)
            continue;
    }
    if($val == 1){ 
        $aFieldsToCheck[] = $field;
    }
}
$aFieldsToCheck[] = 'cust_country';

// If email fields do not match show error message
if (isset($_POST['cust_confirm_email'])) {
    if ($_POST['cust_email'] !== $_POST['cust_confirm_email']) {
        $aErrorBG[] = 'cust_email';
        $aErrorBG[] = 'cust_confirm_email';
        $aErrMsg[] = $TXT_BAKERY['ERR_EMAILS_NOT_MATCHED'];
    }
}


// Add a charset besides of latin to the address form regexp
// Makes use of unicode scripts (see http://www.regular-expressions.info/unicode.html#script)
$us = '';
if (!empty($TXT_BAKERY['ADD_CHARSET'])) {
    switch (strtolower($TXT_BAKERY['ADD_CHARSET'])) {
        case 'cyrillic': $us = '\p{Cyrillic}'; break;
        case 'greek': $us = '\p{Greek}';       break;
        case 'hebrew': $us = '\p{Hebrew}';     break;
        case 'arabic': $us = '\p{Arabic}';     break;
    }
}


foreach ($_POST as $field => $value) {
    if ($field != 'pay_methods') {
        $field = strip_tags($field);
        $value = strip_tags($value);
        if(!in_array($field, $aFieldsToCheck))     continue;
        if(isReq($field) == false && $value == '') continue;
        if (strpos($field, 'company') !== false) {
            if (!preg_match('#^[\p{Latin}' . $us . '0-9.+&\s\- ]{0,50}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_NAME'];
            }
        }
        
        if (strpos($field, 'first_name') !== false) {
            if (!preg_match('#^[\p{Latin}' . $us . '.\s\'\- ]{1,50}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_NAME'];
            }
        }

        if (strpos($field, 'last_name') !== false) {
            if (!preg_match('#^[\p{Latin}' . $us . '\s\'\- ]{1,50}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_NAME'];
            }
        }

        if (strpos($field, 'cust_tax_no') !== false &&
            strpos($setting_tax_group, $setting_shop_country) !== false) {
            $value = trim($value);
            if (!check_vat($value, $setting_tax_group)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_CUST_TAX_NO'];
            }
        }
        if (strpos($field, 'street') !== false) {
            if (strpos($field, 'street_number') !== false) {
                continue;
            }
            if (!preg_match('#^[\p{Latin}' . $us . '0-9.,/\s\- ]{1,50}$#u', $value)) {                
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_STREET'];
            }
        }
        if (strpos($field, 'city') !== false) {
            if (!preg_match('#[\p{Latin}' . $us . '.\s\- ]{1,50}#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_CITY'];
            }
        }

        if (strpos($field, 'state') !== false) {
            if (!preg_match('#^[\p{Latin}' . $us . '0-9.\s\- ]{1,50}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_STATE'];
            }
        }

        if (strpos($field, 'country') !== false) {
            if (!preg_match('#^[A-Z]{2}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_COUNTRY'];
            }
        }

        if (strpos($field, 'email') !== false) {
            if (!preg_match('#^.+@.+\..+$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_EMAIL'];
            }
        }

        if (strpos($field, 'zip') !== false) {
            if (!preg_match('#^[A-Za-z0-9\s\- ]{4,10}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_ZIP'];
            }
        }

        if (strpos($field, 'phone') !== false) {
            if (!preg_match('#^[0-9)(xX+./\s\- ]{7,20}$#u', $value)) {
                $aErrorBG[] = $field;
                $aErrMsg[] = ''.lazyspecial($value, ENT_QUOTES).' '.$TXT_BAKERY['ERR_INVAL_PHONE'];
            }
        }
        
        $$field = strip_tags(trim($database->escapeString($value)));
    }    
}
// If any errors occured show address form again
if (!empty($aErrMsg)) {
    $form_error = '';
    foreach ($aErrMsg as $value) {
        $form_error .= $value . '<br />';
    }
    $form_error .= '<br />' . $TXT_BAKERY['ERR_INVAL_TRY_AGAIN'];
    include __DIR__ . '/checkout_form.php';
    return;
}

$_POST = array_map('bxt_cleanupJsonString', $_POST);

// Loop through post vars and import them into session var and the current symbol table
foreach(['cust', 'ship'] as $type){
    foreach ($aFieldsToCheck as $field => $val) {
        
        $rec = str_replace($type.'_', '', $val);
        if (!isset($_SESSION['bxt'][$type][$rec])){
            $_SESSION['bxt'][$type][$rec] = '';
        }
        if (isset($_POST[$type.'_'.$rec])){
            $_SESSION['bxt'][$type][$rec] = strip_tags($_POST[$type.'_'.$rec]);
        }
    }
}

// Make update string
$aInsert = [];
if (isset($_SESSION['USER_ID'])) {
    $aInsert['user_id'] = $_SESSION['USER_ID'];
}
$aInsert['timestamp'] = time();

$aUpdates = [];
foreach ($_POST as $field => $value) {
     if(in_array($field, $aFieldsToCheck)){
        if (in_array($field, ['cust_first_name', 'cust_last_name', 'cust_email'])) {
             // for REQUEST MODE:
            // these fields will come into the DB table separately aswell
            $sTmp = str_replace('cust_', '', $field);
            $aInsert[$sTmp] = $value;
        }
        
        // shop mode:
        $aUpdates[$field] = $value;
     }     
}

// Update `_customer` table and continue the check out process
$aUpdates['order_id'] = $iOrderID;
if (isset($_SESSION['USER_ID'])) {
    $aUpdates['user_id'] = $_SESSION['USER_ID'];
} 

$aUpdates['json_order'] = json_encode(bxt_cartContents($iOrderID), JSON_HEX_APOS);

if($cfg['use_payment'] == true){
    $database->updateRow(
        '{BXT}_customer', 
        'order_id', 
        $aUpdates
    );    
    if($database->is_error()){
        trigger_error($database->get_error());
    }
}

if($cfg['use_payment'] == false){
    unset($aUpdates['json_order']);
    // Update `_requests` table and go to request completion
    $aInsert['order_id'] = $iOrderID;

    $aInsert['json'] = json_encode($aUpdates, JSON_UNESCAPED_UNICODE);
    //debug_dump($aInsert['json']);
    $database->insertRow("{BXT}_requests", $aInsert);
    if($database->is_error()){
        trigger_error($database->get_error());
    }

    if($database->get_error() == false){
        $_SESSION['bxt']['latest_request_id'] = $database->getLastInsertId();
    }    
    
    // Update _customer Row to set  'submitted' => 'request'
    $database->updateRow(
        '{BXT}_customer', 
        'order_id', 
        array(
            'order_id'   => $iOrderID,
            'submitted'  => 'request',
            'json_order' => json_encode(bxt_cartContents($iOrderID, true), JSON_UNESCAPED_UNICODE),
        )
    );
    if($database->is_error()){
        trigger_error($database->get_error());
        //debug_dump($database->get_error());
    }
    include 'checkout_request_confirmation.php';
    
} else {
    // Update `_customer` table and continue the check out process
    $aUpdates['order_id'] = $iOrderID;
    if (isset($_SESSION['USER_ID'])) {
	$aUpdates['user_id'] = $_SESSION['USER_ID'];
    } 
    
    include 'checkout_payment_methods.php';
     
}
return;