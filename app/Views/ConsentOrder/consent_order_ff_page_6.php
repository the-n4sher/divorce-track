<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>

<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>
    
    <?php $hide_respondent = ( $page_data[0]['has_contact'] == 0 ) ? TRUE : FALSE; ?>

<style>
    body{
        background-image: none!important;
    }
    input.pricebtn{margin-bottom:3px;}
</style>


<br /><br />
<div class="row">
    <div class="col-8 coff">
        <div class="panel panel-primary">
            <div class="panel-heading"><h2></h2></div>
            <div class="panel-body">

                <?php

                     if (session()->has("errors") ){

                        echo ' <div class="alert alert-danger">
                                    <button class="close" data-dismiss="alert"></button>';
                                    foreach(session("errors") as $e){
                                        echo $e . "<br />";
                                    }
                        echo '</div>';                          
                    }

                    ?>

                <div id="stage-image">

                    <div class="ff_info_box">
                        <p>
                            <?php $wording = ( in_array($package_code, ["CBO", "CBC", "DPPCBO"]) ) ? "Clean Break Order" : "Consent Order application" ; ?>

                            When the judge considers a <?=$wording;?>, it is not just approved on the basis that the parties are in agreement. It is the obligation of the Judge to ensure that both parties are entirely happy with the proposed <?=$wording;?> before it is sealed and legally binding.<br /><br />

                            If the Judge isn’t happy to approve the <?=$wording;?> at first glance, they will often query the order. Common queries include: ‘What is the basis of the order?’ ‘How is the Order fair to the parties?’ ‘What is the rationale?’ Any amendments that need to be made to the Order on the basis of these questions will incur a fee of up to £125. This will also delay the process considerably (at least 6 weeks) and therefore it is mandatory that you detail how you came to the arrangements you have in the boxes below. <br /><br />

                            This information will then be incorporated within the <?=$wording;?> documentation in order that the Judge can have a clear picture of how you came to your agreement. 

                            <?php if($this->data['res_logged_in'] == false) : ?>
                                <br><br> Please click <a href="<?=site_url();?>documents/cc_example.pdf" target="_blank">here</a> to view an example.
                            <?php else : ?>
                                <br /><br /><b style="color:red;">THE APPLICANT HAS ALREADY COMPLETED THIS SECTION  - IF YOU ARE UNHAPPY WITH THIS CONTENT, YOU WILL NEED TO CONTACT THE APPLICANT DIRECTLY </b> 
                            <?php endif;?>



                        </p>
                    </div>

                    <?=form_open();?>

                    <div class="ff_info_box">
                        <h1>ALREADY ACTIONED</h1>
                        <p>Please confirm any arrangements already carried out, for example if the house has already been transferred and the date the action took place.</p>
                    </div>

                    <?php
                        $disable_field = "";
                        if($hide_respondent == TRUE && $res_logged_in === true) {
                            $disable_field = "readonly";
                        }
                        
                    ?>

                     <?php
                        $readOnly = ( $package_code == "CBO" || $package_code == "DPPCBO") ? "readonly" : "";
                        $defaultCBCWording = ( $readOnly == "readonly" ) ? "If you need to enter any information in this box, please call 01793 384029 to discuss upgrading your service" : "Leave blank or enter a minimum of 35 characters";
                        $defaultPlaceHolder = ( $readOnly == "readonly" ) ? "Please enter a minimum of 20 characters" : "Leave blank or enter a minimum of 20 characters";
                    ?>

                    <textarea <?=$disable_field;?> name="already_actioned" id="already_actioned" class="form-control" style="width:100%;height:400px;" max="500" placeholder="<?=$defaultPlaceHolder;?>"><?php if(isset($page_data[0]['already_actioned'])) {echo $page_data[0]['already_actioned'];}else{echo set_value('already_actioned');}?></textarea> <br>
                    
                
                    <div class="ff_info_box">
                        <h1>CURRENTLY ACTIONING</h1>
                        <p>Please confirm any arrangements that are currently being carried out and the date you expect this to carried out by, for example the house is being transferred at the moment</p>
                    </div>

                    <textarea <?=$readOnly;?> <?=$disable_field;?> name="currently_actioned" id="currently_actioned" class="form-control" style="width:100%;height:400px;" max="500" placeholder="<?=$defaultCBCWording;?>"><?php if(isset($page_data[0]['currently_actioned'])) {echo $page_data[0]['currently_actioned'];}else{echo set_value('currently_actioned');}?></textarea> <br>
  
                    <div class="ff_info_box">
                        <h1>TO BE ACTIONED</h1>
                        <p>Please confirm any arrangements that will be carried out upon the court granting the Consent Order, for example the house shall be transferred within 28 days of the Consent Order.<br />
                        There are additional examples for each option to add for reference - <span id="seeExample" style="cursor: pointer;"><b>Click To See examples</b></span></p>

                        <br>
                        <div class="examples" style="display:none;background-color: #D3D3D3;padding:20px; color:#000;">
                            <h3>The parties have agreed and are currently due to carry out the following:</h3>
                            <p>e.g. the Applicant will pay the Respondent a lump sum of £5,000 within 28 days of the date of the Consent Order, or 7 days of the Decree Absolute, whichever is the late.</p>
                            <p>e.g. the Respondent shall pay monthly spousal maintenance payments to the Applicant. Payments shall start upon the 1st day of the month following the date of the Consent Order. Payments shall be at a rate of £300 per month and shall end upon the Applicant’s cohabitation or re-marriage.</p>
                            <p>e.g. the Applicant will share 50% of their pension with the Respondent. The pension share shall be implemented following the date of the Consent Order.</p>
                        </div>

                    </div>

                    <textarea <?=$readOnly;?> <?=$disable_field;?> name="tobe_actioned" id="tobe_actioned" class="form-control" style="width:100%;height:400px;" max="500" placeholder="<?=$defaultCBCWording;?>"><?php if(isset($page_data[0]['tobe_actioned'])) {echo $page_data[0]['tobe_actioned'];}else{echo set_value('tobe_actioned');}?></textarea>

                    <div class="coff-nav">
                        <input type="submit" name="page6_submit" value="Next" class="next-btn btn btn-primary">

                        <?php   
                            // We need to switch the ID of the previous back if pension share is removed otherwise they have no way to get back
                            $pension_cut_off = "2026-01-04 23:59:59";
                            $pension_share_remove_codes = [];
                            $back_id = 5;

                            if(env("CI_ENVIRONMENT") == "development"){
                                $pension_share_remove_codes = [4, 290, 126, 127];
                            }else{
                                $pension_share_remove_codes = [5, 353, 196, 197];
                            }

                            $back_id = (in_array($pid, $pension_share_remove_codes) && !empty($orderPlaced) && $orderPlaced > $pension_cut_off) ? 4 : 5;
                        ?>


                        <a class="prev-btn btn" href="<?=base_url();?>consent-order/consent_order_ff/<?=$back_id;?>">Previous</a>
                    </div>


                    <?=form_close();?>
                    

                </div>

            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function()
    {

        $(document).on('click','#seeExample', function(){
            $('.examples').toggle();
        });

    });

    function toogle_div()
    {
        $('#applant-details').toggle();
    }

    function check_agreement(){
        $()
    }
</script>
    
<?php $this->endSection() ;?>