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

// Make use of the skinable backend themes of WB > 2.7
// Check if THEME_URL is supported otherwise use ADMIN_URL
if (!defined('THEME_URL')) {
	define('THEME_URL', ADMIN_URL);
}

// Look for language File
if (LANGUAGE_LOADED) {
    require_once __DIR__.'/languages/EN.php';
    if (file_exists($sLangFile = __DIR__.'/languages/'.LANGUAGE.'.php')) 
    	require_once $sLangFile;
}

// Get id
if (!isset($_GET['item_id']) OR !is_numeric($_GET['item_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$item_id = $_GET['item_id'];
}
// Get from
if (isset($_GET['from']) AND $_GET['from'] == 'add_item') {
	$show_item_mover = false;
} else {
	$show_item_mover = true;
}


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
require_once(WB_PATH.'/modules/bakery/functions.php');

// Get item
$query_item = $database->query("SELECT * FROM {BXT}_items WHERE item_id = '$item_id'");
$fetch_item = $query_item->fetchRow();
$fetch_item = array_map('lazystrip', $fetch_item);
$fetch_item = array_map('lazyspecial', $fetch_item);

// Get some default values
require_once(WB_PATH.'/modules/bakery/config.php');

// Get general settings
$query_settings = $database->query("SELECT * FROM {BXT}_general_settings");
$fetch_settings = $query_settings->fetchRow();
$fetch_settings = array_map('lazystrip', $fetch_settings);

// Prepopulate the text fields with previously entered item data when it has been submitted incompletely
if (isset($_SESSION['bxt']['item']) && is_array($_SESSION['bxt']['item'])) {
	$fetch_item['sku']               = htmlspecialchars($_SESSION['bxt']['item']['sku']);
	$fetch_item['stock']             = htmlspecialchars($_SESSION['bxt']['item']['stock']);
	$fetch_item['price']             = htmlspecialchars($_SESSION['bxt']['item']['price']);
	$fetch_item['shipping']          = htmlspecialchars($_SESSION['bxt']['item']['shipping']);
	$fetch_item['tax_rate']          = htmlspecialchars($_SESSION['bxt']['item']['tax_rate']);
	$fetch_item['definable_field_0'] = htmlspecialchars($_SESSION['bxt']['item']['definable_field_0']);
	$fetch_item['definable_field_1'] = htmlspecialchars($_SESSION['bxt']['item']['definable_field_1']);
	$fetch_item['definable_field_2'] = htmlspecialchars($_SESSION['bxt']['item']['definable_field_2']);
	$fetch_item['description']       = htmlspecialchars($_SESSION['bxt']['item']['description']);
	$fetch_item['full_desc']         = htmlspecialchars($_SESSION['bxt']['item']['full_desc']);
	$fetch_item['images']            = $_SESSION['bxt']['item']['images'];
	$fetch_item['imgresize']         = $_SESSION['bxt']['item']['imgresize'];
	$fetch_item['quality']           = htmlspecialchars($_SESSION['bxt']['item']['quality']);
	$fetch_item['maxheight']         = htmlspecialchars($_SESSION['bxt']['item']['maxheight']);
	$fetch_item['maxwidth']          = htmlspecialchars($_SESSION['bxt']['item']['maxwidth']);
	$fetch_item['active']            = htmlspecialchars($_SESSION['bxt']['item']['active']);
	$fetch_item['new_section_id']    = $_SESSION['bxt']['item']['new_section_id'];
	$fetch_item['action']            = $_SESSION['bxt']['item']['action'];	
	unset($_SESSION['bxt']['item']);
}


?>
<h2>1. <?= $TEXT['ADD'] . '/' . $TEXT['MODIFY'] . ' ' . $TXT_BAKERY['ITEM']; ?></h2>

<form name="modify" action="<?= WB_URL; ?>/modules/bakery/save_item.php" method="post" enctype="multipart/form-data" style="margin: 0;">

    <input type="hidden" name="section_id" value="<?= $section_id; ?>" />
    <input type="hidden" name="page_id" value="<?= $page_id; ?>" />
    <input type="hidden" name="item_id" value="<?= $item_id; ?>" />
    <input type="hidden" name="link" value="<?= $fetch_item['link']; ?>" />

    <table cellpadding="2" cellspacing="0" border="0" align="center" width="98%">
        <tr>
            <td width="20%" align="right"><?= $TXT_BAKERY['NAME']; ?>:</td>
            <td>
                <input type="text" name="title" id="title" style="width: 98%;" maxlength="150" value="<?= $fetch_item['title']; ?>" />
            </td>
        </tr>
        <tr>
            <td width="20%" align="right" valign="top"><?= $TXT_BAKERY['DESCRIPTION']; ?>:</td>
            <td>
                <textarea name="description" id="description" style="width: 98%; height: 50px;"><?= $fetch_item['description']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td width="20%" align="right"><?= $TXT_BAKERY['SKU']; ?>:</td>
            <td>
                <input type="text" name="sku" id="sku" style="width: 100px; display:inline-block" maxlength="150" value="<?= $fetch_item['sku']; ?>" />
            </td>
        </tr>
        <tr>
            <td width="20%" align="right"><?= $TXT_BAKERY['IN_STOCK']; ?>:</td>
            <td>
                <input type="text" name="stock" id="stock" style="width: 100px; text-align: right;  display:inline-block" maxlength="150" value="<?= $fetch_item['stock']; ?>" />
            </td>
        </tr>
        <tr>
            <td width="20%" align="right"><?= $TXT_BAKERY['PRICE']; ?>:</td>
            <td>
                <input type="text" name="price" id="price" style="width: 100px; text-align: right;  display:inline-block" maxlength="150" value="<?= $fetch_item['price']; ?>" />&nbsp;<?= $fetch_settings['shop_currency']; ?>
            </td>
        </tr>
        <tr>
            <td width="20%" align="right"><?= $TXT_BAKERY['SHIPPING']; ?>:</td>
            <td>
                <input type="text" name="shipping" id="shipping" style="width: 100px; text-align: right;  display:inline-block" maxlength="150" value="<?= $fetch_item['shipping']; ?>" />&nbsp;<?= $fetch_settings['shop_currency']; ?>&nbsp;&nbsp;&nbsp;(<?= $TXT_BAKERY['PER_ITEM']; ?>)
            </td>
        </tr>
        <tr>
            <?php
                $selected           = 0;
                $top                = '';
                $no_tax_rate        = '';
                $item_tax_rate      = $fetch_item['tax_rate'];
                $settings_tax_rate  = $fetch_settings['tax_rate'];
                $settings_tax_rate1 = $fetch_settings['tax_rate1'];
                $settings_tax_rate2 = $fetch_settings['tax_rate2'];
                // Show error message if no tax rate has been set
                if ($settings_tax_rate == 0 && $settings_tax_rate1 == 0 && $settings_tax_rate2 == 0) {
                        $top = "valign='top'";
                        $no_tax_rate = "<span style='color: red;'>{$TXT_BAKERY['SET_TAX_RATE']}:</span> <a href='".WB_URL."/modules/bakery/modify_general_settings.php?page_id=$page_id&amp;section_id=$section_id'> &gt; {$TXT_BAKERY['GENERAL_SETTINGS']}</a><br />";
                }
               ?>
            <td width='20%' align='right' <?=$top?>><?=$TXT_BAKERY['TAX_RATE']?>:</td>
            <td><?=$no_tax_rate?>
                <select name='tax_rate'>
                    <option value='<?=$settings_tax_rate?>'
                    <?php
                        if ($item_tax_rate == $settings_tax_rate) { 
                            echo " selected "; 
                            $selected = 1;                     
                        }
                    ?>
                    > <?=$settings_tax_rate?>%</option>
                    <?php
                    // Only show 2nd and 3rd taxe rate if they have been set
                    if ($settings_tax_rate1 > 0) { 
                        echo "<option value='".$settings_tax_rate1."'";
                        if ($item_tax_rate == $settings_tax_rate1) { 
                             echo " selected "; 
                            $selected = 1;                                     
                        }
                        echo "> $settings_tax_rate1%</option>\n";
                    }
                    if ($settings_tax_rate2 > 0) { 
                        echo "<option value='".$settings_tax_rate2."'";
                        if ($item_tax_rate == $settings_tax_rate2) { 
                             echo " selected "; 
                            $selected = 1;                                     
                        }
                        echo "> ".$settings_tax_rate2."%</option>\n";
                    }
                    echo "</select>\n";
                if ($selected == 0) { 
                    echo "<span style='color: red;'> ".$TXT_BAKERY['SAVED_TAX_RATE'].": <b>".$item_tax_rate."%</b></span><br />";                     
                }
            ?>
	    </td>
	</tr>
	<?php
	// Generate the required adaptable text fields
	for ($i=0; $i<=3; $i++) {
		if (isset($fetch_settings['definable_field_'.$i]) && !empty($fetch_settings['definable_field_'.$i])) {
                ?>
                <tr>
                    <td width="20%" align="right"><?= $fetch_settings['definable_field_' . $i]; ?>:</td>
                    <td>
                        <input type="text" name="definable_field_<?= $i; ?>" id="definable_field_<?= $i; ?>" style="width: 98%;" maxlength="150" value="<?= $fetch_item['definable_field_' . $i]; ?>" />
                    </td>
                </tr>
		<?php
		}
	}
	?>
            <tr>
		<td width="20%" align="right"><?=$TEXT['ACTIVE']; ?>:</td>
		<td>
                    <input type="radio" name="active" id="active_true" value="1" <?php if ($fetch_item['active'] == 1) { echo ' checked="checked"'; } ?> />
                    <label for="active_true"><?=$TEXT['YES']; ?></label>
                    &nbsp;
                    <input type="radio" name="active" id="active_false" value="0" <?php if ($fetch_item['active'] == 0) { echo ' checked="checked"'; } ?> />
                    <label for="active_false"><?=$TEXT['NO']; ?></label>
                </td>
            </tr>
<?php


// Only show item mover for existing items
if ($show_item_mover) {
?>
	<tr>
            <td width="20%" align="right"><?=$TXT_BAKERY['ITEM_TO_PAGE']; ?>... </td>
            <td>
	<?php
	// Bakery page list
	$query_pages = "SELECT p.page_id, p.page_title, p.visibility, p.admin_groups, p.admin_users, p.viewing_groups, p.viewing_users, s.section_id FROM {TP}pages p INNER JOIN {TP}sections s ON p.page_id = s.page_id WHERE s.module = 'bakery' AND p.visibility != 'deleted' ORDER BY p.level, p.position ASC";
	$get_pages = $database->query($query_pages);
	
	if ($get_pages->numRows() > 0) {
		// Generate sections select
		echo "<select name='new_section_id' style='width: 240px'>\n";
		while($page = $get_pages->fetchRow()) {
			$page = array_map('lazystrip', $page);
			// Only display if visible
			if ($admin->page_is_visible($page) == false)
				continue;
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			// Check user perms
			$in_group = false;
			foreach ($admin->get_groups_id() as $cur_gid){
				if (in_array($cur_gid, $admin_groups)) {
					$in_group = true;
				}
			}
			if (($in_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
				$can_modify = true;
			} else {
				$can_modify = false;
			}
			// Options
			echo "<option value='{$page['section_id']}'";
			echo $fetch_item['section_id'] == $page['section_id'] ? ' selected="selected"' : '';
			echo $can_modify == false ? " disabled='disabled' style='color: #aaa;'" : '';
			echo ">{$page['page_title']}</option>\n";
			// Prepare prechecked radio buttons
			$action_move = '';
			$action_duplicate = '';
			if (isset($fetch_item['action']) && $fetch_item['action'] == "duplicate") {
				$action_duplicate = ' checked="checked"';
			} else {
				$action_move = ' checked="checked"';
			}
		
		} ?>
		</select>
		<input name="action" type="radio" id="action_move" value="move"<?=$action_move; ?> /><label for="action_move">...<?=$TXT_BAKERY['MOVE']; ?></label>&nbsp; 
		<input name="action" type="radio" id="action_duplicate" value="duplicate"<?=$action_duplicate; ?> /><label for="action_duplicate">...<?=$TXT_BAKERY['DUPLICATE']; ?></label>
<?php	
	}
	else {	
		echo $TEXT['NONE_FOUND'];
	} ?>
		</td>
	</tr>
<?php
}



// ITEM FULL DESCRIPTION WYSIWYG EDITOR
// ************************************
?>	
	<tr>
		<td width="20%" height="40" align="right" valign="bottom"><b><?=$TXT_BAKERY['FULL_DESC']; ?>:</b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php
			$content = $fetch_item['full_desc'];
			$name    = "full_desc";
			$id      = "full_desc";
			$width   = "98%";
			$height  = "300px";
			if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
				function show_wysiwyg_editor($name,$id,$content,$width,$height) {
					echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
				}
			} else {
				$id_list=array("full_desc");
				require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
			}		
			show_wysiwyg_editor($name,$id,$content,$width,$height);
			?>
	  </td>
	</tr>
	<tr height="40" class="mod_bakery_submit_row_b">
		<td colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left" style="padding-left: 12px;">
					<input name="save_and_return" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px;background-color:#4db34e" />
					<input name="save" type="submit" value="<?=$TEXT['SAVE'].' &amp; '.$TEXT['BACK']; ?>" style="width: 160px; margin-left: 20px;" />
				</td>
				<td align="right" style="padding-right: 12px;">
				<input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; float: right;" />
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br /><br /><br />
<?php



// ITEM IMAGES
// ***********

// Title and table header
?>
<a name="images"><h2>2. <?=$TXT_BAKERY['ITEM'].' '.$TXT_BAKERY['IMAGES']; ?></h2></a>
<table cellpadding="4" cellspacing="0" border="0" width="98%" align="center">
	<tr height="38" valign="bottom" class="mod_bakery_submit_row_b">
	  <th width="8%" align="left"><span style="margin-left: 5px;"><?=$TXT_BAKERY['PREVIEW']; ?></span></th>
	  <th align="left"><?=$TXT_BAKERY['FILE_NAME']; ?></th>
	  <th width="15%" align="left">HTML title Attribute<br />* HTML alt Attribute</th>
	  <th width="15%" align="left"><?=$TXT_BAKERY['CAPTION']; ?></th>
	  <th width="8%" align="left"><?=$TXT_BAKERY['OPTION_ATTRIBUTES']; ?></th>
	  <th width="5%" align="left" colspan="2"><?=$TXT_BAKERY['POSITION']; ?></th>
	  <th width="3%" align="left"><?=$TEXT['ACTIVE']; ?></th>
	  <th width="3%" align="left"><?=$TEXT['DELETE']; ?></th>
	</tr>

	<?php
	// Get all images of this item
	$row      = 'a'; // Row color
	$no_image = true;
	$main_img = '<b>'.$TXT_BAKERY['MAIN_IMAGE'].'</b><br />';

	// Prepare image / thumb url and thumb path
	$img_url    = WB_URL.MEDIA_DIRECTORY.'/'.$img_dir.'/images/item'.$item_id.'/';
	$thumb_url  = WB_URL.MEDIA_DIRECTORY.'/'.$img_dir.'/thumbs/item'.$item_id.'/';
	$thumb_path = WB_PATH.MEDIA_DIRECTORY.'/'.$img_dir.'/thumbs/item'.$item_id.'/';

	// Get image top position for this item
	$top_img = $database->get_one("SELECT MAX(position) FROM {BXT}_images WHERE item_id = '$item_id'");

	// Get image data from db
	$query_image = $database->query("SELECT * FROM {BXT}_images WHERE item_id = '$item_id' ORDER BY position ASC");
	if ($query_image->numRows() > 0) {
		$no_image = false;
		while ($image = $query_image->fetchRow()) {
			$image      = array_map('lazystrip', $image);
			$img_id     = $image['img_id'];
			$image_file = $image['filename'];
			$image['delete_image'] = 0;

			// Check if png image has a jpg thumb (version < 1.7.6 used jpg thumbs only)
			$thumb_file = $image_file;
			if (!file_exists($thumb_path.$thumb_file)) {
				$thumb_file = str_replace('.png', '.jpg', $thumb_file);
			}

			// Use session image data if user has been sent back to complete form		
			if (isset($fetch_item['images'])) {
				$image['title']             = $fetch_item['images'][$img_id]['title'];
				$image['alt']               = $fetch_item['images'][$img_id]['alt'];
				$image['caption']           = $fetch_item['images'][$img_id]['caption'];
				$image['item_attribute_id'] = $fetch_item['images'][$img_id]['attribute'];
				$image['active']            = $fetch_item['images'][$img_id]['active'];
				$image['delete_image']      = $fetch_item['images'][$img_id]['delete_image'];
			}

			// Get items attributes
			$query_items_attributes = $database->query("SELECT a.attribute_name, a.attribute_id FROM {BXT}_options o INNER JOIN {BXT}_attributes a ON o.option_id = a.option_id INNER JOIN {BXT}_item_attributes ia ON a.attribute_id = ia.attribute_id WHERE ia.item_id = '$item_id' ORDER BY o.option_name, LENGTH(a.attribute_name), a.attribute_name ASC");

			// Generate attribute select
			$option_select = '<option value=""></option>'."\n";
			if ($query_items_attributes->numRows() > 0) {
				while ($attribute = $query_items_attributes->fetchRow()) {
					$attribute      = array_map('lazystrip', $attribute);
					$selected       = $image['item_attribute_id'] == $attribute['attribute_id'] ? ' selected="selected"' : '';
					$option_select .= "\t\t\t".'<option value="'.$attribute['attribute_id'].'"'.$selected.'>'.$attribute['attribute_name']."</option>\n";
				}
			}

			// Prepare html output 
			$image = array_map('lazyspecial', $image);
			?>

		<tr class="row_<?=$row; ?>">
		  <td><a href="<?=$img_url.$image_file; ?>" target="_blank"><img src="<?=$thumb_url.$thumb_file; ?>" alt="<?=$TXT_BAKERY['IMAGE'].' '.$image_file; ?>" title="<?=$image_file; ?>" height="40" border="0" /></a>
		  </td>
		  <td>
		  	<?=$main_img; ?>
		    <a href="<?=$img_url.$image_file; ?>" target="_blank" style="word-break: break-all;"><?=$image_file; ?></a>
		  </td>
		  <td>
			<input type="text" name="images[<?=$img_id; ?>][title]" style="width: 150px;" maxlength="255" value="<?=$image['title']; ?>" />
			<input type="text" name="images[<?=$img_id; ?>][alt]" style="width: 150px;" maxlength="255" value="<?=$image['alt']; ?>" />
		  </td>
		  <td>
		    <textarea name="images[<?=$img_id; ?>][caption]" rows="3" style="width: 200px;"><?=$image['caption']; ?></textarea>
		  </td>
		  <td>
		    <select name="images[<?=$img_id; ?>][attribute]" style="width: 90px;">
		      <?=$option_select; ?>
		    </select>
		  </td>
		  <td align="right">
		  <?php if ($image['position'] != 1) { ?>
		    <a href="<?=WB_URL; ?>/modules/bakery/move_img_up.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;item_id=<?=$item_id; ?>&amp;img_id=<?=$img_id; ?>" title="<?=$TEXT['MOVE_UP']; ?>">
		      <img src="<?=THEME_URL; ?>/images/up_16.png" border="0" alt="/\" />
		    </a>
		  <?php } ?>
		  </td>
		  <td align="left">
		  <?php if ($image['position'] != $top_img) { ?>
		    <a href="<?=WB_URL; ?>/modules/bakery/move_img_down.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;item_id=<?=$item_id; ?>&amp;img_id=<?=$img_id; ?>" title="<?=$TEXT['MOVE_DOWN']; ?>">
		      <img src="<?=THEME_URL; ?>/images/down_16.png" border="0" alt="\/" />
		    </a>
		  <?php } ?>
		  </td>
		  <td nowrap="nowrap" align="center">
		  	<input type="checkbox" name="images[<?=$img_id; ?>][active]" value="1"<?php if ($image['active'] == 1) {echo ' checked="checked"';} ?> />
		  </td>
		  <td nowrap="nowrap" align="center">
		  	<input type="checkbox" name="images[<?=$img_id; ?>][delete_image]" value="<?=$image_file; ?>"<?php if ($image['delete_image'] == $image_file) {echo ' checked="checked"';} ?> />
		  </td>
		</tr>
		<?php
		$row = $row == 'a' ? 'b' : 'a'; // Alternate row color
		$main_img = '';
		}
	}

	// Display message if no directories nor images found
	if ($no_image) {
		echo "<tr height='30'><td colspan='5'>\n";
		echo "<span style='color: red; padding-left: 50px;'>".$TEXT['NONE_FOUND']."</span>";
		echo "</td></tr>";
	}
	?>
</table>
<br /><br />


<?php
// Image upload
?>
<a name="images"><h2>3. <?=$TEXT['ADD'].' '.$TXT_BAKERY['IMAGES']; ?></h2></a>
<table cellpadding="2" cellspacing="0" border="0" width="100%" align="center">	
	<tr align="left" valign="top">
		<td>
		<?php
		// Image resize table
		?>
			<table class="mod_bakery_img_resize_table_b" cellspacing="4">
				<tr>
					<th colspan="2">
						<input type="checkbox" name="imgresize" id="imgresize" value="yes"<?=$fetch_item['imgresize'] == 'yes' ? ' checked="checked"' : ''; ?> />
						<label for="imgresize"><strong><?=$TXT_BAKERY['IMAGE'].' '.$TEXT['RESIZE']; ?></strong></label>
					</th>
				</tr>				
				<tr>
					<td><?=$TXT_BAKERY['MAX_WIDTH']; ?>:</td>
					<td><input type="text" size="5" name="maxwidth" value="<?=$fetch_item['maxwidth']; ?>" /></td>
				</tr>			
				<tr>
					<td><?=$TXT_BAKERY['MAX_HEIGHT']; ?>:</td>
					<td><input type="text" size="5" name="maxheight" value="<?=$fetch_item['maxheight']; ?>" /></td>
				</tr>				
				<tr>
					<td> <?=$TXT_BAKERY['JPG_QUALITY']; ?>:</td>
					<td><input type="text" size="3" name="quality" value="<?=$fetch_item['quality']; ?>" /></td>
				</tr>
			</table>
		</td>
		<td width="70%">
		<?php
		// Image upload table
		?>
		<table align="left" id="upload" style="margin: 5px;">	
			<tr>
				<td>
					<input type="file" name="image[]">
				</td>
			</tr>	
			<tfoot>
				<tr>
					<td>
						<span onclick="addFile(' [-] <?=$TEXT['DELETE']; ?>')" style="cursor: pointer;"> [+]  <?=$TEXT['ADD']; ?></span>
						<br /><br />
					</td>
				</tr>
			</tfoot>			
		</table>
		</td>
	</tr>
	<tr height="40" class="mod_bakery_submit_row_b">
		<td colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left" style="padding-left: 12px;">
					<input name="save_and_return_to_images" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px;;background-color:#4db34e" />
					<input name="save" type="submit" value="<?=$TEXT['SAVE'].' &amp; '.$TEXT['BACK']; ?>" style="width: 160px; margin-left: 20px;" />
				</td>
				<td align="right" style="padding-right: 12px;">
				<input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; float: right;" />
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br /><br /><br />
<?php



// ITEM OPTIONS AND ATTRIBUTES
// ***************************

// Title and table header
?>

<a name="options"><h2>4. <?=$TXT_BAKERY['ITEM_OPTIONS']; ?></h2></a>
<table cellpadding="2" cellspacing="0" border="0" width="100%" align="center">
	<tr height="30" valign="bottom" class="mod_bakery_submit_row_b">
	  <th width="320" align="left"><span style="margin-left: 5px;"><?=$TXT_BAKERY['OPTION_NAME'].": ".$TXT_BAKERY['OPTION_ATTRIBUTES']; ?></span></th>
	  <th width="140" align="center"><?=$TXT_BAKERY['OPTION_PRICE'] ?></th>
	  <th>&nbsp;</th>
	  <th colspan="2" align="center"><?=$TEXT['ACTIONS']; ?></th>
	</tr>

<?php
// Initialize vars
$listed_attribute_ids = array();
$attribute_id         = '';
$ia_operator          = '';
$ia_price             = '';

// Get items attributes
$query_items_attributes = $database->query("SELECT o.option_name, a.attribute_name, a.attribute_id, ia.assign_id, ia.price, ia.operator FROM {BXT}_options o INNER JOIN {BXT}_attributes a ON o.option_id = a.option_id INNER JOIN {BXT}_item_attributes ia ON a.attribute_id = ia.attribute_id WHERE ia.item_id = '$item_id' ORDER BY o.option_name, LENGTH(a.attribute_name), a.attribute_name ASC");

if ($query_items_attributes->numRows() > 0) {
	$row = 'a';
	// Show table with all existing item attributes
	while ($option = $query_items_attributes->fetchRow()) {
		$option = array_map('lazystrip', $option);
		// Get the item attribute which should be modified and start a new loop
		if (isset($_GET['attribute_id']) && $option['attribute_id'] == $_GET['attribute_id']) {
			$assign_id    = $option['assign_id'];
			$attribute_id = $option['attribute_id'];
			$ia_operator  = $option['operator'];
			$ia_price     = $option['price'];
			continue;
		}
		// Add all listed attribute ids to an array => omit them in the option and attribute select
		$listed_attribute_ids[] = $option['attribute_id'];


		// Show the existing item attributes
		?>
	<tr class="row_<?=$row; ?>" height="20">
	  <td align="left"><span style="margin-left: 5px;"><?=$option['option_name'].": ".$option['attribute_name']; ?></span></td>
	  <td align="right"><?=$option['operator'].' '.$fetch_settings['shop_currency'].' '.$option['price']; ?></td>
	  <td>&nbsp;</td>
	  <td align="center" width="22">
		<a href="<?=WB_URL; ?>/modules/bakery/modify_item.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;item_id=<?=$item_id; ?>&amp;attribute_id=<?=$option['attribute_id']; ?>#options" title="<?=$TEXT['MODIFY']; ?>">
			<img src="<?=THEME_URL; ?>/images/modify_16.png" alt="<?=$TEXT['MODIFY'].' '.$TXT_BAKERY['OPTION_NAME']; ?>" border="0" />
		</a>
	  </td>
	  <td align="left" width="22">
		<a href="javascript: confirm_link('<?=$TEXT['ARE_YOU_SURE']; ?>', '<?=WB_URL; ?>/modules/bakery/delete_item_attribute.php?page_id=<?=$page_id; ?>&section_id=<?=$section_id; ?>&item_id=<?=$item_id; ?>&attribute_id=<?=$option['attribute_id'] ?>');" title="<?=$TEXT['DELETE']; ?>">
			<img src="<?=THEME_URL; ?>/images/delete_16.png" border="0" alt="<?=$TEXT['DELETE'].' '.$TXT_BAKERY['OPTION_NAME']; ?>" />
		</a>
	  </td>
	</tr>
	<?php
	// Alternate row color
	$row = $row == 'a' ? 'b' : 'a';
	}
} else {
	echo "<tr height='30'><td colspan='5'>\n";
	echo "<span style='color: red; padding-left: 50px;'>".$TEXT['NONE_FOUND']."</span>";
	echo "</td></tr>";
}

// Show form to add new item attributes
echo "<tr height='50' class='mod_bakery_submit_row_b'>\n<td>\n";
// Get options and attributes
$query_options = $database->query("SELECT o.option_name, o.option_id, a.attribute_id, a.attribute_name FROM {BXT}_options o INNER JOIN {BXT}_attributes a ON o.option_id = a.option_id ORDER BY o.option_name, LENGTH(a.attribute_name), a.attribute_name ASC");
if ($query_options->numRows() > 0) {
	// Generate option and attribute select
	echo "<select name='attribute_id' style='width: 320px'>\n";
	while($option = $query_options->fetchRow()) {
		$option = array_map('lazystrip', $option);
		// Only display if not listed in the item attributes table above	
		if (in_array($option['attribute_id'], $listed_attribute_ids))
			continue;
		echo "<option value='{$option['attribute_id']}'";
		echo $attribute_id == $option['attribute_id'] ? ' selected="selected"' : '';
		echo ">{$option['option_name']}: {$option['attribute_name']}</option>\n";
	}
	echo "</select>";
} else {
	echo "{$TEXT['NONE_FOUND']}&nbsp;&nbsp;<a href='".WB_URL."/modules/bakery/modify_options.php?page_id=$page_id&amp;section_id=$section_id'>&gt; {$TEXT['ADD']}/<span style='text-transform: lowercase;'>{$TEXT['MODIFY']}/{$TEXT['DELETE']}</span></a>";
}
?>
	  </td>
	  <td align="right">
		<select name="ia_operator">
		  <option value="+"<?=$ia_operator == "+" ? ' selected="selected"' : ''; ?>> + </option>
		  <option value="-"<?=$ia_operator == "-" ? ' selected="selected"' : ''; ?>> - </option>
		  <option value="="<?=$ia_operator == "=" ? ' selected="selected"' : ''; ?>> = </option>
		</select>
		
	  </td>
	  <td>
	  <input type="text" name="ia_price" style="width: 60px; text-align: right;  display:inline-block" maxlength="150" value="<?=$ia_price; ?>" />
	  <?=$fetch_settings['shop_currency']; ?>
		<?php 
		if (!empty($assign_id)) {
			echo '<input type="hidden" name="assign_id" value="'.$assign_id.'" />';
		}
		?></td>
	  <td colspan="2"><input type="submit" name="save_attribute" value=" <?=$TEXT['ADD']; ?> " />
	  </td>
	</tr>
</table>
</form>

<?php

// Print admin footer
$admin->print_footer();
