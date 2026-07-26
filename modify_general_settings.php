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


require('../../config.php');

// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';

// Look for language files
if (LANGUAGE_LOADED) {
    require_once __DIR__.'/languages/EN.php';
    if (LANGUAGE != "EN" && file_exists($sLCFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
        require_once $sLCFile;
    }
}

// Look for country file
if (LANGUAGE_LOADED) {
    if (file_exists($sLCFile = __DIR__.'/languages/countries/'.LANGUAGE.'.php')) {
        require_once $sLCFile;
    }
} else {
    require_once __DIR__.'/languages/countries/EN.php';
}

require_once __DIR__.'/functions.php';

$cfg = bxt_getGlobalCfg();
$aCheckoutForm = checkoutFormDataArray();

// Look for state file
$use_states = false;
if (file_exists($sFile = __DIR__.'/languages/states/'.lazystrip($cfg['shop_country']).'.php')) {
    require_once($sFile);
    $use_states = true;
}
?>
<script language="javascript" type="text/javascript">
    function mod_bakery_country_reload_b() {
        document.getElementsByName("reload")[0].value = "true";
        document.modify.submit();
    }
    function mod_bakery_toggle_stock_mode_b() {
        if (document.getElementsByName("stock_mode")[0].value == "text" || document.getElementsByName("stock_mode")[0].value == "img") {
                document.getElementById('stock_limit').style.display = 'inline';
        } else {
                document.getElementById('stock_limit').style.display = 'none';
        }
    }
</script>
<h2><?=$TXT_BAKERY['GENERAL_SETTINGS']; ?>
        <span style="float:right;font-weight: 600; background-color: #ccc;padding:2px 6px; ">
    <?php 
     if($cfg['use_payment'] == 1){
         echo 'Shop Mode <i class="fa fa-shopping-cart"></i> ';
     }else{
         echo 'Skip Payments Mode <i class="fa fa-commenting"></i> ';
     }
    ?></span>
    </h2><?php
// EDIT CSS BUTTON
require_once WB_PATH.'/framework/module.functions.php';
edit_module_css('bakery');
?>
<form name="modify" action="<?=WB_URL; ?>/modules/bakery/save_general_settings.php" method="post" style="margin: 0;">
    <input type="hidden" name="section_id" value="<?=$section_id; ?>" />
    <input type="hidden" name="page_id" value="<?=$page_id; ?>" />
    <input type="hidden" name="reload" value="false" />    
    <table cellpadding="2" cellspacing="0" border="0" align="center" width="98%">
    <!-- Shop -->
	<tr valign="bottom">
                <td width="30%" height="32" align="right"><strong><?=$TXT_BAKERY['SHOP'].' '.$TXT_BAKERY['SETTINGS']; ?>:</strong></td>
                <td height="32" colspan="4">&nbsp;</td>
        </tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHOP_NAME']; ?>:</td>
		<td colspan="4">
                    <input type="text" name="shop_name" style="width: 98%" maxlength="100" value="<?=lazystrip($cfg['shop_name']); ?>" /></td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHOP_EMAIL']; ?>:</td>
		<td colspan="4">
                    <input type="text" name="shop_email" style="width: 98%" maxlength="50" value="<?=lazystrip($cfg['shop_email']); ?>" /></td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHOP'].' '.$TEXT['PAGES_DIRECTORY']; ?>:</td>
		<td colspan="4">
                    <input type="text" name="pages_directory" style="width: 98%" maxlength="20" value="<?=lazystrip($cfg['pages_directory']); ?>" /></td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['TAC_URL']; ?>:</td>
		<td colspan="4">
                    <input type="text" name="tac_url" style="width: 98%" maxlength="255" value="<?=lazystrip($cfg['tac_url']); ?>" /></td>
	</tr>
	
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['CANCELLATION_URL']; ?>:</td>
		<td colspan="4">
                    <input type="text" name="cancellation_url" style="width: 98%" maxlength="255" value="<?=lazystrip($cfg['cancellation_url']); ?>" /></td>
	</tr>
	
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['PRIVACY_URL']; ?>:</td>
		<td colspan="4">
                    <input type="text" name="privacy_url" style="width: 98%" maxlength="255" value="<?=lazystrip($cfg['privacy_url']); ?>" /></td>
	</tr>
	
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHOP_COUNTRY']; ?>:</td>
		<td colspan="4">
                    <select name="shop_country" style="width: 98%" onchange="mod_bakery_country_reload_b()">
                        <?php
                            for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
                                    $country = $TXT_BAKERY['COUNTRY_NAME'][$n];
                                    $country_code = $TXT_BAKERY['COUNTRY_CODE'][$n];
                                    echo "<option value='$country_code'";
                                    if ($country_code == lazystrip($cfg['shop_country'])) {
                                            echo ' selected="selected"';
                                    }
                                    echo ">$country</option>\n";
                            }
                        ?>
                    </select>
                </td>
	</tr>
	<tr>
		<td width="30%" align="right"<?php if (!$use_states) { echo " class='mod_bakery_disabled_b'"; } ?>><?=$TXT_BAKERY['SHOP_STATE']; ?>:</td>
		<td colspan="4">
                    <select name="shop_state"<?php if (!$use_states) { echo " disabled='disabled'"; } ?> style='width: 98%'>
			<?php
			if ($use_states) {
				for ($n = 1; $n <= count($TXT_BAKERY['STATE_NAME']); $n++) {
                                    $state = $TXT_BAKERY['STATE_NAME'][$n];
                                    $state_code = $TXT_BAKERY['STATE_CODE'][$n];
                                    echo "<option value='$state_code'";
                                    if ($state_code == lazystrip($cfg['shop_state'])) {
                                        echo ' selected="selected"';
                                    }
                                    echo ">$state</option>\n";
				}
			} ?>
                    </select>		
                </td>
	</tr>
	
	<tr>
            <td align="right"><?=$TXT_BAKERY['USE_ONLINE_PAYMENT']?>:</td>
            <td colspan="4">
                <input type="hidden" name="use_payment" value="0">
                <label>
                    <input type="checkbox" name="use_payment" value="1" <?php if ($cfg['use_payment'] == 1) { echo 'checked="checked"'; } ?> />
                    <?=$TXT_BAKERY['HINT_ONLINE_PAYMENT']?>
                </label>
            </td>
	</tr>
	<tr>
	  <td align="right" valign="top"><?=$TXT_BAKERY['ADDRESS_FORM']; ?>:</td>
	  <td colspan="4">
              <?php 
              /**
               *    <!-- ADDRESS FORM SETTINGS -->
               */
              ?>                
                <table width="98%" class="formfield-selector">
                    <thead>
                        <tr class="thead"> 
                            <th width="180" align="right"></th>
                            <th width="100"><?=$TXT_BAKERY['VISIBLE']?></th>
                            <th width="100"><?=$TXT_BAKERY['REQUIRED']?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                        $aDisabled = bxt_formDisabledArray($cfg['use_payment']);
                        
                        foreach($aCheckoutForm as $key => $field){
                            if(empty($field)) continue;
                            $bDisabled = (in_array($key, $aDisabled));
                        ?>
                        <tr class="tr-<?=(strpos($key, 'ship') !== FALSE) ? 'shipaddress':''?>"> 
                            <td align="right"><?=$field[0]?></td>
                            <td align="center">
                                <input type="checkbox" name="show_fields[<?=$key?>]" value="1"
                                    <?=$bDisabled ? ' disabled ' : ''?> 
                                    <?=$field[1] == 1 || $bDisabled ? ' checked ' : ''?> 
                                >
                            </td>
                            <td align="center">
                                <!--<input type="hidden" name="required_fields[<?=$key?>]"  value="<?=$field[2]?>">-->
                                <input type="checkbox" name="required_fields[<?=$key?>]"  value="1"
                                    <?=$bDisabled ? ' disabled ' : ''?> 
                                    <?=$field[2] == 1 || $bDisabled ? ' checked ' : ''?>
                                >
                            </td>
                            <td>
                                <?php 
                                    if(strpos($key, 'cust') !== FALSE){
                                        if(isset($field[3])){ 
                                            echo '<label style="font-size:xx-small;">'
                                            . '<input type="hidden" value="0" name="option['.$field[3][0].']">'
                                            . '<input type="checkbox"  value="1" name="option['.$field[3][0].']" '.($field[3][2] == 1 ? ' checked':'').'> '.$field[3][1].''
                                            . '</label>';                        
                                        }                                    
                                    }
                                    ?> 
                            </td>
                        </tr>
                        <?php 
                            if($key == 'cust_message' ){?>
                                <tr class="tr-shipaddress-check">
                                    <td colspan="4" align="center">
                                        <big><b> <?=$TXT_BAKERY['ADD_SHIP_FORM']?></b></big><br>
                                    
                                        <label>
                                            <input type="radio" name="shipping_form" value="none" <?=($cfg['shipping_form'] == "none") ? 'checked' : ''?>>
                                            <?=strtolower($TEXT['NONE'])?>
                                        </label>
                                        <label>
                                            <input type="radio" name="shipping_form" value="request" <?=($cfg['shipping_form'] == "request") ? 'checked' : ''?>>
                                            <?=$TXT_BAKERY['SHIPPING_FORM_REQUEST']; ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="shipping_form" value="hideable" <?=($cfg['shipping_form'] == "hideable") ? 'checked' : ''?>>
                                            <?=$TXT_BAKERY['SHIPPING_FORM_HIDEABLE']; ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="shipping_form" value="always" <?=($cfg['shipping_form'] == "always") ? 'checked' : ''?>>
                                            <?=$TXT_BAKERY['SHIPPING_FORM_ALWAYS']; ?>
                                        </label>
                                    </td>
                                </tr>
                                 <tr class="thead tr-shipaddress"> 
                                    <th width="180" align="right"></th>
                                    <th width="100"><?=$TXT_BAKERY['VISIBLE']?></th>
                                    <th width="100"><?=$TXT_BAKERY['REQUIRED']?></th>
                                    <th></th>
                                </tr>
                                <?php 
                            }
                        }  ?>
                    </tbody>
                </table>
              
              <!--
		  <table width="98%" border="0" cellspacing="0" cellpadding="0">
		  	<tr>
		  		<td width="38%"><input type="checkbox" name="company_field" id="company_field" value="show" <?php if ($cfg['company_field'] == 'show') { echo 'checked="checked"'; } ?> />
			  	<label for="company_field"><?=$TXT_BAKERY['SHOW_COMPANY_FIELD']; ?></label></td>
		  		<td><input type="checkbox" name="tax_no_field" id="tax_no_field" value="show" <?php if ($cfg['tax_no_field'] == 'show') { echo 'checked="checked"'; } ?> onclick="javascript: sync_checkboxes(this);" />
		  		<label for="tax_no_field"><?=$TXT_BAKERY['SHOW_TAX_NO_FIELD']; ?></label></td>
		  	</tr>
		  	<tr>
		  		<td><input type="checkbox" name="state_field" id="state_field" value="show" <?php if ($cfg['state_field'] == 'show') { echo 'checked="checked"'; } ?> />
		  		<label for="state_field"><?=$TXT_BAKERY['SHOW_STATE_FIELD']; ?></label></td>
		  		<td><input type="checkbox" name="zip_location" id="zip_location" value="end" <?php if ($cfg['zip_location'] == 'end') { echo 'checked="checked"'; } ?> />
		  		<label for="zip_location"><?=$TXT_BAKERY['SHOW_ZIP_END_OF_ADDRESS']; ?></label></td>
		  	</tr>
		  </table>
              -->
	  </td>
    </tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['RIGHT_OF_REVOCATION']; ?>:</td>
	  <td colspan="4"><input type="checkbox" name="no_revocation" id="no_revocation" value="e-goods" <?php if ($cfg['no_revocation'] == 'e-goods') { echo 'checked="checked"'; } ?> />
		<label for="no_revocation"><?=$TXT_BAKERY['WAIVER_OF_RIGHT_TO_REVOKE']; ?></label>
	  </td>
	</tr>
        <?php 
        /* to be deleted */
        /*
	<tr>
	  <td align="right"><?=$TXT_BAKERY['CUST_MESSAGE']; ?>:</td>
	  <td colspan="4">
		<input type="checkbox" name="cust_msg" id="cust_msg" value="show" <?php if ($cfg['cust_msg'] == 'show') { echo 'checked="checked"'; } ?> />
		<label for="cust_msg"><?=$TXT_BAKERY['SHOW_TEXTAREA']; ?></label>
        </tr>
         * 
         */ ?>
	<tr>
	  <td align="right" valign="top"><?=$TXT_BAKERY['CART']; ?>:</td>
	  <td colspan="4">
	    <input type="checkbox" name="skip_cart" id="skip_cart" value="yes" <?php if ($cfg['skip_cart'] == 'yes') { echo 'checked="checked"'; } ?> />
		<label for="skip_cart"><?=$TXT_BAKERY['SKIP_CART_AFTER_ADDING_ITEM']; ?></label><br /><span style="margin-left: 22px;">(<?=$TXT_BAKERY['MINICART_STRONGLY_RECOMMENDED']; ?>)</span></td>
    </tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['SETTINGS']; ?>:</td>
	  <td colspan="4">
	    <input type="checkbox" name="display_settings" id="display_settings" value="1" <?php if ($cfg['display_settings'] == '1') { echo 'checked="checked"'; } ?> />
		<label for="display_settings"><?=$TXT_BAKERY['DISPLAY_SETTINGS_TO_ADMIN_ONLY']; ?></label></td>
    </tr>
