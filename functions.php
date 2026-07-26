<?php

/* stupid PHP 8.1 fixes by florian -- BEGIN -- */

function lazystrip($val) {
	if ($val!==null) {
		$val = stripslashes($val);
	}
	return $val;
}

function lazyspecial($val) {
	if ($val!==null) {
		$val = htmlspecialchars($val);
	}
	return $val;
}

function lazyexplode($x,$y) {
	if ($y!==null) {
		$val = explode($x,$y);
	} else {
		$val = array();
	}
	return $val;
}

/* stupid PHP 8.1 fixes by florian -- END -- */

function bxt_getGlobalCfg($iShopID = 0){
    return $GLOBALS['database']->get_array(
        "SELECT * FROM `{BXT}_general_settings` WHERE `shop_id` = ".$iShopID
    )[0];
}

function bxt_formConfig(){
    // get Form Settings from the DB
    $aFormCfg = $GLOBALS['database']->get_one(
        "SELECT `form_config` 
            FROM `{BXT}_general_settings` 
                WHERE `shop_id` = '0'"
    );
    $aFormSettings = json_decode($aFormCfg, 1);
    
    $aOutput = [];
    foreach(aInitialFormSettings() as $type=>$set){
        foreach($set as $key){
            if(isset($aFormSettings[$type][$key])){
               $aOutput[$type][$key] = $aFormSettings[$type][$key];
            } else {
               $aOutput[$type][$key] = 0;                
            }
        } 
    }
    
    // extra handling for split_street_number
    $aOutput['show_fields']['cust_street_number'] = 0;
    $aOutput['show_fields']['ship_street_number'] = 0;
    $aOutput['required_fields']['cust_street_number'] = 0;
    $aOutput['required_fields']['ship_street_number'] = 0;
    if($aOutput['option']['split_street_number'] == 1){
        if($aOutput['required_fields']['cust_street'] == 1){
            $aOutput['show_fields']['cust_street_number'] = 1;
            $aOutput['required_fields']['cust_street_number'] = 1;
        }    
        if($aOutput['required_fields']['ship_street'] == 1){
            $aOutput['show_fields']['ship_street_number'] = 1;
            $aOutput['required_fields']['ship_street_number'] = 1;
        }
    }
    return $aOutput;
}

function bxt_showReq($field = ''){
    $retVal = '';
    if(bxt_formConfig()['required_fields'][$field]){
        $retVal = '<span class="required">*</span>';        
    }
    return $retVal;
}
function bxt_attrReq($field = '', $type=''){
    $retVal = '';
    $aReqFields = bxt_formConfig()['required_fields'];
    if($field != ''){
        if($aReqFields[$field] == 1){
            $retVal = ' required="required" ';        
        }
    } else {
        if(in_array($type, ['cust', 'ship'])){
            $aTmp = [];
            foreach($aReqFields as $rec => $val){
                if($val == 1){
                    if(strpos($rec, $type.'_') !== false) {
                            $aTmp[] = $rec;
                    }                
                }
            }
            $aReqFields = $aTmp;    
        }
        $retVal = $aReqFields;
    }
    return $retVal;
}

function bxt_checkReqFld($field = ''){
    $bRetVal = false;
    $aData = checkoutFormDataArray();
    if($aData[$field][4] == 1){
        $sRetVal = true;
    }
    return $sRetVal;
}


function bxt_showField($field = ''){
    $aFormCfg = bxt_formConfig();
    if(isset($aFormCfg['show_fields'][$field]))
        return $aFormCfg['show_fields'][$field];
    else return false;
}
function bxt_option($field = ''){
    return bxt_formConfig()['option'][$field];
}

function bxt_formDisabledArray($bUsePayment = 1){

    $aTmp = array('first_name', 'last_name','email');    
    
    if($bUsePayment){
        #$aTmp = array_merge($aTmp, array('street','zip','city', 'country'));  
        $aTmp = array_merge($aTmp, array('street','zip','city'));  
    }
    
    $aDisabledFields = [];    
    foreach(['cust', 'ship'] as $type){
        foreach($aTmp as $rec){
            $aDisabledFields[] = $type.'_'.$rec;
        }
    }    
    return $aDisabledFields;
}


