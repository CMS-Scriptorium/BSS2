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

// Make use of the skinable backend themes of WB > 2.7
// Check if THEME_URL is supported otherwise use ADMIN_URL
if (!defined('THEME_URL')) {
	define('THEME_URL', ADMIN_URL);
}

// Look for language file
if (LANGUAGE_LOADED) {
    require_once(__DIR__.'/languages/EN.php');
    if (file_exists($sLangFile = __DIR__.'/languages/'.LANGUAGE.'.php')) 
    	require_once($sLangFile);
}

// Show current or archived orders
$view = 'current';
if (isset($_GET['view'])) {
    $view = $_GET['view'];
}

// Get the time until the admin will be alerted if the invoice has not been payed
$query_payment_methods = $database->query("SELECT value_2, value_3 FROM {BXT}_payment_methods WHERE directory = 'invoice' LIMIT 1");
if ($query_payment_methods->numRows() > 0) {
	$payment_method = $query_payment_methods->fetchRow();
	$invoice_alert  = is_numeric($payment_method['value_2']) ? $payment_method['value_2'] : 0;
	$reminder_alert = is_numeric($payment_method['value_3']) ? $payment_method['value_3'] : 0;
}

// Toggle between current orders and archived / canceled orders
if ($view == 'current') {
	$toggle         = 'archive';
	$toggle_page    = $TXT_BAKERY['ORDER_ARCHIVED'];
	$current_page   = $TXT_BAKERY['ORDER_CURRENT'];
	$query_customer = $database->query("SELECT * FROM {BXT}_customer WHERE status != 'archived' AND status != 'canceled' AND submitted != 'no' ORDER BY order_date DESC");
}
else {
	$toggle         = 'current';
	$toggle_page    = $TXT_BAKERY['ORDER_CURRENT'];
	$current_page   = $TXT_BAKERY['ORDER_ARCHIVED'];
	$query_customer = $database->query("SELECT * FROM {BXT}_customer WHERE status = 'archived' OR status = 'canceled' AND submitted != 'no' ORDER BY order_date DESC");
}


echo '<h2>'.$TXT_BAKERY['ORDER_ADMIN'].': <br/>'.$current_page.' <span style="text-transform: lowercase;">'.$TEXT['MODIFY'].' / '.$TEXT['DELETE'].'</span></h2>';


// Show buttons
?>
<script type="text/javascript">
	function newInvoice(url) {
	  if (screen.availHeight) {
	    var invoiceWindowHeight = screen.availHeight;
	  }
	  else {
	    var invoiceWindowHeight = 800;
	  }
	  invoiceWindow = window.open(url + "#bottom", "", "width=750, height=" + invoiceWindowHeight + ", left=100, top=0, scrollbars=yes");
	  invoiceWindow.focus();
	}
	
	function showOrder(url) {
	  orderWindow = window.open(url, "", "width=600, height=500, left=150, top=100, scrollbars=yes");
	  orderWindow.focus();
	}
</script>

<table width="98%" align="center" cellpadding="0" cellspacing="0">
  <tr height="30" class="mod_bakery_submit_row_b">
	<td align="left" width="50%" style="padding-left: 12px;">
		<input type="button" value="<?=$toggle_page; ?>" onclick="javascript: window.location = '<?=WB_URL; ?>/modules/bakery/modify_orders.php?page_id=<?=$page_id; ?>&view=<?=$toggle; ?>';" />
	</td>
	<td align="right" width="50%" style="padding-right: 12px;">
		<input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" />
	</td>
  </tr>
</table>
<br />
<?php


