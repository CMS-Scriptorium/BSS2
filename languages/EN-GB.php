<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org)
  Copyright (C) 2007 - 2017, Christoph Marti

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



/*
  ***********************
  TRANSLATORS PLEASE NOTE
  ***********************
  
  Thank you for translating Bakery!
  Include your credits in the header of this file right above the licence terms.
  

*/

/*
  ***************
  BRITISH ENGLISH
  ***************

  Uses British instead of American English terms:
  ZIP		=> Postcode
  Zip		=> Postcode
  State		=> County
  state		=> county
  Cart		=> Basket
  cart		=> basket
  Shipping	=> Postage
  shipping	=> postage
  color		=> colour
  canceled	=> cancelled
*/



// MODUL DESCRIPTION
$module_description = 'Bakery is a WBCE shopping cart module with catalog, basket, stock administration, order administration and invoice print feature. Payment in advance, invoice, cash on delivery and/or different payment gateways. Further information can be found on the <a href="http://www.bakery-shop.ch" target="_blank">Bakery Website</a>.';

// MODUL BAKERY VARIOUS TEXT
$TXT_BAKERY['SETTINGS'] = 'Settings';
$TXT_BAKERY['GENERAL_SETTINGS'] = 'General Settings';
$TXT_BAKERY['PAGE_SETTINGS'] = 'Page Settings';
$TXT_BAKERY['PAYMENT_METHODS'] = 'Payment Methods';
$TXT_BAKERY['SHOP'] = 'Shop';
$TXT_BAKERY['PAYMENT'] = 'Payment';
$TXT_BAKERY['EMAIL'] = 'Email';
$TXT_BAKERY['LAYOUT'] = 'Layout';
$TXT_BAKERY['PAGE_OFFLINE'] = 'Set Page offline';
$TXT_BAKERY['OFFLINE_TEXT'] = 'Offline Text';
$TXT_BAKERY['CONTINUE_URL'] = 'Continue Shopping URL';
$TXT_BAKERY['OVERVIEW'] = 'Overview';
$TXT_BAKERY['DETAIL'] = 'Item Detail';
$TXT_BAKERY['SHOP_NAME'] = 'Shop Name';
$TXT_BAKERY['TAC_URL'] = 'Terms &amp; Conditions URL';
$TXT_BAKERY['CANCELLATION_URL'] = 'Cancellation terms URL';
$TXT_BAKERY['PRIVACY_URL'] = 'Privacy Policy URL';
$TXT_BAKERY['SHOP_EMAIL'] = 'Shop Email';
$TXT_BAKERY['SHOP_COUNTRY'] = 'Shop Country';
$TXT_BAKERY['SHOP_STATE'] = 'Shop County';
$TXT_BAKERY['ADDRESS_FORM'] = 'Address Form';
$TXT_BAKERY['SHIPPING_FORM_REQUEST'] = 'on request';
$TXT_BAKERY['SHIPPING_FORM_HIDEABLE'] = 'hideable';
$TXT_BAKERY['SHIPPING_FORM_ALWAYS'] = 'always';
$TXT_BAKERY['DOMESTIC_ADDRESSES_HIDE_COUNTRY'] = 'Display domestic addresses without country';
$TXT_BAKERY['SHOW_COMPANY_FIELD'] = 'Show Company Field';
$TXT_BAKERY['SHOW_STATE_FIELD'] = 'Show County Field';
$TXT_BAKERY['SHOW_TAX_NO_FIELD'] = 'Show VAT No Field';
$TXT_BAKERY['SHOW_ZIP_END_OF_ADDRESS'] = 'Postcode at End of Address';
$TXT_BAKERY['RIGHT_OF_REVOCATION'] = 'Right of revocation';
$TXT_BAKERY['WAIVER_OF_RIGHT_TO_REVOKE'] = 'Waiving the right to revoke when purchasing digital products';
$TXT_BAKERY['CUST_MESSAGE'] = 'Customers Message';
$TXT_BAKERY['SHOW_TEXTAREA'] = 'Show Textarea';
$TXT_BAKERY['ALLOW_OUT_OF_STOCK_ORDERS'] = 'Allow out of Stock Orders';
$TXT_BAKERY['SKIP_CART_AFTER_ADDING_ITEM'] = 'Skip basket view after adding item to basket';
$TXT_BAKERY['MINICART_STRONGLY_RECOMMENDED'] = 'MiniCart strongly recommended';
$TXT_BAKERY['DISPLAY_SETTINGS_TO_ADMIN_ONLY'] = 'Display Settings to Admin (id = 1) only';
$TXT_BAKERY['FREE_DEFINABLE_FIELD'] = 'Free definable Field';
$TXT_BAKERY['STOCK_MODE_TEXT'] = 'Show Stock to Customers as Text';
$TXT_BAKERY['STOCK_MODE_IMAGE'] = 'Show Stock to Customers as Image';
$TXT_BAKERY['STOCK_MODE_NUMBER'] = 'Show Stock to Customers as Number';
$TXT_BAKERY['STOCK_MODE_NONE'] = 'Do not show Stock to Customers';
$TXT_BAKERY['SHOP_CURRENCY'] = 'Shop Currency Code';
$TXT_BAKERY['SEPARATOR_FOR'] = 'Separator for';
$TXT_BAKERY['DECIMAL'] = 'Decimal';
$TXT_BAKERY['GROUP_OF_THOUSANDS'] = 'Group of Thousands';

