<?php
/*
  
  Copyright (C) 2007 - 2021, Christoph Marti
Copyleft 2021- Christian M. Stefan, Florian Meerwinck
  Copyright (C) 2020 - 2021, Christian M. Stefan <stefek@designthings.de>

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

// Database
global $database;
require_once __DIR__.'/info.php';

// Setup styles to help identify errors
echo'
<style type="text/css">
    .good {color: green; font-weight:bold;}
    .bad {color: red; font-weight:bold;}
    .ok {color: blue; font-weight:bold;}
    .warn {color: yellow; font-weight:bold;}
</style>
';

// UPGRADE TO VERSION 0.70
// ***********************
$database->addPrefix('{BXT}', TABLE_PREFIX.'mod_bakery');

if ($module_version < 1.85) {
    include __DIR__.'/upgrade_legacy.php';
}


$aMsg = array();
if (version_compare($module_version, '2.0.0', "<")) {
      
    // UPGRADES TO 2.0.0
    $aMsg['EN'][] = "<h2>Upgrade to Version 2.0.0</h2>";
    $aMsg['DE'][] = "<h2>Upgrade auf Version 2.0.0</h2>";

    // add new fields to _customer table
    $aAddFields = [];
    // {BXT}_customer
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `cust_street_number` VARCHAR(16) NOT NULL AFTER `cust_street`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `cust_address_addition` VARCHAR(255) NOT NULL AFTER `cust_street_number`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `ship_street_number` VARCHAR(16) NOT NULL AFTER `ship_street`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `ship_address_addition` VARCHAR(255) NOT NULL AFTER `ship_street_number`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `cust_mobile` VARCHAR(32) NOT NULL AFTER `cust_phone`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `ship_phone` VARCHAR(32) NOT NULL AFTER `ship_zip`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_customer` ADD `ship_mobile` VARCHAR(32) NOT NULL AFTER `ship_phone`;"; 

    // {BXT}_items
    $aAddFields[] = "ALTER TABLE `{BXT}_items` ADD `seo_description` VARCHAR(255) NOT NULL AFTER `created_by`;"; 
    $aAddFields[] = "ALTER TABLE `{BXT}_items` ADD `seo_title` VARCHAR(255) NOT NULL AFTER `created_by`;"; 

    // {BXT}_page_settings
    $aAddFields[] = "ALTER TABLE `{BXT}_page_settings` ADD `layout` VARCHAR(32) NOT NULL DEFAULT '' AFTER `lightbox2`;"; 

    // {BXT}_general_settings        
    $aAddFields[] = "ALTER TABLE `{BXT}_general_settings` ADD `form_config` TEXT NULL DEFAULT NULL;";
    $aAddFields[] = "ALTER TABLE `{BXT}_general_settings` ADD `use_payment` TINYINT(1) NOT NULL DEFAULT '1';";
    $aErr = array();
    foreach($aAddFields as $statement){
        $database->query($statement);
        if($database->is_error()){
            $aErr[] = $database->get_error();
        }
    }
    if(!empty($aErr)){
        $sErr = '<pre class="bad">'.implode(PHP_EOL, $aErr).'</pre>';
        $aMsg['EN'][] = $sErr;
        $aMsg['DE'][] = $sErr;
        unset($aErr);
    } else {
        $aMsg['EN'][] = '<p class="good">Database Field changes successfull!</p>';
        $aMsg['DE'][] = '<p class="good">Änderungen an Datenbankfeldern erfolgreich!</p>';
    }

    $aMsg['EN'][] = "<h3>Install new Form Settings</h3>";
    $aMsg['DE'][] = "<h3>Installiere neue Formulareinstellungen</h3>";
    require_once __DIR__.'/functions.php';
    $cfg = bxt_getGlobalCfg();
    $aInitialFormSettings = array (
        'show_fields' => array (
            'cust_company' => ($cfg['company_field'] == 'show')?1:0,
            'cust_first_name' => 1,
            'cust_last_name' => 1,
            'cust_email' => 1,
            'cust_street' => 1,
            'cust_address_addition' => 0,
            'cust_city' => 1,
            'cust_state' => ($cfg['state_field'] == 'show')?1:0,
            'cust_country' => 1,
            'cust_zip' => 1,
            'cust_phone' => 1,
            'cust_mobile' => 1,
            'cust_tax_no' => ($cfg['tax_no_field'] == 'show')?1:0,
            'cust_message' => ($cfg['cust_msg'] == 'show')?1:0,
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
            'ship_mobile' => 0,
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
            'ship_mobile' => 0,
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
    if($database->is_error()){
        $sErr = '<pre class="bad">'.$database->get_error().'</pre>';
        $aMsg['EN'][] = $sErr;
        $aMsg['DE'][] = $sErr;
    } else {
        $aMsg['EN'][] = '<p class="good">Successfull!</p>';
        $aMsg['DE'][] = '<p class="good">Erfolgreich!</p>';
    }
        
    $aMsg['EN'][] = "<h3>CREATE NEW TABLE `mod_bakery_requests`</h3>";
    $aMsg['DE'][] = "<h3>ERSTELLE NEUE TABELLE `mod_bakery_requests`</h3>";
    // install requests Table
    $sNewTable = "CREATE TABLE IF NOT EXISTS `{BXT}_requests` (
        `request_id` int(6) NOT NULL AUTO_INCREMENT,
        `order_id`   int(6) NOT NULL,
        `timestamp`  int(11) NOT NULL,
        `user_id`    int(6) NOT NULL,
        `first_name` varchar(64) NOT NULL,
        `last_name`  varchar(64) NOT NULL,
        `email`      varchar(64) NOT NULL,
        `status`     int(1) NOT NULL,
        `json`       text NOT NULL,           
        PRIMARY KEY (`request_id`)
    )";
    $database->query($sNewTable);
    if($database->is_error()){
        $sErr = '<pre class="bad">'.$database->get_error().'</pre>';
        $aMsg['EN'][] = $sErr;
        $aMsg['DE'][] = $sErr; 
    } else {
        $aMsg['EN'][] = '<p class="good">Successfull!</p>';
        $aMsg['DE'][] = '<p class="good">Erfolgreich!</p>';
    }
    
     $aMsg['EN'][] = "<h3>Change Module function to 'page,preinit'</h3>";
     $aMsg['DE'][] = "<h3>Ändere function des Moduls zu 'page,preinit'</h3>";
    if(file_exists(__DIR__.'/preinit.php')){
        $aUpdate = array(
            'directory' => 'bakery',
            'function'  => 'page, preinit'
        );
        $database->updateRow('{TP}addons', 'directory', $aUpdate);
        if($database->is_error()){ 
            $sErr = '<pre class="bad">'.$database->get_error().'</pre>';
            $aMsg['EN'][] = $sErr;
            $aMsg['DE'][] = $sErr;
            trigger_error ($sErr,  E_USER_WARNING);
        } else {
            $aMsg['EN'][] = '<p class="good">Module function updated successfully.</p>';
            $aMsg['DE'][] = '<p class="good">Funktion des Moduls erfolgreich aktualisiert.</p>';
        }
    }
}

if (version_compare($module_version, '2.0.14', "<")) {
      
    // UPGRADES TO 2.0.14
    $aMsg['EN'][] = "<h2>Upgrade to Version 2.0.14</h2>";
    $aMsg['DE'][] = "<h2>Upgrade auf Version 2.0.14</h2>";
    
    $aMsg['EN'][] = "<h3>Change and Add DB Fields to Tables</h3>";
    $aMsg['DE'][] = "<h3>Hinzufügen und Ändern von Datenbankfeldern</h3>";
    $aFields = [];
    $aFields[] = "ALTER TABLE `{BXT}_general_settings` ADD `lightbox_plugin` VARCHAR(132) NOT NULL DEFAULT 'lightbox2' AFTER `use_captcha`;";
    $aFields[] = "ALTER TABLE `{BXT}_customer` ADD `json_order` TEXT NULL DEFAULT NULL AFTER `invoice`;"; 
    $aFields[] = "ALTER TABLE `{BXT}_page_settings` CHANGE `lightbox2` `lightbox` VARCHAR(10) NOT NULL DEFAULT 'detail';";

    $aErr = array();
    foreach($aFields as $statement){
        $database->query($statement);
        if($database->is_error()){
            $aErr[] = $database->get_error();
        }
    }
    if(!empty($aErr)){
        $sErrs = '<pre class="bad">'.implode(PHP_EOL, $aErr).'</pre>';
        $aMsg['EN'][] = $sErrs;
        $aMsg['DE'][] = $sErrs;
        unset($aErr);
    } else {
        $aMsg['EN'][] = '<p class="good">Successfull!</p>';
        $aMsg['DE'][] = '<p class="good">Erfolgreich!</p>';
    }    

    if($database->field_exists("{BXT}_page_settings", 'lightbox2') == true){
        $aMsg['EN'][] = "<h3>Change DB Fieldname lightbox2 to lightbox</h3>";
        $aMsg['DE'][] = "<h3>Ändere DB Feldname lightbox2 zu lightbox</h3>";
        $sStatement = "ALTER TABLE `{BXT}_page_settings` CHANGE `lightbox2` `lightbox` VARCHAR(10) NOT NULL DEFAULT 'detail';";
        $database->query($sStatement);	 
        if($database->is_error()){            
            $sErr = '<pre class="bad">'.$database->get_error().'</pre>';
            $aMsg['EN'][] = $sErr;
            $aMsg['DE'][] = $sErr;
        } else {
            $aMsg['EN'][] = '<p class="good">Successfull!</p>';
            $aMsg['DE'][] = '<p class="good">Erfolgreich!</p>';
        }
    }
}

if (version_compare($module_version, '2.0.17', "<")) {
    $aMsg['EN'][] = "<h2>Upgrade to Version 2.0.17</h2>";    
    $aMsg['DE'][] = "<h2>Upgrade auf Version 2.0.17</h2>";    
    
    /*
        R E M O V E   O B S I L E T E   F I L E S
    */
    $aMsg['EN'][] = "<h3>Remove obsolete files from modules directory</h3>";
    $aMsg['DE'][] = "<h3>Entferne nicht länger benötigte Dateien:</h3>";
    $aRemoveFiles = array(  
            // up/down mechanism included into the modify.php file
            'move_up.php',
            'move_down.php',
        
            'check_vat.php',   // function check_vat() moved to functions.php
            'eu_tax_zone.php', // $cfg_tax_group setting moved to config.php
                
            // the files below have been renamed from ´view_*´ to `checkout_*` prefix
            'view_confirmation.php',
            'view_form.php',
            'view_invoice.php',
            'view_order.php',
            'view_pay_methods.php',
            'view_summary.php',
    );

    $sStr = '';
    $sStrDE = '';
    foreach($aRemoveFiles as $sTmp){
            $sFile = __DIR__.'/'.$sTmp;
            if(file_exists($sFile)){
                if(unlink($sFile)){
                    $sStr .= "\tFile /<b>".$sTmp."</b> was removed successfully.\n";
                    $sStrDE .= "\tDatei /<b>".$sTmp."</b> erfolgreich entfernt \n";
                }
            }
    }
    $aMsg['EN'][] = "<pre>".$sStr."</pre>";
    $aMsg['DE'][] = "<pre>".$sStrDE."</pre>";
    
    
    $aMsg['EN'][] = "<p><big>No Database changes were necessary for this upgrade.</big></p>";
    $aMsg['DE'][] = "<p><big>Keine Datenbankänderungen erforderlich.</big></p>";
}

