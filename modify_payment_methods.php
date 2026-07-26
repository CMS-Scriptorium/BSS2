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
require(WB_PATH.'/modules/admin.php');
require_once(WB_PATH.'/modules/bakery/functions.php');

// Look for language file
if (LANGUAGE_LOADED) {
    require_once __DIR__.'/languages/EN.php';
    if (file_exists($sFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
        require_once $sFile;
    }
}

// Check installed payment methods and load new ones
require_once __DIR__.'/payment_methods/load.php';

// Get selected payment method
$payment_method = isset($_GET['payment_method']) ? strip_tags($_GET['payment_method']) : 'advance';
?>


<script language="javascript" type="text/javascript">
	function mod_bakery_select_payment_method_b() {
		document.getElementsByName("reload")[0].value = "true";
		document.modify.submit();
	}
</script>

<form name="modify" action="<?=WB_URL; ?>/modules/bakery/save_payment_methods.php" method="post" style="margin: 0;">
    <input type="hidden" name="section_id" value="<?=$section_id; ?>" />
    <input type="hidden" name="page_id" value="<?=$page_id; ?>" />
    <input type="hidden" name="update_payment_method" value="<?=$payment_method; ?>" />
    <input type="hidden" name="reload" value="false" />

    <table cellpadding="2" cellspacing="0" border="0" align="center" width="98%">
	<tr>
		<td colspan="5"><h2><?=$TXT_BAKERY['SELECT_PAYMENT_METHODS']; ?></h2></td>
	</tr>
	<tr>
	<td>
	<input type="button" value="<?=$MENU['HELP']; ?>" onclick="javascript: window.open('<?=WB_URL; ?>/modules/bakery/help.php?page_id=<?=$page_id; ?>&section_id=<?=$section_id; ?>&payment_method=<?=$payment_method; ?>#email','bar','top=50,left=50,width=800,height=600');" style="width: 100px;" />
	</td>
	</tr>
	<tr valign="top">
            <td align="right"><b><?=$TXT_BAKERY['PAYMENT_METHODS']; ?>:</b></td>
            <td colspan="4">
            <?php



            // LOAD ALL PAYMENT METHODS, DISPLAY CHECKBOXES AND GENERATE A DROP DOWN MENU
            // **************************************************************************

            // Initialize vars
            $sPaymentMethodOptions = '';
            $sPaymentMethodTitle  = '';

            // Get content of payment methods table
            $aPaymentMethods = $database->get_array(
                "SELECT `pm_id`, `active`, `directory`, `name` 
                    FROM `{BXT}_payment_methods` 
                    ORDER BY `pm_id` ASC" 
            );
            if (is_array($aPaymentMethods)) {
                // Generate html table with checkboxes
                $i = 0;
                $num_col = 3;
                ?>
                <ul>
                    
                <?php
                // Loop through payment methods
                foreach ($aPaymentMethods as $rec) {
                    $rec = array_map('lazystrip', $rec);
                    $pm_id     = $rec['pm_id'];
                    $active    = $rec['active'];
                    $directory = $rec['directory'];
                    $name      = $rec['name'];



                    // Get localized payment method name or fall back to english version
                    unset($TXT_BAKERY[$payment_method]);
                    $payment_method_name = $name;
                    $no_include = true;

                    // Look for payment method language files
                    if (LANGUAGE_LOADED) {

                        // Default english
                        if (file_exists($sFile = __DIR__.'/payment_methods/'.$directory.'/languages/EN.php')) {
                            include_once $sFile;
                            $no_include = false;
                        }
                        // Current language
                        if (file_exists($sFile = __DIR__.'/payment_methods/'.$directory.'/languages/'.LANGUAGE.'.php')) {
                            include_once $sFile;
                            $no_include = false;
                        }
                        // Warning if no language file has been found at all and skip this method 
                        // Probably the payment method directory is missing
                        if ($no_include) {
                            echo '<p style="color: red;"><b>Failed to include language files.</b><br />The payment method &quot;'.$name.'&quot; is not available. Make sure the requested payment method directory and associated files exist on your server.</p>';
                            continue;
                        }
                        if (!empty($TXT_BAKERY[$payment_method]['NAME'])) {
                            $payment_method_name = $TXT_BAKERY[$payment_method]['NAME'];
                        }
                        elseif (!empty($TXT_BAKERY[$payment_method]['TITLE'])) {
                            $payment_method_name = $TXT_BAKERY[$payment_method]['TITLE'];
                        }
                    }


                    $sChecked = $active ? ' checked' : '';                    
                    // Generate list  of checkboxes
                    ?><li>
                        <label><input type="checkbox" <?=$sChecked?> name="payment_methods[<?=$pm_id?>]" value="<?=$directory?>">
                                <img src="<?=get_url_from_path( __DIR__.'/payment_methods/'.$directory)?>/icon.png" title="<?=strtoupper($directory)?>"> <?=$payment_method_name?>
                        </label>
                        <input type="hidden" name="all_payment_methods[]" value="<?=$pm_id?>" /></li>
                    <?php


                    // Generate select options for modifying payment methods
                    $sPaymentMethodOptions .= '<option value="'.$directory.'"';
                    if ($payment_method == $directory) {
                            $sPaymentMethodOptions .= ' selected="selected"';
                            $sPaymentMethodTitle   = $payment_method_name;
                    }
                    $sPaymentMethodOptions .= '>'.$payment_method_name.'</option>'."\n";
                }
                
            }
            ?>
            </ul>
        </td>
    </tr>
    <tr valign="bottom">
	  <td colspan="5" height="65" valign="bottom"><h2><?=$TEXT['MODIFY'].' '.$TXT_BAKERY['PAYMENT_METHOD'].' &laquo;'.$sPaymentMethodTitle; ?>&raquo;</h2></td>
    </tr>
    <tr>
        <td width="30%" align="right"><strong><?=$TEXT['PLEASE_SELECT']; ?>:</strong></td>
        <td colspan="4">
            <select name='modify_payment_method' style='width: 98%' onchange='mod_bakery_select_payment_method_b()'>
                <?=$sPaymentMethodOptions; ?>
            </select>
        </td>
    </tr>
	
	<?php
	// CURRENT PAYMENT METHOD SETTINGS
	// *******************************
	
	// Get data of current payment method for modification
	$no_setting    = true;
	$setting_table = '';
	$setting_info  = '';
	unset($TXT_BAKERY[$payment_method]);
	$query_payment_methods = $database->query("SELECT * FROM {BXT}_payment_methods WHERE directory = '$payment_method' LIMIT 1");
	if ($query_payment_methods->numRows() > 0) {
		$fetch_payment_methods = $query_payment_methods->fetchRow();
		$fetch_payment_methods = array_map('lazystrip', $fetch_payment_methods);
                
		$cust_email_subject    = $fetch_payment_methods['cust_email_subject'];
		$cust_email_body       = $fetch_payment_methods['cust_email_body'];
		$shop_email_subject    = $fetch_payment_methods['shop_email_subject'];
		$shop_email_body       = $fetch_payment_methods['shop_email_body'];

		// Look for payment method language file
		if (LANGUAGE_LOADED) {
		    include __DIR__.'/payment_methods/'.$payment_method.'/languages/EN.php';
		    if (file_exists($sFile = __DIR__.'/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
		        include $sFile;
		    }
		}

		// Generate textareas
		for ($i = 1; $i <= 6; $i++) {
			$field = $fetch_payment_methods['field_'.$i];
			$txt_index = strtoupper($field);
			$value = $fetch_payment_methods['value_'.$i];
			if (!empty($field) && $field != 'invoice_template' && $field != 'invoice_alert' && $field != 'reminder_alert' ) {
                            $no_setting = false;
                            $setting_table .= ''                                    
                            . '<tr>'
                                . '<td width="30%" align="right" valign="top">'
                                    . $TXT_BAKERY[$payment_method][$txt_index].':'
                                . '</td>'
                                . '<td colspan="4">'
                                    . '<textarea name="update[value_'.$i.']" rows="3" style="width: 98%;">'.$value.'</textarea>'
                                . '</td>'
                            . '</tr>';
			}

			// Special input fields for the invoice and reminder alert 
			elseif ($field == 'invoice_alert' || $field == 'reminder_alert') {
                            $setting_table .= '<tr>
                                <td width="30%" align="right">'.$TXT_BAKERY[$payment_method][$txt_index].':</td>
                                <td colspan="4">
                                      <input type="text" maxlength="3" name="update[value_'.$i.']" style="width: 30px; text-align: right;" value="'.$value.'" /> '.$TXT_BAKERY['DAYS'].'</td>
                              </tr>';
			}

			// Special textarea for invoice template
			elseif ($field == 'invoice_template') {
                           
                            $setting_table .= '
                                <tr valign="bottom">
                                    <td width="30%" height="32" align="right">
                                        <strong>'.$TXT_BAKERY['LAYOUT'].' '.$TXT_BAKERY['SETTINGS'].':</strong>
                                    </td>
                                   
                                </tr>
                                <tr>
                                    <td width="30%" align="right" valign="top">
                                        '.$TXT_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'].':
                                    </td>
                                    <td colspan="4">
                                        <textarea name="update[value_4]" style="width: 98%; height: 100px;">'.htmlentities($value).'</textarea>
                                    </td>
                                </tr>
                                ';
			}
		}

		// If no payment method setting has been set
		$setting_info = $no_setting ? $TXT_BAKERY['NO_PAYMENT_METHOD_SETTING'] : '&nbsp;';
	}

	// Show payment method header
	echo '<tr valign="bottom">';
	echo '<td width="30%" height="32" align="right"><strong>'.$TXT_BAKERY['SETTINGS'].':</strong></td>';
	echo '<td height="32" colspan="4">'.$setting_info.'</td>';
	echo '</tr>';

	// Show payment method textareas
	echo $setting_table;
	
	// Show payment method notice if exists
	if (isset($TXT_BAKERY[$payment_method]['NOTICE']) && !empty($TXT_BAKERY[$payment_method]['NOTICE'])) {
            echo '<tr valign="top">';
            echo '<td width="30%" height="32" align="right"><strong>'.$TXT_BAKERY['NOTICE'].':</strong></td>';
            echo '<td height="32" colspan="4"><p style="width: 97%; margin: 0; padding: 3px; border: solid 1px #FFD700; background-color: #FFFFDD;">'.$TXT_BAKERY[$payment_method]['NOTICE'].'</p></td>';
            echo '</tr>';
	}
	
	// Emails to customer and shop ?>
	
    <tr valign="bottom">
        <td width="30%" height="32" align="right">
            <strong><?=$TXT_BAKERY['EMAIL']; ?>:</strong>
        </td>
        
    </tr>
    <tr>
        <td width="30%" align="right">
            <?=$TXT_BAKERY['EMAIL_SUBJECT'].' '.$TXT_BAKERY['CUSTOMER']; ?>:
        </td>
        <td colspan="4">
            <input type="text" name="update[cust_email_subject]" style="width: 98%" value="<?=$cust_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <td width="30%" align="right" valign="top">
            <?=$TXT_BAKERY['EMAIL_BODY'].' '.$TXT_BAKERY['CUSTOMER']; ?>:
        </td>
        <td colspan="4">
            <textarea name="update[cust_email_body]" style="width: 98%; height: 80px;"><?=$cust_email_body; ?></textarea>
        </td>
    </tr>

    <tr>
        <td width="30%" align="right">
            <?=$TXT_BAKERY['EMAIL_SUBJECT'].' '.$TXT_BAKERY['SHOP']; ?>:
        </td>
        <td colspan="4">
            <input type="text" name="update[shop_email_subject]" style="width: 98%" value="<?=$shop_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <td width="30%" align="right" valign="top">
            <?=$TXT_BAKERY['EMAIL_BODY'].' '.$TXT_BAKERY['SHOP']; ?>:
        </td>
        <td colspan="4">
            <textarea name="update[shop_email_body]" style="width: 98%; height: 80px;"><?=$shop_email_body; ?></textarea>
        </td>
    </tr>
</table>
<br />
<table width="98%" align="center" cellpadding="0" cellspacing="0" class="mod_bakery_submit_row_b">
    <tr valign="top">
        <td height="30" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="save" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" /></td>
        <td height="30" align="right">
        <input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; margin-top: 5px;" />&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
</form>
<?php
$admin->print_footer();// Print admin footer