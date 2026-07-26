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


// REMOVE DIRECTORIES
// ==================
// Get module pages directory from general setting table
$sBxtPagesDir = $database->get_one(
    "SELECT `pages_directory` FROM `{BXT}_general_settings`"
);
require_once(__DIR__.'/config.php'); // Get $img_dir
$aDirsRemove = array(
    PAGES_DIRECTORY.'/'.$sBxtPagesDir, 
    MEDIA_DIRECTORY.'/'.$img_dir
);

foreach($aDirsRemove as $dir){
    $dir = WB_PATH.$dir;
    if (is_dir($dir)) rm_full_dir($dir);
}

// REMOVE DB TABLES
// ==================
$database->query("DELETE FROM `{TP}search` WHERE `name` = 'module' AND `value` = 'bakery'");
$database->query("DELETE FROM `{TP}search` WHERE `extra` = 'bakery'");

$aTablesRemove = array(
    'items',
    'images',
    'options',
    'attributes',
    'item_attributes',
    'customer',
    'general_settings',
    'page_settings',
    'payment_methods',
    'requests',
    'order',
);
foreach($aTablesRemove as $tbl){
    $tbl = '{BXT}_'.$tbl;
    $database->query("DROP TABLE IF EXISTS `".$tbl."`");
}

