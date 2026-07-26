<?php 
// Load Frontend CSS from MiniForm Module
I::insertCssFile(WB_URL.'/modules/miniform/frontend.css');

if(!isset($data)){
    $data = $aData; // from  `bakery/checkout_form.php`
}
$aFields = bxt_formConfig();

// define variables for BE use
if (!isset($aErrors)) {
	$aErrors = array (
	"cust_company"=>"",
	"cust_first_name"=>"",
	"cust_last_name"=>"",
	"cust_street"=>"",
	"cust_street_number"=>"",
	"cust_address_addition"=>"",
	"cust_zip"=>"",
	"cust_city"=>"",
	"cust_phone"=>"",
	"cust_mobile"=>"",
	"cust_email"=>"",
	"cust_confirm_email"=>"",
	"cust_tax_no"=>"",
	"cust_message"=>"",
	"ship_company"=>"",
	"ship_first_name"=>"",
	"ship_last_name"=>"",
	"ship_street"=>"",
	"ship_street_number"=>"",
	"ship_address_addition"=>"",
	"ship_zip"=>"",
	"ship_city"=>"",
	"ship_phone"=>"",
	"ship_mobile"=>""	
	);
}

if (!isset($setting_shipping_form)) {
	$setting_shipping_form = "foo";
}