$TXT_BAKERY['PAYMENT_METHOD'] = 'Payment Method';
$TXT_BAKERY['SELECT_PAYMENT_METHOD'] = 'Select Payment Method';
$TXT_BAKERY['SELECT_PAYMENT_METHODS'] = 'Select Payment Methods';
$TXT_BAKERY['NO_PAYMENT_METHOD_SETTING'] = 'No Payment Method Setting to be set.';
$TXT_BAKERY['NOTICE'] = 'Notice';
$TXT_BAKERY['DAYS'] = 'Days';

$TXT_BAKERY['TAX_RATE'] = 'Tax Rate';
$TXT_BAKERY['SAVED_TAX_RATE'] = 'Currently saved Tax Rate';
$TXT_BAKERY['SET_TAX_RATE'] = 'Set Tax Rate';
$TXT_BAKERY['TAX_INCLUDED'] = 'Prices incl. Tax';
$TXT_BAKERY['TAX_GROUP'] = 'EU Tax Zone Countries';
$TXT_BAKERY['DOMESTIC'] = 'domestic';
$TXT_BAKERY['ZONE_COUNTRIES'] = 'to specific Countries (Multiple Choice)';
$TXT_BAKERY['ABROAD'] = 'abroad';
$TXT_BAKERY['PER_ITEM'] = 'per Product';
$TXT_BAKERY['SHIPPING_BASED_ON'] = 'Postage based on';
$TXT_BAKERY['SHIPPING_METHOD_FLAT'] = 'a flat Amount';
$TXT_BAKERY['SHIPPING_METHOD_ITEMS'] = 'Number of Items';
$TXT_BAKERY['SHIPPING_METHOD_POSITIONS'] = 'Number of Positions';
$TXT_BAKERY['SHIPPING_METHOD_PERCENTAGE'] = 'Percentage of Subtotal';
$TXT_BAKERY['SHIPPING_METHOD_HIGHEST'] = 'Item with the highest Postage Cost';
$TXT_BAKERY['SHIPPING_METHOD_NONE'] = 'none';
$TXT_BAKERY['FREE_SHIPPING'] = 'Free Postage';
$TXT_BAKERY['OVER'] = 'over';
$TXT_BAKERY['SHOW_FREE_SHIPPING_MSG'] = 'Inform Customers about free Postage Limit';
$TXT_BAKERY['EMAIL_SUBJECT'] = 'Email Subject';
$TXT_BAKERY['EMAIL_BODY'] = 'Email Body';
$TXT_BAKERY['ITEM'] = 'Product';
$TXT_BAKERY['ITEMS'] = 'Products';
$TXT_BAKERY['ITEMS_PER_PAGE'] = 'Products per Page';
$TXT_BAKERY['NUMBER_OF_COLUMNS'] = 'Number of Columns';
$TXT_BAKERY['USE_CAPTCHA'] = 'Use Captcha';
$TXT_BAKERY['MODIFY_THIS'] = 'Update Page Settings of <b>current</b> Bakery Page only.';
$TXT_BAKERY['MODIFY_ALL'] = 'Update Page Settings (without &quot;Continue Shopping URL&quot;) of <b>all</b> Shop Pages.';
$TXT_BAKERY['MODIFY_MULTIPLE'] = 'Update Page Settings (without &quot;Continue Shopping URL&quot;) of <b>selected</b> Shop Page(s) (Multiple Choice):';

