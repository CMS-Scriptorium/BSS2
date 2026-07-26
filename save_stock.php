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


require_once('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require_once(WB_PATH.'/modules/admin.php');

// Get category
if (isset($_POST['cat'])) {
	$category = is_numeric($_POST['cat']) ? $_POST['cat'] : '';
} else {
	$category = '';
}

// Loop through the items... 
foreach ($_POST['stock'] as $item_id => $stock) {
	$stock = $database->escapeString(strip_tags($stock));
	$active = isset($_POST['active'][$item_id]) ? 1 : 0;
	// ...and update items
	$database->query("UPDATE `{BXT}_items` SET `stock` = '$stock', `active` = '$active' WHERE `item_id` = '$item_id'");
}

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_stock.php?page_id='.$page_id.'&cat='.$category);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_stock.php?page_id='.$page_id.'&cat='.$category);
}

// Print admin footer
$admin->print_footer();
