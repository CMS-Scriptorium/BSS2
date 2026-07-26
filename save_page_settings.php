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


require_once('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require_once(WB_PATH.'/modules/admin.php');

// This code removes any php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');

$page_offline = isset($_POST['page_offline']) ? "yes" : "no";
$offline_text = $database->escapeString(strip_tags($_POST['offline_text']));
$continue_url = $database->escapeString(strip_tags($_POST['continue_url']));
$header = $database->escapeString(str_replace($friendly, $raw, $_POST['header']));
$item_loop = $database->escapeString(str_replace($friendly, $raw, $_POST['item_loop']));
$footer = $database->escapeString(str_replace($friendly, $raw, $_POST['footer']));
$item_header = $database->escapeString(str_replace($friendly, $raw, $_POST['item_header']));
$item_footer = $database->escapeString(str_replace($friendly, $raw, $_POST['item_footer']));
$items_per_page = $_POST['items_per_page'];
$num_cols = $_POST['num_cols'];
if (extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) {
	$resize = $_POST['resize'];
} else {
	$resize = '';
}

$lightbox='';
 if (isset($_POST['lb2_overview'])) {
	$lightbox .= "1 ";
} 
if (isset($_POST['lb2_detail'])) {
	$lightbox .= "2 ";
}	
if (isset($_POST['ftr_overview'])) {
	$lightbox .= "3 ";
}
if (isset($_POST['ftr_detail'])) {
	$lightbox .= "4 ";	
} 
if (isset($_POST['ftr_autoload'])) {
	$lightbox .= "5 ";	
} 

if (isset($_POST['lb2_overview']) && isset($_POST['ftr_overview']) || isset($_POST['lb2_detail']) && isset($_POST['ftr_detail'])) {
	$lightbox="3 4";
}

// Update settings without the "continue shopping url" of specified section ids
if ($_POST['modify'] == "multiple") {
	$where_clause = '';
	foreach ($_POST['modify_sections'] as $section_id) {
		if (!is_numeric($section_id)) {
			continue;
		}
		$where_clause .= "`section_id` = '$section_id' OR ";
	}
	$where_clause = rtrim($where_clause, ' OR ');

	$database->query("UPDATE `{BXT}_page_settings` SET `page_offline` = '$page_offline', `offline_text` = '$offline_text', `header` = '$header', `item_loop` = '$item_loop', `footer` = '$footer', `item_header` = '$item_header', `item_footer` = '$item_footer', `items_per_page` = '$items_per_page', `num_cols` = '$num_cols', `resize` = '$resize', `lightbox` = '$lightbox' WHERE $where_clause");
}

// Update settings without the "continue shopping url" of all section ids 
elseif ($_POST['modify'] == "all") {
$database->query("UPDATE `{BXT}_page_settings` SET `page_offline` = '$page_offline', `offline_text` = '$offline_text', `header` = '$header', `item_loop` = '$item_loop', `footer` = '$footer', `item_header` = '$item_header', `item_footer` = '$item_footer', `items_per_page` = '$items_per_page', `num_cols` = '$num_cols', `resize` = '$resize', `lightbox` = '$lightbox'");
}

// Update settings of current section id only
elseif ($_POST['modify'] == "current") {
$database->query("UPDATE `{BXT}_page_settings` SET `page_offline` = '$page_offline', `offline_text` = '$offline_text', `continue_url` = '$continue_url', `header` = '$header', `item_loop` = '$item_loop', `footer` = '$footer', `item_header` = '$item_header', `item_footer` = '$item_footer', `items_per_page` = '$items_per_page', `num_cols` = '$num_cols', `resize` = '$resize', `lightbox` = '$lightbox' WHERE `section_id` = '$section_id'");
}

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();