function checkoutFormDataArray() {
    global $TXT_BAKERY;
    $formCfg = bxt_formConfig();

    return array(
        'cust_company' => array( 
            $TXT_BAKERY['CUST_COMPANY'],  
            $formCfg['show_fields']['cust_company'], 
            $formCfg['required_fields']['cust_company'], // is_required?           
        ),
        'cust_first_name' => array(
            $TXT_BAKERY['CUST_FIRST_NAME'],
            1,  
            1,       
        ),
        'cust_last_name' => array(
            $TXT_BAKERY['CUST_LAST_NAME'],          
            1,               
            1,   
        ),
        'cust_street'  => array(
            $TXT_BAKERY['CUST_ADDRESS'],           
            $formCfg['show_fields']['cust_street'],         
            $formCfg['required_fields']['cust_street'], 
            array(
                'split_street_number', 
                $TXT_BAKERY['SPLIT_STREET_NUMBER'].'?',
                $formCfg['option']['split_street_number'],
            )
        ),        
        'cust_address_addition'  => array(
            $TXT_BAKERY['CUST_ADDRESS_ADDITION'],           
            $formCfg['show_fields']['cust_address_addition'],    
            $formCfg['required_fields']['cust_address_addition'], 
        ),
        'cust_zip' => array(
            $TXT_BAKERY['CUST_ZIP'],           
            $formCfg['show_fields']['cust_zip'],     
            $formCfg['required_fields']['cust_zip'], 
            array(
                'zip_location', 
                $TXT_BAKERY['SHOW_ZIP_END_OF_ADDRESS'].'?',
                $formCfg['option']['zip_location'],
            )
        ),      
        'cust_city' => array(
            $TXT_BAKERY['CUST_CITY'],            
            $formCfg['show_fields']['cust_city'],     
            $formCfg['required_fields']['cust_city'],   
        ),
        'cust_state' => array(
            $TXT_BAKERY['CUST_STATE'],           
            $formCfg['show_fields']['cust_state'],     
            $formCfg['required_fields']['cust_state'],  
        ),
        'cust_country' => array(
            $TXT_BAKERY['CUST_COUNTRY'],           
            $formCfg['show_fields']['cust_country'],               
            $formCfg['required_fields']['cust_country'],
        ),
        'cust_email' => array(
            $TXT_BAKERY['EMAIL'],           
            $formCfg['show_fields']['cust_email'],       
            $formCfg['required_fields']['cust_email'],
            array(
                'use_repeat_email', 
                $TXT_BAKERY['CUST_CONFIRM_EMAIL'].'?',
                $formCfg['option']['use_repeat_email'],
            )
        ),
        'cust_phone' => array(
            $TXT_BAKERY['CUST_PHONE'],           
            $formCfg['show_fields']['cust_phone'],        
            $formCfg['required_fields']['cust_phone'],   
        ),        
        'cust_mobile' => array(
            $TXT_BAKERY['CUST_MOBILE'],           
            $formCfg['show_fields']['cust_mobile'],         
            $formCfg['required_fields']['cust_mobile'], 
        ),        
        'cust_tax_no' => array(
            $TXT_BAKERY['CUST_TAX_NO'],           
            $formCfg['show_fields']['cust_tax_no'],      
            $formCfg['required_fields']['cust_tax_no'], 
        ),
        'cust_message' => array(
            $TXT_BAKERY['CUSTOMER_MESSAGE'],           
            $formCfg['show_fields']['cust_message'],          
            $formCfg['required_fields']['cust_message'],    
        ),

        'ship_company' => array( 
            $TXT_BAKERY['SHOW_COMPANY_FIELD'], 
            $formCfg['show_fields']['ship_company'],        
            $formCfg['required_fields']['ship_company'],  
        ),
        'ship_first_name' => array(
            $TXT_BAKERY['CUST_FIRST_NAME'],
            $formCfg['show_fields']['ship_first_name'],    
            $formCfg['required_fields']['ship_first_name'],  
        ),
        'ship_last_name' => array(
            $TXT_BAKERY['CUST_LAST_NAME'],
            $formCfg['show_fields']['ship_last_name'], 
            $formCfg['required_fields']['ship_last_name'], 
        ),
        'ship_street' => array(
            $TXT_BAKERY['CUST_ADDRESS'],            
            $formCfg['show_fields']['ship_street'],  
            $formCfg['required_fields']['ship_street']
        ),     
        'ship_address_addition' => array(
            $TXT_BAKERY['CUST_ADDRESS_ADDITION'],           
            $formCfg['show_fields']['ship_address_addition'],    
            $formCfg['required_fields']['ship_address_addition'], 
        ),
        'ship_zip' => array(
            $TXT_BAKERY['CUST_ZIP'],           
            $formCfg['show_fields']['ship_zip'],     
            $formCfg['required_fields']['ship_zip']
        ),
        'ship_city' => array(
            $TXT_BAKERY['CUST_CITY'],           
            $formCfg['show_fields']['ship_city'],   
            $formCfg['required_fields']['ship_city'],  
        ),
        'ship_state' => array(
            $TXT_BAKERY['CUST_STATE'],           
            $formCfg['show_fields']['ship_state'],     
            $formCfg['required_fields']['ship_state'],  
        ),
        'ship_country'=> array(
            $TXT_BAKERY['CUST_COUNTRY'],            
            $formCfg['show_fields']['ship_country'],      
            $formCfg['required_fields']['ship_country'],      
        ),        
        'ship_phone'=> array(
            $TXT_BAKERY['CUST_PHONE'],            
            $formCfg['show_fields']['ship_phone'],      
            $formCfg['required_fields']['ship_phone'],      
        ),        
        'ship_mobile'=> array(
            $TXT_BAKERY['CUST_MOBILE'],            
            $formCfg['show_fields']['ship_mobile'],      
            $formCfg['required_fields']['ship_mobile'],      
        ),        
    );
}