<!--<tr>
		<td width="25%" align="right"><?=$TXT_BAKERY['USE_CAPTCHA']; ?>:</td>
		<td colspan="4">
		  <input type="checkbox" name="use_captcha" id="use_captcha" value="yes" <?php if ($cfg['use_captcha'] == 'yes') { echo 'checked="checked"'; } ?> />
	</tr>-->
	
	
<!-- Items -->
	<tr valign="bottom">
		  <td width="30%" height="32" align="right"><strong><?=$TXT_BAKERY['ITEM'].' '.$TXT_BAKERY['SETTINGS']; ?>:</strong></td>
		  <td height="32" colspan="4">&nbsp;</td>
    </tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['FREE_DEFINABLE_FIELD']; ?> 1:</td>
		<td colspan="4">
			<input type="text" name="definable_field_0" style="width: 98%" maxlength="50" value="<?=lazystrip($cfg['definable_field_0']); ?>" />
		</td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['FREE_DEFINABLE_FIELD']; ?> 2:</td>
		<td colspan="4">
			<input type="text" name="definable_field_1" style="width: 98%" maxlength="50" value="<?=lazystrip($cfg['definable_field_1']); ?>" />
		</td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['FREE_DEFINABLE_FIELD']; ?> 3:</td>
		<td colspan="4">
			<input type="text" name="definable_field_2" style="width: 98%" maxlength="50" value="<?=lazystrip($cfg['definable_field_2']); ?>" />
		</td>
	</tr>
	<tr>
		<td align="right" valign="top" style="padding-top: 5px;"><?=$TXT_BAKERY['STOCK']; ?>:</td>
		<td colspan="4">
	    <select name="stock_mode" style="width: 98%" onchange="mod_bakery_toggle_stock_mode_b()">
	      <option value="text" <?php if ($cfg['stock_mode'] == "text") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['STOCK_MODE_TEXT']; ?></option>
	      <option value="img" <?php if ($cfg['stock_mode'] == "img") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['STOCK_MODE_IMAGE']; ?></option>
	      <option value="number" <?php if ($cfg['stock_mode'] == "number") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['STOCK_MODE_NUMBER']; ?></option>
		  <option value="none" <?php if ($cfg['stock_mode'] == "none") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['STOCK_MODE_NONE']; ?></option>
        </select>
		<br />
		<span id="stock_limit" style="display: none;">&laquo;<?=ucfirst($TXT_BAKERY['SHORT_OF_STOCK']); ?>&raquo;: <input name="stock_limit" type="text" style="width: 25px; text-align: center;" value="<?=lazystrip($cfg['stock_limit']); ?>" maxlength="3" /> &ndash; 1 <?=$TXT_BAKERY['ITEMS']; ?></span>		
		</td>
    </tr>
	<script language="javascript" type="text/javascript">
		if (document.getElementsByName("stock_mode")[0].value == "text" || document.getElementsByName("stock_mode")[0].value == "img") {
			document.getElementById('stock_limit').style.display = 'inline';
		}
	</script>
	<tr>
	  <td align="right">&nbsp;</td>
	  <td colspan="4">
	    <input type="checkbox" name="out_of_stock_orders" id="out_of_stock_orders" value="1" <?php if ($cfg['out_of_stock_orders'] == '1') { echo 'checked="checked"'; } ?> />
		<label for="out_of_stock_orders"><?=$TXT_BAKERY['ALLOW_OUT_OF_STOCK_ORDERS']; ?></label></td>
    </tr>	

