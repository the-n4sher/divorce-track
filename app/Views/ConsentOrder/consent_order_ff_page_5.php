<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>

<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>


    <style>
    /*body{
        background-image: none!important;
    }*/
    input.pricebtn{margin-bottom:3px;}


    /* KAMAL HERE*/
    .modal-backdrop {
      z-index: -1;
    }

    .hide-section{
        display: none;
    }

    html {
      scroll-behavior: smooth;
    }

    #save_pension_update{
        position: fixed;
        right: 0px;
        margin-right: 5px;
        bottom: 20px;
        right: 30px;
        padding: 15px 40px;
        display: none;
    }

    .modal-open #the-main-content {
        z-index: 20;
    }

    .modal-dialog.modal-lg.modal-dialog-centered {
        min-width: 75%;
    }

    .modal.fade.in {
        background-color: rgba(0,0,0,0.8);
        padding-top: 50px;
    }

    .modal-header .close {
        opacity: 1;
    }

    .modal-header .close span {
        color: #000;
        font-size: 60px;
    }

    .ff_info_box {
        background-color: #1abc9c;
        color: #fff;
    }

    .form-group {
        position: relative;
    }

    .modal {
        margin-left: 16.6667%;
    }

    @media only screen and (min-width: 1100px) {
        .pension-alert-note {
            position: absolute; right: 0; width: 45%; top: 0px;
        }
    }

    @media only screen and (max-width: 768px) {
        .modal {
            margin-left: 0%;
        }
    }

</style>

<!--  if the application is split we want to hide the checkboxes from the form -->
<?php //if($is_split_app == 1) : ?>
<!--    <style>
        .no_info{display: none;}
    </style> -->
<?php //endif;?>


<br /><br />
<div class="row">
    <div class="col-8 coff">
        <div class="panel panel-primary">
            <div class="panel-heading"><h2></h2></div>
            <div class="panel-body">


                <div id="stage-image">

                    <div class="ff_info_box">
                        <p>
                            <h3>PENSION SHARING</h3>
                            <b>IMPORTANT INFORMATION IF YOU AND YOUR SPOUSE ARE PENSION SHARING (PLEASE READ THROUGH BEFORE COMPLETING YOUR FINANCIAL DETAILS)</b>
                        </p>

                        <p>If you and your spouse have agreed that you will have a Pension Sharing Order as part of your settlement, please ensure that you complete this section in full. This information is required not only by the Court, but by the Pension provider responsible for the implementation of the transfer.<br /><br />

                        The name of the Pension Arrangement is not necessarily the same as the Pension Company for example, the name of the Pension Company might be ZURICH, but the name of the Arrangement could be 'Zurich final salary scheme' or something similar. <br><br>

                        Please check your correspondence from your Pension provider for these details if you are not sure, or for avoidance of doubt, if you give them a call and ask, they will be able to help. Furthermore, if you have a specific contact at the pension department, please provide this as it will make things a lot easier and quicker. We have to by law contact the trustees for them to confirm we can implement the transfer.</p>

                        <p>Please check with the pension provider in respect of any pension charges that they will charge you to share the pension. It is very important you do this as we are not privy to this information. It is your responsibility to get this information and decide who will pay the cost relating this before the order is submitted to Court.</p>

                        <p>If either party wants to share a private pension, we will prepare a Form P1 that will be needed to be filed at the Court, when the Consent Order is applied for. If you are not pension sharing, please move onto the next question. If you have agreed to pension share, please complete the following details:</p>
                    </div>




                    <?php 
                        $table_data = "";
                        $display = ( is_array($user_pensions) && count($user_pensions) > 0  ) ? "" : "display:none";
                        $has_pensions = false;
                        $count = "";
                        if(is_array($user_pensions) && count($user_pensions) > 0 )
                        {   
                            // We will use this to prepopulate the properties dropdwn
                            $count = count($user_pensions);

                            // If true we will prepopulate the drop down to say yes and how many properties
                            $has_pensions = true;
                        }
                    ?>  


                    <?=form_open();?>

                        <div class="form-group" style="margin:0px!important;text-align:left!important;">

                            <?php if( is_array($user_pensions) && count($user_pensions) > 0  && $res_logged_in == true && $has_missing_pension_info == true) :?>
                                <div class="alert alert-warning pension-alert-note">
                                    The other party has completed as much as they can in regards to the Pension Share information; however there are some details that they were not sure of.
                                    Therefore in order to complete the application in full, please click the 'Edit/Review' button below and complete the missing information
                                </div>
                            <?php endif;?>

                            <label for="">Are you pension sharing?</label>
                            <select name="pension_sharing" id="pension_sharing" class="form-control" style="width:50%;">
                                <option value="0">No</option>
                                <option value="1" <?php if($has_pensions == TRUE){echo "selected";}?>>Yes</option>
                            </select>

                            <div id="upgrade_msg" style="display: none;">
                                    <div class="alert alert-danger">
                                        The service you have purchased does not include pension sharing, if you wish to upgrade to include this, please call the team on 01793 384 029.
                                    </div>
                            </div>

                        </div><br>

                        <div class="form-group" id="pension_cont" style="margin:0px!important;text-align:left!important;<?=$display;?>">
                            <label for="how_many_pensions">How many pensions are you sharing?</label>
                            <select name="how_many_pensions" id="how_many_pensions" style="width:50%;" class="form-control">
                                <?php for($i=1; $i<= 100; $i++):?>
                                    <?php
                                      $selected = ( $i == $count ) ? "selected" : "";
                                    ?>
                                    <option value="<?=$i;?>" <?=$selected;?> ><?=$i;?></option>
                                <?php endfor;?>
                            </select>

                            <br>
                            <div class="form-group" style="margin:0px!important;text-align:left!important;">        
                                <?php if($res_logged_in == FALSE): ?>
                                    <?php // we dont want to show the button if split application for the respondent ;?>
                                    <button type="button" class="btn btn-primary" id="pension_btn" onclick="add_pension();">Add Pension Details</button>
                                <?php endif;?>
                            </div>

                        </div>


                        <?php if(session()->has("SUCC") ) : ?>
                          <br>
                          <div class="row">
                              <div class="col-lg-12">
                                  <div class="alert alert-success">
                                      <?=htmlentities(session("SUCC"));?>
                                  </div>
                              </div>
                          </div>
                         <?php endif;?>

                         <?php if( isset($_GET['up']) && $_GET['up']  == 1) : ?>
                            <div class="row">
                              <div class="col-lg-12">
                                  <div class="alert alert-success">
                                      Pension successfully updated.
                                  </div>
                              </div>
                          </div>
                         <?php endif;?>


                         <?php 
                        $table_data = "";
                        $display = ( is_array($user_pensions) && count($user_pensions) > 0  ) ? "" : "display:none";
                        $count = "";

                        if(is_array($user_pensions) && count($user_pensions) > 0 )
                        {   
                            foreach($user_pensions as $up)
                            {
                                
                                $table_data .= '';
                                    $table_data .= '<tr>';

                                            $agreement_name = ( $up["name_of_arrangements"] == "" ) ? "<b>Information not supplied</b>" : $up["name_of_arrangements"];

                                        $table_data .= '<th scope="row">'.$up['pension_number'].' - '.$agreement_name.'</th>';
                                        $table_data .= '<td><a href="#" onclick="update_modal('.$up['id'].');return false;">Edit/Review</a></td>';


                                        // If the respondent is loged in, they can only delete THEIR own pensions
                                        if($res_logged_in == true)
                                        {
                                             if($up["petitioner_row"] == 1)
                                             {
                                                    $table_data .= '<td>&nbsp;</td>';
                                             }else{
                                                    $table_data .= '<td><a href="#" onclick="remove_pension('.$up['id'].');">Delete</a></td>';
                                             }

                                        }else{

                                                // The respondent isnt logegd in BUT its a split application, dont allow the applicant to change the
                                                if($up["respondent_row"] == 1 && $is_split_app == true)
                                                {
                                                    $table_data .= '<td>&nbsp;</td>';
                                                }else{
                                                    // Add the default delete button in as its not split or its the applicant logged in
                                                    $table_data .= '<td><a href="#" onclick="remove_pension('.$up['id'].');">Delete</a></td>';
                                                }
                                                
                                        }
                                $table_data .= '</tr>';
                            }
                        }
                        ?>
                        
                        <br><div id="system-message"></div>
                        <div id="pension-table-cont" style="<?=$display;?>">
                            
                            <hr>
                            <table class="table" id="pension-table">
                              <thead class="thead-dark">
                                <tr>
                                  <th scope="col">Pension Number</th>
                                  <th scope="col">Edit</th>
                                  <th scope="col">Delete</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?=$table_data;?>
                              </tbody>
                            </table>
                        </div>

                        <br><br>

                        <div class="coff-nav">
                            <?php $wording_btn = ( $has_missing_pension_info == true) ? "Please review the pension(s) above." : "Next"; ?>
                            <button href="#" id="page_9_submit" onclick="validate_submit();return false;" <?php if($has_missing_pension_info == true){echo "disabled";};?> class="next-btn btn btn-primary"><?=$wording_btn;?></button>
                            <a class="prev-btn btn" href="<?=base_url();?>consent-order/consent_order_ff/4">Previous</a>
                        </div>
                    <?=form_close();?>

                </div>

            </div>
        </div>
    </div>
