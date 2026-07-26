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

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
// Get some default values
require_once(WB_PATH.'/modules/bakery/config.php');
require_once(WB_PATH.'/modules/bakery/functions.php');

// Look for language File
if (LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}

// Get header and footer
$query_page_settings = $database->query("SELECT * FROM {BXT}_page_settings WHERE section_id = '$section_id'");
$fetch_page_settings = $query_page_settings->fetchRow();

// Set raw html <'s and >'s to be replaced by friendly html code
$raw      = array('<', '>');
$friendly = array('&lt;', '&gt;');

// Get list of all module bakery pages and prepare <select>
$continue_url_select = '';
$cur_continue_url    = lazystrip($fetch_page_settings['continue_url']);

$query_pages = "SELECT p.page_id, p.link, p.visibility, p.admin_groups, p.admin_users, p.viewing_groups, p.viewing_users, s.section_id FROM {TP}pages p INNER JOIN {TP}sections s ON p.page_id = s.page_id WHERE s.module = 'bakery' AND p.visibility != 'deleted' ORDER BY p.level, p.position ASC";
$get_pages = $database->query($query_pages);

if ($get_pages->numRows() > 0) {
	// Generate sections select
	$continue_url_select .= "<select name='continue_url' style='width: 98%'>\n";
	while($page = $get_pages->fetchRow()) {
		$page = array_map('lazystrip', $page);
		// Only display if visible
		if ($admin->page_is_visible($page) == false)
			continue;
		// Get user perms
		$admin_groups = lazyexplode(',', str_replace('_', '', $page['admin_groups']));
		$admin_users = lazyexplode(',', str_replace('_', '', $page['admin_users']));
		// Check user perms
		$in_group = FALSE;
		foreach ($admin->get_groups_id() as $cur_gid){
			if (in_array($cur_gid, $admin_groups)) {
				$in_group = TRUE;
			}
		}
		if (($in_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
			$can_modify = true;
		} else {
			$can_modify = false;
		}
		// Options
		$continue_url         = WB_URL.PAGES_DIRECTORY.$page['link'].PAGE_EXTENSION;
		$continue_url_select .= '<option value="'.$page['page_id'].'"';
		$continue_url_select .= $cur_continue_url == $page['page_id'] ? ' selected="selected"' : '';
		$continue_url_select .= $can_modify == false ? " disabled='disabled' style='color: #aaa;'" : '';
		$continue_url_select .= '>'.$continue_url.'</option>'."\n";	
	}
	$continue_url_select .= "</select>";
}
?>



<form name="modify" action="<?=WB_URL; ?>/modules/bakery/save_page_settings.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?=$section_id; ?>" />
<input type="hidden" name="page_id" value="<?=$page_id; ?>" />

<table cellpadding="2" cellspacing="0" border="0" align="center" width="98%">
	<tr>
		<td colspan="3"><h2><?=$TXT_BAKERY['PAGE_SETTINGS']; ?></h2></td>
	</tr>


<!-- Shop -->
	<tr valign="bottom">
		  <td width="25%" height="32" align="right"><strong><?=$TXT_BAKERY['SHOP'].' '.$TXT_BAKERY['SETTINGS']; ?>:</strong></td>
		  <td width="12" height="32" colspan="2">&nbsp;</td>
    </tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['PAGE_OFFLINE']; ?>:</td>
	  <td colspan="2"><input type="checkbox" name="page_offline" id="page_offline" value="yes" <?php if ($fetch_page_settings['page_offline'] == 'yes') { echo 'checked="checked"'; } ?> /></td>
    </tr>
	<tr>
	  <td align="right"><?=$TXT_BAKERY['OFFLINE_TEXT']; ?>:</td>
	  <td colspan="2"><input type="text" name="offline_text" style="width: 98%" maxlength="255" value="<?=lazystrip($fetch_page_settings['offline_text']); ?>" /></td>
    </tr>
	<tr>
		<td width="25%" align="right"><?=$TXT_BAKERY['CONTINUE_URL']; ?>:</td>
		<td colspan="2"><?=$continue_url_select; ?></td>
	</tr>


<!-- Layout -->
	<tr valign="bottom">
	  <td width="25%" height="32" align="right"><strong><?=$TXT_BAKERY['LAYOUT'].' '.$TXT_BAKERY['SETTINGS']; ?>:</strong></td>
	  <td height="32" colspan="2"><input type="button" value="<?=$MENU['HELP']; ?>" onclick="javascript: window.open('<?=WB_URL; ?>/modules/bakery/help.php?page_id=<?=$page_id; ?>&section_id=<?=$section_id; ?>','foo','top=50,left=50,width=800,height=600');" style="width: 100px;" /></td>
    </tr>
	<tr>
		<td width="25%" align="right" valign="top"><?=$TXT_BAKERY['OVERVIEW'].' ('.$TEXT['HEADER']; ?>):</td>
		<td colspan="2">
			<textarea name="header" style="width: 98%; height: 80px;"><?=lazystrip($fetch_page_settings['header']); ?></textarea></td>
	</tr>
	<tr>
		<td width="25%" align="right" valign="top"><?=$TXT_BAKERY['OVERVIEW'].' ('.$TXT_BAKERY['ITEM'].'-'.$TEXT['LOOP']; ?>):</td>
		<td colspan="2">
			<textarea name="item_loop" style="width: 98%; height: 80px;"><?=lazystrip($fetch_page_settings['item_loop']); ?></textarea></td>
	</tr>
	<tr>
		<td width="25%" align="right" valign="top"><?=$TXT_BAKERY['OVERVIEW'].' ('.$TEXT['FOOTER']; ?>):</td>
		<td colspan="2">
			<textarea name="footer" style="width: 98%; height: 80px;"><?=str_replace($raw, $friendly, lazystrip($fetch_page_settings['footer'])); ?></textarea>		</td>
	</tr>
	<tr>
		<td width="25%" align="right" valign="top"><?=$TXT_BAKERY['DETAIL'].' ('.$TEXT['HEADER']; ?>):</td>
		<td colspan="2">
			<textarea name="item_header" style="width: 98%; height: 80px;"><?=str_replace($raw, $friendly, lazystrip($fetch_page_settings['item_header'])); ?></textarea>		</td>
	</tr>
	<tr>
		<td width="25%" align="right" valign="top"><?=$TXT_BAKERY['DETAIL'].' ('.$TEXT['FOOTER']; ?>):</td>
		<td colspan="2">
			<textarea name="item_footer" style="width: 98%; height: 80px;"><?=str_replace($raw, $friendly, lazystrip($fetch_page_settings['item_footer'])); ?></textarea>		</td>
	</tr>
	<tr>
		<td width="25%" align="right"><?=$TXT_BAKERY['ITEMS_PER_PAGE']; ?>:</td>
		<td colspan="2">
			<input type="text" name="items_per_page" style="width: 35px" value="<?=$fetch_page_settings['items_per_page']; ?>" /> 0 = <?=$TEXT['UNLIMITED']; ?>		</td>
	</tr>
	<tr>
		<td width="25%" align="right"><?=$TXT_BAKERY['NUMBER_OF_COLUMNS']; ?>:</td>
		<td colspan="2">
			<select name="num_cols" style="width: 40px;">
				<?php
				for ($i = 1; $i <= 10; $i++) {
					if ($fetch_page_settings['num_cols'] == $i) { 
						$selected = ' selected';
					} else { 
						$selected = '';
					}
					echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
				}
				?>
			</select></td>
	</tr>
	<?php if (extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */ ?>
	<tr>
		<td width="25%" align="right"><?=$TXT_BAKERY['THUMBNAIL'].' '.$TEXT['SIZE']; ?>:</td>
		<td colspan="2">
			<select name="resize" style="width: 20%;">
				<?php
				foreach ($default_thumb_sizes AS $size => $size_name) {
					if ($fetch_page_settings['resize'] == $size) {
						$selected = ' selected';
					} else { 
						$selected = '';
					}
					echo '<option value="'.$size.'"'.$selected.'>'.$size_name.'</option>';
				}
				?>
			</select></td>
	</tr>
	<tr>
		<td width="25%" align="right">Gallery Script:</td>
		<td colspan="4">
			<?php
			$lbSettings = $fetch_page_settings['lightbox'];
			if (strpos($lbSettings,'1') !== FALSE || $lbSettings=="overview" || $lbSettings=="all") { $checkLb2o = 'checked="checked"'; } else { $checkLb2o ='';}
			if (strpos($lbSettings,'2') !== FALSE || $lbSettings=="detail" || $lbSettings=="all") { $checkLb2d = 'checked="checked"'; } else { $checkLb2d ='';}
			if (strpos($lbSettings,'3') !== FALSE) { $checkFtro = 'checked="checked"'; } else { $checkFtro ='';}
			if (strpos($lbSettings,'4') !== FALSE) { $checkFtrd = 'checked="checked"'; } else { $checkFtrd ='';}
			if (strpos($lbSettings,'5') !== FALSE) { $checkFtra = 'checked="checked"'; } else { $checkFtra ='';}
			?>
		
		  <input type="checkbox" name="lb2_overview" id="lb2_overview" value="overview" <?=$checkLb2o; ?> />
		  <label for="lb2_overview">Lightbox2 - <?=$TXT_BAKERY['OVERVIEW']; ?></label> &nbsp;&nbsp;
		  <input type="checkbox" name="lb2_detail" id="lb2_detail" value="detail" <?=$checkLb2d; ?> />
		  <label for="lb2_detail">Lightbox2 - <?=$TXT_BAKERY['DETAIL']; ?></label>
		  <br><br>
		  <input type="checkbox" name="ftr_overview" id="ftr_overview" value="overview" <?=$checkFtro; ?> />
		  <label for="ftr_overview">Fotorama - <?=$TXT_BAKERY['OVERVIEW']; ?></label> &nbsp;&nbsp;
		  <input type="checkbox" name="ftr_detail" id="ftr_detail" value="detail" <?=$checkFtrd; ?> />
		  <label for="ftr_detail">Fotorama - <?=$TXT_BAKERY['DETAIL']; ?></label>
		  <input type="checkbox" name="ftr_autoload" id="ftr_autoload" value="autoload" <?=$checkFtra; ?> />
		  <label for="ftr_autoload">Fotorama - Autoload</label>
		  <br>
		  <p><strong><a href="javascript:void(0)" id="galleryhelp">Hilfe zu diesen Optionen</a></strong></p>
		  	  
		  
		  </td>
	</tr>
	<?php } ?>
