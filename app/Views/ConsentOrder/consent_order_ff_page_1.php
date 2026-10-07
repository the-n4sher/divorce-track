<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>

<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>

<?php  
   // If the package code is either 'DPPCO','CBC','CBCFT','CBO and purchased after the 4th Jan 2026 
   $pension_share_remove_codes = [];
   $pension_cut_off = "2026-01-04 23:59:59";

   if(env("CI_ENVIRONMENT") == "development"){
    $pension_share_remove_codes = [4, 290, 126, 127];
   }else{
    $pension_share_remove_codes = [5, 353, 196, 197];
   }

   $codesToCheck = $codesToCheck ?? [];

?>

<style>
    .hide-class{
        display:none;
    }
</style>
 
<br /><br />
<div class="row">
    <div class="col-8 coff">

        <div class="panel panel-primary" id="child-warning">
            <div class="panel-heading"><h2></h2></div>
            <div class="panel-body">

                <?php $cutOff = "2024-11-21 00:00:00"; ?>

                <div id="stage-image">
                    <div>

                        <?php if($package_code == "CBO" || $package_code == "DPPCBO") : ?>

                            <?php if(isset($pid) && in_array($pid, $pension_share_remove_codes) && !empty($orderPlaced) && $orderPlaced > $pension_cut_off) : ?>                   
                                <div class="alert alert-danger">
                                    <p><b>PLEASE NOTE</b> - This service does not include Pension Sharing.<br><br>
                                    Should you wish to include a Pension Share - then you will need to call 01793 384029 to upgrade your service.</p>
                                </div>
                            <?php endif; ?>

                            <h1>Clean Break Order Fact Finder</h1><br>

                            <p style="text-align: left;">For us to draft your Clean Break Order and supporting documents, please complete the following questionnaire. Before we can send the Clean Break Order for drafting, both parties' financial details are required. You will need to provide as much detail as possible.</p>


                            <?php
                                if($orderPlaced > $cutOff) :
                            ?>

                            <?php endif;?>



                            <p style="text-align: left;">To enable the Solicitor to draft your order as instructed by both parties, you need to ensure that all the information you provide on the questionnaire is correct at the time of submission.</p> 

                            <p style="text-align: left;">Please ensure you have included all information requested. We must have the Respondent’s financial details and agreement to sign prior to sending to the Solicitor for drafting.</p>


                            <p style="text-align: left;">Please note: any fact finders not fully completed with both parties’ details will be returned. It is your responsibility to collect all the relevant information.</p>

                            <p style="text-align: left;">This is a remote drafting service, we require you to complete the fact finder as accurately as you can. If on the first draft there are errors or something has been missed, please do not hesitate to contact us at consentorder@divorce-online.co.uk.</p>

                            <p style="text-align: left;"> We will endeavour to have this corrected within <b>3-5 working days</b>. Once the order has been drafted, any information that you wish to add or change could leave you subject to further costs. Any redrafting work undertaken by the solicitor is subject to a timescale of approximately 8-10 working days.</p>

                            <p style="text-align: left;"><b>
                                Please refer to the explanatory notes in the shaded boxes prior to completing each section of the questionnaire. This could answer any queries you may have however, if not please email us at <a href="mailto:consentorder@divorce-online.co.uk">consentorder@divorce-online.co.uk</a>, or call on 01793 211211.
                            </b></p><br />

                        <?php else: ?>

                            <?php if(isset($pid) && in_array($pid, $pension_share_remove_codes) && !empty($orderPlaced) && $orderPlaced > $pension_cut_off) : ?>                   
                                <div class="alert alert-danger">
                                    <p><b>PLEASE NOTE</b> - This service does not include Pension Sharing.<br><br>
                                    Should you wish to include a Pension Share - then you will need to call 01793 384029 to upgrade your service.</p>
                                </div>
                            <?php endif; ?>


                            <?php $title = ( !empty($package_code) && in_array($package_code, $codesToCheck) ) ? "Basic Clean Break Questionnaire" : "Consent Order Fact Finder"; ?>
                            <h1><?= $title; ?></h1><br>

                            <?php if($package_code && in_array($package_code, $codesToCheck)) : ?>
                                <div class="alert alert-danger">
                                    <h2>Is a Simple Clean Break Order Right For Me?</h2>

                                    <P>This service is designed for couples who have no assets to divide. This includes couples who:</P> <br>
                                    <ul style="display: inline-block; text-align: left;">
                                        <li>Rented their home together and have no property to split.</li>
                                        <li>Had no significant savings, investments, or pensions to share.</li>
                                        <li>Have already gone their separate ways with nothing to document.</li>
                                        <li>Are financially independent and do not rely on each other's income.</li>
                                    </ul>
                                    <br /><br />

                                    <p>If you have property, pensions, savings, or a child/spousal maintenance agreement or other assets to divide, even if you have already done so informally, for example by selling a family home and splitting the proceeds, you will need a Consent Order to make that agreement legally binding and enforceable in court.</p>
                                    <br>

                                    <p>A Clean Break Order alone is not sufficient to protect either party where finances have been divided. View our Consent Order Service or call us on 01793 384029, and we can advise which service is right for you.</p>
                                    <br>

                                    <p>Click the following box to confirm you agree to these terms.</p>
                                    <br>

                                    <div>
                                        <input type="checkbox" name="agree_to_terms" id="agree_to_terms">
                                        <label for="agree_to_terms">I agree to the terms and conditions</label>
                                    </div>
                                </div>
                            <?php endif;?>


                            <?php if(!empty($package_code) && in_array($package_code, $codesToCheck)) : ?>
                                    <?php if($orderPlaced > $cutOff) :?>
                                    <p>
                                    Where you need detailed assistance in completing the financial questionnaire (D81) we can provide a 30 minute telephone call to have you completing this  - please email the office on <a href="mailto:support@divorce-online.co.uk" target="_blank">support@divorce-online.co.uk</a> to book this in
                                    </p>

                                    <?php endif;?>

                                    <p>To enable the Solicitor to draft your Clean Break Order as instructed by both parties, you need to ensure that all the information you provide on the questionnaire is correct at the time of submission. Please ensure you have included all information requested. 


                                    <br /><br />We must have the Respondent's financial details and agreement to sign prior to sending to the Solicitor for drafting.</p>

                                    <p><b>
                                        Without both parties information you will not be able to complete this online fact finder.
                                    </b></p><br>

                                    <!-- Youtube Video to be added here -->

                            <?php else: ?>

                                <p>For us to draft your Consent Order and supporting documents, please complete the following online questionnaire. Before we can send the Consent Order for drafting, both parties’ financial details are required.</p>

                                <?php if($orderPlaced > $cutOff) :?>
                                <p>
                                Where you need detailed assistance in completing the financial questionnaire (D81) we can provide a 30 minute telephone call to have you completing this  - please email the office on <a href="mailto:support@divorce-online.co.uk" target="_blank">support@divorce-online.co.uk</a> to book this in
                                </p>

                                <?php endif;?>

                                <p>To enable the Solicitor to draft your order as instructed by both parties, you need to ensure that all the information you provide on the questionnaire is correct at the time of submission. Please ensure you have included all information requested. 


                                <br /><br />We must have the Respondent's financial details and agreement to sign prior to sending to the Solicitor for drafting.</p>

                                <p><b>
                                    Without both parties information you will not be able to complete this online fact finder.
                                </b></p><br>
                            <?php endif;?>

                        <?php endif;?>

                        <p>
                          <button class="btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                           Click Here To View Our Frequently Asked Questions
                          </button>
                        </p>


                        <?php  if(validation_errors()) : ?>
                                <br>
                                <div class="alert alert-danger">
                                    <button class="close" data-dismiss="alert"></button>
                                    <?php foreach(validation_errors() as $e) : ?>
                                        <?=$e . "<br />"; ?>
                                    <?php endforeach;?>   
                                </div>
                        <?php endif;?>

                        <div class="collapse" id="collapseExample">
                            <br>    
                          <div class="card card-body">
                            <?php if (!empty($package_code) && !in_array($package_code, $codesToCheck)) : ?>
                                <div>
                                    <div style="text-align: center;"><b>Do I have to provide my financial information?</b></div><br>
                                        <p>YES. It is absolutely VITAL that both parties provide all the information asked for, as the Judge will not approve an Order whereby either party has not been truthful and honest. You will not be fully protected by the Order if you do not supply the required information.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>My spouse is refusing to provide the information required. What can I do?</b></div><br>
                                        <p>If one party is refusing to provide the required information, then unfortunately you are unable to apply for a Consent Order. You would need to seek legal advice from a solicitor.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>What if we have no agreement and no assets?</b></div><br>
                                        <p>Then you can still apply for a Consent Order so that you can protect any future assets you accrue. You must enter 0’s in the income and capital section, if applicable.</p>
                                </div>

                                 <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>How much is it to submit a Consent Order to the court?</b></div><br>
                                        <p>There is a court filing fee of £60 unless on a low income or in receipt of benefits however, this payment will not be needed straight away. Once the paperwork has been drawn up and signed by both parties, this is when the £60 is due</p>
                                </div>

                                  <div>
                                    <div style="text-align: center;"><b>What will happen once I have submitted the Consent Order questionnaire?</b></div><br>
                                        <p>Once the questionnaire has been received, it will be sent over the Solicitor so that they can start the drafting process. This will take <u><?=getenv("CONSENT_ORDER_DUE_DAYS_STANDARD");?> working days.</u> Please do not chase unless the drafting time exceeds the <?=getenv("CONSENT_ORDER_DUE_DAYS_STANDARD");?> working days. When the drafted documentation is returned from the Solicitor, it will be sent to you for checking and signing along with further guidance notes.</p>

                                        <p>Should you wish to fast track the agreement, this can be done for £125. The order will then be completed within 7 working days. If you would like to proceed with the fast track option, please contact our new enquiries team on <b>01793 384029</b></p>
                                </div>

                                 <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>The figures look very unequal on paper? What will the Judge think?</b></div><br>
                                        <p>The District Judge considering the matter has a duty to ensure that the proposed division of assets is fair to both parties, in keeping with s.25 Matrimonial Causes Act (1973). <br>
                                        The Order will be measured against the ‘yardstick of equality’. <br>
                                        The starting point for the division of financial assets on divorce is 50/50 as neither party’s contribution to the marriage is seen as more or less significant than the other. <br>
                                        This is not to say that marital assets must always be divided equally; only that the decision making behind a departure from an equal division of the assets requires a full and detailed explanation for the Judge to consider. <br>
                                        Generally, the longer the marriage, the closer the Court will expect the assets to be 50/50.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>Can the court tell us to change our arrangements?</b></div><br>
                                        <p>No. the Consent Order is an amicable agreement made by two consenting adults. The Judge may ask for further information, but they cannot change your decisions. The court will not get involved in the delegation of assets unless they are asked to; that is a different process and cannot be done via a Consent Order.</p>
                                </div>


                                <div>
                                    <div style="text-align: center;"><b>What if the Judge refuses the Consent Order?</b></div><br>
                                        <p>Whilst we do our best to ensure that the Consent Order is approved, if the Judge was to raise any queries with the order, then we will be able to offer guidance on the next steps.</p>
                                </div>

                                 <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>How long will the process take?</b></div><br>
                                        <p>The whole Consent Order process will take between 2-3 months, if the Consent Order is approved on first submission. If it not, the process can be extended by a further 6 weeks (variable based on court timescales).</p>
                                </div>
                            <?php else : ?>
                                <div>
                                    <div style="text-align: center;"><b>Do I have to provide my financial information?</b></div><br>
                                        <p>YES. It is absolutely VITAL that both parties provide all the information asked for, as the Judge will not approve an Order whereby either party has not been truthful and honest. You will not be fully protected by the Order if you do not supply the required information.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>My spouse is refusing to provide the information required. What can I do?</b></div><br>
                                        <p>If one party is refusing to provide the required information, then unfortunately you are unable to apply for a Clean Break Order. You would need to seek legal advice from a solicitor.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>What if we have no agreement and no assets?</b></div><br>
                                        <p>Then you can still apply for a Clean Break Order so that you can protect any future assets you accrue. You must enter 0's in the income and capital section, if applicable.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>How much is it to submit a Clean Break Order to the court?</b></div><br>
                                        <p>There is a court filing fee of £60 unless on a low income or in receipt of benefits however, this payment will not be needed straight away. Once the paperwork has been drawn up and signed by both parties, this is when the £60 is due</p>
                                </div>

                                <div>
                                    <div style="text-align: center;"><b>What will happen once I have submitted the Clean Break Order questionnaire?</b></div><br>
                                        <p>Once the questionnaire has been received, your paperwork will be immediately available to you</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>The figures on the D81 Statement of Information look very unequal on paper? What will the Judge think?</b></div><br>
                                        <p>The District Judge considering the matter has a duty to ensure that the proposed division of assets is fair to both parties, in keeping with s.25 Matrimonial Causes Act (1973). <br>
                                        The Order will be measured against the 'yardstick of equality'. <br>
                                        The starting point for the division of financial assets on divorce is 50/50 as neither party's contribution to the marriage is seen as more or less significant than the other. <br>
                                        This is not to say that marital assets must always be divided equally; only that the decision making behind a departure from an equal division of the assets requires a full and detailed explanation for the Judge to consider. <br>
                                        Generally, the longer the marriage, the closer the Court will expect the assets to be 50/50.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>Can the court tell us to change our arrangements?</b></div><br>
                                        <p>No. the Clean Break Order is an amicable agreement made by two consenting adults. The Judge may ask for further information, but they cannot change your decisions. The court will not get involved in the delegation of assets unless they are asked to; that is a different process and cannot be done via a Clean Break Order.</p>
                                </div>

                                <div>
                                    <div style="text-align: center;"><b>What if the Judge refuses the Clean Break Order?</b></div><br>
                                        <p>Whilst we do our best to ensure that the Clean Break Order is approved, if the Judge was to raise any queries with the order, then we will be able to offer guidance on the next steps.</p>
                                </div>

                                <div style="margin-top:30px;">
                                    <div style="text-align: center;"><b>How long will the process take?</b></div><br>
                                        <p>The whole Clean Break Order process will take between 2-3 months, if the Clean Break Order is approved on first submission. If it not, the process can be extended by a further 6 weeks (variable based on court timescales).</p>
                                </div>
                            <?php endif; ?>


                               
                          </div>
                        </div>


                        
                        
                        <div id="show_option_container">   
                        <br>
                        <hr>
                            <h3>Are you in a position to be able to complete the fact finder with the respondent?</h3>

                            <?php 

                                $yes_checked = ( $data && array_key_exists("show_option", $data) && $data["show_option"] == 1 ) ? "checked" : ""; 
                                $no_checked =  ( $data && array_key_exists("show_option", $data) && $data["show_option"] == 0 ) ? "checked" : "";
                            ?>

                            <input type="radio" name="show_option" id="show_option_yes" data-js-show-options value="1" <?=$yes_checked;?> > Yes | 
                            <input type="radio" name="show_option" id="show_option_no" data-js-show-options value="0" <?=$no_checked;?> > No


                                <?php $style = ($data && array_key_exists("show_option", $data) && $data["show_option"] == 0 ) ? "" : "display:none;"; ?>
                                <div id="send_email" style="<?=$style;?>">
                                    
                                    <br><br>
                                    <p><b>
                                        If you are not able to sit with the Respondent to complete the online fact finder together, or over the telephone then please enter the Respondents email address below and once you have completed your part of this form the Respondent will be sent an email to log in to complete their part. <br><br>

                                        We will not be able to draft your Order until the Respondent has completed their part in full.<br><br>

                                        The Respondent will be able to see your submitted information however they will not be able to edit it in any way.  
                                    </b></p> <br><br>


                                    <p><b>Note: If you do not have the Respondents email address and are unable to complete the Consent Order Fact Finder together - then please call the office on 01793 211211 as we will have to discuss other options with you.</b></p><br><br>

                                    <!-- This is used to pull the page down to the bottom-->
                                    <?php
                                        $ses_set = ( session()->has("SUCC") != "" ) ? true : false;
                                    ?>
                                    <input type="hidden" name="flash_data_set" id="flash_data_set" value="<?=$ses_set;?>">

                                    <?=form_open();?>

                                    <?php 
                                            if(session()->has("SUCC") != ""){
                                                    echo ' <div class="alert alert-success">';
                                                            echo session("SUCC");
                                                    echo '</div>'; 
                                            }  
                                         ?>


                                        <input type="email" style="border:1px solid black;" class="form-control" name="respondent_separate_email" id="respondent_separate_email" value="<?=old('respondent_separate_email');?>" placeholder="Enter respondent email">
                                
                                    <p>This is a remote drafting service; we require you to complete the fact finder as accurately as you can. If, on the first draft there are errors or something has been missed, please do not hesitate to contact us at <a href="mailto:consentorder@divorce-online.co.uk">consentorder@divorce-online.co.uk</a> and we will endeavour to have this corrected within 5-7 working days. </p> <br><br>

                                    <p><b>Once the order has been drafted, any information that you wish to add, or change could leave you subject to further costs. Any redrafting work undertaken by the solicitor is subject to a timescale of approximately 8-10 working days.</b></p>


                                    <div class="coff-nav">
                                        <input type="submit" id="no-ch-maint" name="start_ff" class="next-btn btn btn-primary" value="Begin Online Fact Finder">
                                    </div>
                                    
                                    <?=form_close();?>
                            
                                </div>
                        </div>


                        <div id="normal_process" style="display:none;">

                                <br><br>
                                <?php if($package_code && !in_array($package_code, $codesToCheck)) : ?>
                                    <p>This is a remote drafting service; we require you to complete the fact finder as accurately as you can. If, on the first draft there are errors or something has been missed, please do not hesitate to contact us at <a href="mailto:support@divorce-online.co.uk">support@divorce-online.co.uk</a> and we will endeavour to have this corrected within 5-7 working days. </p> <br><br>
                                    <p><b>Once the order has been drafted, any information that you wish to add, or change could leave you subject to further costs. Any redrafting work undertaken by the solicitor is subject to a timescale of approximately 8-10 working days.</b></p>
                                <?php else : ?>
                                    <p>Please do not hesitate to contact us at <a href="mailto:support@divorce-online.co.uk">support@divorce-online.co.uk</a> if you have any questions prior to submitting the Order to the Court</p>
                                <?php endif; ?>
                                
                                <?php $hideClass= ($package_code && in_array($package_code, $codesToCheck)) ? "hide-class" : ""; ?>

                                <div class="coff-nav <?=$hideClass;?>">
                                    <?=form_open();?>
                                        <input type="submit" name="has_contact_submit" class="next-btn btn btn-primary" value="Begin Online Fact Finder">
                                    <?=form_close();?>
                                </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() 
    {


        $('#page1-cont').hide();

         $(document).on('change','[name=show_option]',function()
         {

            var currentVal = $(this).val(),
                noContact = $('#send_email'),
                hasContact = $('#normal_process');

            if(currentVal == 1){
                noContact.hide();
                hasContact.show();
            }else{
                noContact.show();
                hasContact.hide();
            }
            
         });


        $(document).on('click','#no-ch-maint',function()
        {
            $('#child-warning').hide();
            $('#page1-cont').show();
        });


        // Get the flash data hidden field value
        var is_flash_data = $('#flash_data_set').val()
        if(is_flash_data == 1)
        {
            // We have flash data, pull the user down scroll
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        }




        $(document).on('click','#save_id',function(e)
        {
            e.preventDefault();
            
            // update a hidden field
            $('#save_only').val("1");


            $('#no-ch-maint').trigger("click");
        })



    });


    
</script>

<?php if(!empty($package_code) && in_array($package_code, $codesToCheck)) : ?>

    <script>
       $(document).ready(function () {

            // onclick of the agree to terms checkbox, show the normal process
            // Remove the hide-class from the .coff-nav div , read it if the checkbox is unchecked
            $('#agree_to_terms').on('click', function() {
                if($(this).is(':checked')) {
                    $('.coff-nav').removeClass('hide-class');
                } else {
                    $('.coff-nav').addClass('hide-class');
                }
            });

            $('#show_option_container').hide();
            $('#normal_process').show();
        });
    </script>
<?php endif;?>


<?php $this->endSection() ;?>