function timeago($iTimestamp) {
	$sRetVal = '';
	if($iTimestamp != NULL){		
	
		$length = array("60","60","24","30","12","10");

		switch(LANGUAGE){
			case 'DE' :
				$aTimeUnits = array(
					'singular' => array("Sekunde", "Minute", "Stunde", "Tag", "Monat", "Jahr"), 
					'plural'   => array("Sekunden", "Minuten", "Stunden", "Tagen", "Monaten", "Jahren"), 
				);					
				$sString = "vor {{AMOUNT}} {{UNITS}}";  // vor 15 Tagen
			break;
			
			case 'NL' :
			break;			
			
			case 'FR' :
			break;
			
			case 'PL' :
				$aTimeUnits = array(
					'singular' => array("sekunda", "minuta", "godzina", "dzień", "miesiąc", "rok"), 
					'plural'   => array("sekund", "minut", "godzin", "dni", "miesięcy", "lat"), 
				);					
				$sString = "{{AMOUNT}} {{UNITS}} temu";  // 15 dni temu
			break;
			
			default:		
				$aTimeUnits = array(
					'singular' => array("second", "minute", "hour", "day", "month", "year"), 
					'plural'   => array("seconds", "minutes", "hours", "days", "months", "years")
				);				
				$sString = "{{AMOUNT}} {{UNITS}} ago";  // 15 days ago
			break;
		}


		$currentTime = time();
		if($currentTime >= $iTimestamp) {
			$diff     = time()- $iTimestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
				$diff = $diff / $length[$i];
			}
			
			$iAmount = round($diff);
			$aRplc = array(
				'AMOUNT' => $iAmount, 
				'UNITS' => $aTimeUnits[($iAmount > 1) ? 'plural' : 'singular'][$i]
			);
			$sRetVal = replace_vars($sString, $aRplc);
		}
	}
	return $sRetVal;
}


function aInitialFormSettings(){
    return array (
        'show_fields' => array (
            'cust_company',
            'cust_first_name',
            'cust_last_name',
            'cust_email',
            'cust_street',
            'cust_address_addition',
            'cust_city',
            'cust_state',
            'cust_country',
            'cust_zip',
            'cust_phone',
            'cust_mobile',
            'cust_tax_no',
            'cust_message',
            'ship_company',
            'ship_first_name',
            'ship_last_name',
            'ship_street',
            'ship_address_addition',
            'ship_city',
            'ship_state',
            'ship_country',
            'ship_zip',
            'ship_phone',
            'ship_mobile',
        ),
        'required_fields' => array (
            'cust_company',
            'cust_first_name',
            'cust_last_name',
            'cust_street',
            'cust_address_addition',
            'cust_city',
            'cust_state',
            'cust_country',
            'cust_zip',
            'cust_phone',
            'cust_mobile',
            'cust_email',
            'cust_tax_no',
            'cust_message',
            'ship_company',
            'ship_first_name',
            'ship_last_name',
            'ship_street',
            'ship_address_addition',
            'ship_city',
            'ship_state',
            'ship_country',
            'ship_zip',
            'ship_phone',
            'ship_mobile',
        ), 
        'option' => array(
            'split_street_number', 
            'use_repeat_email', 
            'zip_location'
        )
    );
}

