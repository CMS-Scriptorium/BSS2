<?php 
    // show content of cart as array (all data)
    // debug_dump($aCart);
    // debug_dump($cfg);
?>

<h2 class="mod_bakery_h_f"><?=$TXT_BAKERY['CART']?></h2>
<?php if ($sSuccessMsg != '') : ?>
    <div class="mod_bakery_success_f">
        <p><?=$sSuccessMsg?></p>
    </div>
<?php endif; ?>

<?php if ($sErrorMsg != ''): ?>
    <div class="mod_bakery_error_f">
        <?=$sErrorMsg?>
        <form>
            <p>
                <input type="submit" name="continue_shopping" class="mod_bakery_bt_continue_f" value="<?=$TXT_BAKERY['CONTINUE_SHOPPING']?>" />
            </p>
        </form>
        <br />
    </div>
<?php endif; ?>

<?php if(!empty($aCart['items'])): ?>
    <p class="mod_bakery_ordernum_f"><?=$TXT_BAKERY['ORDER_ID']?>: <?=$order_id?></p>
    <hr class="mod_bakery_hr_f" />

    <form action="<?=$cfg['continue_url']?>" method="post">
        <div class="grid no-gutters mod_bakery_row mod_bakery_cart_head">
            <div class="unit half">
                <div class="grid">
                    <div class="unit one-third hide-on-mobiles">
                        &nbsp;
                    </div>
                    <div class="unit two-thirds">
                        <?=$TXT_BAKERY['NAME']?>
                    </div>
                </div>
            </div>
            <div class="unit half">
                <div class="unit one-third">
                    <?=$TXT_BAKERY['QUANTITY']?>
                </div>
                <div class="unit two-thirds">
                    <div class="grid hide-on-mobiles">
                        <div class="unit one-third">
                            <?=$TXT_BAKERY['PRICE']?> <?=$sCurrency?>
                        </div>
                        <div class="unit one-third">
                            <?php if($bShowShipping):?><span><?=$TXT_BAKERY['SHIPPING']?> <?=$sCurrency?></span><?php endif; ?> &nbsp;
                        </div>
                        <div class="unit one-third align-right">
                            <?=$TXT_BAKERY['SUM']?> <?=$sCurrency?>
                        </div>
                    </div>	
                    <div class="grid only-on-mobiles">
                        <div class="unit whole">
                            <?=$TXT_BAKERY['PRICE']?> <?php if($bShowShipping):?><span>/<?=$TXT_BAKERY['SHIPPING']?></span> /<?php endif; ?> <?=$TXT_BAKERY['SUM']?> <?=$sCurrency?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <?php   
        // <!-- start:BODY -->
        foreach($aCart['items'] as $item):                           
    ?>
        <div class="grid no-gutters mod_bakery_row mod_bakery_cart_row">
            <div class="unit half">
                <div class="grid">
                    <div class="unit one-third hide-on-mobiles">
                        <a href="<?=$item['link']?>"><img src="<?=$item['thumb_url']?>" alt="<?=$item['name']?>" width="<?=$item['thumb_width']?>" height="<?=$item['thumb_height']?>" border="0" /></a>
                        <br/><?=$item['sku']?>
                    </div>
                    <div class="unit two-thirds">
                        <span class="mod_bakery_cart_item_f"><a href="<?=$item['link']?>"><?=$item['name']?></a></span>
                        <br /><?php if($item['show_attribute'] != ''):?><?=$item['show_attribute']?><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="unit half">
                <div class="unit one-third">
                    <input type="number" name="quantity[<?=$item['item_id']?>][<?=$item['attributes']?>]" value="<?=$item['quantity']?>" id="id_<?=$item['item_id']?>_<?=$item['attributes']?>" class="mod_bakery_item_input_f" size="4" />
                    <a href="#" onclick="javascript: mod_bakery_delete_item_f('<?=$item['item_id']?>_<?=$item['attributes']?>');"> <img src="<?=WB_URL?>/modules/bakery/images/delete.gif" alt="<?=$TEXT['DELETE']?>" title="<?=$TEXT['DELETE']?>" /></a>
                </div>
                <div class="unit two-thirds">
                    <div class="grid hide-on-mobiles">
                        <div class="unit one-third">
                            <?=$item['f_price']?>
                        </div>
                        <div class="unit one-third">
                            <?php if($bShowShipping):?><span><?=$item['f_shipping']?></span><?php endif; ?> &nbsp;
                        </div>
                        <div class="unit one-third align-right">
                            <?=$item['f_item_total']?>
                        </div>
                    </div>	
                    <div class="grid only-on-mobiles">
                        <div class="unit whole">
                            <?=$item['f_price']?> <?php if($bShowShipping):?><span>/<?=$item['f_shipping']?></span><?php endif; ?> /  <?=$item['f_item_total']?>
                        </div>
                    </div>			
                </div>
            </div>
        </div>
    <?php                                
        //<!-- end:BODY -->
        endforeach;                                
    ?>
        <div class="grid mod_bakery_sum">	
            <div class="unit whole align-right">
                <?=$TXT_BAKERY['SUM']?>: <?=$sCurrency?>&nbsp;<?=$aCart['f_order_total']?>
            </div>
        </div>

        <div class="grid mod_bakery_buttons">
            <div class="unit two-thirds">
                <input type="submit" name="continue_shopping" class="mod_bakery_bt_continue_f" value="<?=$TXT_BAKERY['CONTINUE_SHOPPING']?>" />
                <input type="submit" name="update_cart" id="update" class="mod_bakery_bt_update_f" value="<?=$TXT_BAKERY['UPDATE_CART']?>" />
            </div>
            <div class="unit one-third align-right">
                <input type="submit" name="submit_order" class="mod_bakery_bt_order_f" value="<?=$TXT_BAKERY['SUBMIT_ORDER']?>" />
            </div>
        </div>

        <input type="hidden" name="order_id" value="<?=$order_id?>" />
    </form>
	<?php else: ?>	
		<div class="mod_bakery_error_f">
		<?=$TXT_BAKERY['ERR_CART_EMPTY']?>
		<form action="<?=$cfg['continue_url']?>" method="post">
		  <input type="submit" name="continue_shopping" class="mod_bakery_bt_continue_f" value="<?=$TXT_BAKERY['CONTINUE_SHOPPING']?>" />
		</form>  
		</div>
<?php endif; ?>