</div>


<?php /* MODAL START*/ ?>


<div class="modal fade" id="pensionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="pensionModalTitle" aria-hidden="true">
<button href="#" class="btn-lg btn-danger" id="save_pension_update">Save</button>

<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="pension_number_text"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="pension_num" id="pension_num" value="">



        <div id="section-1">
            <div class="options-holder">
        
                <input type="hidden" id="pension_type" name="pension_type" value="A">
            </div>
        </div> <!-- Section 1 end -->


        <div id="section-2" style="margin-top:10px;">

            <div id="ptype_a" class="">

                <div id="error-type_a"></div>

                <div class="well"><b>A. Transferor's details: (The party that is sharing their pension)</b>
                </div>
                <div class="form-group">
                    <label for="a_transferor_name">The full name by which the Transferor is known:</label>
                    <textarea name="a_transferor_name" id="a_transferor_name" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('a_transferor_name', false,'q1_checked')" name="q1_checked" id="q1_checked"><br></div>
                </div>

                <div class="form-group">
                    <label for="a_transferor_all_names">All names by which the Transferor has been known: (e.g. maiden name)</label>
                    <textarea name="a_transferor_all_names" id="a_transferor_all_names" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                        <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('a_transferor_all_names', false,'q2_checked')" name="q2_checked" id="q2_checked"><br></div>
                </div>

                <div class="form-group">
                    <label for="a_transferor_dob">The Transferor’s date of birth:</label>
                    <input type="text" name="a_transferor_dob" id="a_transferor_dob" class="form-control" value="">
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('a_transferor_dob', false,'q3_checked')" name="q3_checked" id="q3_checked"><br></div>
                </div>

                
                <div class="form-group">
                    <label for="a_transferor_address">The Transferor’s Address:</label>
                    <textarea name="a_transferor_address" id="a_transferor_address" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                        <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('a_transferor_address', false,'q4_checked')" name="q4_checked" id="q4_checked"><br></div>
                </div>

                <div class="form-group">
                    <label for="a_transferor_address_nat_num">The Transferor’s National Insurance Number:</label>
                    <input type="text" name="a_transferor_address_nat_num" id="a_transferor_address_nat_num" class="form-control" value="">
                        <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('a_transferor_address_nat_num', false,'q5_checked')" name="q5_checked" id="q5_checked"><br></div>
                </div>
                <br>
                <button class="btn btn-primary" onclick="manage_sections();return false;">Next</button>
            </div> <!-- type a form end -->


            <div id="ptype_b" class="hide-section">

                <div class="well"><b>B. Transferee's details: (The party that is receiving the pension share)</b></div>

                <div id="error-type_b"></div>

                <div class="form-group">
                    <label for="b_transferor_name">The full name by which the Transferee is known:</label>
                    <textarea name="b_transferor_name" id="b_transferor_name" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('b_transferor_name', false,'q6_checked')" name="q6_checked" id="q6_checked"><br></div>
                </div>

                <div class="form-group">
                    <label for="b_transferor_all_names">All names by which the Transferee has been known: (e.g. maiden name)</label>
                    <textarea name="b_transferor_all_names" id="b_transferor_all_names" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('b_transferor_all_names', false,'q7_checked')" name="q7_checked" id="q7_checked"><br></div>
                </div>

                <div class="form-group">
                    <label for="b_transferor_dob">The Transferee's date of birth:</label>
                    <input type="text" name="b_transferor_dob" id="b_transferor_dob" class="form-control">
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('b_transferor_dob', false,'q8_checked')" name="q8_checked" id="q8_checked"><br></div>
                </div>

                
                <div class="form-group">
                    <label for="b_transferor_address">The Transferee's Address:</label>
                    <textarea name="b_transferor_address" id="b_transferor_address" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('b_transferor_address', false,'q9_checked')" name="q9_checked" id="q9_checked"><br></div>
                </div>

                <div class="form-group">
                    <label for="b_transferor_address_nat_num">The Transferee's National Insurance Number:</label>
                    <input type="text" name="b_transferor_address_nat_num" id="b_transferor_address_nat_num" class="form-control">
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('b_transferor_address_nat_num', false,'q10_checked')" name="q10_checked" id="q10_checked"><br/></div>
                </div>

                <br>
                <button id="ptype_b_prev" class="btn btn-primary" onclick="previous_section('A');">Previous Question</button>
                <br><br>
                <button class="btn btn-primary" onclick="manage_sections();return false;">Next</button>
            </div>

            <div id="ptype_c" class="hide-section">

                <div id="error-type_c"></div>

                <div class="well"><b>C. Details of the Transferor’s Pension</b></div>
                <div class="form-group">
                    <label for="name_of_arrangements">Name of the Arrangements: (please check with your pension company)</label>
                    <textarea name="name_of_arrangements" id="name_of_arrangements" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                        <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('name_of_arrangements', false,'q11_checked')" name="q11_checked" id="q11_checked"><br/></div>
                </div>

                <div class="form-group">
                    <label for="name_and_address">Name and Address of the Person/Company responsible for the Pension arrangements:</label>
                    <textarea name="name_and_address" id="name_and_address" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('name_and_address', false,'q12_checked')" name="q12_checked" id="q12_checked"><br/></div>

                </div>

                <div class="form-group">
                    <label for="ref_number">Reference no. of Pension Arrangement:</label>
                    <textarea name="ref_number" id="ref_number" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('ref_number', false,'q13_checked')" name="q13_checked" id="q13_checked"><br/></div>
                </div>


                <div class="form-group">
                    <label for="pencent_of_pension">What percentage (%) of the Pension will be shared - you cannot give a value in pounds and pence, please enter a number between 0-100 to represent the % you wish to share e.g. 100.00</label>
                    <input type="text" name="pencent_of_pension" id="pencent_of_pension" class="form-control" placeholder="100.00" value="000.00">
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('pencent_of_pension', false,'q14_checked')" name="q14_checked" id="q14_checked"><br/></div>
                </div>


                <div class="form-group">
                    <label for="how_pension_paid">How will the pension charges be paid: 
                        <button type="button" class="btn btn-secondary example-popover" data-container="body" data-toggle="popover" data-placement="top" data-content="If there are charges to transfer the pension, who will be responsible for these?"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
