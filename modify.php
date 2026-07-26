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

//Look for language File
if (LANGUAGE_LOADED) {
    require_once(__DIR__.'/languages/EN.php');
    if (file_exists($sFile = __DIR__.'/languages/'.LANGUAGE.'.php')) {
        require_once $sFile;
    }
}

// Get some default values
require_once __DIR__.'/config.php';
// Include WB functions file
require_once WB_PATH.'/framework/functions.php';
// Include BAKERY's functions file
require_once __DIR__.'/functions.php';
$cfg = bxt_getGlobalCfg();

$display_settings = "inline";
$display_settings = "";
if ($cfg['display_settings'] == "1") {
    $display_settings = "none";
    if ($_SESSION['USER_ID'] == 1) {
        $display_settings = "inline";
    }
}
$sUrlTrail = $_SERVER['PHP_SELF'].'?page_id='.$page_id.'&section_id='.$section_id;
$oMsgBox = new MessageBox();

bxt_modify_processes();

// activate/deactivate record?
if(isset($_GET['active']) && isset($_GET['item_id'])){
    if(is_numeric($_GET['active']) && is_numeric($_GET['item_id'])){
        if($database->updateRow('{BXT}_items', 'item_id',
            array(
                'item_id' => $_GET['item_id'],
                'active'  => $_GET['active']
            )                
        )){
            $oMsgBox->success($TEXT['SUCCESS']);
            $sMsg = $oMsgBox->fetchDisplay();
            #echo $sMsg;
            $oMsgBox->redirect($sUrlTrail.'#row_'.$_GET['item_id']);
        }
    }    
}

// Delete empty Database records
$database->query("DELETE FROM {BXT}_items WHERE page_id = '$page_id' and section_id = '$section_id' and title=''");

$sModuleURL = get_url_from_path(__DIR__);
$sSecParam = 'page_id='.$page_id.'&amp;section_id='.$section_id;
?>

		
<div id="mod_bakery_modify_b">
    <ul class="bxt-menu">
        <li><?php if($cfg['use_payment'] == 1): ?>
            <a href="<?=$sModuleURL?>/modify_orders.php?<?=$sSecParam?>">
                <i class="fa fa-shopping-cart"></i> <?=$TXT_BAKERY['ORDER_ADMIN']; ?>
            </a>
            <?php else: ?>
            <a href="<?=$sModuleURL?>/modify_requests.php?<?=$sSecParam?>">
                <i class="fa fa-commenting"></i> <?=$TXT_BAKERY['REQUESTS']; ?>
            </a>
            <?php endif; ?>
        </li>
        <li>
            <a href="<?=$sModuleURL?>/modify_stock.php?<?=$sSecParam?>">
                <i class="fa fa-building"></i> <?=$TXT_BAKERY['STOCK_ADMIN']; ?>
            </a>
        </li>
        <li>
            <a href="<?=$sModuleURL?>/modify_options.php?<?=$sSecParam?>">
                <i class="fa fa-th"></i> <?=$TXT_BAKERY['ITEM_OPTIONS']; ?>
            </a>
        </li>
        <?php if($cfg['use_payment'] == 1): ?>
        <li>
            <a href="<?=$sModuleURL?>/modify_payment_methods.php?<?=$sSecParam?>">
                <i class="fa fa-usd"></i> <?=$TXT_BAKERY['PAYMENT_METHODS']; ?>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="<?=$sModuleURL?>/modify_page_settings.php?<?=$sSecParam?>">
                <i class="fa fa-paint-brush"></i> <?=$TXT_BAKERY['PAGE_SETTINGS']; ?>
            </a>
        </li>
        <li>
            <a href="<?=$sModuleURL?>/modify_general_settings.php?<?=$sSecParam?>">
                <i class="fa fa-sliders"></i> <?=$TXT_BAKERY['GENERAL_SETTINGS']; ?>
            </a>
        </li>
    </ul>

    <!--<h2><?=$TEXT['MODIFY'].' / '.$TEXT['DELETE'].' '.$TXT_BAKERY['ITEM']; ?></h2>-->
    <button onclick="javascript: window.location = '<?=$sModuleURL?>/add_item.php?<?=$sSecParam?>';" class="add-button active"><i class="fa fa-plus-circle"></i> <?=$TXT_BAKERY['ADD_ITEM']; ?></button>
<?php

// Loop through existing items
$query_items = $database->query("SELECT * FROM {BXT}_items WHERE section_id = '$section_id' ORDER BY position ASC");

