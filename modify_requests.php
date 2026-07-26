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
require WB_PATH.'/modules/admin.php';
require __DIR__.'/functions.php';
$cfg = bxt_getGlobalCfg();
$sCurrency = $cfg['shop_currency'];
I::insertCssFile(WB_URL.'/modules/miniform/backend.css');
// Look for language file
if (LANGUAGE_LOADED) {
    require_once __DIR__.'/languages/EN.php';
    if (file_exists($sLangFile = __DIR__.'/languages/'.LANGUAGE.'.php')) 
    	require_once $sLangFile;
}

$sUrlTrail = $_SERVER['PHP_SELF'].'?page_id='.$page_id.'&amp;section_id='.$section_id;
$oMsgBox = new MessageBox();
// archive record?
if(isset($_GET['archive'])){
    if(is_numeric($_GET['archive'])){
        if($database->updateRow('{BXT}_requests', 'request_id',
            array(
                'request_id' => $_GET['archive'],
                'status'     => 4
            )                
        )){
            $oMsgBox->success('Eintrag #'.$_GET['archive'].' wurde ins Archiv verschoben');
        }
    }    
}
if(isset($_GET['recover'])){
    if(is_numeric($_GET['recover'])){
        if($database->updateRow('{BXT}_requests', 'request_id',
            array(
                'request_id' => $_GET['recover'],
                'status'     => 0
            )                
        )){
            $oMsgBox->success('Eintrag #'.$_GET['recover'].' wurde aus dem Archiv verschoben');
        }
    }    
}

// delete record?
if(isset($_GET['delete'])){
    if(is_numeric($_GET['delete'])){
        if($database->delRow('{BXT}_requests', 'request_id', $_GET['delete'])){
            $oMsgBox->success('Eintrag #'.$_GET['delete'].' wurde gelöscht');
        }
    }    
}
$sMsg = $oMsgBox->fetchDisplay();
echo $sMsg;
// Show current or archived orders
$view = 'current';
if (isset($_GET['view'])) {
	$view = $_GET['view'];
}
?>
<ul class="bxt-menu">
        <li>
            <a href="<?=$sUrlTrail?>"<?=($view == 'current') ? ' class="active"':'';?>>
                <i class="fa fa-commenting"></i> <?=$TXT_BAKERY['REQUESTS']; ?>
            </a>
        </li>
        <li>
            <a href="<?=$sUrlTrail?>&amp;view=archive"<?=($view == 'archive') ? ' class="active"':'';?>>
                <i class="fa fa-archive"></i> <?=$TXT_BAKERY['REQUESTS']; ?>-Archiv
            </a>
        </li>
    </ul>
<?php

    $iStatus = 0;
    if($view == 'archive'){
        $sUrlTrail .= '&amp;view=archive';
        $iStatus = 4;
        $TXT_BAKERY['REQUESTS'] .= '-Archiv';
    }
    $heighestTimestamp = $database->get_one("SELECT max(`timestamp`) FROM `{BXT}_requests`");
    $sLatestMessage = timeago($heighestTimestamp);
    $aData = $database->get_array("SELECT * FROM `{BXT}_requests` WHERE `status` = ".$iStatus);
    //debug_dump($aData);
