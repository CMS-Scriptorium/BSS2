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

// Include WB template parser and create template object
require_once WB_PATH.'/include/phplib/template.inc';
$tpl = new Template(__DIR__.'/templates/pay_methods');
$tpl->set_unknowns('remove'); // (remove:=default, keep, comment)
// Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
$tpl->debug = 0;

// Include WB functions file
require_once WB_PATH.'/framework/functions.php';

// Assign page filename for tracking with Google Analytics _trackPageview() function
global $ga_page;
$ga_page = '/view_pay_method.php';


// TITLE, CUSTOMERS MESSAGE AND TERMS & CONDITIONS
// ***********************************************

// Customers message
$display_cust_msg = $setting_cust_msg == 'show' ? 'block' : 'none';
$cust_msg         = '';
if (!empty($_SESSION['bxt']['cust_msg'])) {
	$cust_msg = lazyspecial($_SESSION['bxt']['cust_msg'], ENT_QUOTES);
}

// If tac url is set customers have to accept the terms & conditions
$display_agree = 'none';
$display_tac   = 'none';
$tac_link      = '<a href="'.$setting_tac_url.'" target="_blank" rel="nofollow,noopener">'.$TXT_BAKERY['AGREE'].' '.$setting_shop_name.'</a>';
if (!empty($setting_tac_url)) {
	$display_agree = 'block';
	$display_tac   = 'block';
}

$cancellation_link = $TXT_BAKERY['CANCELLATION_PRE'].' <a href="'.$setting_cancellation_url.'" target="_blank" rel="nofollow,noopener">'.$TXT_BAKERY['CANCELLATION'].'</a> '.$TXT_BAKERY['CANCELLATION_POST'];
$privacy_link      = $TXT_BAKERY['PRIVACY_PRE'].' <a href="'.$setting_privacy_url.'" target="_blank" rel="nofollow,noopener">'.$TXT_BAKERY['PRIVACY'].'</a> '.$TXT_BAKERY['PRIVACY_POST'];

// No right of revocation when purchasing digital goods
$display_no_revocation = 'none';
if ($setting_no_revocation == 'e-goods') {
	$display_agree         = 'block';
	$display_no_revocation = 'block';
}

// Show title, customers message and terms & conditions using template file
$tpl->set_file('pay_methods_title', 'title.htm');
$tpl->set_var(array(
	'WB_URL'=>WB_URL,
	'SETTING_CONTINUE_URL'   => $setting_continue_url,
	'TXT_TAC_AND_PAY_METHOD' => $TXT_BAKERY['TAC_AND_PAY_METHOD'],
	'DISPLAY_AGREE'          => $display_agree,
	'DISPLAY_TAC'            => $display_tac,
	'TXT_JS_AGREE'           => $TXT_BAKERY['JS_AGREE'],
	'TAC_LINK'               => $tac_link,
	'CANCELLATION_LINK'      => $cancellation_link,
	'PRIVACY_LINK'           => $privacy_link,
	'DISPLAY_NO_REVOCATION'  => $display_no_revocation,
	'TXT_NO_REVOCATION'      => $TXT_BAKERY['FULL_WAIVER_OF_RIGHT_TO_REVOKE'],
	'TXT_PAY_METHOD'         => $TXT_BAKERY['SELECT_PAY_METHOD'],
	'DISPLAY_CUST_MSG'       => $display_cust_msg,
	'TXT_ENTER_CUST_MSG'     => $TXT_BAKERY['ENTER_CUST_MSG'],
	'CUST_MSG'               => $cust_msg
));
$tpl->pparse('output', 'pay_methods_title');


// DISPLAY LIST OF PAYMENT METHODS
// *******************************

// Only show payment method/payment gateway if we have to
if ($num_payment_methods > 0) {
	foreach ($setting_payment_methods as $payment_method) {
		if (is_file($sFile = __DIR__.'/payment_methods/'.$payment_method.'/gateway.php')) {
			include $sFile;
		}
	}
} else {
	// Show payment methods error using template file
	$tpl->set_file('pay_methods_error', 'error.htm');
	$tpl->set_var(array(
		'ERR_NO_PAYMENT_METHOD'	=> $TXT_BAKERY['ERR_NO_PAYMENT_METHOD']
	));
	$tpl->pparse('output', 'pay_methods_error');
}

// Show payment methods footer using template file
$tpl->set_file('pay_methods_footer', 'footer.htm');
$tpl->set_var(array(
	'ERR_NO_PAYMENT_METHOD'	=> $TXT_BAKERY['ERR_NO_PAYMENT_METHOD']
));
$tpl->pparse('output', 'pay_methods_footer');
?><script>var page = 'checkout_payment_methods.php';</script>