<!-- Payment -->
	<tr valign="bottom">
	  <td width="30%" height="32" align="right"><strong><?=$TXT_BAKERY['PAYMENT'].' '.$TXT_BAKERY['SETTINGS']; ?>:</strong></td>
	  <td height="32" colspan="4">&nbsp;</td>
    </tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHOP_CURRENCY']; ?>:</td>
		<td colspan="4">
			<input type="text" name="shop_currency" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['shop_currency']); ?>" maxlength="3" /> 
		  (USD, EUR, CHF, ... &nbsp;&gt;&nbsp;<a href="https://en.wikipedia.org/wiki/ISO_4217#Active_codes" target="_blank">ISO 4217</a>) </td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SEPARATOR_FOR']; ?>:</td>
		
		<td colspan="5"><?=$TXT_BAKERY['DECIMAL']; ?>: 
			<input name="dec_point" type="text" style="width: 75px; display:inline-block;" value="<?=lazystrip($cfg['dec_point']); ?>" maxlength="1" /> &nbsp;&nbsp;&nbsp;
			<?=$TXT_BAKERY['GROUP_OF_THOUSANDS']; ?>: 
			<input name="thousands_sep" type="text" style="width: 75px; display:inline-block;" value="<?=lazystrip($cfg['thousands_sep']); ?>" maxlength="1" />		</td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['TAX']; ?>:</td>
		<td colspan="4">
			<input type="radio" name="tax_by" id="tax_by_country" value="country"<?php if ($cfg['tax_by'] == 'country') { echo ' checked="checked"'; } ?> />
			<label for="tax_by_country"><?=$TXT_BAKERY['SHOP_COUNTRY']; ?></label>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="tax_by" id="tax_by_state" value="state"<?php if ($cfg['tax_by'] == 'state') { echo ' checked="checked"'; } ?><?php if (!$use_states) { echo " disabled='disabled'"; } ?> />
			<label for="tax_by_state"<?php if (!$use_states) { echo " class='mod_bakery_disabled_b'"; } ?>><?=$TXT_BAKERY['SHOP_STATE']; ?></label>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="tax_by" id="tax_by_none" value="none"<?php if ($cfg['tax_by'] == 'none') { echo ' checked="checked"'; } ?> />
			<label for="tax_by_none"><?=$TEXT['NONE']; ?></label>		</td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['TAX_RATE'].' '.$TXT_BAKERY['ITEM']; ?>:</td>
		<td width="13%">
			1.<input type="text" name="tax_rate" size="5" maxlength="5" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['tax_rate']); ?>" />%</td>
        <td width="13%">
			2.<input type="text" name="tax_rate1" size="5" maxlength="5" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['tax_rate1']); ?>" />%</td>
	    <td width="13%">
			3.<input type="text" name="tax_rate2" size="5" maxlength="5" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['tax_rate2']); ?>" />%</td>
	    <td><input type="checkbox" name="tax_included" id="tax_included" value="included" <?php if ($cfg['tax_included'] == 'included') { echo 'checked="checked"'; } ?> />
			<label for="tax_included"><?=$TXT_BAKERY['TAX_INCLUDED']; ?></label>
		</td>
	</tr>