</table>

<div id="galleryinfo" style="display:none; border:1px solid #666; padding:10px; background-color:#fff">
<p>Es kann auf den Übersichts- und Detailseiten jeweils <em>entweder</em> Lightbox2 <em>oder</em> Fotorama verwendet werden (wird beides angeklickt, wird die Einstellung auf Fotorama gesetzt). Es ist natürlich auch möglich, ein anderes, manuell zu installierendes/konfigurierendes Galerie- oder Lightboxscript zu verwenden (z.B. Colorbox).</p>
<p>Bei der Einstellung Fotorama werden für die Detailansicht die Platzhalter [IMAGE], [THUMB] und [THUMBS] geleert, da nur [IMAGES] bei Fotorama benötigt wird.</p>
<p><strong>PROFI-FEATURE:</strong></p>
<p>Im Normalfall muss Fotorama nicht gesondert initialisiert werden (Einstellung Fotorama-Autoload aktiv). Sofern allerdings die Möglichkeit bestehen soll, abhängig von einer bestimmten Artikeloption zu einem bestimmten Galeriebild zu springen, muss die Option "Fotorama-Autoload" abgewählt werden und Fotorama zusammen mit dem auszuführenden Script bei den Seiteneinstellungen manuell initialisiert werden.<p>
<p>Beispiel Übersicht (Artikel-Schleife) für Platzhalter [THUMBS]:</p>
<pre>
&lt;script&gt;
$(function () {
var $fotoramaDiv_[ITEM_ID] = $('#fotorama_[ITEM_ID]').fotorama();
	var fotorama_[ITEM_ID] = $fotoramaDiv_[ITEM_ID].data('fotorama');
	$("#mod_bakery_option_select_1_[ITEM_ID]").on('change', function() {
		id2show = 'mod_bakery_thumb_[ITEM_ID]_attr'+this.value+'_f';
		fotorama_[ITEM_ID].show(id2show);
	});
});
&lt;/script&gt;
</pre>

