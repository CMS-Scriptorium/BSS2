<?php

/*
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (C) 2007 - 2021, Christoph Marti
Copyleft 2021- Christian M. Stefan, Florian Meerwinck

  LICENSE TERMS:
  Please read the software license agreement included in this package
  carefully before using the software. By installing and using the software,
  your are agreeing to be bound by the terms of the software license.
  If you do not agree to the terms of the license, do not use the software.
  Using any part of the software indicates that you accept these terms.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


require '../../config.php';


// Validate the GET values
if (!isset($_GET['page_id']) OR !isset($_GET['section_id']) OR !isset($_GET['order_id']) OR !is_numeric($_GET['page_id']) OR !is_numeric($_GET['section_id']) OR !is_numeric($_GET['order_id'])) {
    header("Location: ".ADMIN_URL."/pages/index.php");
} else {
    $page_id    = $_GET['page_id'];
    $section_id = $_GET['section_id'];
    $order_id   = $_GET['order_id'];
}

// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';
require __DIR__.'/functions.php';

// Look for language file
if (LANGUAGE_LOADED) {
    require_once(__DIR__.'/languages/EN.php');
    if (file_exists(__DIR__.'/languages/'.LANGUAGE.'.php')) {
        require_once(__DIR__.'/languages/'.LANGUAGE.'.php');
    }
}

// Look for country file
if (LANGUAGE_LOADED) {
    if (file_exists(__DIR__.'/languages/countries/'.LANGUAGE.'.php')) {
        require_once(__DIR__.'/languages/countries/'.LANGUAGE.'.php');
    }
}
else {
    require_once(__DIR__.'/languages/countries/EN.php');
}
$cfg = bxt_getGlobalCfg();

$sModuleURL = get_url_from_path(__DIR__);
I::insertCssFile($sModuleURL.'/gridism.css');
$sFormActionURL = $sModuleURL.'/save_order.php';
$sFormCancelURL = $sModuleURL.'/modify_orders.php?page_id='.$page_id;
$sendButtonTxt = $TEXT['SAVE'];
$select_shop_country = '';

if($cfg['use_payment']){
    $show_ship_form = true;
}
// Get customer data from DB customer table
$aOrder = $database->get_array(
    "SELECT * FROM {BXT}_customer WHERE order_id = '$order_id'"
)[0];
if (is_array($aOrder)) {    
    $data = array_map('lazystrip', $aOrder);
    // Import variables from the returned array into the current symbol table
    $data = array_map('lazyspecial', $data);
    extract($data);
    // Make human readable form of the order date
    $data['order_date'] = bxt_correctDate($data['order_date']);  
    
    // CUSTOM and SHIO Country Options 
    $cust_country_options = $ship_country_options = '';
    for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
        $country      = $TXT_BAKERY['COUNTRY_NAME'][$n];
        $country_code = $TXT_BAKERY['COUNTRY_CODE'][$n];
        $cust_country_options .= '<option value="'.$country_code.'"';
        $ship_country_options .= '<option value="'.$country_code.'"';
        if ($country_code == $data['cust_country']) {
            $cust_country_options .= ' selected';
        }
        if ($country_code == $ship_country) {
            $ship_country_options .= ' selected';
        }
        $cust_country_options .= '>'.$country.'</option>'."\n";
        $ship_country_options .= '>'.$country.'</option>'."\n";
    }
    
    // CUSTOM and SHIP State/Bundesland Options     
    $cust_state_options = $ship_state_options = '';
    if (file_exists($sFile = __DIR__.'/languages/states/'.$data['cust_country'].'.php')) {
        require $sFile;
        
        for ($n = 1; $n <= count($TXT_BAKERY['STATE_NAME']); $n++) {
            $state      = $TXT_BAKERY['STATE_NAME'][$n];
            $state_code = $TXT_BAKERY['STATE_CODE'][$n];
            $cust_state_options .= '<option value="'.$state_code.'"';
            $ship_state_options .= '<option value="'.$state_code.'"';
            if ($state_code == $data['cust_state']) {
                    $cust_state_options .= ' selected';
            }
            if ($state_code == $ship_state) {
                    $ship_state_options .= ' selected';
            }
            $cust_state_options .= '>'.$state.'</option>'."\n";
            $ship_state_options .= '>'.$state.'</option>'."\n";
        }        
    }
?>


<h2><?=$TXT_BAKERY['EDIT_ORDER'].' '.$TEXT['OF'].' '.$cust_first_name.' '.$cust_last_name?></h2>
<p><strong><?=$TXT_BAKERY['ORDER']?></strong></p>
<p>
    <?=$TXT_BAKERY['ORDER_ID']?>: <b><?=$data['order_id']?></b><br>
    <?=$TXT_BAKERY['INVOICE_ID']?>: <b><?=$data['invoice_id']?></b><br> 
     <?=$TXT_BAKERY['ORDER_DATE']?>: <b><?=$data['order_date']?></b>
</p>
<div style="width:95%">
<?php
include(__DIR__.'/templates/checkout_form.tpl.php');
?>
</div>
<!--
<form name="modify" action="<?=$sFormActionURL?>" method="post">
    <input type="hidden" name="page_id" value="<?=$page_id?>" />
    <input type="hidden" name="section_id" value="<?=$section_id?>" />
    <input type="hidden" name="order_id" value="<?=$data['order_id']?>" />
    <table cellpadding="2" cellspacing="0" border="0" align="center" width="98%">
    <tr>
        <td width="25%" align="right"><?=$TXT_BAKERY['ORDER_DATE']?>:</td>
        <td colspan="4"><?=$data['order_date']?></td>
    </tr>
    <tr valign="bottom">
        <td width="25%" height="32" align="right"><strong><?=$TXT_BAKERY['ADDRESS']?>:</strong></td>
        <td height="32" colspan="4">&nbsp;</td>
    </tr>
    <?php
    // Make array for the customer address form
    if ($cfg['zip_location'] == 'end') {
        // Show zip at the end of address
        $cust_info = array('cust_email' => $TXT_BAKERY['CUST_EMAIL'], 'cust_company' => $TXT_BAKERY['CUST_COMPANY'], 'cust_first_name' => $TXT_BAKERY['CUST_FIRST_NAME'], 'cust_last_name' => $TXT_BAKERY['CUST_LAST_NAME'], 'cust_tax_no' => $TXT_BAKERY['CUST_TAX_NO'], 'cust_street' => $TXT_BAKERY['CUST_ADDRESS'], 'cust_city' => $TXT_BAKERY['CUST_CITY'], 'cust_state' => $TXT_BAKERY['CUST_STATE'], 'cust_zip' => $TXT_BAKERY['CUST_ZIP'], 'cust_country' => $TXT_BAKERY['CUST_COUNTRY'], 'cust_phone' => $TXT_BAKERY['CUST_PHONE']);
        $length = array('cust_email' => '50', 'cust_confirm_email' => '50', 'cust_company' => '50', 'cust_first_name' => '50', 'cust_last_name' => '50', 'cust_tax_no' => '20', 'cust_street' => '50', 'cust_zip' => '10', 'cust_city' => '50', 'cust_state' => '50', 'cust_phone' => '20');
    } else {
        // Show zip inside of address
        $cust_info = array('cust_email' => $TXT_BAKERY['CUST_EMAIL'], 'cust_company' => $TXT_BAKERY['CUST_COMPANY'], 'cust_first_name' => $TXT_BAKERY['CUST_FIRST_NAME'], 'cust_last_name' => $TXT_BAKERY['CUST_LAST_NAME'], 'cust_tax_no' => $TXT_BAKERY['CUST_TAX_NO'], 'cust_street' => $TXT_BAKERY['CUST_ADDRESS'], 'cust_zip' => $TXT_BAKERY['CUST_ZIP'], 'cust_city' => $TXT_BAKERY['CUST_CITY'], 'cust_state' => $TXT_BAKERY['CUST_STATE'], 'cust_country' => $TXT_BAKERY['CUST_COUNTRY'], 'cust_phone' => $TXT_BAKERY['CUST_PHONE']);
        $length = array('cust_email' => '50', 'cust_confirm_email' => '50', 'cust_company' => '50', 'cust_first_name' => '50', 'cust_last_name' => '50', 'cust_tax_no' => '20', 'cust_street' => '50', 'cust_zip' => '10', 'cust_city' => '50', 'cust_state' => '50', 'cust_phone' => '20');
    }   
    
    // Make the form
    foreach ($cust_info as $field => $value) {
        // The customer state
        if ($field == 'cust_state') {

            // Hide state field
            if ($cfg['state_field'] == 'hide') {
                continue;
            }

            // Include state file depending on selected customer country
            if (file_exists(__DIR__.'/languages/states/'.$data['cust_country'].'.php')) {
                require(__DIR__.'/languages/states/'.$data['cust_country'].'.php');

                // State dropdown menu
                echo '<tr><td width="25%" align="right">'.$TXT_BAKERY['CUST_STATE'].':</td><td colspan="4"><select name="cust_state" style="width: 98%">';
                echo '<option value="">'.$TEXT['PLEASE_SELECT'].'&hellip;</option>';
                for ($n = 1; $n <= count($TXT_BAKERY['STATE_NAME']); $n++) {
                    $state      = $TXT_BAKERY['STATE_NAME'][$n];
                    $state_code = $TXT_BAKERY['STATE_CODE'][$n];
                    echo '<option value="'.$state_code.'"';
                    if ($state_code == $data['cust_state']) {
                            echo ' selected="selected"';
                    }
                    echo '>'.$state.'</option>'."\n";
                }
                echo '</select></td></tr>'."\n";
                unset($TXT_BAKERY['STATE_NAME'], $TXT_BAKERY['STATE_CODE']);
            }
        }

        // The customer country
        elseif ($field == 'cust_country') {
            echo '<tr><td width="25%" align="right">'.$TXT_BAKERY['CUST_COUNTRY'].':</td><td colspan="4">'
                    . '<select name="cust_country" style="width: 98%">';
            for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
                $country      = $TXT_BAKERY['COUNTRY_NAME'][$n];
                $country_code = $TXT_BAKERY['COUNTRY_CODE'][$n];
                echo '<option value="'.$country_code.'"';
                if ($country_code == $data['cust_country']) {
                    echo ' selected="selected"';
                }
                echo '>'.$country.'</option>'."\n";
            }
            echo '</select></td></tr>'."\n";
        }

        // And the others customer textfields
        else {
            echo '<tr><td width="25%" align="right">'.$value.':</td>
            <td colspan="4"><input type="text" style="width: 98%" name="'.$field.'" value="'.lazyspecial(@$$field, ENT_QUOTES).'" maxlength="'.$length[$field].'" /></td></tr>'."\n";
        }
    }
?>

    <tr valign="bottom">
        <td width="25%" height="32" align="right"><strong><?=$TXT_BAKERY['SHIP_ADDRESS']?>:</strong></td>
        <td height="32" colspan="4">&nbsp;</td>
    </tr>
    <?php
    // Make array for the shipping address form
    if ($cfg['zip_location'] == 'end') {
        // Show zip at the end of address
        $ship_info = array('ship_company' => $TXT_BAKERY['CUST_COMPANY'], 'ship_first_name' => $TXT_BAKERY['CUST_FIRST_NAME'], 'ship_last_name' => $TXT_BAKERY['CUST_LAST_NAME'], 'ship_street' => $TXT_BAKERY['CUST_ADDRESS'], 'ship_city' => $TXT_BAKERY['CUST_CITY'], 'ship_state' => $TXT_BAKERY['CUST_STATE'], 'ship_zip' => $TXT_BAKERY['CUST_ZIP'], 'ship_country' => $TXT_BAKERY['CUST_COUNTRY']);
        $length = array('ship_company' => '50', 'ship_first_name' => '50', 'ship_last_name' => '50', 'ship_street' => '50', 'ship_zip' => '10', 'ship_city' => '50', 'ship_state' => '50');
    } else {
        // Show zip inside of address
        $ship_info = array('ship_company' => $TXT_BAKERY['CUST_COMPANY'], 'ship_first_name' => $TXT_BAKERY['CUST_FIRST_NAME'], 'ship_last_name' => $TXT_BAKERY['CUST_LAST_NAME'], 'ship_street' => $TXT_BAKERY['CUST_ADDRESS'], 'ship_zip' => $TXT_BAKERY['CUST_ZIP'], 'ship_city' => $TXT_BAKERY['CUST_CITY'], 'ship_state' => $TXT_BAKERY['CUST_STATE'], 'ship_country' => $TXT_BAKERY['CUST_COUNTRY']);
        $length = array('ship_company' => '50', 'ship_first_name' => '50', 'ship_last_name' => '50', 'ship_street' => '50', 'ship_zip' => '10', 'ship_city' => '50', 'ship_state' => '50');
    }


    // Make the shipping address form
    foreach ($ship_info as $field => $value) {

        // The shipping state
        if ($field == 'ship_state') {

            // Hide state field
            if ($cfg['state_field'] == 'hide') {
                continue;
            }

            // Include state file depending on selected shipping country
            if (file_exists(__DIR__.'/languages/states/'.$ship_country.'.php')) {
                require(__DIR__.'/languages/states/'.$ship_country.'.php');

                // State dropdown menu
                echo '<tr><td width="25%" align="right">'.$TXT_BAKERY['CUST_STATE'].':</td><td colspan="4"><select name="ship_state" style="width: 98%">';
                echo '<option value="">'.$TEXT['PLEASE_SELECT'].'&hellip;</option>';
                for ($n = 1; $n <= count($TXT_BAKERY['STATE_NAME']); $n++) {
                    $state      = $TXT_BAKERY['STATE_NAME'][$n];
                    $state_code = $TXT_BAKERY['STATE_CODE'][$n];
                    echo '<option value="'.$state_code.'"';
                    if ($state_code == $ship_state) {
                        echo ' selected="selected"';
                    }
                    echo '>'.$state.'</option>'."\n";
                }
                echo '</select></td></tr>'."\n";
                unset($TXT_BAKERY['STATE_NAME'], $TXT_BAKERY['STATE_CODE']);
            }
        }

        // The shipping country
        elseif ($field == 'ship_country') {
            echo '<tr><td width="25%" align="right">'.$TXT_BAKERY['CUST_COUNTRY'].':</td><td colspan="4"><select name="ship_country" style="width: 98%">';
            echo '<option value="">'.$TEXT['PLEASE_SELECT'].'&hellip;</option>';
            for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
                $country      = $TXT_BAKERY['COUNTRY_NAME'][$n];
                $country_code = $TXT_BAKERY['COUNTRY_CODE'][$n];
                echo '<option value="'.$country_code.'"';
                if ($country_code == $ship_country) {
                        echo ' selected="selected"';
                }
                echo '>'.$country.'</option>'."\n";
            }
            echo '</select>';
        }

        // And the others shipping textfields
        else {
            echo '<tr><td width="25%" align="right">'.$value.':</td>
            <td colspan="4"><input type="text" style="width: 98%" name="'.$field.'" value="'.lazyspecial(@$$field, ENT_QUOTES).'" maxlength="'.$length[$field].'" /></td></tr>'."\n";
        }
    }
?>
    <tr valign="top" class="mod_bakery_submit_row_b">
        <td height="40" colspan="5">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" style="padding-left: 12px;">
                        <input name="save_and_return" type="submit" value="<?=$TEXT['SAVE']?>" style="width: 160px; margin-top: 10px;">
                        <input name="save" type="submit" value="<?=$TEXT['SAVE'].' &amp; '.$TEXT['BACK']?>" style="width: 240px; margin-left: 20px;">
                    </td>
                    <td align="right" style="padding-right: 12px;">
                        <input type="button" value="<?=$TEXT['CANCEL']?>" onclick="javascript: window.location = '<?=WB_URL?>/modules/bakery/modify_orders.php?page_id=<?=$page_id?>';" style="width: 160px; margin-top: 10px;">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
</form>
-->
<?php
} else {
    echo '<p class="mod_bakery_error_b">'.$TEXT['NONE_FOUND'].'!</p>';
}

// Print admin footer
$admin->print_footer();