// install Droplets
include __DIR__.'/droplets/droplets.functions.php';
$aDroplets = array(
    'ModBakeryCartLink',
    'ModBakeryMiniCart'
);
$aMsg['EN'][] = "<h2>Upgrade Bakery Droplets</h2>";    
$aMsg['DE'][] = "<h2>Aktualisere Bakery Droplets</h2>";    
foreach($aDroplets as $droplet){
    $sFile = __DIR__.'/droplets/'.$droplet.'.php';
    if(is_readable($sFile)){
        if(importDropletFromFile($sFile)){
            $aMsg['EN'][] = '<p class="good">Droplet <b>[['.$droplet.']]</b> updated successfully.</p>';
            $aMsg['DE'][] = '<p class="good">Droplet <b>[['.$droplet.']]</b> erfolgreich aktualisiert.</p>';
        }
    }
}
?>
<br />
<?php 
// DISPLAY UPGRADE LOG
// *****************************************************
    if(empty($aMsg)){
        $aMsg['EN'][] = '<p class="ok">No Database Changes have been necessary with this upgrade.</p>';
        $aMsg['DE'][] = '<p class="ok">Es waren keine DB Änderungen erforderlich für dieses Upgrade.</p><br>';
    } else {
        $sLC = (LANGUAGE == "DE") ? "DE" : "EN";
        foreach($aMsg[$sLC] as $string) echo $string;        
    }
    
    $sCompleted = 'Upgrade completed!';     
    $sInfo = "Please check the upgrade log carefully. Save a copy for later use. Then click&hellip;";
    if(LANGUAGE == "DE"){
        $sCompleted = 'Upgrade abgeschlossen!';
        $sInfo = "Bitte das obige Upgrade-Log sorgfältig überprüfen und ggf. speichern.&hellip;";
    }
?>
<br><p class="good"><big><?=$sCompleted?></big></p>

<div style="padding: 15px 10px; text-align: center; color: blue; border: solid 1px blue; background-color: #DCEAFE;">
	<p style="font-weight:bold;"><?=$sInfo?></p>
	<form action="">
		<input type="button" value="OK" onclick="location.href='index.php'" style="width: 30%;">
	</form>
</div>
<script type="text/javascript">stop();</script>

<?php
$admin->print_footer(); // Print admin footer