<p>Beispiel Übersicht (Artikel-Schleife) für Platzhalter [IMAGES]:</p>
<pre>
&lt;script&gt;
$(function () {
var $fotoramaDiv_[ITEM_ID] = $('#fotorama_[ITEM_ID]').fotorama();
	var fotorama_[ITEM_ID] = $fotoramaDiv_[ITEM_ID].data('fotorama');
	$("#mod_bakery_option_select_1_[ITEM_ID]").on('change', function() {
		id2show = 'mod_bakery_img_[ITEM_ID]_attr'+this.value+'_f';
		fotorama_[ITEM_ID].show(id2show);
	});
});
&lt;/script&gt;
</pre>

<p>Beispiel Detailansicht (Fußzeile):</p>
<pre>
&lt;script&gt;
$(function () {
	var $fotoramaDiv = $('#fotorama').fotorama();
	var fotorama = $fotoramaDiv.data('fotorama');
	$("#mod_bakery_option_select_1").on('change', function() {
		id2show = 'mod_bakery_img_attr'+this.value+'_f';
		fotorama.show(id2show);
	});
});
&lt;/script&gt;
</pre>
<p>In diesem Beispiel wird bei Auswahl einer Option mit der Option-ID 1 das dem ausgewählten Optionswert zugeordnete Bild angesprungen. Es sollte immer nur ein Bild einem bestimmten Optionswert zugeordnet sein, (also z.B. Bild 2 für Farbe=blau, Bild 5 für Farbe=rot usw., aber nicht Bild 2 für Farbe=grün und Bild 3 ebenfalls für Farbe=grün.)</p>
<p>Die Option-ID wird bei "Artikel Optionen" angezeigt (fortlaufende, unveränderliche Nummer).</p>
</div>
		  