/**
 *  this function was copied from the file <check_vat.php> from earlier versions of Bakery 
 */
function check_vat($vat, $tax_group) {

	// No check if soap extension is not laoded
	if (!extension_loaded('soap')) {
        return true;
	}

	// No check if vat number string has been left empty
    if (empty($vat)) {
        return true;
	}

	// Clean vat number string
	$invalid_chars = array(chr(0), chr(9), chr(10), chr(11), chr(13), chr(173));
	$vat           = str_replace($invalid_chars, '', $vat);

	// Split country code and vat number
    $country_code = strtoupper(substr($vat, 0, 2));
    $vat_no       = substr($vat, 2);
    
    // Country code must make part of the EU tax zone
    if (strpos($tax_group, $country_code) === false) {
    	return false;
    }
    
    // Number part can not be empty
    if (empty($vat_no)) {
    	return false;
    }

	// Check vat using SOAP
	$result   = null;
	$wsdl_url = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
	try {  
	    $soap   = @new SoapClient($wsdl_url, array('exceptions' => 1));
	    $result = $soap->checkVat(array('countryCode' => $country_code, 'vatNumber' => $vat_no));
	} catch(SoapFault $E) {  
	    echo '<div class="mod_bakery_error_f"><p>'.$E->faultstring.'</p></div>'; 
	}
    if (isset($result->valid) && !$result->valid) {
        return false;
	}
	return true;
}

function bxt_correctDate($iDate){
    return gmdate(DEFAULT_DATE_FORMAT.', '.DEFAULT_TIME_FORMAT, $iDate + TIMEZONE);
}
function bxt_price_format($price){
    $cfg = bxt_getGlobalCfg();
    return number_format($price, 2, $cfg['dec_point'], $cfg['thousands_sep']);    
}