$TXT_BAKERY['ADD_ITEM'] = 'Add Product';
$TXT_BAKERY['NAME'] = 'Product Name';
$TXT_BAKERY['SKU'] = 'SKU#';
$TXT_BAKERY['PRICE'] = 'Price';
$TXT_BAKERY['OPTION_NAME'] = 'Option Name';
$TXT_BAKERY['OPTION_ATTRIBUTES'] = 'Option Attributes';
$TXT_BAKERY['OPTION_PRICE'] = 'Option Price';
$TXT_BAKERY['ITEM_OPTIONS'] = 'Item Options';
$TXT_BAKERY['EG_OPTION_NAME'] = 'eg. colour';
$TXT_BAKERY['EG_OPTION_ATTRIBUTE'] = 'eg. red';
$TXT_BAKERY['INCL'] = 'Inclusive';
$TXT_BAKERY['EXCL_SHIPPING'] = 'excluding Postage';
$TXT_BAKERY['EXCL_SHIPPING_TAX'] = 'excluding Postage and Tax';
$TXT_BAKERY['TAX'] = 'Tax';
$TXT_BAKERY['QUANTITY'] = 'Quantity';
$TXT_BAKERY['SUM'] = 'Sum';
$TXT_BAKERY['SUBTOTAL'] = 'Subtotal';
$TXT_BAKERY['TOTAL'] = 'Total';
$TXT_BAKERY['SHIPPING'] = 'Postage';
$TXT_BAKERY['SHIPPING_COST'] = 'Shipping';
$TXT_BAKERY['DESCRIPTION'] = 'Brief Description';
$TXT_BAKERY['FULL_DESC'] = 'Full Description';
$TXT_BAKERY['PREVIEW'] = 'Preview';
$TXT_BAKERY['FILE_NAME'] = 'File Name';
$TXT_BAKERY['MAIN_IMAGE'] = 'Main Image';
$TXT_BAKERY['THUMBNAIL'] = 'Thumbnail';
$TXT_BAKERY['CAPTION'] = 'Caption';
$TXT_BAKERY['POSITION'] = 'Position';
$TXT_BAKERY['IMAGE'] = 'Image';
$TXT_BAKERY['IMAGES'] = 'Images';
$TXT_BAKERY['MAX_WIDTH'] = 'max. Width (px)';
$TXT_BAKERY['MAX_HEIGHT'] = 'max. Height (px)';
$TXT_BAKERY['JPG_QUALITY'] = 'JPG Quality';
$TXT_BAKERY['NON'] = 'non';
$TXT_BAKERY['ITEM_TO_PAGE'] = 'Move Item to Page';
$TXT_BAKERY['MOVE'] = 'move';
$TXT_BAKERY['DUPLICATE'] = 'duplicate';

$TXT_BAKERY['CART'] = 'Shopping Basket';
$TXT_BAKERY['ORDER'] = 'Order';
$TXT_BAKERY['ORDER_ID'] = 'Order#';
$TXT_BAKERY['INVOICE_ID'] = 'Invoice#';
$TXT_BAKERY['CONTINUE_SHOPPING'] = 'Continue shopping';
$TXT_BAKERY['ADD_TO_CART'] = 'Add to basket';
$TXT_BAKERY['VIEW_CART'] = 'View basket';
$TXT_BAKERY['UPDATE_CART'] = 'Update basket';
$TXT_BAKERY['UPDATE_CART_SUCCESS'] = 'Basket was updated successfully.';
$TXT_BAKERY['SUBMIT_ORDER'] = 'Submit order';
$TXT_BAKERY['BUY'] = 'Buy';
$TXT_BAKERY['CANCEL_ORDER'] = 'Cancel order';
$TXT_BAKERY['ORDER_SUMMARY'] = 'Review and place your order';