<script>
$(document).ready(function(){
  $("#galleryhelp").click(function(){
    $("#galleryinfo").toggle();
  });
});
</script>	

<?php
// Bakery page list
$query_pages = "SELECT p.page_id, p.page_title, p.visibility, p.admin_groups, p.admin_users, p.viewing_groups, p.viewing_users, s.section_id FROM {TP}pages p INNER JOIN {TP}sections s ON p.page_id = s.page_id WHERE s.module = 'bakery' AND p.visibility != 'deleted' ORDER BY p.level, p.position ASC";
$get_pages = $database->query($query_pages);


// Generate sections select
if ($get_pages->numRows() > 0) {
	$sections_select = '';
	while ($page = $get_pages->fetchRow()) {
		$page = array_map('lazystrip', $page);
		// Only display if visible
		if ($admin->page_is_visible($page) == false)
			continue;
		// Get user perms
		$admin_groups = lazyexplode(',', str_replace('_', '', $page['admin_groups']));
		$admin_users = lazyexplode(',', str_replace('_', '', $page['admin_users']));
		// Check user perms
		$in_group = FALSE;
		foreach ($admin->get_groups_id() as $cur_gid) {
			if (in_array($cur_gid, $admin_groups)) {
				$in_group = TRUE;
			}
		}
		if (($in_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
			$can_modify = true;
		} else {
			$can_modify = false;
		}
		// Options
		$sections_select .= "<option value='{$page['section_id']}'";
		if (isset($fetch_item['new_section_id']) && $fetch_item['new_section_id'] == $page['section_id']) {
			$sections_select .= ' selected="selected"';
		}
		elseif ($section_id == $page['section_id']) {
			$sections_select .= ' selected="selected"';
		}
		$sections_select .= $can_modify == false ? " disabled='disabled' style='color: #aaa;'" : '';
		$sections_select .= ">{$page['page_title']}</option>\n";
	}
}



// Save page settings   ?>
<table width="98%" align="center" cellpadding="0" cellspacing="4" class="mod_bakery_submit_row_b" style="padding: 10px;">
	<tr>
        <td><input type="radio" name="modify" id="modify_current" value="current" checked="checked" /></td>
        <td colspan="2"><label for="modify_current"><em><?=$TXT_BAKERY['MODIFY_THIS']; ?></em></label></td>
	</tr>
	<tr>
        <td><input type="radio" name="modify" id="modify_all" value="all" /></td>
        <td colspan="2"><label for="modify_all"><em><?=$TXT_BAKERY['MODIFY_ALL']; ?></em></label></td>
	</tr>
	<tr>
        <td><input type="radio" name="modify" id="modify_multiple" value="multiple" /></td>
        <td><label for="modify_multiple"><em><?=$TXT_BAKERY['MODIFY_MULTIPLE']; ?></em></label></td>
        <td rowspan="2">
		  <select name="modify_sections[]" multiple="multiple" style="width: 240px; margin: 0 5px 0 0;">
			<?=$sections_select; ?>
		  </select>
		</td>
	</tr>
	<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
	</tr>
	<tr>
        <td colspan="2" height="30" align="left">
		  <input name="save" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px; margin: 5px 0 0 15px;" />		</td>
        <td height="30" align="right">
		  <input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; margin: 5px 15px 0 0;" />
		</td>
	</tr>
</table>
</form>

<?php

// Print admin footer
$admin->print_footer();