</svg></button></label>

                    <select name="how_pension_paid" id="how_pension_paid" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="50/50">50/50 (shared between both parties)</option>
                        <option value="Transferor">Transferor</option>
                        <option value="Transferee_paid">Transferee</option>
                        <option value="other">Other</option>                                
                    </select>
                </div>
                <br>

                <div class="other-cont" style="display: none;">
                    <label for="">Applicant %</label>
                    <input type="text" name="percent_of_pension_other_app" id="percent_of_pension_other_app" class="form-control">

                    <label for="">Respondent %</label>
                    <input type="text" name="percent_of_pension_other_res" id="percent_of_pension_other_res" class="form-control">
                </div>

                <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('how_pension_paid', true,'q15_checked')" name="q15_checked" id="q15_checked"><br/></div>


                <button id="ptype_c_prev" class="btn btn-primary" onclick="previous_section('B');">Previous Question</button>   
                <br><br>
                <button class="btn btn-primary" onclick="manage_sections();return false;">Next</button>
            </div>

            

            <br>
            <hr>
            <div id="final-question" class="hide-section">

                  <div id="final-question-errors"></div>

                  <div class="form-group">
                    <label for="is_internal_transfer">In cases where the Transferee has a choice of an internal or external transfer, if the Transferee has indicated a preference, what is that preference?</label>
                    <select name="is_internal_transfer" id="is_internal_transfer" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="1">Internal Transfer (within the same pension company)</option>
                        <option value="0">External Transfer (with a different pension company)</option>
                    </select>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('is_internal_transfer', true,'q16_checked')" name="q16_checked" id="q16_checked"><br/></div>
                  </div>


                <p>In the case of an external transfer only, please complete the following (this is recommended but optional, if the information is unknown at this stage)</p>

                <div class="form-group">
                    <label for="qualifying_arrangement_name">The name of the qualifying arrangement, which has agreed to accept the pension credit:</label>
                    <textarea name="qualifying_arrangement_name" id="qualifying_arrangement_name" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('qualifying_arrangement_name', false,'q17_checked')" name="q17_checked" id="q17_checked"><br/></div>
                </div>

                <div class="form-group">
                    <label for="qualifying_arrangement_address">The address of the qualifying arrangement:</label>
                    <textarea name="qualifying_arrangement_address" id="qualifying_arrangement_address" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('qualifying_arrangement_address', false,'q18_checked')" name="q18_checked" id="q18_checked"><br/></div>
                </div>


                <div class="form-group">
                    <label for="qualifying_arrangement_policy_num">If known, the Transferee's membership or policy number in the qualifying arrangement and the reference number of the new provider:</label>
                    <textarea name="qualifying_arrangement_policy_num" id="qualifying_arrangement_policy_num" cols="3" rows="3" maxlength="100" class="form-control"></textarea>
                    <div class="no_info">I do not have this information right now <input type="checkbox" onclick="remove_field_value('qualifying_arrangement_policy_num', false,'q19_checked')" name="q19_checked" id="q19_checked"><br/></div>
                </div>
                <br>
                <button id="ptype_c_prev" class="btn btn-primary" onclick="previous_section('C');">Previous Question</button>   
                <br><br>
                <button id="save_pension" data-modalmode="" data-rowid="" onclick="save_pension();return false;" class="btn btn-primary">Save</button>

            </div><!-- final question end -->

        </div><!--  Section 2 end -->

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="clear_modal_data(false);">Close</button>
      </div>
    </div>
  </div>
</div>



<?php /* MODAL END */ ?>



