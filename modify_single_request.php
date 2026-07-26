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
// archive record?
if(!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])){
    die();  
}

$iOrderID = intval($_GET['order_id']);
//$sOutput = cartContentRaw($_GET['order_id']);

$view = "single";
?>

<h2><?=$TXT_BAKERY['REQUESTS']; ?>-ID <?=$iOrderID?></h2>

<div class="content-box">      
    <table id="mf_msgs_<?=$section_id ?>" class="mf_messages" cellpadding="2" cellspacing="0" border="0" width="100%">
            <?php 
            $rec = $database->get_array("SELECT * FROM `{BXT}_requests` WHERE `order_id` = ".$iOrderID)[0];
            $sDate = date(DATE_FORMAT.' - '.TIME_FORMAT,$rec['timestamp']+TIMEZONE);
            ?>
            <tr class="setline">
                <td><a href="<?=WB_URL?>/modules/bakery/modify_single_request.php?page_id=<?=$page_id.'&amp;section_id='.$section_id.'&amp;order_id='.$rec['order_id'];?>"></a>
                        <a class="msg_toggle" rel="<?=$rec['request_id']?>" title="<?=$TXT_BAKERY['REQUESTS']?>-ID: <?=$rec['request_id']?>" href="#request_id_<?=$rec['request_id']?>">
                            <span class="icon">&#9993;</span>
                            <small>ID: <?=$rec['request_id']?></small>
                            <b><?=$rec['first_name']?> <?=$rec['last_name']?></b>, <i><?=$rec['email']?></i>
                        </a> 
                    </td>
                    <td align="right">
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
            <tr id="request_id_<?=$rec['request_id']?>" <?php /* style="display:block;"*/?>>
                <td colspan="3">
                    <div class="req-content">
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
                                $sCustomerMessage = $val;
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
                        <b><?=$TXT_BAKERY['CUST_MESSAGE']?>: </b>
                                <?=$sCustomerMessage == '' ? $TEXT['NONE'] : $sCustomerMessage?>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>

                </td>
            </tr>
            </tbody>
	</table>
    <br>
                    <a class="button" href="<?=WB_URL.'/modules/bakery/modify_requests.php?page_id='.$page_id.'&section_id='.$section_id?>">&laquo; <?=$TXT_BAKERY['REQUESTS']; ?></a>
    
    
    
<script>
    /*
    $(function() {
        $(".msg_toggle").click(function(e){
            e.preventDefault();
            var openID = $(this).attr('rel');
            $("tr#request_id_"+openID + " div.req-content").slideToggle();
            console.log("click "+openID);
        });
    });
    */
</script>
    
</div>
<?php        
// Print admin footer
$admin->print_footer();