function bxt_cartArray($order_id, $bBackup = false){
    global  $database;
    require __DIR__.'/config.php';
    $aItems = $database->get_array(
        "SELECT * FROM {BXT}_order WHERE order_id = ".$order_id
    );

    $items = array();
    if (empty($aItems)) {        
        return $items;
    }


    // GET ITEM DETAILS FROM DATABASE
    // ******************************
    $items = array();
    // Get order id, item id, attributes, sku, quantity, price and tax_rate from db table order
    $i = 1;
    foreach ($aItems as $row1) {
        foreach ($row1 as $field => $value) {
            if ($field != "order_id") {
                $items[$i][$field] = $value;
                // Get item name, shipping. link and main image from db items table
                if ($field == "item_id") {
                    $sql_result2 = $database->query("SELECT title, shipping, link FROM {BXT}_items WHERE item_id = '" . $row1['item_id'] . "'");
                    $row2 = $sql_result2->fetchRow();
                    $items[$i]['name'] = $row2[0];
                    $items[$i]['shipping'] = $row2[1];
                    $items[$i]['link'] = WB_URL . PAGES_DIRECTORY . $row2[2] . PAGE_EXTENSION;

                    // Item thumbnail
                    // Default if no thumb exists
                    $items[$i]['thumb_url'] = WB_URL . '/modules/bakery/images/transparent.gif';
                    $items[$i]['thumb_width'] = $cart_thumb_max_size;
                    $items[$i]['thumb_height'] = $cart_thumb_max_size;
                    // Get main thumb (image with position == 1)
                    $main_thumb = '';
                    $main_thumb = $database->get_one("SELECT filename FROM {BXT}_images WHERE item_id = '{$row1['item_id']}' AND active = '1' ORDER BY position ASC LIMIT 1");
                    // Item thumb if exists
                    $thumb_dir = '/' . $img_dir . '/thumbs/item' . $row1['item_id'] . '/';
                    $items[$i]['thumb_path'] = WB_PATH . MEDIA_DIRECTORY . $thumb_dir . $main_thumb;
                    if (is_file($items[$i]['thumb_path'])) {
                        // Thumb URL
                        $items[$i]['thumb_url'] = WB_URL . MEDIA_DIRECTORY . $thumb_dir . $main_thumb;
                        // Get thumb image size
                        $size = getimagesize($items[$i]['thumb_path']);
                        if ($size[0] > 1 && $size[1] > 1) {
                            if ($size[0] > $size[1]) {
                                $items[$i]['thumb_height'] = round($cart_thumb_max_size * $size[1] / $size[0]);
                            } elseif ($size[0] < $size[1]) {
                                $items[$i]['thumb_width'] = round($cart_thumb_max_size * $size[0] / $size[1]);
                            }
                        }
                    }
                    unset($items[$i]['thumb_path']);
                    if($bBackup == true){
                        $items[$i]['link']      = str_replace(WB_URL . PAGES_DIRECTORY, '', $items[$i]['link']);
                        $items[$i]['thumb_url'] = str_replace([WB_URL,  MEDIA_DIRECTORY], '', $items[$i]['thumb_url']);
                    }
                }
            }
        }

        // Default if item has no attributes
        $items[$i]['show_attribute'] = '';
        $items[$i]['attribute_price'] = 0;
        // Get item attribute ids
        if ($items[$i]['attributes'] != "none") {
            $attribute_ids = explode(",", $items[$i]['attributes']);
            foreach ($attribute_ids as $attribute_id) {
                // Get option name and attribute name, price, operator (+/-/=)
                $query_attributes = $database->query("SELECT o.option_name, a.attribute_name, ia.price, ia.operator FROM {BXT}_options o INNER JOIN {BXT}_attributes a ON o.option_id = a.option_id INNER JOIN {BXT}_item_attributes ia ON a.attribute_id = ia.attribute_id WHERE ia.item_id = {$items[$i]['item_id']} AND ia.attribute_id = $attribute_id");
                $attribute = $query_attributes->fetchRow();
                // Calculate the item attribute prices sum depending on the operator
                if ($attribute['operator'] == "+") {
                    $items[$i]['attribute_price'] = $items[$i]['attribute_price'] + $attribute['price'];
                } elseif ($attribute['operator'] == "-") {
                    $items[$i]['attribute_price'] = $items[$i]['attribute_price'] - $attribute['price'];
                    // If operator is '=' then override the item price by the attribute price
                } elseif ($attribute['operator'] == "=") {
                    $items[$i]['price'] = $attribute['price'];
                }
                // Prepare option and attributes for display in cart table
                $items[$i]['show_attribute'] .= ", " . $attribute['option_name'] . ":&nbsp;" . $attribute['attribute_name'];
            }
            // Now calculate item price including all attribute prices
            $items[$i]['price'] = $items[$i]['price'] + $items[$i]['attribute_price'];
            // Never undercut zero
            $items[$i]['price'] = $items[$i]['price'] < 0 ? 0 : $items[$i]['price'];
            // Remove leading comma and space
            $items[$i]['show_attribute'] = substr($items[$i]['show_attribute'], 2);
        }
        // Increment counter
        $i++;
    }
    return $items;
}

function bxt_cartContents($order_id, $bBackup = false){
    $aRetVal = array();
    $items = bxt_cartArray($order_id, $bBackup);
    
    // Determine shipping sum of all items specified
    $shipping_sum = array();
    for ($i = 1; $i <= sizeof($items); $i++) {
        $shipping_sum[] = $items[$i]['shipping'];
    }
    $shipping_sum = array_sum($shipping_sum);    
    $bShowShipping = ($shipping_sum > 0);
    
    $order_total = 0;    
    for ($i = 1; $i <= sizeof($items); $i++) {
        $items[$i]['f_price'] = bxt_price_format($items[$i]['price']);        
        $items[$i]['f_shipping'] = 0;
        if ($bShowShipping == true) {
            // Calculate order total with shipping per item
             $items[$i]['f_shipping'] = bxt_price_format($items[$i]['shipping']);
            // See http://www.bakery-shop.ch/#shipping_total
            // $total = $items[$i]['quantity'] * ($items[$i]['price'] + $items[$i]['shipping']);
        } 
        
        $tmpTotal = $items[$i]['quantity'] * $items[$i]['price'];        
        $order_total = $order_total + $tmpTotal;        
        $items[$i]['f_item_total'] = bxt_price_format($tmpTotal);
    }
    
    $aRetVal['show_shipping'] = $bShowShipping;
    $aRetVal['items'] = $items;
    $aRetVal['f_order_total']  = bxt_price_format($order_total);
    
    return $aRetVal;
}