<script>


    function remove_checkboxes(respondent_row, petitioner_row, res_logged_in)
    {   


            // get the fields and there values
            var a_transferor_name_val = $('#a_transferor_name').val(),
                    q1_checked = $('#q1_checked').is(":checked"),
                    a_transferor_all_names_val = $('#a_transferor_all_names').val(),
                    q2_checked = $('#q2_checked').is(":checked"),
                    a_transferor_dob_val = $('#a_transferor_dob').val(),
                    q3_checked = $('#q3_checked').is(":checked"),
                    a_transferor_address_val = $('#a_transferor_address').val(),
                    q4_checked = $('#q4_checked').is(":checked"),
                    a_transferor_address_nat_num_val = $('#a_transferor_address_nat_num').val(),
                    q5_checked = $('#q5_checked').is(":checked"),
                    b_transferor_name_val = $('#b_transferor_name').val(),
                    q6_checked = $('#q6_checked').is(":checked"),
                    b_transferor_all_names_val = $('#b_transferor_all_names').val(),
                    q7_checked = $('#q7_checked').is(":checked"),
                    b_transferor_dob_val = $('#b_transferor_dob').val(),
                    q8_checked = $('#q8_checked').is(":checked"),
                    b_transferor_address_val = $('#b_transferor_address').val(),
                    q9_checked = $('#q9_checked').is(":checked"),
                    b_transferor_address_nat_num_val = $('#b_transferor_address_nat_num').val(),
                    q10_checked = $('#q10_checked').is(":checked")
                    name_of_arrangements_val = $('#name_of_arrangements').val(),
                    q11_checked = $('#q11_checked').is(":checked"),
                    name_and_address_val = $('#name_and_address').val(),
                    q12_checked = $('#q12_checked').is(":checked"),
                    ref_number_val = $('#ref_number').val(),
                    q13_checked = $('#q13_checked').is(":checked"),
                    pencent_of_pension_val = $('#pencent_of_pension').val(),
                    q14_checked = $('#q14_checked').is(":checked"),
                    how_pension_paid_val = $('#how_pension_paid option:selected').val(),
                    is_internal_transfer_val = $('#is_internal_transfer_val option:selected').val(),
                    q15_checked = $('#q15_checked').is(":checked"),
                    q16_checked = $('#q16_checked').is(":checked"),
                    q17_checked = $('#q17_checked').is(":checked"),
                    qualifying_arrangement_address_val = $('#qualifying_arrangement_address').val(),
                    q18_checked = $('#q18_checked').is(":checked"),
                    qualifying_arrangement_policy_num_val = $('#qualifying_arrangement_policy_num').val(),
                    q19_checked = $('#q19_checked').is(":checked"),
                    qualifying_arrangement_name_val = $('#qualifying_arrangement_name_val').val();

                // If the field has data we need to remove the checkbox and stop the respondent being able to type over the information
                if(a_transferor_name_val != "")
                {
                    var q1_checked = $('#q1_checked');
                    q1_checked.hide();
                    q1_checked.parent().hide();
                    $('#a_transferor_name').attr("readonly", true);
                }

                if(a_transferor_all_names_val != "")
                {
                    var q2_checked = $('#q2_checked');
                    q2_checked.hide();
                    q2_checked.parent().hide();
                    $('#a_transferor_all_names').attr("readonly", true);
                }

                if(a_transferor_dob_val != "")
                {
                    var q3_checked = $('#q3_checked');
                    q3_checked.hide();
                    q3_checked.parent().hide();
                    $('#a_transferor_dob').attr("readonly", true);
                }

                if(a_transferor_address_val != "")
                {
                    var q4_checked = $('#q4_checked');
                    q4_checked.hide();
                    q4_checked.parent().hide();
                    $('#a_transferor_address').attr("readonly", true);
                }

                if(a_transferor_address_nat_num_val != "")
                {
                    var q5_checked = $('#q5_checked');
                    q5_checked.hide();
                    q5_checked.parent().hide();
                    $('#a_transferor_address_nat_num').attr("readonly", true);
                }


                // Page B
                if(b_transferor_name_val != "")
                {
                    var q6_checked = $('#q6_checked');
                    q6_checked.hide();
                    q6_checked.parent().hide();
                    $('#b_transferor_name').attr("readonly", true);
                }

                if(b_transferor_all_names_val != "")
                {
                    var q7_checked = $('#q7_checked');
                    q7_checked.hide();
                    q7_checked.parent().hide();
                    $('#b_transferor_all_names').attr("readonly", true);
                }

                if(b_transferor_dob_val != "")
                {
                    var q8_checked = $('#q8_checked');
                    q8_checked.hide();
                    q8_checked.parent().hide();
                    $('#b_transferor_dob').attr("readonly", true);
                }

                if(b_transferor_address_val != "")
                {
                    var q9_checked = $('#q9_checked');
                    q9_checked.hide();
                    q9_checked.parent().hide();
                    $('#b_transferor_address').attr("readonly", true);
                }

                if(b_transferor_address_nat_num_val != "")
                {
                    var q10_checked = $('#q10_checked');
                    q10_checked.hide();
                    q10_checked.parent().hide();
                    $('#b_transferor_address_nat_num').attr("readonly", true);
                }
                
                // Page C
                if(name_of_arrangements_val != "")
                {
                    var q11_checked = $('#q11_checked');
                    q11_checked.hide();
                    q11_checked.parent().hide();
                    $('#name_of_arrangements').attr("readonly", true);
                }

                if(name_and_address_val != "")
                {
                    var q12_checked = $('#q12_checked');
                    q12_checked.hide();
                    q12_checked.parent().hide();
                    $('#name_and_address').attr("readonly", true);
                }

                if(ref_number_val != "")
                {
                    var q13_checked = $('#q13_checked');
                    q13_checked.hide();
                    q13_checked.parent().hide();
                    $('#ref_number').attr("readonly", true);
                }

                if(pencent_of_pension_val != "")
                {
                    var q14_checked = $('#q14_checked');
                    q14_checked.hide();
                    q14_checked.parent().hide();
                    $('#pencent_of_pension').attr("readonly", true);
                }

                if(how_pension_paid_val != "")
                {
                    var q15_checked = $('#q15_checked');
                    q15_checked.hide();
                    q15_checked.parent().hide();
                    $('#how_pension_paid').attr("disabled", true);
                }

                    
                // Page D
                if(is_internal_transfer_val != "")
                {
                    var q16_checked = $('#q16_checked');
                    q16_checked.hide();
                    q16_checked.parent().hide();
                    $('#is_internal_transfer').attr("disabled", true);
                }

                if(qualifying_arrangement_name_val != "")
                {
                    var q17_checked = $('#q17_checked');
                    q17_checked.hide();
                    q17_checked.parent().hide();
                    $('#qualifying_arrangement_name').attr("readonly", true);
                }

                if(qualifying_arrangement_address_val != "")
                {
                    var q18_checked = $('#q18_checked');
                    q18_checked.hide();
                    q18_checked.parent().hide();
                    $('#qualifying_arrangement_address').attr("readonly", true);
                }

                if(qualifying_arrangement_policy_num_val != "")
                {
                    var q19_checked = $('#q19_checked');
                    q19_checked.hide();
                    q19_checked.parent().hide();
                    $('#qualifying_arrangement_policy_num').attr("readonly", true);
                }
                    
    }

    function remove_field_value(field, select, checkbox)
    {

        var  is_checked = $('#'+checkbox+'').is(":checked");
        if(is_checked)
        {
                var msg = confirm("You've selected the `I don't have the information` checkbox, this will remove any value(s) found in the field above the checkbox you have selcted. Do you want to continue?");
                if(msg == true)
                {
                        if(select == false)
                        {
                            // its a text field or texarea
                            $('#'+field+'').val('');
                        }else{
                            // is a select field
                            $('select#'+field+'').prop('selectedIndex',0);
                        }
                }else{
                     // remove the checkbox
                     $('#'+checkbox+'').prop("checked",false);
                }
        }
    }


    function manage_sections()
    {
        // get the current selected option A, B or C
        var selectedVal = $('#pension_type').val(),
            errors = [];

        switch(selectedVal)
        {
            case 'A':
                //Show section a
                $('#ptype_a').removeClass("hide-section");
                // Hide section b,c
                $('#ptype_b, #ptype_c').addClass("hide-section");
                validate_section("a");
            break;

            case 'B':
                //Show section b
                $('#ptype_b').removeClass("hide-section");
                // Hide section a,c
                $('#ptype_a, #ptype_c').addClass("hide-section");
                validate_section("b");
            break;

            case 'C':
                //Show section c
                $('#ptype_c').removeClass("hide-section");
                // Hide section a,b
                $('#ptype_a, #ptype_b').addClass("hide-section");
                validate_section("c");
            break;
        }
    }

    function save_pension()
    {

        // check the form forms are valid, if so, save the data
        var is_internal_transfer = $('#is_internal_transfer option:selected').val(),
            qualifying_arrangement_name =  $.trim($('#qualifying_arrangement_name').val()),
            qualifying_arrangement_address =  $.trim($('#qualifying_arrangement_address').val()),
            qualifying_arrangement_policy_num =  $.trim($('#qualifying_arrangement_policy_num').val()),
            errors = [],
            total_errors = 0
            error_div = $('#final-question-errors'),
            is_update = $('#save_pension').attr("data-modalmode"),
            update_id = $('#save_pension').attr("data-rowid");

        // Clear the errors
        error_div.empty();


        // get the check boxes for the "i dont have this information"
        checkbox_16 = ( $('#q16_checked').is(":checked") ) ? true : false;
        checkbox_17 = ( $('#q17_checked').is(":checked") ) ? true : false;
        checkbox_18 = ( $('#q18_checked').is(":checked") ) ? true : false;
        checkbox_19 = ( $('#q19_checked').is(":checked") ) ? true : false;


        if(checkbox_16 == false)
        {
                if(is_internal_transfer == "")
                {
                    total_errors++;
                    errors.push("Please select either internal or external from the dropdown supplied");
                }
        }


        if(checkbox_17 == false)
        {
                if(qualifying_arrangement_name.length > 0)
                {
                    if(qualifying_arrangement_name.length > 100)
                    {
                        total_errors++;
                        errors.push("The name of the qualifying arrangement must not be more than 100 characters");
                    }
                }
        }


        if(checkbox_18 == false)
        {
                if(qualifying_arrangement_address.length > 0)
                {
                    if(qualifying_arrangement_address.length  > 100)
                    {
                        total_errors++;
                        errors.push("The address of the qualifying arrangement must not be more than 100 characters");
                    }
                }
        }


        if(checkbox_19 == false)
        {
                if(qualifying_arrangement_policy_num.length > 0)
                {
                    if(qualifying_arrangement_policy_num.length  > 100)
                    {
                        total_errors++;
                        errors.push("The transferee's membership or policy number must not be more than 100 characters");
                    }
                }
        }


        //If not errors then we save the data else show errors
        if(total_errors > 0)
        {
            // Process the errors
            process_errors(errors, error_div);
        }else{

            // Save the data
            var insert_row = ( is_update != "update") ? 1 : 0,
                update_row = ( is_update == "update") ? 1 : 0;

            // Do Ajax save/update the data
            update_pension_db(insert_row, update_row);
        }
    }

    function previous_section(section)
    {

        switch(section)
        {
            case 'A':
                // hide sections
                $('#final-question,#ptype_b,#ptype_c').addClass("hide-section");
                $('#ptype_a').removeClass("hide-section");
                $('#pension_type').val('A');
            break;

            case 'B':
                // hide sections
                $('#final-question,#ptype_a,#ptype_c').addClass("hide-section");
                $('#ptype_b').removeClass("hide-section");
                $('#pension_type').val('B');
            break;

            case 'C':
                // hide sections
                $('#final-question,#ptype_a,#ptype_b').addClass("hide-section");
                $('#ptype_c').removeClass("hide-section");
                $('#pension_type').val('C');
            break;
        }

    }

    function validate_section(answer_selected)
    {
                    // Do Validation, get the values
        var errors = [],
            total_errors = 0,
            error_div_a = $('#error-type_a'),
            error_div_b = $('#error-type_b'),
            error_div_c = $('#error-type_c'),
            a_transferor_name = $.trim($('#a_transferor_name').val()),
            a_transferor_all_names = $.trim($('#a_transferor_all_names').val()),
            a_transferor_dob = $.trim($('#a_transferor_dob').val()),
            a_transferor_address = $.trim($('#a_transferor_address').val()),
            a_transferor_address_nat_num = $.trim($('#a_transferor_address_nat_num').val()),
            b_transferor_name = $.trim($('#b_transferor_name').val()),
            b_transferor_all_names = $.trim($('#b_transferor_all_names').val()),
            b_transferor_dob = $.trim($('#b_transferor_dob').val()),
            b_transferor_address = $.trim($('#b_transferor_address').val()),
            b_transferor_address_nat_num = $.trim($('#b_transferor_address_nat_num').val()),
            name_of_arrangements = $.trim($('#name_of_arrangements').val()),
            name_and_address = $.trim($('#name_and_address').val()),
            ref_number = $.trim($('#ref_number').val()),
            pencent_of_pension = $.trim($('#pencent_of_pension').val()),
            how_pension_paid = $('#how_pension_paid option:selected').val();


        // Clear the error divs
        error_div_a.empty();
        error_div_b.empty();
        error_div_c.empty(),
        $('#final-question').addClass("hide-section");

        switch(answer_selected)
        {
            case "a":


                // get the check boxes for the "i dont have this information"
                checkbox_1 = ( $('#q1_checked').is(":checked") ) ? true : false;
                checkbox_2 = ( $('#q2_checked').is(":checked") ) ? true : false;
                checkbox_3 = ( $('#q3_checked').is(":checked") ) ? true : false;
                checkbox_4 = ( $('#q4_checked').is(":checked") ) ? true : false;
                checkbox_5 = ( $('#q5_checked').is(":checked") ) ? true : false;


                if(checkbox_1 == false)
                {
                        if(a_transferor_name.length < 2 && checkbox_1 == false)
                        {
                            total_errors++;
                            errors.push("Transferor name is a required field");

                                if(a_transferor_name.length > 100)
                                {
                                    total_errors++;
                                    errors.push("Transferor name must be less than 100 characters");
                                }
                        }
                }

            
                if(checkbox_2 == false)
                {
                        if(a_transferor_all_names != "")
                        {
                                if(a_transferor_all_names.length < 2)
                                {
                                    total_errors++;
                                    errors.push("Please supply all names by which the Transferor has been known as");
                                }

                                if(a_transferor_all_names.length > 100)
                                {
                                    total_errors++;
                                    errors.push("The all names by which the Transferor has been known as field must be less than 100 characters");
                                }
                        }
                }

                
                if(checkbox_3 == false)
                {
                        if(a_transferor_dob.length < 10)
                        {
                            total_errors++;
                            errors.push("The transferor date of birth is required. (dd-mm-YYYY)");

                        }else{

                            // Check the user is over 16
                            var today = new Date();
                    
                            var dob = a_transferor_dob;  // Format: '12/11/1998'
                            var bdYear, year;
                            bdYear = dob.substring(6, 10) - 0;
                            year = new Date().getFullYear();
                            var age = 16;

                            if((year - bdYear) < age)
                            {
                                total_errors++;
                                errors.push("The transferor must be over the age of 16");
                            }
                        }
                }


                if(checkbox_4 == false)
                {
                        if(a_transferor_address.length < 10)
                        {
                            total_errors++;
                            errors.push("The transferor's address is a required field");
                        }

                        if(a_transferor_address.length > 100)
                        {
                            total_errors++;
                            errors.push("The transferor's address must be less than 100 characters");
                        }
                }


                if(checkbox_5 == false)
                {

                        if(a_transferor_address_nat_num.length < 2)
                        {
                            total_errors++;
                            errors.push("The transferor's national insurance number is a required field");
                            
                        }else{

                                // Check the national insurance number
                                pattern = new RegExp("^[a-zA-Z]{2}[0-9]{6}[a-zA-Z]{1}$");
                                if(!pattern.test(a_transferor_address_nat_num))
                                {
                                    total_errors++;
                                    errors.push("Please enter a valid national insurance number e.g. AA123456A");
                                }
                        }
                }

            
                // Do we have errors?
                if(total_errors > 0)
                {
                    // Process the errors
                    process_errors(errors, error_div_a);
                }else{
                    // Close section a and open B
                    $('#ptype_a').addClass("hide-section");
                    $('#ptype_b').removeClass("hide-section");
                    $('#pension_type').val('B');
                }

            break;

            case "b":


                // get the check boxes for the "i dont have this information"
                checkbox_6 = ( $('#q6_checked').is(":checked") ) ? true : false;
                checkbox_7 = ( $('#q7_checked').is(":checked") ) ? true : false;
                checkbox_8 = ( $('#q8_checked').is(":checked") ) ? true : false;
                checkbox_9 = ( $('#q9_checked').is(":checked") ) ? true : false;
                checkbox_10 = ( $('#q10_checked').is(":checked") ) ? true : false;


                if(checkbox_6 ==  false)
                {
                        if(b_transferor_name.length < 2)
                        {
                            total_errors++;
                            errors.push("Transferee name is a required field");
                        }

                        if(b_transferor_name.length > 100)
                        {
                            total_errors++;
                            errors.push("Transferee's name must be less than 100 characters");
                        }
                }


                if(checkbox_7 ==  false)
                {
                        if(b_transferor_all_names != "")
                        {
                            if(b_transferor_all_names.length < 2)
                            {
                                total_errors++;
                                errors.push("Please supply all names by which the Transferee has been known as");
                            }

                            if(b_transferor_all_names.length > 100)
                            {
                                total_errors++;
                                errors.push("The all names by which the Transferee has been known as field must be less than 100 characters");
                            }
                        }
                }


                if(checkbox_8 ==  false)
                {
                        if(b_transferor_dob.length < 10)
                        {
                            total_errors++;
                            errors.push("The Transferee's date of birth is required. (dd-mm-YYYY)");
                        }else{

                            // Check the user is over 16
                            var today = new Date();
                    
                            var dob = b_transferor_dob;  // Format: '12/11/1998'
                            var bdYear, year;
                            bdYear = dob.substring(6, 10) - 0;
                            year = new Date().getFullYear();
                            var age = 16;

                            if((year - bdYear) < age)
                            {
                                total_errors++;
                                errors.push("The transferee must be over the age of 16");
                            }
                        }
                }


                if(checkbox_9 ==  false)
                {
                        if(b_transferor_address.length < 10)
                        {
                            total_errors++;
                            errors.push("The Transferee's address is a required field");
                        }

                        if(b_transferor_address.length > 100)
                        {
                            total_errors++;
                            errors.push("The Transferee's address must be less than 100 characters");
                        }
                }


                if(checkbox_10 ==  false)
                {

                        if(b_transferor_address_nat_num.length < 2)
                        {
                            total_errors++;
                            errors.push("The Transferee's national insurance number is a required field");
                        }else{

                            // Check the national insurance number
                            pattern = new RegExp("^[a-zA-Z]{2}[0-9]{6}[a-zA-Z]{1}$");
                            if(!pattern.test(b_transferor_address_nat_num))
                            {
                                total_errors++;
                                errors.push("Please enter a valid national insurance number e.g. AA123456A");
                            }
                        }
                }


                if(total_errors > 0)
                {
                    // Process the errors
                    process_errors(errors, error_div_b);
                }else{
                    // Close section a and open B
                    $('#ptype_a, #ptype_b').addClass("hide-section");
                    $('#ptype_c').removeClass("hide-section");
                    $('#pension_type').val('C');
                }

            break;

            case "c":


                // get the check boxes for the "i dont have this information"
                checkbox_11 = ( $('#q11_checked').is(":checked") ) ? true : false;
                checkbox_12 = ( $('#q12_checked').is(":checked") ) ? true : false;
                checkbox_13 = ( $('#q13_checked').is(":checked") ) ? true : false;
                checkbox_14 = ( $('#q14_checked').is(":checked") ) ? true : false;
                checkbox_15 = ( $('#q15_checked').is(":checked") ) ? true : false;

              // If the pop is visable hide id
              if($('.popover-content').is(':visible')){
                    //do stuff in here
                    $('.example-popover').click();
                }


                if(checkbox_11 == false)
                {
                        if(name_of_arrangements.length < 2)
                        {
                            total_errors++;
                            errors.push("Name of the arrangements is a required field");
                        }

                        if(name_of_arrangements.length > 100)
                        {
                            total_errors++;
                            errors.push("Name of the arrangements must be less than 100 characters");
                        }
                }

                if(checkbox_12 == false)
                {

                        if(name_and_address.length < 2)
                        {
                            total_errors++;
                            errors.push("Name and address of the person responsible for the pension arrangements is a required field");
                        }

                        if(name_and_address.length > 100)
                        {
                            total_errors++;
                            errors.push("Name and address of the person responsible for the pension arrangements must be less than 100 characters");
                        }
                }


                if(checkbox_13 == false)
                {
                        if(ref_number.length < 2)
                        {
                            total_errors++;
                            errors.push("Reference no. of pension arrangement is a required field");
                        }

                        if(ref_number.length > 100)
                        {
                            total_errors++;
                            errors.push("Reference no. of pension arrangement must be less than 100 characters");
                        }
                }



                if(checkbox_14 == false)
                {

                        if(!$.isNumeric(pencent_of_pension) || pencent_of_pension < 0 || pencent_of_pension > 100)
                        {
                            total_errors++;
                            errors.push("Percentage of pension to be shared is a required field, please enter a number between 0-100");
                        }else{

                                // if the above checked passed then we want to check the format

                                // check that the field follows the correct pattern ddd.dd or dd.dd
                                pattern = new RegExp("^[0-9]{1,3}\.{1}[0-9]{2}$");
                                if(!pattern.test(pencent_of_pension))
                                {
                                    total_errors++;
                                    errors.push("Please ensure the sharing percentage is entered correctly e.g. 100.00 / 40.00");
                                }

                                // Check the value is over 0
                                if(pencent_of_pension <= 0.00){
                                    total_errors++;
                                    errors.push("The shared pension percentage must be greater than 0%");
                                }

                        }
                }


                if(checkbox_15 == false)
                {
                        if(how_pension_paid == "")
                        {
                            total_errors++;
                            errors.push("Please select how this pension will be paid from the dropdown menu supplied");
                        }
                }



                if(total_errors > 0)
                {
                    // Process the errors
                    process_errors(errors, error_div_c);
                }else{
                    $('#ptype_c').addClass("hide-section");
                    open_next_section();
                }
            break;
        }

    }

    function open_next_section()
    {
        $('#final-question').removeClass("hide-section");
        document.querySelector('#final-question').scrollIntoView({ 
          behavior: 'smooth' 
        });
    }

    function add_pension()
    {

        // Make sure the save button is hidden
        $('#save_pension_update').hide();

        // We need to find out how many rows we have in the table and +1 to the row counter as this is what we will use in the database
        var rowCount = ( $('#pension-table-cont tbody >tr').length == 0 ) ? 1 : $('#pension-table-cont tbody >tr').length  + 1;

        // Clear the modal mode
        $('#save_pension').attr("data-modalmode","");
        $('#save_pension').attr("data-rowid","");

        // We need to check if the number of rows would be greater than the dropdown value
        var current_prop_count = $('#how_many_pensions option:selected').val();

        if(current_prop_count < rowCount)
        {
            // Close the modal and show error
            $('#system-message').append('<div class="alert alert-danger">Please increase the amount of pensions you are sharing from the dropdown provided to add an extra pension.</div>');
             setTimeout(function()
             { 
                // Clear the success div
                $('#system-message').empty();
             }, 10000);

            // Close the model
            $('#pensionModal').modal('hide');

        }else{          
            //Update the dynamic content
            $('#pensionModal').find("#pension_num").val(rowCount);
            $('#pensionModal').find("#pension_number_text").text("Adding Information For Pension "+rowCount+"");

            $('#pensionModal').modal('show');
        }

        
    }


    function validate_submit()
    {

        // Has the user said yes to selling a property
        var pension_sharing_val = $('#pension_sharing option:selected').val(),
            redirectUrl = '<?=site_url();?>consent-order/consent_order_ff/6';

        if(pension_sharing_val == 1)
        {

            // Get the amount of properties from the dropdown and compare to the table of properties
            var rowCount = $('#pension-table-cont tbody >tr').length,
                pensionsSelected = $('#how_many_pensions option:selected').val();


            if(pensionsSelected == rowCount)
            {
                // Redirect as the values match
                window.location.href = ""+redirectUrl+"";
            }else{

                // The amounts dont match
                $('#system-message').append('<div class="alert alert-danger">Error: The number of pensions selected does not match the information you have supplied. Please check and try again.</div>');
                 setTimeout(function()
                 { 
                    // Clear the success div
                    $('#system-message').empty();
                 }, 8000);
            }   

        }else{
            // Redirect as the user answered no
            window.location.href = ""+redirectUrl+"";
        }
    
    }


    function update_pension_db(insert, update)
    {

        // Check if the user is happy and wants to proceed
        var confirm_msg = confirm("Press 'OK' to confirm pension details or cancel to make any changes.");

        // We only process once the user confirms
        if(confirm_msg == true)
        {

            // Update the database, if success add a new table row
            var table_container              =    $('#pension-table-cont'),
                pension_type                 =    $('input[name=pension_type]:checked').val(),
                a_transferor_name            =    $('#pensionModal').find("#a_transferor_name").val(),
                a_transferor_all_names       =    $('#pensionModal').find("#a_transferor_all_names").val(),
                a_transferor_dob             =    $('#pensionModal').find("#a_transferor_dob").val(),
                a_transferor_address         =    $('#pensionModal').find("#a_transferor_address").val(),
                a_transferor_address_nat_num =    $('#pensionModal').find("#a_transferor_address_nat_num").val(),
                b_transferor_name            =    $('#pensionModal').find("#b_transferor_name").val(),
                b_transferor_all_names       =    $('#pensionModal').find("#b_transferor_all_names").val(),
                b_transferor_dob             =    $('#pensionModal').find("#b_transferor_dob").val(),
                b_transferor_address         =    $('#pensionModal').find("#b_transferor_address").val(),
                b_transferor_address_nat_num =    $('#pensionModal').find("#b_transferor_address_nat_num").val(),
                name_of_arrangements         =    $('#pensionModal').find("#name_of_arrangements").val(),
                name_and_address             =    $('#pensionModal').find("#name_and_address").val(),
                ref_number                   =    $('#pensionModal').find("#ref_number").val(),
                pencent_of_pension           =    $('#pensionModal').find("#pencent_of_pension").val(),
                how_pension_paid             =    $('#how_pension_paid option:selected').val(),
                qualifying_arrangement_name  =    $('#qualifying_arrangement_name').val(),
                is_internal_transfer         =    $('#is_internal_transfer option:selected').val(),
                qualifying_arrangement_address  = $('#qualifying_arrangement_address').val(),
                qualifying_arrangement_policy_num  = $('#qualifying_arrangement_policy_num').val(),
                pension_number =    $('#pension_num').val(),
                percent_of_pension_other_app = $('#percent_of_pension_other_app').val(),
                percent_of_pension_other_res = $('#percent_of_pension_other_res').val(),
                rowid           =    $('#save_pension').attr("data-rowid"),
                checkbox_1 = ( $('#q1_checked').is(":checked") ) ? 1 : 0,
                checkbox_2 = ( $('#q2_checked').is(":checked") ) ? 1 : 0,
                checkbox_3 = ( $('#q3_checked').is(":checked") ) ? 1 : 0,
                checkbox_4 = ( $('#q4_checked').is(":checked") ) ? 1 : 0,
                checkbox_5 = ( $('#q5_checked').is(":checked") ) ? 1 : 0,
                checkbox_6 = ( $('#q6_checked').is(":checked") ) ? 1 : 0,
                checkbox_7 = ( $('#q7_checked').is(":checked") ) ? 1 : 0,
                checkbox_8 = ( $('#q8_checked').is(":checked") ) ? 1 : 0,
                checkbox_9 = ( $('#q9_checked').is(":checked") ) ? 1 : 0,
                checkbox_10 = ( $('#q10_checked').is(":checked") ) ? 1 : 0,
                checkbox_11 = ( $('#q11_checked').is(":checked") ) ? 1 : 0,
                checkbox_12 = ( $('#q12_checked').is(":checked") ) ? 1 : 0,
                checkbox_13 = ( $('#q13_checked').is(":checked") ) ? 1 : 0,
                checkbox_14 = ( $('#q14_checked').is(":checked") ) ? 1 : 0,
                checkbox_15 = ( $('#q15_checked').is(":checked") ) ? 1 : 0,
                checkbox_16 = ( $('#q16_checked').is(":checked") ) ? 1 : 0,
                checkbox_17 = ( $('#q17_checked').is(":checked") ) ? 1 : 0,
                checkbox_18 = ( $('#q18_checked').is(":checked") ) ? 1 : 0,
                checkbox_19 = ( $('#q19_checked').is(":checked") ) ? 1 : 0;


            // Do ajax call
            $.ajax(
            {
                type: "POST",
                url: "<?=base_url();?>consent-order/addPensionCo",
                data: {
                    "is_update": update,
                    "rowid": rowid,
                    "pension_type": pension_type,
                    "a_transferor_name":a_transferor_name,
                    "a_transferor_all_names": a_transferor_all_names,
                    "a_transferor_dob": a_transferor_dob,
                    "a_transferor_address":a_transferor_address,
                    "a_transferor_address_nat_num": a_transferor_address_nat_num,
                    "b_transferor_name": b_transferor_name,
                    "b_transferor_all_names":b_transferor_all_names,
                    "b_transferor_dob":b_transferor_dob,
                    "b_transferor_address":b_transferor_address,
                    "b_transferor_address_nat_num": b_transferor_address_nat_num,
                    "name_of_arrangements": name_of_arrangements,
                    "name_and_address":name_and_address,
                    "ref_number": ref_number,
                    "pencent_of_pension": pencent_of_pension,
                    "how_pension_paid": how_pension_paid,
                    "is_internal_transfer" : is_internal_transfer,
                    "qualifying_arrangement_name" : qualifying_arrangement_name,
                    "qualifying_arrangement_address": qualifying_arrangement_address,
                    "qualifying_arrangement_policy_num": qualifying_arrangement_policy_num,
                        "pension_number": pension_number,
                        "applicant_other": percent_of_pension_other_app,
                        "respondent_other": percent_of_pension_other_res,
                        "checkbox_1" : checkbox_1,
                        "checkbox_2" : checkbox_2,
                        "checkbox_3" : checkbox_3,
                        "checkbox_4" : checkbox_4,
                        "checkbox_5" : checkbox_5,
                        "checkbox_6" : checkbox_6,
                        "checkbox_7" : checkbox_7,
                        "checkbox_8" : checkbox_8,
                        "checkbox_9" : checkbox_9,
                        "checkbox_10" : checkbox_10,
                        "checkbox_11" : checkbox_11,
                        "checkbox_12" : checkbox_12,
                        "checkbox_13" : checkbox_13,
                        "checkbox_14" : checkbox_14,
                        "checkbox_15" : checkbox_15,
                        "checkbox_16" : checkbox_16,
                        "checkbox_17" : checkbox_17,
                        "checkbox_18" : checkbox_18,
                        "checkbox_19" : checkbox_19,
                },
                success: function(res)
                {

                    if(res == "NO DATA SENT")
                    {
                        alert("Error processing your request, please try again. (REF: EMPARR)");
                    }else if(res == "DB ERROR"){
                        alert("Sorry, we have encountered an error, please try again or contact support (REF:DBERR)");
                    }else if(res == "MISSING DATA"){
                        alert("Sorry, we have encountered an error, please try again or contact support (REF:NODATA)");
                    }else if(res == "SUCERROR"){
                        alert("Sorry, we have encountered an error, please try again or contact support (REF:SECERROR)");
                    }else if(res == "SUCCESSUP"){
                        // clear_modal_data
                        clear_modal_data(true, true);   
                    }else{                  
                        // clear_modal_data
                        clear_modal_data(true);
                        location.reload();              
                    }
                }
            });
        }
    }


    function update_modal(id)
    {
        var rowID = parseInt(id),
                has_contact = '<?=$is_split_app;?>';

        <?php // remove save button for split app ;?>
        if(has_contact == 0){
            $('#save_pension').hide();
        }
        // Make sure the save button is show
        $('#save_pension_update').show();

        // First thing we need to do is fetch the data from the db
        $.ajax(
        {
            type: "POST",
                url: "<?=base_url();?>consent-order/fetchPensionData",
                data: {
                    "id": rowID
                },
                success: function(res)
                {
                    if(res == "FAILED")
                    {
                        alert("Error processing your request, please try again. (REF: SECERR)");
                    }else if(res == "DBERROR"){
                        alert("Error processing your request, please try again. (REF: DBERR)");
                    }else{
                        // We will reset the form before we start populating the form
                        reset_form();

                        // Parse the returned JSON 
                        res = $.parseJSON(res);


                        // If this is a split applicant AND this row belongs the appliant and the respondent is logged in hide the save button in the popup
                        var has_contact = '<?=$is_split_app;?>',
                                is_split_app = ( has_contact == 0 ) ? true : false,
                                res_logged_in = '<?=$hide_save;?>';


                        // if we are dealing with a split application we need to know who owns it
                        var respondent_row = "",
                                petitioner_row = "",
                                respondent_row = res.respondent_row,
                                petitioner_row = res.petitioner_row;


                        // Set the Modal mode to update
                        $('#save_pension').attr("data-modalmode","update");
                        $('#save_pension').attr("data-rowid",rowID);


                        // TODO:
                        // Get the is_internal_transfer and qualifying_arrangement_name values and add populate the modal
                        $('select#is_internal_transfer').val(res.is_internal_transfer);
                        $('#qualifying_arrangement_name').val(res.qualifying_arrangement_name);
                       
                        // Repopulate fields
                        var option_selected = res.pension_type;

                        $('#a_transferor_name').val(res.a_transferor_name);
                        $('#a_transferor_all_names').val(res.a_transferor_all_names);
                        $('#a_transferor_dob').val(res.a_transferor_dob);
                        $('#a_transferor_address').val(res.a_transferor_address);
                        $('#a_transferor_address_nat_num').val(res.a_transferor_address_nat_num);
                        $('#b_transferor_name').val(res.b_transferor_name);
                        $('#b_transferor_all_names').val(res.b_transferor_all_names);
                        $('#b_transferor_dob').val(res.b_transferor_dob);
                        $('#b_transferor_address').val(res.b_transferor_address);
                        $('#b_transferor_address_nat_num').val(res.b_transferor_address_nat_num);

                        $('#name_and_address').val(res.name_and_address);
                        $('#name_of_arrangements').val(res.name_of_arrangements);
                        $('#ref_number').val(res.ref_number);
                        $('#pencent_of_pension').val(res.pencent_of_pension);
                        $('select#how_pension_paid').val(res.how_pension_paid);

                        // Bottom section
                        $('#qualifying_arrangement_address').val(res.qualifying_arrangement_address);
                        $('#qualifying_arrangement_policy_num').val(res.qualifying_arrangement_policy_num);



                        // recheck no info check boxes if needed
                        if(res.q1_checked == "1"){$('#q1_checked').prop("checked", true);}
                        if(res.q2_checked == "1"){$('#q2_checked').prop("checked", true);}
                        if(res.q3_checked == "1"){$('#q3_checked').prop("checked", true);}
                        if(res.q4_checked == "1"){$('#q4_checked').prop("checked", true);}
                        if(res.q5_checked == 1){$('#q5_checked').prop("checked", true);}
                        if(res.q6_checked == 1){$('#q6_checked').prop("checked", true);}
                        if(res.q7_checked == 1){$('#q7_checked').prop("checked", true);}
                        if(res.q8_checked == 1){$('#q8_checked').prop("checked", true);}
                        if(res.q9_checked == 1){$('#q9_checked').prop("checked", true);}
                        if(res.q10_checked == 1){$('#q10_checked').prop("checked", true);}
                        if(res.q11_checked == 1){$('#q11_checked').prop("checked", true);}
                        if(res.q12_checked == 1){$('#q12_checked').prop("checked", true);}
                        if(res.q13_checked == 1){$('#q13_checked').prop("checked", true);}
                        if(res.q14_checked == 1){$('#q14_checked').prop("checked", true);}
                        if(res.q15_checked == 1){$('#q15_checked').prop("checked", true);}
                        if(res.q16_checked == 1){$('#q16_checked').prop("checked", true);}
                        if(res.q17_checked == 1){$('#q17_checked').prop("checked", true);}
                        if(res.q18_checked == 1){$('#q18_checked').prop("checked", true);}
                        if(res.q19_checked == 1){$('#q19_checked').prop("checked", true);}



                        if(is_split_app == true && res_logged_in == true && petitioner_row == 1)
                        {
                            // Remove any check boxes under fields that have data
                            // disable fields for editing for the respondent
                            remove_checkboxes(respondent_row, petitioner_row, res_logged_in);       
                        }

                        // if split and applicant logged in remove checkboxes and lock data
                        if(is_split_app == true && res_logged_in == false && respondent_row == 1)
                        {
                            remove_checkboxes(respondent_row, petitioner_row, res_logged_in);       
                        }

                        console.log(res.how_pension_paid );
                        if(res.how_pension_paid == "other")
                        {
                            $('.other-cont').show();
                            $('#percent_of_pension_other_app').val(res.applicant_other).show();
                            $('#percent_of_pension_other_res').val(res.respondent_other).show();
                        }


                        // Open Modal
                        $('#pensionModal').modal('show');
                        $('#ptype_a').removeClass("hide-section");


                    }
                }
        });
    }


    function process_errors(errors, error_div)
    {
        var output = '<div class="alert alert-danger">';
        // We have errors, loop the array and create error output
        $.each(errors, function(index, value)
        {
            // We want the number next to the errors t start at 1 not 0
            var loop_counter = index + 1;
            output += ''+loop_counter+'. '+value+'<br />';
        });
        output += '</div>';

        // Output the error(s)
        error_div.html(output);
    }


    function remove_pension(id)
    {

        if(id)
        {
            // We have an ID so we can carry on
            var rowID = parseInt(id),
                user_msg = confirm("Are you sure you want to delete this pension?");

            if(user_msg == true)
            {
                $.ajax(
                {
                    type: "POST",
                    url: "<?=base_url();?>consent-order/removePension",
                    data: {
                        "id": rowID, 'logged_in' : '<?=$res_logged_in;?>'
                    },
                    success: function(res)
                    {

                        if(res == "FAILED")
                        {
                            alert("Failed to remove this pension (REF: SECERROR)");

                        }else if( res == "DATABASEERROR"){
                            alert("Sorry, we have encountered an error, please try again or contact support. (REF: DBERR)");

                        }else if( res == "SECERROR"){

                            alert("You are trying to perform an action you don't valid permissions for. Our security team have been notified");

                        }else{
                            // Success - reload the page and show the flash data
                            location.reload();
                        }
                    }
                });
            }
        }
    }


    function hide_pension_dropdown()
    {

        // Prompt user
        var user_msg = confirm("Please press 'OK' to confirm you wish to remove all pension data, this information can NOT be recovered once deleted.");

        if(user_msg == true)
        {
            // Delete data from database
            $.ajax(
            {
                type: "POST",
                url: "<?=base_url();?>consent-order/deletePensionData",
                data: {
                    "delete_data": true
                },
                success: function(res)
                {

                    if(res == "NO DATA")
                    {
                        alert("Error processing your request, please try again. (REF: NODATA)");
                    }else if(res == "DATABASE ERROR"){
                        alert("Error processing your request, please try again. (REF: DBERR)");
                    }else{
                        // SUCCESS
                        // Remove table data from html
                        $('#pension-table').empty().hide();

                        // Show success message
                        $('#system-message').append('<div class="alert alert-success">Pension information has been successfully removed.</div>');
                         setTimeout(function()
                         { 
                            // Clear the success div
                            $('#system-message').empty();
                         }, 8000);

                         // reset the dropdown
                         $('#how_many_pensions').prop('selectedIndex',0);

                         // Hide the property div
                         $('#pension_cont').addClass("hide-section");
                    }

                }
            });
        }       
    }



    

    function clear_modal_data(from_script, update)
    {   
        //Make sure the user understands this  will remove the data
        if(from_script == false)
        {

            var prompt = confirm("Are you sure you want to close this popup?\n\nClosing this popup will permanently delete any data you added/updated for this pension.");
            if(prompt == true)
            {
                reset_form();
            }
            location.reload();

        }else{

            reset_form();
            if(update == true)
            {
                // Set flash data and refresh page
                var redirectUrl = '<?=base_url()?>consent-order/consent_order_ff/5?up=1';
                window.location.href = redirectUrl;
                
                
            }else{
                $('#system-message').append('<div class="alert alert-success">Pension successfully added to the system.</div>');
                 setTimeout(function()
                 { 
                    // Clear the success div
                    $('#system-message').empty();
                 }, 10000);
            }
            
        }   
    }


    function reset_form()
    {
        // hide qestion 1, 2, 3, 4 and section 2
        $('#ptype_a, #ptype_b, #ptype_c').addClass("hide-section");

        // Clear the modal title
        $('#pension_number_text').html("");

        // Clear the row count hidden field
        $('#pension_num').val('');

        // Close the model
        $('#pensionModal').modal('hide');

        // Clear the modal mode
        $('#save_pension').attr("data-modalmode","");
        $('#save_pension').attr("data-rowid","");

        // Reset the fields
        $('#a_transferor_name,#a_transferor_all_names, #a_transferor_dob, #a_transferor_address, #a_transferor_address_nat_num,#b_transferor_name,#b_transferor_all_names, #b_transferor_dob, #b_transferor_address, #b_transferor_address_nat_num, #name_of_arrangements,#name_and_address,#ref_number, #pencent_of_pension,#qualifying_arrangement_address,#qualifying_arrangement_policy_num').empty();

        // Reset dropdowns
        $('#how_pension_paid').prop('selectedIndex',0);
        
    }


    $(document).ready(function()
    {


          // If the pop is visable hide id
          if($('.popover-content').is(':visible')){
                //do stuff in here
                $('.example-popover').click();
            }


        $(document).on('click','#save_pension_update',function()
        {
            $('#save_pension').trigger("click");
        });


        $(document).on('change','#pension_sharing',function()
        {
            // Yes or no ?
            var optionSelected = $("#pension_sharing option:selected").val(),
                  isStandalone = '<?=$consent_type;?>';

            $('#upgrade_msg').hide();
            $('#page_9_submit').attr("disabled",false);
            $('#pension_cont').show();

            if(isStandalone == 2)
            {
                if(optionSelected == 0)
                {
                        // No propeties
                        hide_pension_dropdown();
                }else{
                        // Show the next part of the form
                      
                }
            }

        });


        $(document).on("change",'#how_many_pensions',function()
        {   
            // Get the selected value and how many rows we have
            var selectedVal = $('#how_many_pensions option:selected').val(),
                rowCount    = $('#pension-table-cont tbody >tr').length;


            // if we selected amount for sale is less than the rows throw error
            if(rowCount > selectedVal)
            {
                // Reselect the value from the drpdown
                $('#how_many_pensions').val(rowCount);

                $('#system-message').append('<div class="alert alert-danger">If you wish to decrease the amount of pensions you must delete a pension from the table below and try again.</div>');
                 setTimeout(function()
                 { 
                    // Clear the success div
                    $('#system-message').empty();
                 }, 10000);
            }else{
                $('#pension_btn').trigger("click");
            }
        });


        
        // Date pciker for the DOB
        $("#a_transferor_dob, #b_transferor_dob").datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+0",
        });


        $(document).on("change",'input[name=pension_type]',function()
        {
            manage_sections();
        });


        $(document).ready(function () 
        {
          $('.modal [data-dismiss="modal"]').click(function () {
            
            // If it's a add trigger function
            var is_update = $('#save_pension').attr("data-modalmode");

            if(!is_update)
            {
                clear_modal_data(false);
            }

                location.reload();

          });

            $(".modal").appendTo("body");

            $('.modal').on('shown.bs.modal', function (e) {
                $('html').css('overflow-y', 'hidden');
            });

        });


        $(function () 
        {
          $('.example-popover').popover({
            container: 'body'
          })



          $('#percent_of_pension_other_app,#percent_of_pension_other_res').hide();

          $(document).on('change','#how_pension_paid', function()
          {
                var selectedVal = $('#how_pension_paid option:selected').val();

                if(selectedVal == "other"){
                    $('#percent_of_pension_other_app,#percent_of_pension_other_res').show();
                    $('.other-cont').show();
                }else{
                    $('#percent_of_pension_other_app,#percent_of_pension_other_res').val('').hide();
                    $('.other-cont').hide();
                }
          });



        });


    });
</script>



<?php $this->endSection() ;?>