?>
<h1><?=$TXT_BAKERY['REQUESTS']; ?></h1>
<!--<h2>Die letzte Anfrage kam <?=$sLatestMessage?></h2>-->
<div class="content-box">
        <table id="mf_msgs_<?=$section_id ?>" class="mf_messages" cellpadding="2" cellspacing="0" border="0" width="100%">
            <thead>
                    <tr>
                        <th colspan="2"><?=count($aData)?> entries</th>
                        <th colspan="1">
                        </th>
                    </tr>
            </thead>
            <tbody>
            <?php if(!empty($aData)): 
                
            foreach($aData as $rec):
                $sDate = date(DATE_FORMAT.' - '.TIME_FORMAT,$rec['timestamp']+TIMEZONE);
            ?>
            <tr class="setline">
                <td>
                        <a class="msg_toggle" rel="<?=$rec['request_id']?>" title="<?=$TXT_BAKERY['REQUESTS']?>-ID: <?=$rec['request_id']?>" href="#request_id_<?=$rec['request_id']?>">
                            <span class="icon">&#9993;</span>
                            <small>ID: <?=$rec['order_id']?></small>
                            <b><?=$rec['first_name']?> <?=$rec['last_name']?></b>, <i><?=$rec['email']?></i>
                        </a> 
                    </td>
                    <td align="right">
                        <a href="<?=WB_URL?>/modules/bakery/modify_single_request.php?page_id=<?=$page_id.'&amp;section_id='.$section_id.'&amp;order_id='.$rec['order_id'];?>">
                        <i class="fa fa-paperclip"></i>
                        </a>
                        <small><i><?=$TEXT['DATE'].': <b>'.$sDate?></b></i></small>
                    </td>
                    <td align="right" class="delete_submission">
                        <a href="javascript: confirm_link('<?=$TEXT['ARE_YOU_SURE']; ?>', '<?=$sUrlTrail?>&amp;delete=<?=$rec['request_id']?>');" class="del_icon" title="<?=$TEXT['DELETE']?>"><i class="fa fa-times"></i></a>
                        <?php if($view ==  'archive'): ?>
                        <a href="<?=$sUrlTrail?>&amp;recover=<?=$rec['request_id']?>" class="arch_icon"><i class="fa fa-reply"></i></a>
                        <?php else: ?>
                        <a href="<?=$sUrlTrail?>&amp;archive=<?=$rec['request_id']?>" class="arch_icon"><i class="fa fa-archive"></i></a>
                        <?php endif; ?>
                    </td>
            </tr>
            <tr id="request_id_<?=$rec['request_id']?>">
                <td colspan="3">
                    <div class="req-content" style="display:none;">
                        <table style="width: 100%">
                        <?php 
                        $sCustomerMessage = 'n/a';
                        $aDetails = json_decode($rec['json'], 1);
                        $address_start = false;
                        foreach($aDetails as $key=>$val): 
                            if($key == 'user_id') continue;
                            if($key == 'order_id'){ 
                                $iOrderID = $val;
                                continue;
                            }
                            if($key == 'cust_message'){
                                $sCustomerMessage = nl2br($val);
                                continue;
                            }
                            $str = $key;
                            $str = str_replace('ship', 'cust', $str);
                            
                            if(array_key_exists(strtoupper($str), $TXT_BAKERY)){
                                $str = $TXT_BAKERY[strtoupper($str)];
                            }
                            
                            if(strpos($key, 'ship') !== false && $address_start != true){
                                $address_start = true;
                                echo '<tr><td colspan="2" style="font-size:115%">'.$TXT_BAKERY['SHIP_ADDRESS'].'</td></tr>';
                            }
                            ?>
                           <tr>
                               <td style="width:25%;font-weight:bold; text-align: right;padding-right:8px;">
                                   <span title="<?=$key?>"><?= $str ?></span>:
                               </td>
                               <td>
                                   <?=$val?>
                               </td>
                           </tr>
                        <?php endforeach;?>
                        </table>

                        <?php 
                        $tmpCart = $database->get_one(
                            "SELECT `json_order` FROM `{BXT}_customer` WHERE `order_id` = ".$rec['order_id']
                        ); 
                        $aCart = json_decode($tmpCart, 1);
                        if(!empty($aCart['items'])):
                        ?>
                        <table style="width: 95%;margin:10px;border:1px solid #ccc">
                            <thead>
                                <tr>
                                    <th><?=$TXT_BAKERY['SKU']?></th>
                                    <th></th>
                                    <th><?=$TXT_BAKERY['NAME']?></th>
                                    <th><?=$TXT_BAKERY['PRICE']?></th>
                                    <th><?=$TXT_BAKERY['QUANTITY']?></th>
                                    <th><?=$TXT_BAKERY['SHIPPING']?></th>
                                    <th><?=$TXT_BAKERY['SUM']?></th>
                                </tr>
                            </thead>
                        <?php   
                            foreach($aCart['items'] as $item): 
                                // check if item-file exists and create correct link
                                if(is_readable(WB_PATH . PAGES_DIRECTORY . $item['link']))
                                    $item['link'] = WB_URL . PAGES_DIRECTORY . $item['link'];
                                else 
                                    $item['link'] = '';
                                
                                // check if item-image exists and create correct source
                                if(is_readable(WB_PATH . MEDIA_DIRECTORY . $item['thumb_url']))
                                    $item['thumb_url'] = WB_URL . MEDIA_DIRECTORY . $item['thumb_url'];
                                else 
                                    $item['thumb_url'] = WB_URL.'/modules/bakery/images/nopic.jpg';
                        ?>
                            <tr>
                                <td><?=$item['sku']?></td>
                                <td><img src="<?=$item['thumb_url']?>" width="75"></td>                            
                                <td>
                                    <?php if($item['link'] != ''): ?>
                                    <a href="<?=$item['link']?>"><?=$item['name']?></a>
                                    <?php else: ?>
                                        <?=$item['name']?> <i class="fa fa-exclamation-circle" style="color:red;" title="item no longer available"></i>
                                    <?php endif; ?>
                                    <?php if($item['show_attribute'] != ''):?><br><?=$item['show_attribute']?><?php endif; ?>
                                </td>
                                <td><?=$item['f_price']?></td>
                                <td><?=$item['quantity']?></td>
                                <td><?=$item['f_shipping']?></td>
                                <td><?=$item['f_item_total']?></td>    
                            </tr>
                        <?php                                
                            endforeach;                                 
                        ?>
                            <tfoot>
                                <tr>
                                    <th colspan="7" align="right"><?=$sCurrency.' '.$TXT_BAKERY['TOTAL']?>: <big><?=$aCart['f_order_total']?></big></th>
                                </tr>
                            </tfoot>
                        </table>
                        <b><?=$TXT_BAKERY['CUST_MESSAGE']?>: </b><?=$sCustomerMessage?>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php 
            endforeach;
            else: ?>
                <tr class="msg_textXXXXXXXXXXXX">
                    <td colspan="3"><?=$TEXT['NONE_FOUND']?></td>
                </tr>
            <?php endif; ?>

            </tbody>
	</table>