$TXT_BAKERY['ADDRESS'] = 'Address';
$TXT_BAKERY['ADDRESSES'] = 'Addresses';
$TXT_BAKERY['MODIFY_ADDRESS'] = 'Modify Address';
$TXT_BAKERY['FILL_IN_ADDRESS'] = 'Please fill in your address';
$TXT_BAKERY['SHIP_ADDRESS'] = 'Shipping Address';
$TXT_BAKERY['ADD_SHIP_FORM'] = 'Add Shipping Address';
$TXT_BAKERY['HIDE_SHIP_FORM'] = 'Hide Shipping Address';
$TXT_BAKERY['FILL_IN_SHIP_ADDRESS'] = 'Please fill in the shipping address';
$TXT_BAKERY['TAC'] = 'Terms and Conditions';
$TXT_BAKERY['AGREE'] = 'I agree to the terms and conditions of';
$TXT_BAKERY['CANCELLATION'] = 'cancellation terms and conditions';
$TXT_BAKERY['CANCELLATION_PRE'] = 'I have read and accepted the';
$TXT_BAKERY['CANCELLATION_POST'] = '';
$TXT_BAKERY['PRIVACY'] = 'privacy policy';
$TXT_BAKERY['PRIVACY_PRE'] = 'I have read and accepted the';
$TXT_BAKERY['PRIVACY_POST'] = '';
$TXT_BAKERY['FULL_WAIVER_OF_RIGHT_TO_REVOKE'] = 'I agree and expressly require that you start the execution of the commissioned service before the end of the cancellation period. I am aware that I will lose my right of revocation at the time you have completed the contract.';
$TXT_BAKERY['CANCEL'] = 'You have cancelled your Order.';
$TXT_BAKERY['DELETED'] = 'All your details have been deleted.';
$TXT_BAKERY['THANK_U_VISIT'] = 'Thank you for visiting!';

// MODUL BAKERY CUSTOMER DATA
$TXT_BAKERY['CUST_EMAIL'] = 'Email Address';
$TXT_BAKERY['CUST_CONFIRM_EMAIL'] = 'Confirm email';
$TXT_BAKERY['CUST_COMPANY'] = 'Company';
$TXT_BAKERY['CUST_FIRST_NAME'] = 'First Name';
$TXT_BAKERY['CUST_LAST_NAME'] = 'Last Name';
$TXT_BAKERY['CUST_TAX_NO'] = 'VAT No';
$TXT_BAKERY['OPTIONAL'] = 'optional';
$TXT_BAKERY['CUST_ADDRESS'] = 'Address';
$TXT_BAKERY['CUST_CITY'] = 'City';
$TXT_BAKERY['CUST_STATE'] = 'County';
$TXT_BAKERY['CUST_COUNTRY'] = 'Country';
$TXT_BAKERY['CUST_ZIP'] = 'Postcode';
$TXT_BAKERY['CUST_PHONE'] = 'Telephone';

// MODUL BAKERY PROCESS PAYMENT
$TXT_BAKERY['TAC_AND_PAY_METHOD'] = 'Terms &amp; Conditions and Payment Method';
$TXT_BAKERY['ENTER_CUST_MSG'] = 'You are welcome to send us a Message';
$TXT_BAKERY['SELECT_PAY_METHOD'] = 'Please select a Payment Method';
$TXT_BAKERY['SELECTED_PAY_METHOD'] = 'Selected Payment Method';
$TXT_BAKERY['MODIFY_PAY_METHODS'] = 'Modify Payment Method';
$TXT_BAKERY['THANK_U_ORDER'] = 'Thank you for your order!';

// MODUL BAKERY ORDER ADMINISTRATION
$TXT_BAKERY['ORDER_ADMIN'] = 'Order Administration';
$TXT_BAKERY['ORDER_ARCHIVED'] = 'Archived Orders';
$TXT_BAKERY['ORDER_CURRENT'] = 'Current Orders';

$TXT_BAKERY['CUSTOMER'] = 'Customer';
$TXT_BAKERY['STATUS'] = 'Status';
$TXT_BAKERY['ORDER_DATE'] = 'Order date';
$TXT_BAKERY['EDIT_ORDER'] = 'Edit customer details';

$TXT_BAKERY['STATUS_ORDERED'] = 'Ordered';
$TXT_BAKERY['STATUS_SHIPPED'] = 'Shipped';
$TXT_BAKERY['STATUS_BUSY'] = 'Payment in Process';
$TXT_BAKERY['STATUS_INVOICE'] = 'Invoice';
$TXT_BAKERY['STATUS_REMINDER'] = 'Reminder';
$TXT_BAKERY['STATUS_PAID'] = 'Paid';
$TXT_BAKERY['STATUS_ARCHIVE'] = 'archive';
$TXT_BAKERY['STATUS_ARCHIVED'] = 'archived';
$TXT_BAKERY['STATUS_CANCEL'] = 'cancel';
$TXT_BAKERY['STATUS_CANCELED'] = 'cancelled';

