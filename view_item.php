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

if($img_dir == ''){
    global $img_dir;
    global $use_table;
    global $link_length;
}

// SHOW ITEM DETAIL PAGE
// *********************

// Load jQuery if not loaded yet
?>
<script type="text/javascript">window.jQuery || document.write('<script src="<?=WB_URL; ?>/include/jquery/jquery-.min.js"><\/script>')</script>
<?php	
// If requested include lightbox (css is appended to the frontend.css stylesheet)
if ($setting_lightbox == 'detail' || $setting_lightbox == 'all' ||  strpos($setting_lightbox,"2") !== false) {
	?>
	<script type="text/javascript" src="<?=WB_URL; ?>/modules/bakery/lightbox2/js/lightbox.js"></script>
	<script type="text/javascript">
	//  Lightbox2 options
	lightbox.option({
		'albumLabel': '<?=$TXT_BAKERY['IMAGE']; ?> %1 <?=$TEXT['OF']; ?> %2'
	})
	</script>
	<?php
}
if (strpos($setting_lightbox,"4") !== false) { 	
	?>
	<script type="text/javascript" src="<?=WB_URL; ?>/modules/bakery/fotorama/fotorama.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=WB_URL; ?>/modules/bakery/fotorama/fotorama.css" />
<?php }

// Calculate price change depending on selected item option using js and jquery
?>
<script type="text/javascript" src="<?=WB_URL; ?>/modules/bakery/jquery/calc_price.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	// Get the price container (must be adapted if html template has been modified)
	container     = $('.mod_bakery_item_price_f').parent().next();
	
	// General settings
	currency      = '<?=$setting_shop_currency; ?>';
	decimal_sep   = '<?=$setting_dec_point; ?>';
	thousands_sep = "<?=$setting_thousands_sep; ?>";

	// Calculate price on document ready
	$('.mod_bakery_item_select_f :selected').calcPrice();

	// Calculate price on selcted item option
	$('.mod_bakery_item_select_f').change(function() {
		$('.mod_bakery_item_select_f :selected').calcPrice();
	});
});
</script>
<?php


// Get page and item info
$query_page = $database->query("SELECT `link` FROM `{TP}pages` WHERE `page_id` = '".PAGE_ID."'");
if ($query_page->numRows() > 0) {
	$page      = $query_page->fetchRow();
	$page_link = page_link($page['link']);
} else {
	exit('Page not found');
}

// Get total number of items
$query_total_num = $database->query("SELECT `item_id` FROM `{BXT}_items` WHERE `section_id` = '$section_id' AND `active` = '1' AND `title` IS NOT NULL");
$total_num = $query_total_num->numRows();