// Query customer table
if ($query_customer->numRows() > 0) {
	// Customer table header
	?>
	<form name="modify" action="<?=WB_URL; ?>/modules/bakery/save_orders.php" method="post" style="margin: 0;">
	<input type="hidden" name="section_id" value="<?=$section_id; ?>" />
	<input type="hidden" name="page_id" value="<?=$page_id; ?>" />
	<table cellpadding="2" cellspacing="0" border="0" width="98%" align="center">
	<tr height="30" valign="bottom" class="mod_bakery_submit_row_b">
		<th colspan="2" align="left" style="padding-left: 5px;"><?=$TXT_BAKERY['ORDER']; ?></th>
		<th align="left"><?=$TXT_BAKERY['INVOICE']; ?></th>
		<th colspan="3" align="left"><?=$TXT_BAKERY['CUSTOMER']; ?></th>
		<th align="left"><?=$TXT_BAKERY['ORDER_DATE']; ?></th>
		<th colspan="2" align="left"><?=$TXT_BAKERY['STATUS']; ?></th>
		<th colspan="4"><?=$TEXT['ACTIONS']; ?></th>
	</tr>
	<?php
	
	// List order table
	$row = 'a';
	while ($costumer = $query_customer->fetchRow()) {
		?>
		<tr class="row_<?=$row; ?>" height="20">
			<td width="4%" align="right"><?=$costumer['order_id']; ?></td>
			<td width="30" align="center">
				<?php
				// Show payment method icons
				$payment_method = $costumer['submitted'];

				// Get localized payment method name or fall back to the internal identifier
				$payment_method_name = $payment_method;
				// Look for payment method language file
				if (LANGUAGE_LOADED) {
					if (empty($TXT_BAKERY[$payment_method]['TITLE'])) {
					    include_once(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/EN.php');
					    if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
					        include_once(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php');
					    }
					}
					if (empty($TXT_BAKERY[$payment_method]['NAME'])) {
						$payment_method_name = $TXT_BAKERY[$payment_method]['TITLE'];
					}
					else {
						$payment_method_name = $TXT_BAKERY[$payment_method]['NAME'];
					}
				}

				// Show icon
				echo '<img src="'.WB_URL.'/modules/bakery/payment_methods/'.$payment_method.'/icon.png" alt="'.$payment_method_name.'" title="'.$payment_method_name.'" border="0" />';

			// Show email, customer name and order date ?>
			</td>
			<td width="5%" align="right" style="padding-right: 8px; font-weight: bold;"><?=$costumer['invoice_id']; ?></td>




			<td width="22">
			<a href="mailto:<?=lazystrip($costumer['cust_email']); ?>"><img src="<?=WB_URL; ?>/modules/bakery/images/email.png" alt="<?=$TEXT['EMAIL']; ?>" title="<?=$TEXT['EMAIL'].' '.$TEXT['TO'].' '.lazystrip($costumer['cust_email']); ?>" style="margin-bottom: -3px;" border="0" /></a>
			</td>
			<td width="22">
			<a href="<?=WB_URL; ?>/modules/bakery/modify_order.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;order_id=<?=$costumer['order_id']; ?>"><img src="<?=WB_URL; ?>/modules/bakery/images/user_edit.png" alt="<?=$TXT_BAKERY['EDIT_ORDER']; ?>" title="<?=$TXT_BAKERY['EDIT_ORDER']; ?>" style="margin-bottom: -3px;" border="0" /></a>
			</td>
			<td>
			<a href="<?=WB_URL; ?>/modules/bakery/modify_order.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;order_id=<?=$costumer['order_id']; ?>" title="<?=$TXT_BAKERY['EDIT_ORDER']; ?>"><?=lazystrip($costumer['cust_last_name'])." ".lazystrip($costumer['cust_first_name']); ?></a>
			</td>
			<td width="135"><?=gmdate(DATE_FORMAT.', '.TIME_FORMAT, $costumer['order_date']+TIMEZONE); ?></td>
			<td width="22">
			<?php

			// Show status images
			$status_img_url   = WB_URL.'/modules/bakery/images/status';
			$status_img_style = 'style="margin-bottom: -3px;" border="0"';
			switch (lazystrip($costumer['status'])) {

				case 'ordered': echo '<img src="'.$status_img_url.'/ordered.gif" alt="'.$TXT_BAKERY['STATUS_ORDERED'].'" title="'.$TXT_BAKERY['STATUS_ORDERED'].'" '.$status_img_style.' />'; break;

				case 'shipped': echo '<img src="'.$status_img_url.'/shipped.gif" alt="'.$TXT_BAKERY['STATUS_SHIPPED'].'" title="'.$TXT_BAKERY['STATUS_SHIPPED'].'" '.$status_img_style.' />'; break;

				case 'busy': echo '<img src="'.$status_img_url.'/busy.gif" alt="'.$TXT_BAKERY['STATUS_BUSY'].'" title="'.$TXT_BAKERY['STATUS_BUSY'].'" '.$status_img_style.' />'; break;

				case 'invoice':
					// Invoice alert
					if ($costumer['order_date'] + (60 * 60 * 24 * $invoice_alert) < time() && $invoice_alert != 0) {
						echo '<img src="'.$status_img_url.'/alert.gif" alt="'.$TXT_BAKERY['STATUS_REMINDER'].'" title="'.$TXT_BAKERY['STATUS_REMINDER'].'" '.$status_img_style.' />'; break;	
						}
					else {
						echo '<img src="'.$status_img_url.'/invoice.gif" alt="'.$TXT_BAKERY['STATUS_INVOICE'].'" title="'.$TXT_BAKERY['STATUS_INVOICE'].'" '.$status_img_style.' />'; break;
					}

				case 'reminder':
					// Reminder alert
					if ($costumer['order_date'] + (60 * 60 * 24 * $reminder_alert) < time() && $reminder_alert != 0) {
						echo '<img src="'.$status_img_url.'/alert.gif" alt="'.$TXT_BAKERY['STATUS_REMINDER'].'" title="'.$TXT_BAKERY['STATUS_REMINDER'].'" '.$status_img_style.' />'; break;	
						}
					else {
						echo '<img src="'.$status_img_url.'/reminder.gif" alt="'.$TXT_BAKERY['STATUS_REMINDER'].'" title="'.$TXT_BAKERY['STATUS_REMINDER'].'" '.$status_img_style.' />'; break;
					}

				case 'paid': echo '<img src="'.$status_img_url.'/paid.gif" alt="'.$TXT_BAKERY['STATUS_PAID'].'" title="'.$TXT_BAKERY['STATUS_PAID'].'" '.$status_img_style.' />'; break;

				case 'archived': echo '<img src="'.$status_img_url.'/archived.gif" alt="'.$TXT_BAKERY['STATUS_ARCHIVED'].'" title="'.$TXT_BAKERY['STATUS_ARCHIVED'].'" '.$status_img_style.' />'; break;

				case 'canceled': echo '<img src="'.$status_img_url.'/canceled.gif" alt="'.$TXT_BAKERY['STATUS_CANCELED'].'" title="'.$TXT_BAKERY['STATUS_CANCELED'].'" '.$status_img_style.' />'; break;
			}
			echo '</td>'."\n".'<td width="120">';

// Show status select depending on the payment method
if (lazystrip($costumer['status']) == 'archived' || lazystrip($costumer['status']) == 'canceled') {
	if (lazystrip($costumer['status']) == 'canceled') {
		echo $TXT_BAKERY['STATUS_CANCELED'];
	} else {
		echo $TXT_BAKERY['STATUS_ARCHIVED'];
	}
} else {
	switch (lazystrip($costumer['submitted'])) {
		case 'advance': $select_status = array('ordered' => $TXT_BAKERY['STATUS_ORDERED'], 'paid' => $TXT_BAKERY['STATUS_PAID'], 'shipped' => $TXT_BAKERY['STATUS_SHIPPED'], 'archived' => $TXT_BAKERY['STATUS_ARCHIVE'], 'canceled' => $TXT_BAKERY['STATUS_CANCEL']);
			break;
		case 'invoice': $select_status = array('ordered' => $TXT_BAKERY['STATUS_ORDERED'], 'shipped' => $TXT_BAKERY['STATUS_SHIPPED'], 'invoice' => $TXT_BAKERY['STATUS_INVOICE'], 'reminder' => $TXT_BAKERY['STATUS_REMINDER'], 'paid' => $TXT_BAKERY['STATUS_PAID'], 'archived' => $TXT_BAKERY['STATUS_ARCHIVE'], 'canceled' => $TXT_BAKERY['STATUS_CANCEL']);
			break;
		default: $select_status = array('ordered' => $TXT_BAKERY['STATUS_ORDERED'], 'shipped' => $TXT_BAKERY['STATUS_SHIPPED'], 'archived' => $TXT_BAKERY['STATUS_ARCHIVE'], 'canceled' => $TXT_BAKERY['STATUS_CANCEL']);
	}
	// Generate status select
	echo ' <select name="status['.$costumer['order_id'].']" style="width: 110px;">';
	foreach ($select_status as $option_value => $option_text) {
		echo '<option value="'.$option_value.'"';
		echo lazystrip($costumer['status']) == $option_value ? ' selected="selected"' : '';
		echo '>'.$option_text.'</option>'."\n";
	}
	echo '</select>';
}

// Send invoice button
if ($costumer['sent_invoices'] == 0) {
	$send_invoice_icon = 0;
	$send_invoice_txt  = $TXT_BAKERY['SEND_INVOICE'];
} else {
	$send_invoice_icon = 1;
	$send_invoice_txt  = sprintf($TXT_BAKERY['INVOICE_ALREADY_SENT'], $costumer['sent_invoices']);
}
?>

			</td>
			<td  width="22">
				<a href="<?=WB_URL; ?>/modules/bakery/display_invoice.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;order_id=<?=$costumer['order_id']; ?>" onclick="newInvoice(this.href); return false;"><img src="<?=WB_URL; ?>/modules/bakery/images/print.gif" alt="<?=$TXT_BAKERY['PRINT_INVOICE']; ?>" title="<?=$TXT_BAKERY['PRINT_INVOICE']; ?>" border="0" /></a>
			</td>
			<td  width="22">
				<a href="javascript: confirm_link('<?=$TXT_BAKERY['JS_CONFIRM_SEND_INVOICE']; ?>', '<?=WB_URL; ?>/modules/bakery/send_invoice.php?page_id=<?=$page_id; ?>&section_id=<?=$section_id; ?>&order_id=<?=$costumer['order_id']; ?>');">
					<img src="<?=WB_URL; ?>/modules/bakery/images/email<?=$send_invoice_icon; ?>.png" alt="<?=$send_invoice_txt; ?>" title="<?=$send_invoice_txt; ?>" border="0" /></a>
			</td>

			<td  width="22">
				<a href="<?=WB_URL; ?>/modules/bakery/display_order.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;order_id=<?=$costumer['order_id']; ?>" onclick="showOrder(this.href); return false;" title="<?=$TEXT['VIEW_DETAILS']; ?>">
					<img src="<?=WB_URL; ?>/modules/bakery/images/view.gif" alt="<?=$TXT_BAKERY['INVOICE'].' '.$TEXT['VIEW_DETAILS']; ?>" border="0" />
				</a>
			</td>

			<td width="22">
				<a href="javascript: confirm_link('<?=$TEXT['ARE_YOU_SURE']; ?>', '<?=WB_URL; ?>/modules/bakery/delete_order.php?page_id=<?=$page_id; ?>&section_id=<?=$section_id; ?>&order_id=<?=$costumer['order_id']; ?>&view=<?=$view; ?>');" title="<?=$TEXT['DELETE']; ?>">
					<img src="<?=WB_URL; ?>/modules/bakery/images/delete.gif" border="0" alt="<?=$TEXT['DELETE']; ?>" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if ($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND']."<br /><br />";
}

// Show buttons if view is current
if ($view == 'current') {
	?>
	<table width="98%" align="center" cellpadding="0" cellspacing="0" class="mod_bakery_submit_row_b">
		<tr valign="top">
		  <td height="30" align="left"  style="padding-left: 12px;">
		  <input name="save" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" /></td>
		  <td height="30" align="right"  style="padding-right: 12px;">
		  <input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; margin-top: 5px;" /></td>
		</tr>
	</table>
	</form>
	<?php
}

// Print admin footer
$admin->print_footer();