$TXT_BAKERY['PRINT'] = 'Print';
$TXT_BAKERY['INVOICE'] = 'Invoice';
$TXT_BAKERY['DELIVERY_NOTE'] = 'Delivery note';
$TXT_BAKERY['REMINDER'] = 'Reminder';
$TXT_BAKERY['PRINT_INVOICE'] = 'Print invoice';

$TXT_BAKERY['SEND_INVOICE'] = 'Send Invoice as an HTML email.';
$TXT_BAKERY['INVOICE_ALREADY_SENT'] = 'The Invoice has been sent %d times.';
$TXT_BAKERY['INVOICE_HAS_BEEN_SENT_SUCCESSFULLY'] = 'The Invoice has been mailed to the customer successfully.';

// MODUL BAKERY STOCK ADMINISTRATION
$TXT_BAKERY['STOCK_ADMIN'] = 'Stock Administration';
$TXT_BAKERY['STOCK'] = 'Stock';
$TXT_BAKERY['IN_STOCK'] = 'in Stock';
$TXT_BAKERY['SHORT_OF_STOCK'] = 'Short of Stock';
$TXT_BAKERY['OUT_OF_STOCK'] = 'Out of Stock';
$TXT_BAKERY['N/A'] = 'n/a';
$TXT_BAKERY['ALL'] = 'all';
$TXT_BAKERY['ORDER_ASC'] = 'order ascending';
$TXT_BAKERY['ORDER_DESC'] = 'order descending';
$TXT_BAKERY['SHORT_OF_STOCK_SUBSEQUENT_DELIVERY'] = 'These items are short of stock.<br />You will get a subsequent delivery';
$TXT_BAKERY['SHORT_OF_STOCK_QUANTITY_CAPPED'] = 'These items are short of stock - the quantity has been adjusted';
$TXT_BAKERY['AVAILABLE_QUANTITY'] = 'are available yet';

// EDIT CSS BUTTON
$GLOBALS['TEXT']['CAP_EDIT_CSS'] = 'Edit CSS';

// MODUL BAKERY ERROR MESSAGES (Important: Do not remove <br /> !)
$TXT_BAKERY['ERR_INVALID_FILE_NAME'] = 'Invalid file name';
$TXT_BAKERY['ERR_FILE_NAME_TOO_LONG'] = 'The file name is too long';
$TXT_BAKERY['ERR_OFFLINE_TEXT'] = 'The Shop XXX is offline for maintenance until XXX. Please come back later.<br />Sorry for any inconvenience.';
$TXT_BAKERY['ERR_NO_ORDER_ID'] = 'No SKU number found.';
$TXT_BAKERY['ERR_CART_EMPTY'] = 'The shopping basket is empty.'; 
$TXT_BAKERY['ERR_ITEM_EXISTS'] = 'This item is already present in your basket.<br />You can change the quantity in the shopping basket.';
$TXT_BAKERY['ERR_QUANTITY_ZERO'] = 'The quantity must be a number greater than zero!';
$TXT_BAKERY['ERR_FIELD_BLANK'] = 'The fields highlighted in red are blank. Please enter the required information.';
$TXT_BAKERY['ERR_EMAILS_NOT_MATCHED'] = 'The email addresses did not match!';
$TXT_BAKERY['ERR_INVAL_NAME'] = 'is not a valid name.';
$TXT_BAKERY['ERR_INVAL_CUST_TAX_NO'] = 'is not a valid VAT No.';
$TXT_BAKERY['ERR_INVAL_STREET'] = 'is not a valid address.';
$TXT_BAKERY['ERR_INVAL_CITY'] = 'is not a valid city.';
$TXT_BAKERY['ERR_INVAL_STATE'] = 'is not a valid county.';
$TXT_BAKERY['ERR_INVAL_COUNTRY'] = 'is not a valid country.';
$TXT_BAKERY['ERR_INVAL_EMAIL'] = 'is not a valid email address.';
$TXT_BAKERY['ERR_INVAL_ZIP'] = 'is not a valid zip.';
$TXT_BAKERY['ERR_INVAL_PHONE'] = 'is not a valid phone number.';
$TXT_BAKERY['ERR_INVAL_TRY_AGAIN'] = 'Please verify your entries!';
$TXT_BAKERY['ERR_AGREE'] = 'We can only complete your order if you agree to our terms and conditions. Thank you for understanding!';
$TXT_BAKERY['ERR_NO_PAYMENT_METHOD'] = 'No Payment Method activated.';
$TXT_BAKERY['ERR_EMAIL_NOT_SENT'] = 'Unable to send email. Your order is still valid. Please contact the shop admin on';

