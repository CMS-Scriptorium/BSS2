<?php

function bakeryCart($type = 'mini_cart', $template = 'default') {
    global $database;
    
    // Load Languages and adapt strings depending on SHOP_MODE|REQUEST_MODE
    if (LANGUAGE_LOADED && !isset($TXT_BAKERY)) {
        include WB_PATH . '/modules/bakery/languages/EN.php';
        if (file_exists($sLF = WB_PATH . '/modules/bakery/languages/' . LANGUAGE . '.php')) {
            include $sLF;
        }
    }
    $use_payment = $database->get_one(
        "SELECT `use_payment` FROM `{BXT}_general_settings` WHERE `shop_id` = 0"
    );
    if($use_payment != '1'){
        $TXT_BAKERY['VIEW_CART'] = $TXT_BAKERY['VIEW_REQEST_LIST'];
        $TXT_BAKERY['CART'] = $TXT_BAKERY['REQEST_LIST'];
    }
    
    // GET CONTINUE URL LINK
    
     // GET SECTION ID
    $setting_continue_url = '';
    if (isset($_SESSION['bxt']['last_section_id']) && is_numeric($_SESSION['bxt']['last_section_id'])) {
        // If exits get the section id of the last visited Bakery section...
        $section_id = $_SESSION['bxt']['last_section_id'];
        $clause = "WHERE ps.section_id = '$section_id'";
    } else {
        // ...else get the highest section id
        $clause = "WHERE ps.section_id != '0' ORDER BY ps.section_id ASC LIMIT 1";
    }
    // Get continue url based on the page of the above SECTION ID
    if ($sTmp = $database->get_one(
            "SELECT p.link FROM {TP}pages p INNER JOIN {BXT}_page_settings ps ON p.page_id = ps.page_id WHERE p.page_id = ps.continue_url AND ps.section_id = '$section_id'"
        )
    ) {
        $setting_continue_url = WB_URL . PAGES_DIRECTORY . $sTmp . PAGE_EXTENSION;
    }    
    
    // Code for Droplet [[ModBakeryMiniCart]] for Bakery 2.x
    if ($type == 'mini_cart') {
        
        require_once WB_PATH . '/include/phplib/template.inc';
        $tpl = new Template(WB_PATH . '/modules/bakery/templates/mini_cart');
        $tpl->set_unknowns('keep'); // (remove:=default, keep, comment)
        // Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
        $tpl->debug = 0;
        // Look for language file
        // Check order id
        
        $aStrings = array(
            'WB_URL'            => WB_URL,
            'TXT_CART'          => $TXT_BAKERY['CART'],
            'TXT_ORDER_ID'      => $TXT_BAKERY['ORDER_ID'],
            'TXT_ITEMS'         => $TXT_BAKERY['ITEMS'],
            'TXT_SUM'           => $TXT_BAKERY['SUM'],
            'TXT_EXCL_SHIPPING' => $TXT_BAKERY['EXCL_SHIPPING_TAX'],
            'TXT_VIEW_CART'     => $TXT_BAKERY['VIEW_CART'],                    
            'ERR_CART_EMPTY'    => $TXT_BAKERY['ERR_CART_EMPTY'],
        );
        
        if (isset($_SESSION['bxt']['order_id']) && is_numeric($_SESSION['bxt']['order_id']) && $_SESSION['bxt']['order_id'] >= 0) {
            $order_id = $_SESSION['bxt']['order_id'];
            // Look for items in the DB
            $query_order = $database->query("SELECT item_id, attributes, quantity, price FROM {BXT}_order WHERE order_id = '$order_id'");
            $num_orders = $query_order->numRows();
            if ($num_orders > 0) {
                
                // Get the general settings
                $query_general_settings = $database->query("SELECT shop_currency, dec_point, thousands_sep FROM {BXT}_general_settings");
                if ($query_general_settings->numRows() > 0) {
                    $general_settings = $query_general_settings->fetchRow();
                    $shop_currency    = stripslashes($general_settings['shop_currency']);
                    $dec_point        = stripslashes($general_settings['dec_point']);
                    $thousands_sep    = stripslashes($general_settings['thousands_sep']);
                }
                // Get item_id, attributes, quantity and price from DB order table
                $i = 1;
                while ($order = $query_order->fetchRow()) {
                    foreach ($order as $key => $value) {
                        $items[$i][$key] = $value;
                    }
                    // Initialize var and set default if item has no attributes
                    $attribute['operator'] = "";
                    $items[$i]['attribute_price'] = 0;
                    // Get item attribute price and operator (+/-)
                    if ($items[$i]['attributes'] != "none") {
                        $attribute_ids = explode(",", $items[$i]['attributes']);
                        foreach ($attribute_ids as $attribute_id) {
                            // Get attribute price and operator (+/-)
                            $query_attributes = $database->query("SELECT price, operator FROM {BXT}_item_attributes WHERE item_id = {$items[$i]['item_id']} AND attribute_id = $attribute_id");
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
                        }
                        // Now calculate item price including all attribute prices
                        $items[$i]['price'] = $items[$i]['price'] + $items[$i]['attribute_price'];
                    }
                    // Increment counter
                    $i++;
                }
                // Calculate order total
                $quantity_sum = 0;
                $total = 0;
				if (!isset($items)) {$items=array();}
                for ($i = 1; $i <= sizeof($items); $i++) {
                    $quantity_sum = $quantity_sum + $items[$i]['quantity'];
                    $subtotal = $items[$i]['quantity'] * $items[$i]['price'];
                    $total = $total + $subtotal;
                }
                $f_total = number_format($total, 2, $dec_point, $thousands_sep);
                // Show MiniCart summary using template file
                $tpl->set_file('mini_cart_summary', 'summary.htm');
                $tpl->set_var($aStrings);
                $tpl->set_var(array(
                    'CONTINUE_URL'  => $setting_continue_url,
                    'ORDER_ID'      => $order_id,
                    'SHOP_CURRENCY' => $shop_currency,
                    'QUANTITY_SUM'  => $quantity_sum,
                    'TOTAL'         => $f_total, 
                ));
                $tpl->parse('output', 'mini_cart_summary');
            } else {
                // Show empty MiniCart using template file
                $tpl->set_file('mini_cart_empty', 'empty.htm');                
                $tpl->set_var($aStrings);
                $tpl->parse('output', 'mini_cart_empty');
            }
        } else {
            // Show empty MiniCart using template file
            $tpl->set_file('mini_cart_empty', 'empty.htm');
            $tpl->set_var($aStrings);
            $tpl->parse('output', 'mini_cart_empty');
        }
        return $tpl->get('output');
        
    } 
    
    // Droplet [[ModBakeryCartLink]] 
    elseif($type == 'cart_link') {  
        if($setting_continue_url != ''){
            return '<a href="' . $setting_continue_url . '?view_cart=yes">' . $TXT_BAKERY['VIEW_CART'] . "</a>";
        }        
    }
}