// Get item info
$query_item = $database->query("SELECT * FROM `{BXT}_items` WHERE `item_id` = '".ITEM_ID."' AND `active` = '1'");
if ($query_item->numRows() > 0) {
	$item     = $query_item->fetchRow();	
	$position = $item['position'];
	$title    = lazyspecial(lazystrip($item['title']));
	$price    = number_format(lazystrip($item['price']), 2, $setting_dec_point, $setting_thousands_sep);

	// Initialize vars
	$next_link     = '';
	$previous_link = '';

	// If number of items is limited on overview pages,
	// add saved position as a get parameter to the page link
	if ($setting_items_per_page > 0) {
		$p         = empty($_SESSION['bxt']['position']) ? 0 : $_SESSION['bxt']['position'];
		$page_link = page_link($page['link']).'?p='.$p;
	}

	// Create previous and next links
	$query_surrounding = $database->query("SELECT `item_id` FROM `{BXT}_items` WHERE `position` != '$position' AND `section_id` = '$section_id' AND `active` = '1' LIMIT 1");
	if ($query_surrounding->numRows() > 0) {
		// Get previous
		if ($position > 1) {
			$query_previous = $database->query("SELECT `title`, `link` FROM `{BXT}_items` WHERE `position` < '$position' AND `section_id` = '$section_id' AND `active` = '1' ORDER BY `position` DESC LIMIT 1");
			if ($query_previous->numRows() > 0) {
				$previous = $query_previous->fetchRow();
				// Truncate text and add horizontal ellipsis
				if (strlen($previous['title']) > $link_length) {
					$previous['title'] = substr($previous['title'], 0, $link_length).'…';
				}
				$previous_link = '<a href="'.WB_URL.PAGES_DIRECTORY.$previous['link'].PAGE_EXTENSION.'">&laquo; '.lazyspecial(lazystrip($previous['title'])).'</a>';
			}
		}
		// Get next
		$query_next = $database->query("SELECT `title`, `link` FROM `{BXT}_items` WHERE `position` > '$position' AND `section_id` = '$section_id' AND `active` = '1' ORDER BY `position` ASC LIMIT 1 ");
		if ($query_next->numRows() > 0) {
			$next = $query_next->fetchRow();
			// Truncate text and add horizontal ellipsis
			if (strlen($next['title']) > $link_length) {
				$next['title'] = substr($next['title'], 0, $link_length).'…';
			}
			$next_link = '<a href="'.WB_URL.PAGES_DIRECTORY.$next['link'].PAGE_EXTENSION.'">'.lazyspecial(lazystrip($next['title'])).' &raquo;</a>';
		}
	}

	$out_of = $position.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
	$of     = $position.' '.strtolower($TEXT['OF']).' '.$total_num;
	
	// User who last modified the item
	$uid = $item['modified_by'];
        
        // the user is known when his username can be found
        $bKnownUser = (isset($users[$uid]['username']) AND !empty($users[$uid]['username'])); // ?true:false

	
	// Workout date and time of last modified item
	$item_date = gmdate(DATE_FORMAT, $item['modified_when']+TIMEZONE);
	$item_time = gmdate(TIME_FORMAT, $item['modified_when']+TIMEZONE);



	// Item thumb(s) and image(s)

	// Initialize or reset thumb(s) and image(s) befor laoding next item
	$thumb_arr = array();
	$image_arr = array();
	$thumb     = '';
	$image     = '';

	// Prepare thumb and image directory pathes and urls
	$thumb_path = WB_PATH.MEDIA_DIRECTORY.'/'.$img_dir.'/thumbs/item'.ITEM_ID.'/';
	$img_path   = WB_PATH.MEDIA_DIRECTORY.'/'.$img_dir.'/images/item'.ITEM_ID.'/';
	$thumb_url  = WB_URL.MEDIA_DIRECTORY.'/'.$img_dir.'/thumbs/item'.ITEM_ID.'/';
	$img_url    = WB_URL.MEDIA_DIRECTORY.'/'.$img_dir.'/images/item'.ITEM_ID.'/';

	// Get image data from db
	$query_image = $database->query("SELECT * FROM `{BXT}_images` WHERE `item_id` = '".ITEM_ID."' AND `active` = '1' ORDER BY `position` ASC");
	if ($query_image->numRows() > 0) {
		while ($image = $query_image->fetchRow(MYSQLI_ASSOC)) {
			$image          = array_map('lazystrip', $image);
			$image          = array_map('lazyspecial', $image);
			$img_id         = $image['img_id'];
			$item_attribute = $image['item_attribute_id'];
			$image_file     = $image['filename'];
			$img_alt        = $image['alt'];
			$img_title      = $image['title'];
			$img_caption    = $image['caption'];
			
			if ($img_alt == "") { $img_alt = $title; }
		//	if ($img_title == "") { $img_title = $title; }
			
			// Check if png image has a jpg thumb (version < 1.7.6 used jpg thumbs only)
			$thumb_file = $image_file;
			if (!file_exists($thumb_path.$thumb_file)) {
				$thumb_file = str_replace('.png', '.jpg', $thumb_file);
			}

			// Prepare div image wrapper for image caption
			$caption_prepend = empty($img_caption) ? '' : '<div class="mod_bakery_item_caption_f">';
			$caption_append  = empty($img_caption) ? '' : '<br />'.$img_caption.'</div>';
			if (strpos($setting_lightbox,"4")!=false) {
				$caption_prepend ='';
				$caption_append ='';
			}

			// Add unique image id that corresponds to the item attribute
			$thumb_id = empty($item_attribute) ? '' : 'mod_bakery_thumb_attr'.$item_attribute.'_f';
			$img_id   = empty($item_attribute) ? '' : 'mod_bakery_img_attr'.$item_attribute.'_f';

			// Make array of all item thumbs and images
			if (file_exists($thumb_path.$thumb_file) && file_exists($img_path.$image_file)) {
				// If needed add lightbox link to the thumb/image...
				if ($setting_lightbox == 'detail' || $setting_lightbox == 'all' || strpos($setting_lightbox,"2") !== false ) {
					$prepend = '<a href="'.$img_url.$image_file.'" rel="lightbox[image_'.ITEM_ID.']" title="'.$img_title.'"><img src="';
					$thumb_append = '" alt="'.$img_alt.'" title="'.$img_title.'" id="'.$thumb_id.'" class="mod_bakery_item_thumb_f" /></a>';
					$img_append = '" alt="'.$img_alt.'" title="'.$img_title.'" id="'.$img_id.'" class="mod_bakery_item_img_f" /></a>';
				// ...else add thumb/image only			
				} else {
					$prepend = '<img src="';
					$thumb_append = '" alt="'.$img_alt.'" title="'.$img_title.'" id="'.$thumb_id.'" class="mod_bakery_item_thumb_f" />';
					$img_append = '" alt="'.$img_alt.'" title="'.$img_title.'" id="'.$img_id.'"  data-caption="'.$img_title.'" class="mod_bakery_item_img_f" />';
				}
				// Make array
				$thumb_arr[] = $prepend.$thumb_url.$thumb_file.$thumb_append;
				$image_arr[] = $caption_prepend.$prepend.$img_url.$image_file.$img_append.$caption_append;
			}
		}
	}
	// Main thumb/image (image position 1)
	$thumb = empty($thumb_arr[0]) ? '' : $thumb_arr[0];
	$image = empty($image_arr[0]) ? '' : $image_arr[0];
	unset($thumb_arr[0]);
	unset($image_arr[0]);

	// Make strings for use in the item templates
	$thumbs = implode("\n", $thumb_arr);
	$images = implode("\n", $image_arr);
	if (strpos($setting_lightbox,"4") !== false) {
		if (strpos($setting_lightbox,"5") == false) {
			$ftrAlString = 'data-auto="false"';
		} else {
			$ftrAlString = '';
		}
		$images = '<div id="fotorama" class="fotorama" '.$ftrAlString.' data-allowfullscreen="native" data-nav="thumbs">'.$image.$images.'</div>';
		$image='';
		$thumb='';
		$thumbs='';
	}


	// Show item options and attributes if we have to

	// Initialize vars
	$option        = '';
	$option_select = '';
	$open_tr       = '<div class="grid">';
	$open_td_label = '<div class="unit one-fifth">';
	$open_td_select = '<div class="unit four-fifths">';
	$close_td      = "</div>\n";
	$select_end    = '</div></div>'."\n";
	// Wrap select in a table row
	if ($use_table) {
		$open_tr    = '<tr>'."\n";
		$open_td_label    = '<td valign="top">'."\n";
		$open_td_select    = '<td valign="top">'."\n";
		$close_td   = "\n".'</td>'."\n";
		$select_end = '</td>'."\n".'</tr>';
	}

	// Get number of item options and loop for each of them
	$query_num_options = $database->query("SELECT DISTINCT o.option_name, ia.option_id FROM {BXT}_options o INNER JOIN {BXT}_item_attributes ia ON o.option_id = ia.option_id WHERE ia.item_id = ".ITEM_ID);			
	if ($query_num_options->numRows() > 0) {
		while ($num_options = $query_num_options->fetchRow()) {
			$option_name = lazystrip($num_options['option_name']);
			$option_id   = lazystrip($num_options['option_id']);

			// Get item attributes
			$query_attributes = $database->query("SELECT o.option_name, a.attribute_name, ia.attribute_id, ia.price, ia.operator FROM {BXT}_options o INNER JOIN {BXT}_attributes a ON o.option_id = a.option_id INNER JOIN {BXT}_item_attributes ia ON a.attribute_id = ia.attribute_id WHERE item_id = ".ITEM_ID." AND ia.option_id = '$option_id' ORDER BY o.option_name, LENGTH(a.attribute_name), a.attribute_name ASC");
			if ($query_attributes->numRows() > 0) {
				$option_select .= $open_tr.$open_td_label.'<span class="mod_bakery_item_option_f">'.$option_name.': </span>'.$close_td.$open_td_select.'<select name="attribute[]" id="mod_bakery_option_select_'.$option_id.'" class="mod_bakery_item_select_f"><option value="">...</<option>'."\n"; 
				while ($attributes = $query_attributes->fetchRow()) {
					$attributes = array_map('lazystrip', $attributes);
					// Make attribute select
					$attributes['operator'] = $attributes['operator'] == '=' ? '' : $attributes['operator'];
					$ia_price = ', '.$setting_shop_currency.' '.$attributes['operator'].$attributes['price'];
					$ia_price = $attributes['price'] == 0 ? '' : $ia_price;
					$option_select .= '<option value="'.$attributes['attribute_id'].'">'.$attributes['attribute_name'].$ia_price.'</option>'."\n";
				}
				$option_select .= '</select>'."\n".$select_end;
				$option         = $option_select;
			}
		}
	}

	// Check if we should show number of items, stock image or "in stock" message or nothing at all
	$item_stock = lazystrip($item['stock']);
	// Only show if item stock is not blank
	if ($item_stock === '' && $setting_stock_mode != 'none') {
		$stock = $TXT_BAKERY['N/A'];
	} else {
		// Display number of items
		if ($setting_stock_mode == 'number') {
			if ($item_stock < 1) {
				$stock = 0;
			} else {
				$stock = $item_stock;
			}
		
		// Display stock image
			} elseif ($setting_stock_mode == 'img' && is_numeric($setting_stock_limit) && !empty($setting_stock_limit)) {
				if ($item_stock < 1) {
					$stock = '<img src="'.WB_URL.'/modules/bakery/images/out_of_stock.gif" alt="'.$TXT_BAKERY['OUT_OF_STOCK'].'" title="'.$TXT_BAKERY['OUT_OF_STOCK'].'" class="mod_bakery_main_stock_img_f" />';
				} elseif ($item_stock > $setting_stock_limit) {
					$stock = '<img src="'.WB_URL.'/modules/bakery/images/in_stock.gif" alt="'.$TXT_BAKERY['IN_STOCK'].'" title="'.$TXT_BAKERY['IN_STOCK'].'" class="mod_bakery_main_stock_img_f" />';
				} else {
					$stock = '<img src="'.WB_URL.'/modules/bakery/images/short_of_stock.gif" alt="'.$TXT_BAKERY['SHORT_OF_STOCK'].'" title="'.$TXT_BAKERY['SHORT_OF_STOCK'].'" class="mod_bakery_main_stock_img_f" />';
			}
		// Display stock text message			
		} elseif ($setting_stock_mode == 'text' && is_numeric($setting_stock_limit) && !empty($setting_stock_limit)) {
			if ($item_stock < 1) {
				$stock = '<span class="mod_bakery_item_out_of_stock_f">'.$TXT_BAKERY['OUT_OF_STOCK'].'</span>';
			} elseif ($item_stock > $setting_stock_limit) {
				$stock = '<span class="mod_bakery_item_in_stock_f">'.$TXT_BAKERY['IN_STOCK'].'</span>';
			} else {
				$stock = '<span class="mod_bakery_item_short_of_stock_f">'.$TXT_BAKERY['SHORT_OF_STOCK'].'</span>';
			}
		// Display nothing
		} else {
			$stock = '';
		}
	}

	// Replace [wblinkPAGE_ID] generated by wysiwyg editor by real link
	$item['full_desc'] = lazystrip($item['full_desc']);

	// Replace placeholders by values
         $aTokens = array(
            '[ADD_TO_CART]' => $TXT_BAKERY['ADD_TO_CART'], 
            '[PAGE_TITLE]' => PAGE_TITLE,
            '[THUMB]' => $thumb,
            '[THUMBS]' => $thumbs,
            '[IMAGE]' => $image,
            '[IMAGES]' => $images,
            '[TITLE]' => $title,
            '[ITEM_ID]' => $item['item_id'],
            '[SKU]' => lazystrip($item['sku']),
            '[STOCK]' => $stock,
            '[PRICE]' => $price,
            '[TAX_RATE]' => lazystrip($item['tax_rate']),
            '[SHIPPING]' => lazystrip($item['shipping']),
            '[FIELD_1]' => lazystrip($item['definable_field_0']),
            '[FIELD_2]' => lazystrip($item['definable_field_1']),
            '[FIELD_3]' => lazystrip($item['definable_field_2']),
            '[OPTION]' => $option,
            '[DESCRIPTION]' => lazystrip($item['description']),
            '[FULL_DESC]' => lazystrip($item['full_desc']),
            '[SHOP_URL]' => $setting_continue_url,
            '[SHIPPING_DOMESTIC]' => $setting_shipping_domestic,
            '[SHIPPING_ABROAD]' => $setting_shipping_abroad,
            '[SHIPPING_D_A]' => $setting_shipping_d_a,
            '[CURRENCY]' => $setting_shop_currency,
            '[LINK]' => WB_URL.PAGES_DIRECTORY.$item['link'].PAGE_EXTENSION,
            '[BACK]' => $page_link,
            '[DATE]' => $item_date,
            '[TIME]' => $item_time,

            '[USER_ID]'      => $bKnownUser ? $uid : '',
            '[USERNAME]'     => $bKnownUser ? $users[$uid]['username'] : '',
            '[DISPLAY_NAME]' => $bKnownUser ? $users[$uid]['display_name'] : '',
            '[EMAIL]'        => $bKnownUser ? $users[$uid]['email'] : '',                    

            '[PREVIOUS]' => $previous_link, 
            '[NEXT]' => $next_link, 
            '[OUT_OF]' => $out_of, 
            '[OF]' => $of, 
            '[TEXT_OUT_OF]' => $TEXT['OUT_OF'], 
            '[TEXT_OF]' => $TEXT['OF'], 

            '[TEXT_READ_MORE]' => $TEXT['READ_MORE'],
            '[TXT_ITEM]' => $TXT_BAKERY['ITEM'],
            '[TXT_SKU]' => $TXT_BAKERY['SKU'],
            '[TXT_PRICE]' => $TXT_BAKERY['PRICE'],
            '[TXT_TAX_RATE]' => $TXT_BAKERY['TAX_RATE'],
            '[TXT_SHIPPING]' => $TXT_BAKERY['SHIPPING'],
            '[TXT_DOMESTIC]' => $TXT_BAKERY['DOMESTIC'],
            '[TXT_STOCK]' => $TXT_BAKERY['STOCK'],
            '[TXT_SHIPPING_COST]' => $TXT_BAKERY['SHIPPING_COST'],
            '[TXT_ABROAD]' => $TXT_BAKERY['ABROAD'],
            '[TXT_FULL_DESC]' => $TXT_BAKERY['FULL_DESC'],
            '[TXT_BACK]' => $TEXT['BACK'],
            '[TXT_FIELD_1]' => $setting_definable_field_0,
            '[TXT_FIELD_2]' => $setting_definable_field_1,
            '[TXT_FIELD_3]' => $setting_definable_field_2, 
            '[DISPLAY:STOCK]' => $cfg['stock_mode'] == 'none' ? ' style="display:none"' : ''
        );  
	echo strtr($setting_item_header, $aTokens);// Print item header	
	echo strtr($setting_item_footer, $aTokens);// Print item footer

} else {
	echo $TEXT['NONE_FOUND'];
	return;
}