?>
<div class="miniform grid">
    <?php if(defined('WB_FRONTEND')): ?>
        <h2 class="mod_bakery_h_f">
            <?php if($cfg['use_payment']): ?>
                <img src="<?=get_url_from_path(__DIR__)?>/../images/checkout_steps/step_1.png" alt=""/>
                <?=$TXT_BAKERY['SUBMIT_ORDER'].': '.$TXT_BAKERY['ADDRESS']?>
            <?php else: ?> 
                <?=$TXT_BAKERY['SEND_REQUEST']?>
            <?php endif; ?>
        </h2>
        <p class="mod_bakery_form_p_f"><?=$TXT_BAKERY['FILL_IN_ADDRESS']?>:</p>
    <?php endif; ?>
    <?php if(isset($form_error)): ?>
        <div class="mod_bakery_error_f"><p><?=$form_error?></p></div>
    <?php endif; ?>
    <p style="text-align:right;"><?=$TXT_BAKERY['REQUIREDFIELDS']?></p>

    <form action="<?=$sFormActionURL?>" method="post"> 
        <?php if(defined('WB_FRONTEND') == false): 
            // backend use only
        ?>
            <input type="hidden" name="page_id" value="<?=$page_id?>" />
            <input type="hidden" name="section_id" value="<?=$section_id?>" />
            <input type="hidden" name="order_id" value="<?=$data['order_id']?>" />
        <?php endif; ?>
        <input type="hidden" name="cust_country" value="<?=$select_shop_country?>">
        <?php if (bxt_showField('cust_company')):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['CUST_COMPANY']?></span>
                <input class="<?=$aErrors['cust_company']?>" type="text" name="cust_company" value="<?=$data['cust_company']?>"  />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_first_name')):?>	
        <div class="onethird">
            <label><span><?=$TXT_BAKERY['CUST_FIRST_NAME']?></span>
                <input class="<?=$aErrors['cust_first_name']?>" type="text" name="cust_first_name" value="<?=$data['cust_first_name']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_last_name')):?>
        <div class="twothird pullright">
            <label><span><?=$TXT_BAKERY['CUST_LAST_NAME']?></span>
                <input class="<?=$aErrors['cust_last_name']?>" type="text" name="cust_last_name" value="<?=$data['cust_last_name']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_street')):?>
            <?php if (bxt_option('split_street_number')):?>

                <div class="twothird">
                    <label><span><?=$TXT_BAKERY['CUST_STREET']?></span>
                        <input class="<?=$aErrors['cust_street']?>" type="text" name="cust_street" value="<?=$data['cust_street']?>" />
                    </label>
                </div>
                <div class="onethird pullright">
                    <label><span><?=$TXT_BAKERY['CUST_STREET_NUMBER']?></span>
                        <input  class="<?=$aErrors['cust_street_number']?>" type="text" name="cust_street_number" value="<?=$data['cust_street_number']?>" />
                    </label>
                </div>
            <?php else: ?>
                <div class="full">
                    <label><span><?=$TXT_BAKERY['CUST_ADDRESS']?></span>
                        <input class="<?=$aErrors['cust_street']?>" type="text" name="cust_street" value="<?=$data['cust_street']?>" />
                    </label>
                </div>
            <?php endif;?>
        <?php endif;?>
        <?php if (bxt_showField('cust_address_addition')):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['CUST_ADDRESS_ADDITION']?></span>
                <input type="text" class="<?=$aErrors['cust_address_addition']?>" name="cust_address_addition" value="<?=$data['cust_address_addition']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_zip')):?>
        <div class="onethird">
            <label><span><?=$TXT_BAKERY['CUST_ZIP']?></span>
                <input class="<?=$aErrors['cust_zip']?>" type="text" name="cust_zip" value="<?=$data['cust_zip']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_city')):?>
        <div class="twothird pullright ">
            <label><span><?=$TXT_BAKERY['CUST_CITY']?></span>
                <input class="<?=$aErrors['cust_city']?>" type="text" name="cust_city" value="<?=$data['cust_city']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_state')):?>
        <?php if($cfg['state_field'] == 'show'):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['CUST_STATE']?></span>
                <select name="cust_state" onchange="javascript: mod_bakery_synchro_cust_state_f()">
                    <?=$cust_state_options?>
                </select>
            </label>
        </div>
        <?php endif; ?>
        <?php endif;?>
        <?php 
        if (bxt_showField('cust_country')):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['CUST_COUNTRY']?></span>
                <select name="cust_country" onchange="javascript: mod_bakery_toggle_state_f('<?=$select_shop_country?>', 'cust', 1);">
                    <?=$cust_country_options?>
                </select>
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_phone')):?>
        <div class="<?=(bxt_showField('cust_mobile')==false) ? 'full':'half'?>">
            <label><span><?=$TXT_BAKERY['CUST_PHONE']?></span>
                <input type="tel" class="<?=$aErrors['cust_phone']?>" name="cust_phone" value="<?=$data['cust_phone']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_mobile')):?>
        <div class="half pullright">
            <label><span><?=$TXT_BAKERY['CUST_MOBILE']?></span>
            <input type="tel" class="<?=$aErrors['cust_mobile']?>" name="cust_mobile" value="<?=$data['cust_mobile']?>" />
            </label>
        </div>
        <?php endif;?>
        <?php if (bxt_showField('cust_email')):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['EMAIL']?> </span>
                    <input required="required" class="<?=$aErrors['cust_email']?>" type="email" name="cust_email" value="<?=$data['cust_email']?>" />
            </label>
        </div>	
        <?php endif;?>
        <?php if (bxt_option('use_repeat_email')):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['CUST_CONFIRM_EMAIL']?> <span>*</span></span>
                <input required="required" class="<?=$aErrors['cust_confirm_email']?>" type="email" name="cust_confirm_email" value="<?=$data['cust_confirm_email']?>" />
            </label>
        </div>
        <?php endif;?>
        
        <?php if (bxt_showField('cust_tax_no')):?>
        <div class="full">
            <label><span><?=$TXT_BAKERY['CUST_TAX_NO']?></span>
                <input type="text" class="<?=$aErrors['cust_tax_no']?>" name="cust_tax_no" value="<?=$data['cust_tax_no']?>" />
            </label>
        </div>
        <?php endif;?>
        
        <?php if ($cfg['use_payment'] == 0 && bxt_showField('cust_message')):?>
        <div class="full">
            <label style="width:100%"><span><?=$TXT_BAKERY['CUSTOMER_MESSAGE']?></span>
                <textarea class="<?=$aErrors['cust_message']?>" cols="80" rows="10" name="cust_message"><?=$data['cust_message']?></textarea>
            </label>
        </div>        
        <?php endif;?>
        <?php if($setting_shipping_form == 'hideable' || $setting_shipping_form == 'request'): ?>
        <label for="toggle_shipform">
            <input name="use_shipform" id="toggle_shipform" type="checkbox" value="1" class="toggle_shipform">
            <?=$TXT_BAKERY['ADD_SHIP_FORM']?>
        </label>
        <?php endif;?>
        <?php if($show_ship_form): ?>
        <div id="shipform">
            <p class="mod_bakery_form_p_f"><?=$TXT_BAKERY['FILL_IN_SHIP_ADDRESS']?></p>
            
            <?php
            /**
             * < shipping form >
             */
            ?>
            
            <?php if (bxt_showField('ship_company')):?>
            <div class="full">
                <label><span><?=$TXT_BAKERY['CUST_COMPANY']?></span>
                    <input class="<?=$aErrors['ship_company']?>" type="text" name="ship_company" value="<?=$data['ship_company']?>"  />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_first_name')):?>	
            <div class="onethird">
                <label><span><?=$TXT_BAKERY['CUST_FIRST_NAME']?></span>
                    <input class="<?=$aErrors['ship_first_name']?>" type="text" name="ship_first_name" value="<?=$data['ship_first_name']?>" />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_last_name')):?>
            <div class="twothird pullright">
                <label><span><?=$TXT_BAKERY['CUST_LAST_NAME']?></span>
                    <input class="<?=$aErrors['ship_last_name']?>" type="text" name="ship_last_name" value="<?=$data['ship_last_name']?>" />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_street')):?>
                <?php if (bxt_option('split_street_number')):?>

                    <div class="twothird">
                        <label><span><?=$TXT_BAKERY['CUST_STREET']?></span>
                            <input class="<?=$aErrors['ship_street']?>" type="text" name="ship_street" value="<?=$data['ship_street']?>" />
                        </label>
                    </div>
                    <div class="onethird pullright">
                        <label><span><?=$TXT_BAKERY['CUST_STREET_NUMBER']?></span>
                            <input  class="<?=$aErrors['ship_street_number']?>" type="text" name="ship_street_number" value="<?=$data['ship_street_number']?>" />
                        </label>
                    </div>
                <?php else: ?>
                    <div class="full">
                        <label><span><?=$TXT_BAKERY['CUST_ADDRESS']?></span>
                            <input class="<?=$aErrors['ship_street_number']?>" type="text" name="ship_street" value="<?=$data['ship_street']?>" />
                        </label>
                    </div>
                <?php endif;?>
            <?php endif;?>
            <?php if (bxt_showField('ship_address_addition')):?>
            <div class="full">
                <label><span><?=$TXT_BAKERY['CUST_ADDRESS_ADDITION']?></span>
                    <input type="text" class="<?=$aErrors['ship_address_addition']?>" name="ship_address_addition" value="<?=$data['ship_address_addition']?>" />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_zip')):?>
            <div class="onethird">
                <label><span><?=$TXT_BAKERY['CUST_ZIP']?></span>
                    <input class="<?=$aErrors['ship_zip']?>" type="text" name="ship_zip" value="<?=$data['ship_zip']?>" />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_city')):?>
            <div class="twothird pullright ">
                <label><span><?=$TXT_BAKERY['CUST_CITY']?></span>
                    <input class="<?=$aErrors['ship_city']?>" type="text" name="ship_city" value="<?=$data['ship_city']?>" />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_state')):?>
            <?php if($cfg['state_field'] == 'show'):?>
            <div class="full">
                <label><span><?=$TXT_BAKERY['CUST_STATE']?></span>
                    <select name="ship_state" onchange="javascript: mod_bakery_synchro_ship_state_f()">
                        <?=$ship_state_options?>
                    </select>
                </label>
            </div>
            <?php endif; ?>
            <?php endif;?>
            <?php if (bxt_showField('ship_country')):?>
            <div class="full">
                <label><span><?=$TXT_BAKERY['CUST_COUNTRY']?></span>
                    <input type="hidden" name="ship_country" value="<?=$select_shop_country?>">
                    <select name="ship_country" onchange="javascript: mod_bakery_toggle_state_f('<?=$select_shop_country?>', 'cust', 1);">
                        <?=$ship_country_options?>
                    </select>
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_phone')):?>
            <div class="<?=(bxt_showField('ship_mobile')==false) ? 'full':'half'?>">
                <label><span><?=$TXT_BAKERY['CUST_PHONE']?></span>
                    <input type="tel" class="<?=$aErrors['ship_phone']?>" name="ship_phone" value="<?=$data['ship_phone']?>" />
                </label>
            </div>
            <?php endif;?>
            <?php if (bxt_showField('ship_mobile')):?>
            <div class="half pullright">
                <label><span><?=$TXT_BAKERY['CUST_MOBILE']?></span>
                <input type="tel" class="<?=$aErrors['ship_mobile']?>" name="ship_mobile" value="<?=$data['ship_mobile']?>" />
                </label>
            </div>
            <?php endif;?>          
            
        <?php endif; ?>
        </div>     
        
        <div class="full">
            <?php if($cfg['use_payment'] == 0): ?>
              <p class="dsgvo"><input type="checkbox" name="dsgvo" id="dsgvo" required> <label for="dsgvo"><?=sprintf($TXT_BAKERY['DSGVO_TEXT'], $cfg['privacy_url'])?><span class="mod_bakery_required_star">*</span></label></p> 
            <p class="dsgvo"><input type="checkbox" name="send_copy" id="send_copy"> <label for="send_copy"><?=$TXT_BAKERY['SEND_COPY_TO_CLIENT']?></label></p> 
           
            <?php endif; ?>
            <?php if(isset($sFormCancelURL)):?>
            <div class="half">
                <input type="submit" name="save_form" class="mod_bakery_bt_order_f" value="<?=$sendButtonTxt?>" style="width: 100px; margin-top: 5px;background-color:#4db34e" />	
                <input type="submit" name="save" value="<?=$TEXT['SAVE'].' &amp; '.$TEXT['BACK']?>">
            </div>
            <div class="unit half align-right">
                <input type="button" value="<?=$TEXT['CANCEL']?>" onclick="javascript: window.location = '<?=$sFormCancelURL?>';">
            </div>
            <?php else: ?>
            <div class="unit full align-right">
                <input type="submit" name="save_form" class="mod_bakery_bt_order_f" value="<?=$sendButtonTxt?>" />	
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>   
    var sShopCountry = '<?=$select_shop_country?>';
    var oRequiredCust = <?=json_encode(bxt_attrReq('', 'cust'));?>;
    var bUseShipping = false;
    <?php if(in_array($cfg['shipping_form'], ['hideable', 'request', 'always'])): ?>
    var bUseShipping = true;
    var oRequiredShip = <?=json_encode(bxt_attrReq('', 'ship'));?>;
    var bToogleShipformChecked = <?=json_encode($toggle_shipform_checked)?>;
    <?php endif; ?>  
    
    $( document ).ready(function() {  
        <?php if(in_array($cfg['shipping_form'], ['always'])): ?>             
        var oFields = $.merge( oRequiredCust, oRequiredShip );
        <?php else: ?>                
        var oFields = oRequiredCust;
        <?php endif; ?>  
        $.each(oFields, function(key, val) {
            $('[name='+val+']').prop('required',true); 
            $('[name='+val+']').closest('label').find('span').append(
                $("<span>", {"class": "required"}).html("&nbsp;*")
            );
        });     

        if(bUseShipping){
            
            $('#toggle_shipform').prop("checked", bToogleShipformChecked);
            $('.toggle_shipform').on('change', function () {                
                $('#shipform').toggle(this.checked); 
                var setReq = this.checked ? true : false;
                $.each(oRequiredShip, function(key, val) {   
                    $('[name='+val+']').prop('required', setReq);
                    if(setReq){
                        $('[name='+val+']').parent().find('span').append(
                            $("<span>", {"class": "required", "id" : 'req' + val}).html('&nbsp;*')
                        );
                    } else {
                        $("#req" + val).remove();
                    }
                });      
            }).change(); 

            mod_bakery_toggle_state_f(sShopCountry, 'ship', 0);
        }    
    });
    mod_bakery_toggle_state_f(sShopCountry, 'cust', 0);
</script>