function bxt_cartContentRaw($iOrderID){
    global $database;
    require __DIR__.'/languages/EN.php';
    if (file_exists($sLCFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
        require $sLCFile;
    }
    $sOutput = '';
    
    $aData = $database->get_array("SELECT * FROM `{BXT}_requests` WHERE `order_id` = ".$iOrderID)[0];
    
    $aData['timestamp'] = date(DATE_FORMAT.' - '.TIME_FORMAT,$aData['timestamp']+TIMEZONE);
    $sOutput .= "\t".$TXT_BAKERY['DATE'].': '. $aData['timestamp']."\n";
    $sOutput .= "\t".$TXT_BAKERY['EMAIL'].': '. $aData['email']."\n\n";
/*
 Datum: 26.01.2021 - 23:36
 E-Mail: email@provider.tld
*/
    foreach($aData as $rec){
        $sOutput .= '';
    }

    $tmpCart = $database->get_one(
        "SELECT `json_order` FROM `{BXT}_customer` WHERE `order_id` = ".$iOrderID
    ); 

    $aOrder = array(
        'sku' => 'sku',
        'name' => 'name',
        'attributes' => 'show_attribute',
        'quantity' => 'quantity',
        'price' => 'f_price',
        'shipping' => 'f_shipping',
        'sum' => 'f_item_total',
    );
    $aCart = json_decode($tmpCart, true);


    $sOutput .= "\t<h2>". strtoupper($TXT_BAKERY['REQUESTLIST'])."</h2>";
    $sOutput .= "\t-------------------------------------------\n";
/*
        ANFRAGELISTE
	-------------------------------------------
*/
    $i = 0;
    foreach ($aCart['items'] as $arr=>$item){
        foreach ($aOrder as $key=>$val){
            if(!isset($item['attributes'])){
                continue;
            } 
            if($key == 'attributes') {
                $sOutput .= "\t \t&nbsp;&nbsp;<i> ".$item[$val]."</i>\n";
                continue;
            }
            $sOutput .= "\t <b> ".$TXT_BAKERY[strtoupper($key)]."</b>: ".$item[$val]."\n";
        }
        $i++;
        if($i < sizeof($aCart['items']))
            $sOutput .= "\t-------------------------------------------\n";
/*
	  Art-Nr.: 001
	  Bezeichnung: Honigwachskerze
	  Menge: 1
	  Preis: 45,00
	  Versand: 4,50
	  Gesamt: 45,00
	-------------------------------------------
	  Art-Nr.: 
	  Bezeichnung: Kerzenständer
	  Menge: 1
	  Preis: 15,00
	  Versand: 0,00
	  Gesamt: 15,00
*/      
    }
        $sOutput .= "\t===========================================\n";
        $sOutput .= "\t  <b>".$TXT_BAKERY['TOTAL'].":</b> ".$aCart['f_order_total']."\n";
/*
	===========================================
	  Gesamtsumme: 60,00
*/      

        $sOutput .= "\n";
        $sOutput .= "\n";
       # $sOutput .= "\t===========================================\n";
        $sOutput .= "\t<h3>".strtoupper($TXT_BAKERY['CUSTOMER'])."</h3>";

        $aDetails = json_decode($aData['json'], 1);
        $address_start = false;
        foreach($aDetails as $key=>$val){
            if($key == 'user_id') continue;
            if($key == 'order_id'){ 
                $iOrderID = $val;
                continue;
            }
            if($key == 'cust_message'){
                $sCustomerMessage = $val;
                continue;
            }
            $str = $key;
            $str = str_replace('ship', 'cust', $str);

            if(array_key_exists(strtoupper($str), $TXT_BAKERY)){
                $str = $TXT_BAKERY[strtoupper($str)];
            }

            if(strpos($key, 'ship') !== false && $address_start != true){
                $address_start = true;
                $sOutput .= "\n\t<h3>". $TXT_BAKERY['SHIP_ADDRESS']."</h3>";
            }
            $sOutput .= "\t <b> ".$str.":</b> ".$val."\n";
        }
/*	===========================================
	KUNDE
	  Land: DE
	  Vorname: Max
	  Nachname: Mustermann
	  Telefonnummer: 12345678
	  E-Mail Adresse: email@provider.tld

	Versandadresse
	  Firma: Musterfirma
	  Vorname: Max
	  Nachname: Mustermann
	  Adresszusatz: Adresstzsatz hier
	  Telefonnummer: 12345678
*/
        $sOutput .= "\t===========================================\n";
/*	
	===========================================
	KUNDENBEMERKUNG
		Hier die Kundenbemerkung
*/
		if (!isset($sCustomerMessage)) {$sCustomerMessage='';}
        $sOutput .= "\t<b>".strtoupper($TXT_BAKERY['CUST_MESSAGE'])."</b>\n";
        $sOutput .= "\t\t".($sCustomerMessage == '' ? "n/a" : $sCustomerMessage)."\n";
            
    return $sOutput;
}

function bxt_modify_processes(){
    global $database;

    // REMOVE ITEMS WITH NO TITLE 
    $aEmpty = $database->get_array("SELECT `item_id` FROM `{BXT}_items` WHERE `title` IS NULL AND `item_id` <> 1");
    if(!empty($aEmpty)){
        foreach($aEmpty as $rec){   
            $database->query("DELETE FROM `{BXT}_items` WHERE `item_id` = ".$rec['item_id']." LIMIT 1");
        }
    }
    
    // move_item up and down using a link (not drag&drop
    if(isset($_GET['move_item']) && in_array($_GET['move_item'], ['up', 'down'])){
        if (!isset($_GET['item_id']) OR !is_numeric($_GET['item_id'])) {
            return;
        } else {
            $sDirection = 'move_'.$_GET['move_item'];
            $iItemID = $_GET['item_id'];
            $sField = 'item_id';
            $sTable = '{BXT}_items';
            require WB_PATH.'/framework/class.order.php';            
            $oOrder = new Order($sTable, 'position', $sField, 'section_id');
            if ($oOrder->$sDirection($iItemID)) {
                // success
            } else {
                // error
            }
            return;
        }
    }


}

function bxt_remove_outdated_orders(){
    // Delete db records of not submitted orders older than 1 hour
    global $database;
    $now = time();
    $outdate = $now - (60 * 60 * 1);
    $query_outdated_orders = $database->query("SELECT order_id FROM {BXT}_customer WHERE order_date < $outdate AND submitted = 'no'");
    if ($query_outdated_orders->numRows() > 0) {
        while ($outdated_orders = $query_outdated_orders->fetchRow()) {
            $outdated_order_id = stripslashes($outdated_orders['order_id']);

            // First put not sold items back to stock...
            $query_order = $database->query("SELECT item_id, quantity FROM {BXT}_order WHERE order_id = '$outdated_order_id'");
            if ($query_order->numRows() > 0) {
                while ($order = $query_order->fetchRow()) {
                    $item_id = stripslashes($order['item_id']);
                    $quantity = stripslashes($order['quantity']);
                    // Query item stock
                    $query_items = $database->query("SELECT stock FROM {BXT}_items WHERE item_id = '$item_id'");
                    $item = $query_items->fetchRow();
                    $stock = stripslashes($item['stock']);
                    // Only use stock admin if stock is not blank
                    if (is_numeric($stock) && $stock != '') {
                        // Update stock to required quantity
                        $database->query("UPDATE {BXT}_items SET stock = stock + '$quantity' WHERE item_id = '$item_id'");
                    }
                }
            }

            // ...then delete not submitted orders
            $database->query("DELETE FROM {BXT}_customer WHERE order_id = '$outdated_order_id' AND submitted = 'no'");
            $database->query("DELETE FROM {BXT}_order WHERE order_id = '$outdated_order_id'");
        }
    }
}	

function bxt_cleanupJsonString($sStr) {
    $sPattern = '/(<\s*script.*?<\s*\/\s*script\s*>)/si'; 
    if (preg_match($sPattern, $sStr, $aMatches)) {
        $sStr = str_replace($aMatches[1], '', $sStr); 
    }
    $sStr  = strip_tags($sStr, '<b><i><u><strong><em>');
    $aRplc = array(
        "'"  => '&apos;',
        '('  => '&#40;',
        ')'  => '&#41;',
        '{'  => '&#123;',
        '}'  => '&#125;',
        '"'  => '&quot;',			
        "\n" => '\\n', 
        "\t" => '\\t', 
        "\r" => '\\r', 
        "\b" => '\\b', 
        "\f" => '\\f',
        PHP_EOL => "\\n"
    );
    $sStr = strtr($sStr, $aRplc);		
    return $sStr;
}