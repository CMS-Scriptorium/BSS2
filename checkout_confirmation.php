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

if(!isset($setting_continue_url)){
    $sTmp = $database->get_one("SELECT p.link FROM {TP}pages p INNER JOIN {BXT}_page_settings ps ON p.page_id = ps.page_id WHERE p.page_id = ps.continue_url AND ps.section_id = '$section_id'");
    $setting_continue_url = WB_URL.PAGES_DIRECTORY.$sTmp.PAGE_EXTENSION;    
}
if(!isset($TXT_BAKERY)){
    require __DIR__.'/languages/EN.php';
    if (file_exists($sLCFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
        require $sLCFile;
    }
}

require_once(WB_PATH.'/modules/bakery/functions.php');

// Include WB template parser and create template object
require_once WB_PATH.'/include/phplib/template.inc';
$tpl = new Template(__DIR__.'/templates/confirmation');
$tpl->set_unknowns('remove'); // (remove:=default, keep, comment)
// Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
$tpl->debug = 0;

// Check if payment status and payment method is set
if (is_string($payment_status) && is_string($payment_method)) {

	// Look for payment method language file
	if (LANGUAGE_LOADED) {
		include(__DIR__.'/payment_methods/'.$payment_method.'/languages/EN.php');
		if (file_exists($sFile = __DIR__.'/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
			include $sFile;
		}
	}	

	$aToTpl = array(
			'SETTING_CONTINUE_URL'  => $setting_continue_url,
			'TXT_CONTINUE_SHOPPING' => $TXT_BAKERY['CONTINUE_SHOPPING'],
			'TXT_CANCEL_ORDER'      => $TXT_BAKERY['CANCEL_ORDER'],
			'TXT_JS_CONFIRM'        => $TXT_BAKERY['JS_CONFIRM']
	);

	// ERROR
	if ($payment_status == "error") {

		// Show error message using template file
		$tpl->set_file('error', 'error.htm');
		$tpl->set_var('ERROR',$TXT_BAKERY[$payment_method]['ERROR']);                
		$tpl->set_var($aToTpl);
		$tpl->pparse('output', 'error');
		return;
	}

	// CANCELED
	if ($payment_status == "canceled") {

		// Show message using template file
		$tpl->set_file('canceled', 'canceled.htm');
		$tpl->set_var('CANCELED', $TXT_BAKERY[$payment_method]['CANCELED']);
		$tpl->set_var($aToTpl);
		$tpl->pparse('output', 'canceled');
		return;
	}

	// SUCCESS OR PENDING
	if ($payment_status == "success" || $payment_status == "pending") {

		// Get the order id from the session var or,
		// in case this script has been called by a payment method directly (eg. paypal ipn),
		// use the one provided by the payment gateway
		$iOrderID = isset($order_id) && is_numeric($order_id) ? $order_id : $_SESSION['bxt']['order_id'];
		
		// Initialize var
		$email_sent = 2;

		// UPDATE DB

		// Check if we have to update db and send emails
		$query_customers = $database->query("SELECT submitted FROM {BXT}_customer WHERE order_id = '$iOrderID' AND submitted = 'no' AND  status = 'none'");

		if ($query_customers->numRows() == 1) {

			// Reset email sent to 0
			$email_sent = 0;

			// Consecutive numbering of invoice numbers
			$new_invoice_id = $database->get_one("SELECT MAX(invoice_id) + 1 AS new_invoice_id FROM {BXT}_customer");
			// Update db
			$database->query("UPDATE {BXT}_customer SET submitted = '$payment_method', status = 'ordered', invoice_id = '$new_invoice_id' WHERE order_id = '$iOrderID'");


			// SEND CONFIRMATION EMAILS

			// Get the email templates from the db
			$query_payment_methods = $database->query("SELECT cust_email_subject, cust_email_body, shop_email_subject, shop_email_body FROM {BXT}_payment_methods WHERE directory = '$payment_method'");
			if ($query_payment_methods->numRows() > 0) {
				$payment_methods    = $query_payment_methods->fetchRow();
				$cust_email_subject = lazystrip($payment_methods['cust_email_subject']);
				$cust_email_body    = lazystrip($payment_methods['cust_email_body']);
				$shop_email_subject = lazystrip($payment_methods['shop_email_subject']);
				$shop_email_body    = lazystrip($payment_methods['shop_email_body']);
			}
	
			// Get email data string from db customer table
			$query_customer = $database->query("SELECT invoice FROM {BXT}_customer WHERE order_id = '$iOrderID'");
			if ($query_customer->numRows() > 0) {
				$customer = $query_customer->fetchRow();
				if (!empty($customer['invoice'])) {
					// Convert string to array
					$invoice = lazystrip($customer['invoice']);
					$invoice_array = lazyexplode('&&&&&', $invoice);
	
					// Email vars to replace placeholders in the email body
					$setting_shop_name = $invoice_array[1];
					$bank_account      = $invoice_array[2];
					$cust_name         = $invoice_array[3];
					$cust_email        = $invoice_array[7];
					$shop_email        = $invoice_array[10];
					$address           = $invoice_array[11];
					$cust_address      = $invoice_array[12];
					$ship_address      = $invoice_array[13];
					$item_list         = $invoice_array[14];
					$cust_tax_no       = $invoice_array[15];
					$cust_msg          = $invoice_array[16];
				}
			}
			
			// In case this script has been called by a payment method directly (eg. paypal ipn)
			// we have to add the shop email var
			$setting_shop_email = isset($setting_shop_email) ? $setting_shop_email : $shop_email;
	
	
			// Make transaction status notice
			$transaction_status_notice = '';
			if ($payment_status == 'pending' && isset($TXT_BAKERY[$payment_method]['TRANSACTION_STATUS'])) {
                            $transaction_status_notice  = $TXT_BAKERY[$payment_method]['TRANSACTION_STATUS'];
			}

                        
			$aTokens = array(
				"\r"                   => '', // Remove all "\r" in emails to avoid double line breaks
				'[ORDER_ID]'           => $iOrderID, 
				'[SHOP_NAME]'          => $setting_shop_name, 
				'[BANK_ACCOUNT]'       => $bank_account, 
				'[TRANSACTION_STATUS]' => $transaction_status_notice, 
				'[CUSTOMER_NAME]'      => $cust_name, 
				'[ADDRESS]'            => $address, 
				'[CUST_ADDRESS]'       => $cust_address, 
				'[SHIPPING_ADDRESS]'   => $ship_address, 
				'[CUST_EMAIL]'         => $cust_email, 
				'[ITEM_LIST]'          => $item_list, 
				'[CUST_TAX_NO]'        => $cust_tax_no, 
				'[CUST_MSG]'           => empty($cust_msg) ? "\t".$TEXT['NONE'] : $cust_msg
			);

			$cust_email_subject = strtr($cust_email_subject, $aTokens);
			$cust_email_body    = strtr($cust_email_body, $aTokens);
			$shop_email_subject = strtr($shop_email_subject, $aTokens);
			$shop_email_body    = strtr($shop_email_body, $aTokens);
	
			// Instantiate WBCE Mailer
			$mailer = new Mailer();

			// Force Plaintext
			$mailer->isHTML(false);

			// set From (and Sender / Return-Path)
			$mailer->setFrom($setting_shop_email, $setting_shop_name);

			// Confirmation mail to customer / increase $email_sent counter			
			$mailer->addAddress($cust_email);
			$mailer->Subject = $cust_email_subject;
			$mailer->Body = $cust_email_body;
			if($mailer->send()){
				$email_sent++;
			}

			// Clear address data before creating new mail
			$mailer->clearAddresses();
			
			// Order info mail to shop owner / increase $email_sent counter
			$mailer->addReplyTo($cust_email,$cust_name);
			$mailer->addAddress($setting_shop_email);
			$mailer->Subject = $shop_email_subject;
			$mailer->Body = $shop_email_body;
			if($mailer->send()){
				$email_sent++;
			}
		}


		// WEBSITE CONFIRMATION

		// In case payment data has been transfered in the background (eg. paypal ipn)
		// there is no way to show a confirmation page to the customer
		if (!isset($no_confirmation)) {

			// Show confirmation using template file
			if ($payment_status == 'success') {
				$tpl->set_file('success', 'success.htm');
				$tpl->set_var(array(
					'TXT_SUCCESS'       => $TXT_BAKERY[$payment_method]['SUCCESS'],
					'TXT_SHIPMENT'      => $TXT_BAKERY[$payment_method]['SHIPMENT'],
					'TXT_THANK_U_ORDER' => $TXT_BAKERY['THANK_U_ORDER']
				));
				$tpl->pparse('output', 'success');
			}
			elseif ($payment_status == "pending") {
				$tpl->set_file('pending', 'pending.htm');
				$tpl->set_var(array(
					'TXT_PENDING'       => $TXT_BAKERY[$payment_method]['PENDING'],
					'TXT_SHIPMENT'      => $TXT_BAKERY[$payment_method]['SHIPMENT'],
					'TXT_THANK_U_ORDER' => $TXT_BAKERY['THANK_U_ORDER']
				));
				$tpl->pparse('output', 'pending');
			}
	
			// If emails have not been sent show additional email error using template file	
			if ($email_sent < 2) {
				$shop_email_link = '<a href="mailto:' . $setting_shop_email . '">' . $setting_shop_email . '</a>';
				$tpl->set_file('email_error', 'email_error.htm');
				$tpl->set_var('ERR_EMAIL_NOT_SENT',$TXT_BAKERY['ERR_EMAIL_NOT_SENT'] . ':<br />' . $shop_email_link);
				$tpl->pparse('output', 'email_error');
			}
		}

		// Clean up the session array
		if (isset($_SESSION['bxt'])) {
			unset($_SESSION['bxt']);
		}
		return;
	}
} else {
	echo '<b>ERROR: Payment status or payment method is not defined.</b>';
	return;
}
?><script>var page = 'checkout_confirmation.php';</script>