<?php

/*
  
  Copyright (C) 2007 - 2021, Christoph Marti
Copyleft 2021- Christian M. Stefan, Florian Meerwinck

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License  - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


defined('WB_PATH') or die('insufficient privileges for this operation');

$database->addPrefix('{BXT}', TABLE_PREFIX.'mod_bakery');
$sDefaultSql = __DIR__ .'/install.sql';
if (is_readable($sDefaultSql)) {
    // create needed database tables and set default records
    if ($database->SqlImport($sDefaultSql, TABLE_PREFIX)) {

        // Include default EU tax zone Var from config.php file
        include __DIR__.'/config.php';
        // Set default values for general settings
        $aInsert = array( 
            'shop_name'        => str_replace('http://', '', WB_URL),
            'tac_url'          => WB_URL.PAGES_DIRECTORY.'/',
            'cancellation_url' => WB_URL.PAGES_DIRECTORY.'/',
            'privacy_url'      => WB_URL.PAGES_DIRECTORY.'/',
            'shop_email'       => SERVER_EMAIL,
            'tax_group'        => $cfg_tax_group,
        );
        // Insert into DB table general_settings 
        $database->insertRow("{BXT}_general_settings", $aInsert);

        require_once __DIR__.'/functions.php';
        $cfg = bxt_getGlobalCfg();
        $aInitialFormSettings = array (
            'show_fields' => array (
                'cust_company' => 0,
                'cust_first_name' => 1,
                'cust_last_name' => 1,
                'cust_email' => 1,
                'cust_street' => 1,
                'cust_address_addition' => 0,
                'cust_city' => 1,
                'cust_state' => 0,
                'cust_country' => 1,
                'cust_zip' => 1,
                'cust_phone' => 1,
                'cust_mobile' => 1,
                'cust_tax_no' => 0,
                'cust_message' => 0,
                'ship_company' => 1,
                'ship_first_name' => 1,
                'ship_last_name' => 1,
                'ship_street' => 1,
                'ship_address_addition' => 0,
                'ship_city' => 1,
                'ship_state' => 0,
                'ship_country' => 1,
                'ship_zip' => 1,
                'ship_phone' => 0,
            ),
            'required_fields' => array (
                'cust_company' => 0,
                'cust_first_name' => 1,
                'cust_last_name' => 1,
                'cust_street' => 1,
                'cust_address_addition' => 0,
                'cust_city' => 1,
                'cust_state' => 0,
                'cust_country' => 1,
                'cust_zip' => 1,
                'cust_phone' => 1,
                'cust_mobile' => 0,
                'cust_tax_no' => 0,
                'cust_message' => 0,
                'ship_company' => 1,
                'ship_first_name' => 1,
                'ship_last_name' => 1,
                'ship_street' => 1,
                'ship_address_addition' => 0,
                'ship_city' => 1,
                'ship_state' => 1,
                'ship_country' => 1,
                'ship_zip' => 1,
                'ship_phone' => 0,
            ), 
            'option' => array(
                'split_street_number' => 0, 
                'use_repeat_email' => 0, 
                'zip_location' => 0, // 1 = 'end'     0 = 'inside';
            )

        );
        
        $database->query(
            "UPDATE `{BXT}_general_settings` 
                SET `form_config` = '".json_encode($aInitialFormSettings)."' 
                    WHERE `shop_id` = '0'"
        );


        // Insert info INTo the search table
        // Module query info
        $field_info = array();
        $field_info['page_id']       = 'page_id';
        $field_info['title']         = 'page_title';
        $field_info['link']          = 'link';
        $field_info['description']   = 'description';
        $field_info['modified_when'] = 'modified_when';
        $field_info['modified_by']   = 'modified_by';
        $field_info = serialize($field_info);
        $database->query("INSERT INTO `{TP}search` (name,value,extra) VALUES ('module', 'bakery', '$field_info')");
        // Query start
        $query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, [TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by FROM [TP]mod_bakery_items, [TP]mod_bakery_page_settings, [TP]pages WHERE ";
        $database->query("INSERT INTO `{TP}search` (name,value,extra) VALUES ('query_start', '$query_start_code', 'bakery')");
        // Query body
        $query_body_code = "
        [TP]pages.page_id = [TP]mod_bakery_items.page_id AND [TP]mod_bakery_items.title LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_bakery_items.page_id AND [TP]mod_bakery_items.sku LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_bakery_items.page_id AND [TP]mod_bakery_items.price LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_bakery_items.page_id AND [TP]mod_bakery_items.description LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_bakery_items.page_id AND [TP]mod_bakery_items.full_desc LIKE \'%[STRING]%\'";
        $database->query("INSERT INTO `{TP}search` (name,value,extra) VALUES ('query_body', '$query_body_code', 'bakery')");
        // Query end
        $query_end_code = '';	
        $database->query("INSERT INTO `{TP}search` (name,value,extra) VALUES ('query_end', '$query_end_code', 'bakery')");

        // Insert blank row (there needs to be at least one row for the search to work)
        $database->query("INSERT INTO {BXT}_items (section_id, page_id) VALUES ('0', '0')");
        $database->query("INSERT INTO {BXT}_page_settings (section_id, page_id) VALUES ('0', '0')");
    }
}

// install Droplets
include __DIR__.'/droplets/droplets.functions.php';
$aDroplets = array(
    'ModBakeryCartLink',
    'ModBakeryMiniCart'
);
foreach($aDroplets as $droplet){
    $sFile = __DIR__.'/droplets/'.$droplet.'.php';
    if(is_readable($sFile)){
        if(importDropletFromFile($sFile)){
            echo 'Droplet <b>[['.$droplet.']]</b> installed successfully.<br>';
        }
    }
}