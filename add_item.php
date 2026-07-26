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
require WB_PATH.'/modules/admin.php'; // Include WB admin wrapper script

// Include the ordering class
require WB_PATH.'/framework/class.order.php';
$order = new order(
    '{BXT}_items', 
    'position', 
    'item_id', 
    'section_id'
);

$aInsert = [ 
    'section_id'   => $section_id,
    'page_id'      => $page_id,
    'active'       => 1,
    'created_when' => time(),
    'created_by'   => $admin->get_user_id(),
    'position'     => $order->get_new($section_id),
    'title'         => "",  // Empty string to avoid NULL insert! Likewise, the following.
    'sku'           => "",
    'stock'         => "",
    'definable_field_0' => "",
    'definable_field_1' => "",
    'definable_field_2' => "",
    'link'              => "",
    'description'       => "",
    'full_desc'         => ""
];
// Insert new row into database
$database->insertRow("{BXT}_items", $aInsert);

// Get the id
$item_id = $database->get_one("SELECT LAST_INSERT_ID()");

$sRedirect = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$item_id;
if ($database->is_error()) {
    $admin->print_error($database->get_error(), $sRedirect);
} else {
    // Say that a new record has been added, then redirect to modify_item page
    $admin->print_success($TEXT['SUCCESS'], $sRedirect.'&from=add_item');
}

// Print admin footer
$admin->print_footer();