if ($query_items->numRows() > 0) {
	$num_items = $query_items->numRows();
	?>
	<table cellpadding="2" cellspacing="0" border="0" width="100%" class="mod_bakery_dragndrop_b">
	<!--<caption><?=get_menu_title($page_id); ?></caption>-->
		<thead>
		  <tr height="30" class="grouptr">
                        <th></th>
                        <th style="text-align: right;"><span title="<?=$TXT_BAKERY['ITEM']; ?> ID">ID</span></th>
                        <th></th>
                        <th style="text-align: left;"><?=$TEXT['NAME']; ?></th>				
                        <th><?=$TEXT['ACTIVE']; ?></th>
                        <th><?=$TEXT['DELETE']; ?>?</th>
                        <th></th>
                        <th></th>
                        <th><span id="dragBakeryResult"></span></th>
		  </tr>
		</thead>
		<tbody id="dragBakeryTable">
		<?php 


		// LOOP ITEMS
		while ($post = $query_items->fetchRow()):

			// Prepare thumb path and url
			$thumb_path = WB_PATH.MEDIA_DIRECTORY.'/'.$img_dir.'/thumbs/item'.$post['item_id'].'/';
			$thumb_url  = WB_URL.MEDIA_DIRECTORY.'/'.$img_dir.'/thumbs/item'.$post['item_id'].'/';

			// Get main thumb (image with position == 1)
			$main_image = FALSE;
			$main_image = $database->get_one("SELECT filename FROM {BXT}_images WHERE item_id = '{$post['item_id']}' AND active = '1' ORDER BY position ASC LIMIT 1");

			// Check if png image has a jpg thumb (version < 1.7.6 used jpg thumbs only)
			$main_thumb = $main_image;
			if (!file_exists($thumb_path.$main_thumb)) {
                            $main_thumb = str_replace('.png', '.jpg', $main_thumb);
			}
			$main_thumb_url = $thumb_url.$main_thumb;

		?>
		<tr id="row_<?=$post['item_id']; ?>" class="irow">
			<td class="dragdrop_bakery"></td>
			<td class="item_id"><?=$post['item_id']; ?></td>

			<td style="width: 5%; padding-left: 5px;">
			<div class="mod_bakery_thumbnail_b">
                <?php if ($main_image):
                // Check if main image is set and display it
                ?>				
                    <a href="<?=$main_thumb_url; ?>" target="_blank">
                            <img src="<?=$main_thumb_url; ?>" alt="<?=$TXT_BAKERY['IMAGE'].' '.$main_thumb; ?>" height="48" border="0" />
                    </a>				           
                <?php else: 
                // else show the "noimage" icon --> 
                ?>                   
                   <img src="<?=$sModuleURL?>/images/nopic.jpg" alt="<?=$TEXT['NONE_FOUND']; ?>" title="<?=$TEXT['NONE_FOUND']; ?>" height="48" width="48" border="0" />
                <?php endif; ?>
                </div>     
			</td>
			
			<td style="width: 60%">
				<a href="<?=$sModuleURL?>/modify_item.php?page_id=<?=$page_id; ?>&amp;section_id=<?=$section_id; ?>&amp;item_id=<?=$post['item_id']; ?>" title="<?=$TEXT['MODIFY']; ?>">
					<strong><?=lazystrip($post['title']); ?></strong>
				</a>
			</td>
			
			<td style="width: 10%;text-align: center;">
                            <a class="bxt-icon" href="<?=$sUrlTrail.'&amp;item_id='.$post['item_id'].'&amp;active='.($post['active'] == 1 ? '0' : '1')?>">
                                <i class="fa fa-circle"<?=(($post['active'] == 1) ? ' style="color:#7dc32d;" title="'.$TEXT['YES'].'"' : ' style="color:#d78c87;" title="'.$TEXT['NO'].'"'); ?>></i>
                            </a>
			</td>
			
			<td style="width: 10%" align="center">
				<a href="javascript: confirm_link('<?=$TEXT['ARE_YOU_SURE']; ?>', '<?=$sModuleURL?>/delete_item.php?<?=$sSecParam?>&item_id=<?=$post['item_id']; ?>');" title="<?=$TEXT['DELETE']; ?>" class="bxt-icon del_icon">
					<i class="fa fa-times"></i>
				</a>
			</td>
			
			<td style="width: 18px" class="move_up">
			<?php if ($post['position'] != 1) { ?>
				<a href="<?=sprintf($sUrlTrail.'&move_item=%s&item_id='.$post['item_id'], 'up') ?>" title="<?=$TEXT['MOVE_UP']; ?>" class="bxt-icon">
                                    <i class="fa fa-arrow-circle-up"></i>
				</a>
			<?php } ?>
			</td>
			
			<td style="width: 118px" class="move_down">
			<?php if ($post['position'] != $num_items) { ?>
				<a href="<?=sprintf($sUrlTrail.'&move_item=%s&item_id='.$post['item_id'], 'down') ?>" title="<?=$TEXT['MOVE_DOWN']; ?>" class="bxt-icon">
                                    <i class="fa fa-arrow-circle-down"></i>
                                </a>
			<?php } ?>
			</td>
			<td class="dragdrop_bakery"></td>
		</tr>
		<?php
		
		
	endwhile; //LOOP
	?>
	</tbody>
		<tfoot>
			<tr>
				<td></td>	
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
	<?php
} else {
	echo '<p>'.$TXT_BAKERY['ITEMS'].': <i>'.$TEXT['NONE_FOUND'].'</i></p>';
}
?>
</div> <!-- enddiv #mod_bakery_modify_b -->