// MODUL BAKERY JAVASCRIPT MESSAGES (Important: Do not remove \n !)
$TXT_BAKERY['JS_CONFIRM'] = "Do you really want to cancel your order?";
$TXT_BAKERY['JS_AGREE'] = "We can only complete your order if you agree to our terms and conditions.\nThank you for understanding!";
$TXT_BAKERY['JS_BLANK_CAPTCHA'] = "Please enter the verification number (also known as Captcha)!";
$TXT_BAKERY['JS_INCORRECT_CAPTCHA'] = "The verification number (also known as Captcha) does not match.\nPlease correct your entry!";
$TXT_BAKERY['JS_CONFIRM_SEND_INVOICE'] = "Do you want to email this customer invoice?";

// Bakery 2.x
$TXT_BAKERY['REQUESTLIST'] = 'Request list';  
$TXT_BAKERY['REQUEST'] = 'Request'; 
$TXT_BAKERY['REQUEST_ID'] = 'Request ID'; 
$TXT_BAKERY['VIEW_REQEST_LIST'] = 'Show request list';  
$TXT_BAKERY['UPDATE_REQEST_LIST'] = 'Update request list'; 
$TXT_BAKERY['UPDATE_REQEST_LIST_SUCCESS'] = 'Request list updated successfully.'; 
$TXT_BAKERY['SUBMIT_REQUEST'] = 'Send request';  
$TXT_BAKERY['REQUEST_SUMMARY'] = 'Request summary';  
$TXT_BAKERY['USE_ONLINE_PAYMENT'] = 'Use online payment';  
$TXT_BAKERY['HINT_ONLINE_PAYMENT'] = 'If not selected, the checkout ends after sending the request form (no payment process).';  
$TXT_BAKERY['CUST_STREET'] = 'Street'; 
$TXT_BAKERY['CUST_STREET_NUMBER'] = 'No.'; 
$TXT_BAKERY['CUST_ADDRESS_ADDITION'] = 'Additional address'; 
$TXT_BAKERY['CUSTOMER_MESSAGE'] = 'Message to seller'; 
$TXT_BAKERY['SPLIT_STREET_NUMBER'] = 'Different fields for street and house number'; 

$TXT_BAKERY['VISIBLE'] = 'Show'; 
$TXT_BAKERY['REQUIRED'] = 'Required <sup style="color:red;">*</sup>'; 
$TXT_BAKERY['REQUESTS'] = 'Inquiries'; 
$TXT_BAKERY['SEND_REQUEST'] = 'Send request'; 
$TXT_BAKERY['DATE'] = 'Date'; 
$TXT_BAKERY['REQUEST_EMAIL_SUBJECT'] = "[SHOP_NAME] request #[ORDER_ID]";  
$TXT_BAKERY['REQUEST_EMAIL_BODY'] = "You have received the following request:";  
$TXT_BAKERY['REQUEST_SEE_LINK'] = 'View request in backend: <a href="%s">LINK</a>.';  
$TXT_BAKERY['REQUEST_SENT'] = 'Your request was sent successfully.';  
$TXT_BAKERY['THANK_U_REQUEST'] = "Thank your for your request. We will contact you as soon as possible."; 
$TXT_BAKERY['ERR_REQUESTLIST_EMPTY'] = 'The request list is empty.'; 
$TXT_BAKERY['ERR_REQUEST_ITEM_EXISTS'] = 'This item has been added to the request list already. You can alter the amount in the input field in the list.'; 
$TXT_BAKERY['REQUIREDFIELDS'] = '<small>Fields marked with  <span><span>*</span></span> are required</small>';
$TXT_BAKERY['DSGVO_TEXT'] = 'I have read the <a href="%s" target="_blank">privacy policy</a>. I consent to my data being saved and to being contacted by email or telephone.';