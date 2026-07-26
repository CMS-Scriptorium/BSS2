<?php
/*  
*/
// Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly"); 

// Look for language file

require __DIR__.'/languages/EN.php';
if (file_exists($sLCFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
    require $sLCFile;
}

$iOrderID    = $_SESSION['bxt']['order_id'];
$sClientMail = $_SESSION['bxt']['cust']['email'];
$sClientName = $_SESSION['bxt']['cust']['first_name'] .' '. $_SESSION['bxt']['cust']['last_name'];
$sOutput     = bxt_cartContentRaw($iOrderID);
//debug_dump($sOutput, '$sOutput');
$sSingleRequestLink = WB_URL."/modules/bakery/modify_single_request.php"
        . "?page_id=".PAGE_ID."&section_id=".$section_id."&order_id=".$iOrderID;

/* ***************************************************************** */
 #
 #             SEND EMAIL TO SHOP OWNER
 #
/* ***************************************************************** */
$sMail_subject = $TXT_BAKERY['REQUEST_EMAIL_SUBJECT'];
$sMail_body    = $TXT_BAKERY['REQUEST_EMAIL_BODY'];
$sMail_body    .= "\n<span>".$sOutput."</span>";
$sMail_body    .= "\n".sprintf($TXT_BAKERY['REQUEST_SEE_LINK'], $sSingleRequestLink);

$setting_shop_email = isset($setting_shop_email) ? $setting_shop_email : $shop_email;

// Replace placeholders by values in the email body
$aTokens = array(
    "\r" => '', 
    "\n" => '<br>', 
    '[ORDER_ID]'     => $iOrderID, 
    '[SHOP_NAME]'    => $setting_shop_name, 
    '[CUST_EMAIL]'   => $cust_email, 
    '[CUST_MSG]'     => empty($cust_msg) ? "\t".$TEXT['NONE'] : $cust_msg
);

$sMail_subject = strtr($sMail_subject, $aTokens);
$sMail_body    = strtr($sMail_body, $aTokens);

$oMailer = new Mailer(); // Instantiate WBCE Mailer
$oMailer->isHTML(true); // Force Plaintext

// set From (and Sender / Return-Path)
$oMailer->setFrom($setting_shop_email, $setting_shop_name);
$oMailer->addReplyTo($sClientMail, $sClientName);


// Order info mail to shop owner / increase $email_sent counter
$oMailer->addAddress($setting_shop_email);
$oMailer->Subject = $sMail_subject;
$oMailer->Body    = $sMail_body;
if($oMailer->send()){
    unset($oMailer); // Instantiate WBCE Mailer     
} else {
    debug_dump($oMailer->ErrorInfo, 'PhpMail ERROR');
}


if(isset($_SESSION['bxt']['send_copy']) && $_SESSION['bxt']['send_copy'] == true ){
    /* ***************************************************************** */
    #
    #                     SEND EMAIL TO PROSPECT
    #
   /* ***************************************************************** */
    $sMail_subject = $TXT_BAKERY['REQUEST_EMAIL_SUBJECT_CLIENT'];
    $sMail_body    = $TXT_BAKERY['REQUEST_EMAIL_BODY_CLIENT'];
    $sMail_body    .= "\n<span>".$sOutput."</span>";

    $setting_shop_email = isset($setting_shop_email) ? $setting_shop_email : $shop_email;

    // Replace placeholders by values in the email body
    $aTokens = array(
        "\r" => '', 
        "\n" => '<br>', 
        '[ORDER_ID]'     => $iOrderID, 
        '[SHOP_NAME]'    => $setting_shop_name, 
        '[CUST_EMAIL]'   => $cust_email, 
        '[CUST_MSG]'     => empty($cust_msg) ? "\t".$TEXT['NONE'] : $cust_msg
    );

    $sMail_subject = strtr($sMail_subject, $aTokens);
    $sMail_body    = strtr($sMail_body, $aTokens);

    $oMailer = new Mailer(); // Instantiate WBCE Mailer
    $oMailer->isHTML(true); // Force Plaintext

    // set From (and Sender / Return-Path)
    $oMailer->setFrom($setting_shop_email, $setting_shop_name);

    // Request email address to client
    $oMailer->addAddress($sClientMail);
    $oMailer->Subject = $sMail_subject;
    $oMailer->Body    = $sMail_body;
    
    
    if($oMailer->send()){
        unset($oMailer);     
    } else {
        debug_dump($oMailer->ErrorInfo, 'PhpMail ERROR');
    }
    
}

include __DIR__ . '/templates/checkout_request_confirmation.tpl.php';   


// Clean up the session array
if (isset($_SESSION['bxt'])) {
    unset($_SESSION['bxt']);
}