</div>
	<table width="98%" align="center" cellpadding="0" cellspacing="0" class="mod_bakery_submit_row_b">
		<tr valign="top">
		  <td height="30" align="left"  style="padding-left: 12px;">
		  <!--<input name="save" type="submit" value="<?=$TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" /></td>-->
		  <td height="30" align="right"  style="padding-right: 12px;">
		  <input type="button" value="<?=$TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?=ADMIN_URL; ?>/pages/modify.php?page_id=<?=$page_id; ?>';" style="width: 100px; margin-top: 5px;" /></td>
		</tr>
	</table>
<script>
    $(function() {
        $(".msg_toggle").click(function(e){
            e.preventDefault();
            var openID = $(this).attr('rel');
            $("tr#request_id_"+openID + " div.req-content").slideToggle();
            console.log("click "+openID);
        });
    });
</script>
	<?php
        
// Print admin footer
$admin->print_footer();

/**
 * 61&&&&&localhost/wbce_bakery&&&&&&&&&&Christian Stefan&&&&&asd<br />Christian Stefan<br />Ostlandstrasse 4<br />28790 Schwanewede<br /><br />12456<br /><br /><br /><br /><br />12345678<br />&&&&&asd<br />Christian Stefan<br />Ostlandstrasse 4<br />28790 Schwanewede<br /><br /><br />12345678<br />illuandmoore@yahoo.de&&&&&asd<br />Christian Stefan<br />Ostlandstrasse 4<br />28790 Schwanewede<br /><br />12456<br /><br /><br /><br /><br />12345678<br />&&&&&illuandmoore@yahoo.de&&&&&
<table width="98%" border="0">
<tr>
	<th class="mod_bakery_invoice_th_sku_b">Art-Nr.</th>
	<th class="mod_bakery_invoice_th_name_b">Bezeichnung</th>
	<th class="mod_bakery_invoice_th_quantity_b">Menge</th>
	<th class="mod_bakery_invoice_th_price_b">Preis<br />
		<span class="mod_bakery_invoice_currency_b">CHF &nbsp;&nbsp;</span></th>
	<th class="mod_bakery_invoice_th_shipping_b" style="display: ">Versand<br />
		<span class="mod_bakery_invoice_currency_b">CHF &nbsp;&nbsp;</span></th>
	<th class="mod_bakery_invoice_th_tax_rate_b" style="display: none">Mwst<br />
		<span class="mod_bakery_invoice_tax_rate_b">% &nbsp;</span></th>
	<th class="mod_bakery_invoice_th_sum_b">Gesamt<br />
		<span class="mod_bakery_invoice_currency_b">CHF &nbsp;&nbsp;</span></th>
</tr>
<tr>
	<td colspan="6"><hr class="mod_bakery_hr_b" /></td>
</tr>
<tr>
	<td class="mod_bakery_invoice_td_sku_b">001</td>
	<td class="mod_bakery_invoice_td_name_b"><span class="mod_bakery_invoice_item_b">Pappmaché Figur</span><br /></td>
	<td class="mod_bakery_invoice_td_quantity_b">1</td>
	<td class="mod_bakery_invoice_td_price_b">45,00</td>
	<td class="mod_bakery_invoice_td_shipping_b" style="display: ">4,50</td>
	<td class="mod_bakery_invoice_td_tax_rate_b" style="display: none">0.0</td>
	<td class="mod_bakery_invoice_td_sum_b">45,00</td>
</tr>
<tr>
	<td class="mod_bakery_invoice_td_sku_b"></td>
	<td class="mod_bakery_invoice_td_name_b"><span class="mod_bakery_invoice_item_b">some type of article</span><br /></td>
	<td class="mod_bakery_invoice_td_quantity_b">1</td>
	<td class="mod_bakery_invoice_td_price_b">15,00</td>
	<td class="mod_bakery_invoice_td_shipping_b" style="display: ">0,00</td>
	<td class="mod_bakery_invoice_td_tax_rate_b" style="display: none">0.0</td>
	<td class="mod_bakery_invoice_td_sum_b">15,00</td>
</tr>
<tr>
	<td colspan="6"><hr class="mod_bakery_hr_b" /></td>
</tr>
<tr>
	<td colspan="5" class="mod_bakery_invoice_subtotal_b">Zwischensumme</td>
	<td style="text-align: right">CHF&nbsp;60,00</td>
</tr>
<tr>
	<td colspan="5" class="mod_bakery_invoice_shipping_b">Versandkosten</td>
	<td style="text-align: right">CHF&nbsp;4,50</td>
</tr>
<tr style="display: ">
	<td colspan="5" class="mod_bakery_invoice_tax_b">-  0.0% Mwst</td>
	<td style="text-align: right">CHF&nbsp;0,00</td>
</tr>
<tr>
	<td colspan="6"><hr class="mod_bakery_hr_b" /></td>
</tr>
<tr>
	<td colspan="5" class="mod_bakery_invoice_total_b">Gesamtsumme</td>
	<td style="text-align: right">CHF&nbsp;64,50</td></tr>
<tr>
	<td colspan="6"><hr class="mod_bakery_hr_b" /></td>
</tr>
</table>&&&&&22.12.2020, 02:26&&&&&illuandmoore@yahoo.de&&&&&	asd
	Christian Stefan
	Ostlandstrasse 4
	28790 Schwanewede

	12456

	
	

	12345678
&&&&&	asd
	Christian Stefan
	Ostlandstrasse 4
	28790 Schwanewede
	

	12345678
&&&&&	asd
	Christian Stefan
	Ostlandstrasse 4
	28790 Schwanewede

	12456

	
	

	12345678
&&&&&
	Art-Nr.: 001
	Bezeichnung: Pappmaché Figur
	Menge: 1
	Preis: CHF 45,00
	Versand: CHF 4,50
	Gesamt: CHF 45,00

	Art-Nr.: 
	Bezeichnung: some type of article
	Menge: 1
	Preis: CHF 15,00
	Versand: CHF 0,00
	Gesamt: CHF 15,00

	-------------------------------------
	Zwischensumme: CHF 60,00
	Versandkosten: CHF 4,50 
	-  0.0% Mwst: CHF 0,00
	-------------------------------------
	-------------------------------------
	Gesamtsumme: CHF 64,50
	-------------------------------------&&&&&&&&&&
 */