<!--
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHOW_TAX_NO_FIELD']; ?>:</td>
		<td colspan="4">
	  	  <input type="checkbox" name="tax_no_field" id="tax_no_field" value="show" <?php if ($cfg['tax_no_field'] == 'show') { echo 'checked="checked"'; } ?> onclick="javascript: sync_checkboxes(this);" />
		</td>
	</tr>
-->
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['TAX_GROUP']; ?>:</td>
		<td colspan="4">
	  	  <input type="text" name="tax_group" id="tax_group" value="<?=lazystrip($cfg['tax_group']); ?>" style="width: 98%" />
	  	  <?php
			if (!extension_loaded('soap')) {
				echo '<span style="color: red;">To take advantage of the VAT-No check please make sure the <a href="http://php.net/manual/en/book.soap.php" target="_blank">soap extension</a> is loaded.</span>';
			}
	  	  ?>
		</td>
	</tr>

<!-- Shipping -->
	<tr valign="bottom">
	  <td width="30%" height="32" align="right"><strong><?=$TXT_BAKERY['SHIPPING'].' '.$TXT_BAKERY['SETTINGS']; ?>:</strong></td>
	  <td height="32" colspan="4">&nbsp;</td>
    </tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['TAX_RATE'].' '.$TXT_BAKERY['SHIPPING']; ?>:</td>
	  <td colspan="4">
		  <input type="text" name="tax_rate_shipping" size="5" maxlength="5" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['tax_rate_shipping']); ?>" />%</td>
	</tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['FREE_SHIPPING'].' '.$TXT_BAKERY['OVER']; ?>:</td>
	  <td colspan="4">
	  	<input type="text" name="free_shipping" size="8" maxlength="8" style="width: 155px; display:inline-block" value="<?=lazystrip($cfg['free_shipping']); ?>" /><?=lazystrip($cfg['shop_currency']); ?> &nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="free_shipping_msg" id="free_shipping_msg" value="show" <?php if ($cfg['free_shipping_msg'] == 'show') { echo 'checked="checked"'; } ?> />
		<label for="free_shipping_msg"><?=$TXT_BAKERY['SHOW_FREE_SHIPPING_MSG']; ?></label></td>
    </tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['SHIPPING_BASED_ON']; ?>:</td>
	  <td colspan="4">
	    <select name="shipping_method">
	      <option value="flat" <?php if ($cfg['shipping_method'] == "flat") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['SHIPPING_METHOD_FLAT']; ?></option>
	      <option value="items" <?php if ($cfg['shipping_method'] == "items") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['SHIPPING_METHOD_ITEMS']; ?></option>
	      <option value="positions" <?php if ($cfg['shipping_method'] == "positions") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['SHIPPING_METHOD_POSITIONS']; ?></option>
	      <option value="percentage" <?php if ($cfg['shipping_method'] == "percentage") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['SHIPPING_METHOD_PERCENTAGE']; ?></option>
	      <option value="highest" <?php if ($cfg['shipping_method'] == "highest") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['SHIPPING_METHOD_HIGHEST']; ?></option>
		  <option value="none" <?php if ($cfg['shipping_method'] == "none") { echo 'selected="selected"'; } ?> >
		  <?=$TXT_BAKERY['SHIPPING_METHOD_NONE']; ?></option>
        </select>	  </td>
    </tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHIPPING'].' '.$TXT_BAKERY['DOMESTIC']; ?>:</td>
		<td width="13%">
			<input type="text" name="shipping_domestic"  maxlength="7" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['shipping_domestic']); ?>" /><?php if ($cfg['shipping_method'] != "percentage") { echo lazystrip($cfg['shop_currency']); } else { echo "%"; } ?></td>
	    <td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="30%" align="right"><?=$TXT_BAKERY['SHIPPING'].' '.$TXT_BAKERY['ABROAD']; ?>:</td>
		<td>
			<input type="text" name="shipping_abroad" maxlength="7" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['shipping_abroad']); ?>" /><?php if ($cfg['shipping_method'] != "percentage") { echo lazystrip($cfg['shop_currency']); } else { echo "%"; } ?></td>
		<td colspan="3">&nbsp;</td>
    </tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['SHIPPING']; ?>:</td>
	  <td><input type="text" name="shipping_zone" size="6" maxlength="7" style="width: 75px; display:inline-block" value="<?=lazystrip($cfg['shipping_zone']); ?>" /><?php if ($cfg['shipping_method'] != "percentage") { echo lazystrip($cfg['shop_currency']); } else { echo "%"; } ?></td>
	  <td colspan="3">... <?=$TXT_BAKERY['ZONE_COUNTRIES']; ?>:</td>
    </tr>
	<tr>
		<td width="30%" align="right">&nbsp;</td>
		<td>&nbsp;</td>
        <td colspan="3">
		<?php
			$zone_countries = lazyexplode(",", lazystrip($cfg['zone_countries']));
			echo "<select name='zone_countries[]' class='nos2' size='3' multiple='multiple'>"; 
			for ($n = 1; $n <= count($TXT_BAKERY['COUNTRY_NAME']); $n++) {
				$country = $TXT_BAKERY['COUNTRY_NAME'][$n];
				$country_code = $TXT_BAKERY['COUNTRY_CODE'][$n];
				if ($country_code != lazystrip($cfg['shop_country'])) {
					echo "<option value='$country_code'";
					if (in_array($country_code, $zone_countries)) {
						echo ' selected="selected"';
					}
				}
				echo ">$country</option>\n";
			}
		echo "</select>"; ?></td>
	</tr>
</table>
<table width="98%" align="center" cellpadding="0" cellspacing="0" class="mod_bakery_submit_row_b">
	<tr valign="top">
	  <td height="30" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <input name="save" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;background-color:#4db34e" />
	  <input name="save_back" type="submit" value="<?=$TEXT['SAVE']; ?> & <?=$TEXT['BACK']; ?>" style="" /></td>
	  <td height="30" align="right">
	  <input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; margin-top: 5px;" />&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
</form>
<script>
    // open/close additional Form Settings for Shipping
    $( document ).ready(function() { 
        var targetClass = $(".tr-shipaddress");
        var sVal = $('input[name="shipping_form"]:checked').val();
        if(sVal == 'none'){
           targetClass.hide();
        }
        $('input:radio[name="shipping_form"]').click(function(){                            
            var inputValue = $(this).attr("value");
            if(inputValue == 'none'){
                targetClass.fadeOut();
            } else {
                targetClass.fadeIn();
            }
        });
    });     
</script>
<?php
// Print admin footer
$admin->print_footer();