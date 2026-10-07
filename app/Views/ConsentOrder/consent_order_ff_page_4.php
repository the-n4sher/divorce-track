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

    #save_data_update{
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

    .err-border{
        border: 2px solid red;
    }

    .modal {
        margin-left: 16.6667%;
    }

    @media only screen and (max-width: 768px) {
        .modal {
            margin-left: 0%;
        }
    }

</style>


<?php

    // Create applicant name
    $app_name = "";
    if($page_data[0]["app_forename"] != "")
    {
        $app_name .= " - ". $page_data[0]["app_forename"] . " ";
        $app_name  .= ( $page_data[0]["app_other_names"] != "" ) ? $page_data[0]["app_other_names"] . " " : "";
        $app_name  .= ( $page_data[0]["app_surname"] != "" ) ? $page_data[0]["app_surname"] . " " : "";
    }

    // Get respondent name
    $res_name = "";
    if($page_data[0]["res_forename"] != "")
    {
        $res_name .= " - ". $page_data[0]["res_forename"] . " ";
        $res_name  .= ( $page_data[0]["res_other_names"] != "" ) ? $page_data[0]["res_other_names"] . " " : "";
        $res_name  .= ( $page_data[0]["res_surname"] != "" ) ? $page_data[0]["res_surname"] . " " : "";
    }
?>

<br /><br />
<div class="row">
    <div class="col-8 coff">
        <div class="panel panel-primary">
            <div class="panel-heading"><h2></h2></div>
            <div class="panel-body">


                <div id="stage-image">

                    <div class="ff_info_box">
                        <p>
                            <h3>PROPERTY DETAILS</h3>
                            If you have a property that you are selling, transferring or otherwise, please include the following details e.g. if the property is subject to a mortgage, who is the mortgage provider? What is the property agreement? What is the agreed valuation of the property?
                        </p>
                    </div>




                    <?php 
                        $table_data = "";
                        $display = ( is_array($user_properties) && count($user_properties) > 0  ) ? "" : "display:none";
                        $selling = false;
                        $count = "";
                        if(is_array($user_properties) && count($user_properties) > 0 )
                        {   
                            // We will use this to prepopulate the properties dropdwn
                            $count = count($user_properties);

                            // If true we will prepopulate the drop down to say yes and how many properties
                            $selling = true;


                            foreach($user_properties as $up)
                            {
                                // Format the address
                                $address  = $up['address_line_1'] . ",";
                                $address  .= ( $up['address_line_2']  != "" ) ? $up['address_line_2'] . ", " : "";
                                $address  .= ( $up['address_line_3']  != "" ) ? $up['address_line_3'] . ", " : "";
                                $address  .= ( $up['county']  != "" ) ? $up['county'] . ", " : "";
                                $address .= $up['town_city'] . ", ";
                                $address .= $up['postcode'];

                                // Get the country
                                $country_name = $consent_model->getCountryName($up['country']);
                                $address .= ", " . $country_name;

                                $table_data .= '';
                                    $table_data .= '<tr>';
                                        $table_data .= '<th scope="row">'.$up['property_number'].'</th>';
                                        $table_data .= '<th>'.$address.'</th>';
                                        $table_data .= '<td>'.$up['property_title'].'</td>';


                                        // If the respondent is loged in, they can only delete THEIR own pensions
                                        if($res_logged_in == TRUE)
                                        {

                                                // On a split application the respondent can't edit or delete
                                                if($up["petitioner_row"] == 1)
                                                {
                                                    $table_data .= '<td><a href="#" onclick="update_modal('.$up['id'].');return false;">View</a></td><td>&nbsp;</td>';

                                                }else{
                                                    $table_data .= '<td><a href="#" onclick="update_modal('.$up['id'].');return false;">Edit</a></td>';
                                                    $table_data .= '<td><a href="#" onclick="remove_property('.$up['id'].');">Delete</a></td>'; 
                                                }

                                          }else{
                                                $table_data .= '<td><a href="#" onclick="update_modal('.$up['id'].');return false;">Edit</a></td>';
                                                $table_data .= '<td><a href="#" onclick="remove_property('.$up['id'].');">Delete</a></td>'; 
                                          }


                                $table_data .= '</tr>';
                            }
                        }
                    ?>      
                            



                    <?=form_open();?>

                        <div class="form-group" style="margin:0px!important;text-align:left!important;">
                            <label for=""> Are you selling or transfering any properties?</label>
                            <select name="selling_properties" id="selling_properties" class="form-control" style="width:50%;">
                                <option value="0">No</option>
                                <option value="1" <?php if($selling == TRUE){echo "selected";}?>>Yes</option>
                            </select>
                        </div><br>

                        <div class="form-group" id="for_sale_cont" style="margin:0px!important;text-align:left!important;<?=$display;?>">
                            <label for="how_many_for_sale">How many properties are you selling or transfering?</label>
                            <select name="how_many_for_sale" id="how_many_for_sale" style="width:50%;" class="form-control">
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
                                    <button type="button" class="btn btn-primary" onclick="add_property();">Add Property Details</button> <br>
                                    <div class="alert alert-warning">Click "Add property Details" to add a new property.</div>
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
                                      Property successfully updated.
                                  </div>
                              </div>
                          </div>
                         <?php endif;?>


                    <div class="alert alert-info" id="conveyancer-info" style="display: none;margin-top:10px;text-align:left;">

                        If you have to deal with property as part of your divorce settlement, you are going to need to instruct a conveyancer. <br /><br />

                            Save time and money by asking us to get quotes for you from our panel of expert solicitors. <br /><br />

                            All we need is a few bits of information about your requirements and we can get quotes to your inbox in a matter of minutes. <br /><br />

                            Call us today on <a href='tel:01793 384029'>01793 384029</a> or email <a href='mailto:sales@divorce-online.co.uk'>sales@divorce-online.co.uk</a>
                    </div>
    
                        <div id="property-table-cont" style="<?=$display;?>">
                            <br><div id="system-message"></div>
                            <br><br>
                            <hr>

                            <table class="table" id="property-table">
                              <thead class="thead-dark"">
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Address</th>

                                  <th scope="col">Property Title</th>
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
                            <button href="#" onclick="validate_submit();return false;" class="next-btn btn btn-primary">Next</button>
                            <a class="prev-btn btn" href="<?=site_url();?>consent-order/consent_order_ff/3">Previous</a>
                        </div>
                    <?=form_close();?>

                </div>

            </div>
        </div>
    </div>
</div>


<?php /* MODAL START*/ ?>


<div class="modal fade" id="propertyModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="propertyModalTitle" aria-hidden="true">
<button href="#" class="btn-lg btn-danger" id="save_data_update">Save</button>

<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="property_number_text"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="prop_num" id="prop_num" value="">



        <div id="section-1">

            <div class="ff_info_box">
                <p>
                    <h3>PROPERTY DETAILS</h3>
                    Please answer all the question below by selecting either YES or NO and supplying the required information.
                </p>
            </div>


            <div id="form-errors-section1"></div>
            <form name="property_details_form">

                <?php 
                    if($res_logged_in == TRUE)
                    {
                        $disabled = "disabled";
                    }else{
                        $disabled = "";
                    }
                ?>

                <div class="form-group">
                    <label for="property_title"><b>Property Title Number *</b> – this can be located on any documentation you received from your conveyancing Solicitor at the point of purchase – if you are unable to locate this you can purchase a copy of the Title Register here - <a href="https://www.gov.uk/search-property-information-land-registry" target="_blank">https://www.gov.uk/search-property-information-land-registry</a></label>
                    <br>
                    <div style="display:none;">The property is unregistered and doesn't have a title number<input type="checkbox" name="" id="noTitle"> </div>
                    <input type="text" name="property_title" id="property_title" <?=$disabled;?> class="form-control" required value="">
                </div>

                <div class="form-group">
                    <label for="address_line_1">Address Line 1</label>
                    <input type="text" name="address_line_1" id="address_line_1" <?=$disabled;?>  class="form-control" required value="">
                </div>

                <div class="form-group">
                    <label for="address_line_2">Address Line 2</label>
                    <input type="text" name="address_line_2" id="address_line_2" <?=$disabled;?>  <?=$disabled;?>  class="form-control" required value="">
                </div>

                <div class="form-group">
                    <label for="address_line_3">Address Line 3</label>
                    <input type="text" name="address_line_3" id="address_line_3" <?=$disabled;?>  class="form-control" required value="">
                </div>

                <div class="form-group">
                    <label for="county">Town/City</label>
                    <input type="text" name="town_city" id="town_city" <?=$disabled;?>  class="form-control" required  value="">
                </div>


                <div class="form-group">
                    <label for="county">County</label>
                    <input type="text" name="county" id="county"  <?=$disabled;?> class="form-control" required  value="">
                </div>

            
                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country"  <?=$disabled;?>  class="form-control" required>
                        <option value="">-- Select Country --</option>
                        <?php foreach($countries AS $c) : ?>
                            <?php
                                $selected = ( $c['id'] == 1 ) ? "selected" : "";
                            ?>
                            <option value="<?=$c['id'];?>" <?=$selected;?>><?=$c['country'];?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" name="postcode" id="postcode"  <?=$disabled;?>  class="form-control" required  value="">
                </div>
                

                <button type="button" id="sec1-submit" class="btn btn-primary" onclick="validate_section(1);">Continue</button>
            </form>
        </div> <!-- Section 1 end -->

        <div id="scroll-here">
            <br>
            <div id="section1-succ"></div>
        </div>
        <div class="hide-section" id="section-2">

            <br>
            <div class="ff_info_box">
                <p>
                    <h3>PROPERTY INFORMATION</h3>
                    Please answer all the question below by selecting either YES or NO and supplying the required information.
                </p>
            </div>

            <!-- Question 1 Form Fields -->
            <div id="q1" class="hide-section">

                <div id="q1-error-div"></div>
                <div class="alert alert-info"><strong>Question 1/4</strong></div>

                <div class="form-group">
                    <label for="is_subjected_mortage">Is this property subject to a Mortgage?</label>
                    <select name="is_subjected_mortage" id="is_subjected_mortage"  <?=$disabled;?> class="form-control" onchange="validate_section(2,1);">
                        <option value="">-- Select --</option>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="form-group q1-conts hide-section">
                    <label for="mortage_provider">Mortage provider</label>
                    <input type="text" name="mortage_provider" id="mortage_provider"  <?=$disabled;?> required class="form-control" value="">
                </div>

                <div class="form-group q1-conts hide-section">
                    <button href="#" onclick="validate_section(2,1);return true;" class="btn btn-primary">Next Question</button>
                </div>

            </div>


            <!-- Question 2 Form Fields -->
            <div id="q2" class="hide-section">

                <div class="ff_info_box">
                    <p>
                        What are the arrangements regarding the former matrimonial home? Are there any other arrangements you may have for any other property, such as an investment property. If you are doing a transfer of property, have you spoken to a mortgage advisor?
                    </p>
                </div>
                
                <div id="q2-error-div"></div>
                <div class="alert alert-info"><strong>Question 2/4</strong></div>
                <div class="form-group">
                    <label for="is_transfering_prop_one_name">What do you intend to do with the former matrimonial home( or any other properties)? Then you either select selling or transferring?</label>
                    <select name="is_transfering_prop_one_name"  <?=$disabled;?> id="is_transfering_prop_one_name" class="form-control" onchange="validate_section(2,2);">
                        <option value="">-- Select --</option>
                        <option value="0">Selling</option>
                        <option value="1">Transferring</option>
                    </select>   
                </div>

                <div class="form-group q2-conts hide-section">
                    <label for="one_name_transfer_details">If so, when do you intend to do so? Are you paying the other party a lump sum for their share in the property? Please enter the transfer details in the box provided below</label>
                    <textarea name="one_name_transfer_details"  <?=$disabled;?> id="one_name_transfer_details" cols="30" rows="10" class="form-control" required ></textarea>
                </div>

                <div class="form-group q2-conts" id="q2-buts">
                    <button href="#" onclick="previous_question(1);return true;" class="btn btn-primary">Previous Question</button> | 
                    <button href="#" onclick="validate_section(2,2);return true;" id="q2next" class="btn btn-primary hide-section">Next Question</button>
                </div>

            </div>

            <!-- Question 3 Form Fields -->
            <div id="q3" class="hide-section">
                
                <div id="q3-error-div"></div>
                <div class="alert alert-info"><strong>Question 3/4</strong></div>
                <div class="form-group">
                    <label for="selling_matrimonial_home">Are you selling the former matrimonial home (or any other properties) and splitting the equity? If so, please submit the information in full below including what % are you each looking to receive of the equity?.</label>
                    <select name="selling_matrimonial_home" id="selling_matrimonial_home"  <?=$disabled;?> class="form-control" onchange="validate_section(2,3);">
                        <option value="">-- Select --</option>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="form-group q3-conts hide-section">
                    <label for="selling_matrimonial_home_details">Please enter the details related to selling your matrimonial home in the box provided below</label>
                    <textarea name="selling_matrimonial_home_details" id="selling_matrimonial_home_details"  <?=$disabled;?> cols="30" rows="10" class="form-control"></textarea>


                    <div class="form-group">
                        <label for="already_transfered_equity_split_app">Equity Split % (Applicant<?=$app_name;?>)</label>
                        <input type="number" name="already_transfered_equity_split_app" <?=$disabled;?> id="already_transfered_equity_split_app" class="form-control" value="" placeholder="50">
                    </div>

                    <div class="form-group">
                        <label for="already_transfered_equity_split_res">Equity Split % (Respondent<?=$res_name;?>)</label>
                        <input type="number" name="already_transfered_equity_split_res" <?=$disabled;?> id="already_transfered_equity_split_res" class="form-control" value=""placeholder="50">
                    </div>


                </div>

                <div class="form-group q3-conts" id="q3-buts">
                    <button href="#" onclick="previous_question(2);return true;" class="btn btn-primary">Previous Question</button> | 
                    <button href="#" onclick="validate_section(2,3);return true;" id="q3next" class="btn btn-primary hide-section">Next Question</button>
                </div>
                
            </div>


            <!-- Question 4 Form Fields -->
            <div id="q4" class="hide-section">
                <div class="alert alert-info"><strong>Question 4/4</strong></div>
                <div class="form-group">
                    <label for="already_transfered">If the property has already been transferred/sold, please provide the details below including the address. If it has already been transferred, we need further information. Please state when the transfer took place, who transferred to whom and the lump sum amount if paid. In the event of a sale, please include the address and the split of the equity.</label>

                    <br>
                    <div class="alert alert-warning">If the property is overseas - the value must be converted to Great British Pounds</div>
                    <div id="q4-error-div"></div>

                    <!-- <select name="already_transfered" id="already_transfered" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select> -->
                </div>


                <div class="form-group q4-conts">
                    <label for="already_transfered_details">Please enter the transfer details in the box provided below</label>
                    <textarea name="already_transfered_details" id="already_transfered_details" <?=$disabled;?> cols="30" rows="10" class="form-control"></textarea>
                </div>

                <div class="form-group q4-conts">
                    <label for="already_transfered_equity_split_app">Equity Split % (Applicant<?=$app_name;?>)</label>
                    <input type="number" name="already_transfered_equity_split_app" <?=$disabled;?> id="already_transfered_equity_split_app" class="form-control" value="" placeholder="50">
                </div>

                <div class="form-group q4-conts">
                    <label for="already_transfered_equity_split_res">Equity Split % (Respondent<?=$res_name;?>)</label>
                    <input type="number" name="already_transfered_equity_split_res" <?=$disabled;?> id="already_transfered_equity_split_res" class="form-control" value=""placeholder="50">
                </div>


                <div>
                    <div class="alert alert-info">Please confirm the approximate date when either party will release the other from the mortgage.</div>
                    <div class="form-group q4-conts">
                            <label for="estimated_transfer_time">Estimated Transfer Time</label>
                            <select name="estimated_transfer_time" id="estimated_transfer_time" required class="form-control">
                                <option value="">-- Select --</option>
                                <option value="1-3 months">1-3 Months</option>
                                <option value="3-6 months">3-6 Months</option>
                                <option value="6+ months">6+ Months</option>
                                <option value="other">Other</option>
                            </select>

                            <div id="estimated_other_container" style="display:none;">
                                <br>
                                <label for="estimated_other">Please use this text box to confirm when you think the release will take place</label>
                                <textarea name="estimated_other" id="estimated_other" class="form-control"></textarea>
                            </div>

                    </div>
                </div>

                
                <div class="form-group">
                    <button href="#" onclick="previous_question(3);return true;" class="btn btn-primary">Previous Question</button> | 
                    <button href="#" id="save_data" data-modalmode="" data-rowid="" onclick="validate_section(2,4);return true;" class="btn btn-primary">Save Details</button>
                </div>


            </div>
            
        </div><!--  Section 2 end -->

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="clear_modal_data(false, false, false);">Close</button>
      </div>
    </div>
  </div>
</div>



<?php /* MODAL END */ ?>



<script>

     function format_money(val)
     {

        var userMoneyInput = val,
            strLen = userMoneyInput.length;

        // This will cover things like 500.00 25.00
        if(strLen <= 6)
        {
            var forTest = new RegExp('^[0-9]{1,3}[\.]{1}[0-9]{2}$');
            if(!forTest.test(userMoneyInput) && userMoneyInput != "0.00")
            {
                return false;
            }

        }else{

            var forTest = new RegExp('^[0-9]{1,4},{1}[0-9\.]{2,4}?(,{1})?([0-9\.]{2,4})[0-9]{2}$');

            if(!forTest.test(userMoneyInput) && userMoneyInput != "0.00")
            {
                return false;
            }
        }



    }


    function previous_question(question_num)
    {
        switch(question_num)
        {
            case 1:
                    $('#is_subjected_mortage').prop('selectedIndex',0);
                    $('#q1').removeClass("hide-section");
                    $('#q2, #q3, #q4').addClass("hide-section");
                break;

            case 2:
                    $('#is_transfering_prop_one_name').prop('selectedIndex',0);
                    $('#q2').removeClass("hide-section");
                    $('#q3, #q4').addClass("hide-section");

                    // Unhide question 2's previous and next buttons
                    $('#q2-buts').show();
                break;

            case 3:
                    $('#selling_matrimonial_home').prop('selectedIndex',0);
                    $('#q3').removeClass("hide-section");
                    $('#q4').addClass("hide-section");

                    // Unhide question 3's previous and next buttons
                    $('#q3-buts').show();
                break;
        }
    }

    function validate_submit()
    {

        // Has the user said yes to selling a property
        var selling_prop_val = $('#selling_properties option:selected').val(),
            redirectUrl = '<?=site_url();?>consent-order/consent_order_ff/5';

        if(selling_prop_val == 1)
        {
            // Get the amount of properties from the dropdown and compare to the table of properties
            var rowCount = $('#property-table-cont tbody >tr').length,
                propsSelected = $('#how_many_for_sale option:selected').val();


            if(propsSelected == rowCount)
            {
                // Redirect as the values match
                window.location.href = ""+redirectUrl+"";
            }else{

                // The amounts dont match
                $('#system-message').append('<div class="alert alert-danger">Error: The number of properties you are selling does not match the information you have supplied. Please check and try again.</div>');
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

    function remove_property(id)
    {

        if(id)
        {
            // We have an ID so we can carry on
            var rowID = parseInt(id),
                user_msg = confirm("Are you sure you want to delete this property?");

            if(user_msg == true)
            {
                $.ajax(
                {
                    type: "POST",
                    url: "<?=site_url();?>consent-order/removeProperty",
                    data: {
                        "id": rowID, 'logged_in':'<?=$this->data['res_logged_in'];?>'
                    },
                    success: function(res)
                    {
                        if(res == "FAILED")
                        {
                            alert("Failed to remove this property (REF: SECERROR)");

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

    function validate_section(section, question_num)
    {

        var errors = [],
            total_errors = 0,
            error_div = $('#form-errors-section1');


        // Clear the error div
        error_div.empty();

        switch(section)
        {
            case 1:
                // Do Validation
                var property_title = $('#propertyModal').find("#property_title").val(),
                    address_line_1 = $('#propertyModal').find("#address_line_1").val(),
                    address_line_2 = $('#propertyModal').find("#address_line_2").val(),
                    address_line_3 = $('#propertyModal').find("#address_line_3").val(),
                    county         = $('#propertyModal').find("#county").val(),
                    town_city      = $('#propertyModal').find("#town_city").val(),                  
                    country        = $('#country option:selected').val(),
                    postcode       = $('#propertyModal').find("#postcode").val();

                // Check the property title
                // Check the property title
                if($.trim(property_title).length < 5)
                {
                    total_errors++;
                    errors.push("Property title is a required field");
                    $('#property_title').addClass("err-border");
                }else{
                    $('#property_title').removeClass("err-border");
                }


                if($.trim(property_title).length > 20)
                {
                    total_errors++;
                    errors.push("Property title is a required field - max characters 20");
                    $('#').addClass("err-border");
                }else{
                    $('#').removeClass("err-border");
                }

                // Check address line 1
                if($.trim(address_line_1).length < 5)
                {
                    total_errors++;
                    errors.push("Address line 1 is required");
                    $('#address_line_1').addClass("err-border");
                }else{
                    $('#address_line_1').removeClass("err-border");
                }

                // Check Country
                if(country == "")
                {
                    total_errors++;
                    errors.push("Country is a required field");
                    $('#county').addClass("err-border");
                }else{
                    $('#county').removeClass("err-border");
                }

                // Check Town/City
                if($.trim(town_city).length < 5)
                {
                    total_errors++;
                    errors.push("Town/City is a required field");
                    $('#town_city').addClass("err-border");
                }else{
                    $('#town_city').removeClass("err-border");
                }

                // Check Postcode
                if($.trim(postcode).length < 5)
                {
                    total_errors++;
                    errors.push("Postcode is a required field");
                    $('#postcode').addClass("err-border");
                }else{
                    $('#postcode').removeClass("err-border");
                }

                // Do we have errors?
                if(total_errors > 0)
                {
                    // Process the errors
                    process_errors(errors, error_div);
                    $('#q1, #section-2').addClass("hide-section");
                    $('.success-msg-title').remove();
                }else{
                    // Validation passed, move onto section 2 questions, close up section 1
                    var output = '<div class="alert alert-success success-msg-title">Thank you for supplying the title and address for this property. Please complete the next set of questions in full so we have all the required information about the property.</div>';
                    $('#section1-succ').html(output);

                    // Show section 2 Q1
                    $('#q1, #section-2').removeClass("hide-section");

                    document.querySelector('#sec1-submit').scrollIntoView({ 
                      behavior: 'smooth' 
                    });
                }

            break;

            case 2:

                // Validate question 1
                if(question_num == 1)
                {

                    // Did the use selct yes or no?
                    var yes_or_no = $('#is_subjected_mortage option:selected').val(),
                        error_div = $('#q1-error-div')
                        total_errors = 0,
                        errors = [];


                    // remove field border errors
                    $(document).removeClass("err-border");

                    // Clear the error div
                    error_div.empty();

                    if(yes_or_no == "")
                    {
                        // User didn't select and option
                        $('#q2').addClass("hide-section");              
                        var output = '<div class="alert alert-danger">Please select either Yes or No from the dropdown provided.</div>';
                        error_div.html(output);

                    }else if(yes_or_no == "0"){
                        // No, Show section 2
                        $('#q1').addClass("hide-section");
                        $('#q2').removeClass("hide-section");
                        $('.q1-conts').addClass("hide-section");

                    }else{

                        // Yes
                        $('.q1-conts').removeClass("hide-section");
                        $('#q2, #q3, #q4').addClass("hide-section");     // Make sure question 2, 3 & 4 are hidden as we need to validate again

                        // Do our validation
                        var morg_provider = $('#mortage_provider').val();


                        // Check we have a morg provider
                        if($.trim(morg_provider.length) < 4)
                        {
                                total_errors++;
                            errors.push("Mortage provider is a required field.");
                            $('#mortage_provider').addClass("err-border");
                        }else{
                            $('#mortage_provider').removeClass("err-border");
                        }

                        // Do we have errors?
                        if(total_errors > 0)
                        {
                            // Process the errors
                            process_errors(errors, error_div);
                        }else{
                            // Close up question 1 and open question 2
                            $('#q1').addClass("hide-section");
                            $('#q2').removeClass("hide-section");
                        }

                    }

                }

                // Validate question 2
                if(question_num == 2)
                {

                    // Did the use selct yes or no?
                    var yes_or_no = $('#is_transfering_prop_one_name option:selected').val(),
                        error_div = $('#q2-error-div'),
                        total_errors = 0,
                        errors = [];

                    // Clear the error div
                    error_div.empty();

                    if(yes_or_no == "")
                    {
                        // User didn't select and option
                        $('#q3').addClass("hide-section");              
                        var output = '<div class="alert alert-danger">Please select either Yes or No from the dropdown provided.</div>';
                        error_div.html(output);

                    }else if(yes_or_no == "0"){
                        // No, Show section 2
                        $('#q2').addClass("hide-section");
                        $('#q3').removeClass("hide-section");
                        $('.q2-conts').addClass("hide-section");
                    }else{
                        // Yes
                        $('.q2-conts').removeClass("hide-section");
                        $('.q2-conts').find("#q2next").removeClass("hide-section");
                        $('#q3, #q4').addClass("hide-section");  // Make sure question 3 & 4 are hidden as we need to validate again

                        // Get the value from the textarea
                        var transfer_deets = $('#one_name_transfer_details').val();

                        if($.trim(transfer_deets.length) < 10)
                        {
                                total_errors++;
                            errors.push("Please provide as much information as possible relating to the transfer.");
                             $('#one_name_transfer_details').addClass("err-border");
                        }else{
                                 $('#one_name_transfer_details').removeClass("err-border");
                        }


                        // Do we have errors?
                        if(total_errors > 0)
                        {
                            process_errors(errors, error_div);
                        }else{
                            // Close up question 1 and open question 2
                            $('#q2').addClass("hide-section");
                            $('#q3').removeClass("hide-section");
                        }

                    }
                }

                // Validate question 3
                if(question_num == 3)
                {
                    // Did the use selct yes or no?
                    var yes_or_no = $('#selling_matrimonial_home option:selected').val(),
                        error_div = $('#q3-error-div'),
                        total_errors = 0,
                        errors = [],
                        equity_app = ( $('#already_transfered_equity_split_app').val().length === 1 ) ? "0" + $('#already_transfered_equity_split_app').val() : $('#already_transfered_equity_split_app').val(),
                         equity_res =  ( $('#already_transfered_equity_split_res').val().length === 1 ) ?  "0" + $('#already_transfered_equity_split_res').val() : $('#already_transfered_equity_split_res').val();

                    // Clear the error div
                    error_div.empty();

                    if(yes_or_no == "")
                    {
                        // User didn't select and option
                        $('#q4').addClass("hide-section");              
                        var output = '<div class="alert alert-danger">Please select either Yes or No from the dropdown provided.</div>';
                        error_div.html(output);

                    }else if(yes_or_no == "0"){
                        // No, Show section 2
                        $('#q3').addClass("hide-section");
                        $('#q4').removeClass("hide-section");
                        $('.q3-conts').addClass("hide-section");
                        $('#already_transfered_details,#already_transfered_equity_split_app,#already_transfered_equity_split_res').show();
                        $('#q3next').text("Next Question");
                    }else{
                        // Yes
                        $('.q3-conts').removeClass("hide-section");
                        $('.q3-conts').find("#q3next").removeClass("hide-section");
                        $('#q4').addClass("hide-section");   // Make sure question 4 is hidden as we need to validate again

                        // The user shouldn't see question 5 if they have answered yes
                        $('#already_transfered_details,#already_transfered_equity_split_app,#already_transfered_equity_split_res').hide();
                        $('#already_transfered_equity_split_app,#already_transfered_equity_split_res').show();
                        $('#q3next').text("Save Data");

                        var get_home_details = $('#selling_matrimonial_home_details').val();

                        if($.trim(get_home_details.length) < 10)
                        {
                                total_errors++;
                            errors.push("Please provide as much information as possible relating to selling your matrimonial home.");
                            $('#selling_matrimonial_home_details').addClass("err-border");
                        }else{
                                $('#selling_matrimonial_home_details').addClass("err-border");
                        }


                        if(equity_app.length >= 1)
                      {
                        if(!$.isNumeric(parseInt(equity_app)) || equity_app == "" || parseInt(equity_app) > 100)
                            {
                                 total_errors++;
                             errors.push("Applicants equity split value must be a number between 0 and 100....");
                              $('#already_transfered_equity_split_app').addClass("err-border");
                            }else{
                                    $('#already_transfered_equity_split_app').removeClass("err-border");
                            }
                      }else{
                         total_errors++;
                         $('#already_transfered_equity_split_app').addClass("err-border");
                           errors.push("Applicants equity split value must be a number between 0 and 100");
                      }


                      if(equity_res.length >= 1)
                      {
                        if(!$.isNumeric(equity_res) || equity_res == "" || parseInt(equity_res) > 100)
                            {
                                 total_errors++;
                             errors.push("Respondents equity split value must be a number between 0 and 100");
                             $('#already_transfered_equity_split_res').addClass("err-border");
                            }else{
                                    $('#already_transfered_equity_split_res').removeClass("err-border");
                            }
                      }else{
                         total_errors++;
                           errors.push("Respondents equity split value must be a number between 0 and 100");
                           $('#already_transfered_equity_split_res').addClass("err-border");
                      }

                      // We need to check the numbers make 100%
                      var equity_app = $('#already_transfered_equity_split_app').val(),
                            equity_res =  $('#already_transfered_equity_split_res').val();

                      if(equity_res.length >= 1 && equity_app.length >= 1 )
                      {

                        var cal1 = parseInt(equity_app);
                        var cal2 = parseInt(equity_res);
                        var sumOfBoth = cal1 + cal2;

                        if(sumOfBoth != 100)
                        {
                                total_errors++;
                                errors.push("The percentages added do not add up to 100%, please check and try again.");
                                $('#already_transfered_equity_split_res').addClass("err-border");
                                $('#already_transfered_equity_split_app').addClass("err-border");
                        }else{
                                $('#already_transfered_equity_split_res').removeClass("err-border");
                                $('#already_transfered_equity_split_app').removeClass("err-border");
                        }

                      }

                        // Do we have errors?
                        if(total_errors > 0)
                        {
                            process_errors(errors, error_div);
                        }else{
                            // Close up question 1 and open question 2
                            $('#q3').addClass("hide-section");
                            // Save the data

                            var is_update = $('#save_data').attr("data-modalmode"),
                                    update_id = $('#save_data').attr("data-rowid");

                            var insert_row = ( is_update != "update") ? 1 : 0,
                                    update_row = ( is_update == "update") ? 1 : 0;
                                  update_property_db(insert_row, update_row, true);
                        }
                    }
                }

                // Validate question 4
                if(question_num == 4)
                {

                    // Clear the error div
                    error_div.empty();

                    // This section will save the details to the database
                    var yes_or_no = 1,
                        error_div = $('#q4-error-div'),
                        total_errors = 0,
                        errors = [],
                        is_update = $('#save_data').attr("data-modalmode"),
                        update_id = $('#save_data').attr("data-rowid");

                    $('.q4-conts').removeClass("hide-section");

                    // Get the details
                    var already_transfered_deets = $('#already_transfered_details').val(),
                          equity_app = ( $('#q4').find('#already_transfered_equity_split_app').val().length === 1 ) ? "0" + $('#q4').find('#already_transfered_equity_split_app') : $('#q4').find('#already_transfered_equity_split_app').val(),
                          equity_res =  ( $('#q4').find("#already_transfered_equity_split_res").val().length === 1 ) ?  "0" + $('#q4').find("#already_transfered_equity_split_res") : $('#q4').find('#already_transfered_equity_split_res').val(),
                          est_transfer_time = $('#estimated_transfer_time option:selected').val(),
                          est_transfer_other =  $('#estimated_other').val();


                        // This validation should only run if question 3 is NO
                        var q3_answer = $('#selling_matrimonial_home option:selected').val();

                        // Run the Validation, if successful we need to add the details to the database and add a table row using the update_property_db
                        if($.trim(already_transfered_deets.length) < 10 && q3_answer == 0)
                        {
                                total_errors++;
                            errors.push("Please provide as much information as possible relating to the transfer.");
                            $('#already_transfered_details').addClass("err-border");
                        }else{
                              $('#already_transfered_details').removeClass("err-border");
                        }


                        if(q3_answer == 0)
                        {
                              if(equity_app.length >= 1)
                              {
                                if(!$.isNumeric(parseInt(equity_app)) || equity_app == "" || parseInt(equity_app) > 100)
                                    {
                                         total_errors++;
                                     errors.push("Applicants equity split value must be a number between 0 and 100....");
                                      $('#q4').find('#already_transfered_equity_split_app').addClass("err-border");
                                    }else{
                                            $('#q4').find('#already_transfered_equity_split_app').removeClass("err-border");
                                    }
                              }else{
                                 total_errors++;
                                 $('#q4').find('#already_transfered_equity_split_app').addClass("err-border");
                                   errors.push("Applicants equity split value must be a number between 0 and 100");
                              }


                             if(equity_res.length >= 1)
                             {
                                if(!$.isNumeric(equity_res) || equity_res == "" || parseInt(equity_res) > 100)
                                    {
                                         total_errors++;
                                     errors.push("Respondents equity split value must be a number between 0 and 100");
                                     $('#q4').find('#already_transfered_equity_split_res').addClass("err-border");
                                    }else{
                                            $('#q4').find('#already_transfered_equity_split_res').removeClass("err-border");
                                    }
                              }else{
                                 total_errors++;
                                   errors.push("Respondents equity split value must be a number between 0 and 100");
                                   $('#q4').find('#already_transfered_equity_split_res').addClass("err-border");
                              }

                              // We need to check the numbers make 100%
                              var equity_app = $("#q4").find('#already_transfered_equity_split_app').val(),
                                    equity_res =  $("#q4").find('#already_transfered_equity_split_res').val();

                              if(equity_res.length >= 1 && equity_app.length >= 1 )
                              {

                                var cal1 = parseInt(equity_app);
                                var cal2 = parseInt(equity_res);
                                var sumOfBoth = cal1 + cal2;

                                if(sumOfBoth != 100)
                                {
                                        total_errors++;
                                        errors.push("The percentages added do not add up to 100%, please check and try again.");
                                        $('#q4').find('#already_transfered_equity_split_res').addClass("err-border");
                                        $('#q4').find('#already_transfered_equity_split_app').addClass("err-border");
                                }else{
                                        $('#q4').find('#already_transfered_equity_split_res').removeClass("err-border");
                                        $('#q4').find('#already_transfered_equity_split_app').removeClass("err-border");
                                }

                              }
                            
                                  
                                // make sure that we have an estmated transfer time selected
                                if(est_transfer_time == "" && est_transfer_other == "")
                                {
                                        total_errors++;
                                    errors.push("Estimated transfer time is a required field.");
                                    $('#estimated_transfer_time').addClass("err-border");
                                }else{
                                      $('#estimated_transfer_time').removeClass("err-border");
                                }


                                if(est_transfer_time != "" && est_transfer_time == "other")
                                {
                                    if($.trim(est_transfer_other.length) < 10)
                                    {
                                        total_errors++;
                                    errors.push("Please confirm when you think the release will take place.");
                                    $('#estimated_other').addClass("err-border");
                                    }else{
                                      $('#estimated_other').removeClass("err-border");
                                    }

                                }
                        }
                        

                        // Do we have errors?
                        if(total_errors > 0)
                        {
                            process_errors(errors, error_div);
                        }else{
                            // Save the data
                            var insert_row = ( is_update != "update") ? 1 : 0,
                                    update_row = ( is_update == "update") ? 1 : 0;
                                  update_property_db(insert_row, update_row, false);
                        }
            }
                
        }


    }

    function update_modal(id)
    {
        var rowID = parseInt(id);

        // Make sure the save button is shown
        $('#save_data_update').show();

        // First thing we need to do is fetch the data from the db
        $.ajax(
        {
            type: "POST",
                url: "<?=site_url();?>consent-order/fetchPropertyData",
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

                        if(is_split_app == true && res_logged_in == true && petitioner_row == 1)
                        {

                            // Remove the save button
                            $('#save_data').remove();
                        }


                        // if split and applicant logged in they cant save  respondents data
                        if(is_split_app == true && res_logged_in == false && respondent_row == 1)
                        {

                            // Remove the save button
                            $('#save_data').remove();
                        }


                        // Set the Modal mode to update
                        $('#save_data').attr("data-modalmode","update");
                        $('#save_data').attr("data-rowid",rowID);

                        // Repopulate the modal before opening it
                        $('#property_title').val(res.property_title);
                        $('#address_line_1').val(res.address_line_1);
                        $('#address_line_2').val(res.address_line_2);
                        $('#address_line_3').val(res.address_line_3);
                        $('#town_city').val(res.town_city);
                        $('#county').val(res.county);
                        $('#country').val(res.country);
                        $('#postcode').val(res.postcode);


                        $('#q1, #q2, #q3, #q4').removeClass("hide-section");

                        // Q1
                        $('#is_subjected_mortage').val(res.is_subjected_mortage);
                        if(res.is_subjected_mortage == 1)
                        {
                            // Show the fields
                            $('#mortage_provider').val(res.mortage_provider);
                            $('.q1-conts').removeClass("hide-section");

                        }else{
                            $('#q2').removeClass("hide-section");
                        }


                        // Q2
                        $('#is_transfering_prop_one_name').val(res.is_transfering_prop_one_name);
                        if(res.is_transfering_prop_one_name == 1)
                        {
                            // Show the fields
                            $('#one_name_transfer_details').val(res.one_name_transfer_details);
                            $('.q2-conts').removeClass("hide-section");

                        }else{
                            $('#q3').removeClass("hide-section");
                        }



                        // Q3
                        $('#selling_matrimonial_home').val(res.selling_matrimonial_home);
                        if(res.selling_matrimonial_home == 1)
                        {
                            // Show the fields
                            $('#selling_matrimonial_home_details').val(res.selling_matrimonial_home_details);
                            $('.q3-conts').removeClass("hide-section");
                            $('.q4-conts').addClass("hide-section");
                            $('#q4').addClass("hide-section");
                            $('#q3-buts').append('<button href="#" id="save_data" data-modalmode="update" data-rowid="'+rowID+'" onclick="validate_section(2,3);return true;" class="btn btn-primary">Save Data</button>');

                        }else{
                            $('.q3-conts').addClass("hide-section");
                            $('.q4-conts').removeClass("hide-section");
                            $('#q4').removeClass("hide-section");
                            $('.added_save').remove();
                            
                        }


                        // Q4
                        $('#already_transfered').val(res.already_transfered);
                        if(res.already_transfered == 1)
                        {
                            // Show the fields
                            $('#already_transfered_details').val(res.already_transfered_details);
                            $('#already_transfered_equity_split_app').val(res.already_transfered_equity_split_app);
                            $('#already_transfered_equity_split_res').val(res.already_transfered_equity_split_res);
                            $('.q4-conts').removeClass("hide-section");
                        }

                        $('#estimated_transfer_time').val(res.estimated_transfer_time);

                        if(res.estimated_transfer_time == "other")
                        {
                            $('#estimated_other_container').show();
                            $('#estimated_other').val(res.estimated_other);
                        }


                        // Open Modal
                        $('#propertyModal').modal('show');


                    }
                }
        });
    }


    function update_property_db(insert, update, isFromQ3)
    {

        // Check if the user is happy and wants to proceed
        var confirm_msg = confirm("Press 'OK' to confirm property details or cancel to make any changes.");

        // We only process once the user confirms
        if(confirm_msg == true)
        {

            // Update the database, if success add a new table row
            var table_container =    $('#property-table-cont'),
                property_title  =    $('#propertyModal').find("#property_title").val(),
                    address_line_1  =    $('#propertyModal').find("#address_line_1").val(),
                    address_line_2  =    $('#propertyModal').find("#address_line_2").val(),
                    address_line_3  =    $('#propertyModal').find("#address_line_3").val(),
                    county          =    $('#propertyModal').find("#county").val(),
                    town_city       =    $('#propertyModal').find("#town_city").val(),
                    country         =    $('#country option:selected').val(),
                    postcode        =    $('#propertyModal').find("#postcode").val(),
                    is_subjected_mortage = ( $('#is_subjected_mortage option:selected').val() == "" || $('#is_subjected_mortage option:selected').val() == "0"  ) ? 0 : 1,
                    mortage_provider =   $('#mortage_provider').val(),
                    is_transfering_prop_one_name = ( $('#is_transfering_prop_one_name option:selected').val() == "" || $('#is_transfering_prop_one_name option:selected').val() == "0"  ) ? 0 : 1,
                    one_name_trans =     $('#one_name_transfer_details').val(),
                    selling_matrimonial_home = ( $('#selling_matrimonial_home option:selected').val() == "" || $('#selling_matrimonial_home option:selected').val() == "0"  ) ? 0 : 1,
                    matt_home_details =  $('#selling_matrimonial_home_details').val(),
                    already_transfered = ( $('#already_transfered option:selected').val() == "" || $('#already_transfered option:selected').val() == "0"  ) ? 0 : 1,
                    transfered_details = $('#already_transfered_details').val();

                    if(isFromQ3 == false)
                    {
                        var equity_app = $('#q4').find('#already_transfered_equity_split_app').val(),
                              equity_res = $('#q4').find('#already_transfered_equity_split_res').val();
                    }else{
                        var equity_app = $('#q3').find('#already_transfered_equity_split_app').val(),
                              equity_res = $('#q3').find('#already_transfered_equity_split_res').val();
                    }

                    var property_number =    $('#prop_num').val(),
                    est_transfer_time = $('#estimated_transfer_time option:selected').val(),
                    est_transfer_other = $('#estimated_other').val(),
                    rowid           =    $('#save_data').attr("data-rowid");

            // Do ajax call
            $.ajax(
            {
                type: "POST",
                url: "<?=site_url();?>consent-order/addPropertyCo",
                data: {
                    "is_update": update,
                    "rowid": rowid,
                    "property_title": property_title,
                    "address_line_1": address_line_1,
                    "address_line_2": address_line_2,
                    "address_line_3": address_line_3,
                    "county": county,
                    "town_city": town_city,
                    "country": country,
                    "postcode": postcode,
                    "is_subjected_mortage": is_subjected_mortage,
                    "mortage_provider": mortage_provider,
                    "is_transfering_prop_one_name" : is_transfering_prop_one_name,
                    "one_name_transfer_details": one_name_trans,
                    "selling_matrimonial_home": selling_matrimonial_home,
                    "selling_matrimonial_home_details": matt_home_details,
                    "already_transfered": already_transfered,
                    "already_transfered_details": transfered_details,
                    "already_transfered_equity_split_app": equity_app,
                    "already_transfered_equity_split_res": equity_res,
                    "property_number": property_number,
                    "estimated_transfer_time": est_transfer_time,
                    "estimated_other": est_transfer_other
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

        }else{
            // If the user clicks cancel we want to reshow the question 4 divs and fields
            $('#already_transfered_details,#already_transfered_equity_split_app,#already_transfered_equity_split_res').show();
            $('#q3next').text("Save Data");
        }
    }

    function clear_modal_data(from_script, update, simple_clear)
    {   
        //Make sure the user understands this  will remove the data
        if(from_script == false && simple_clear == false)
        {

            var prompt = confirm("Are you sure you want to close this popup?\n\nClosing this popup will permanently delete any data you added/updated for this property.");
            if(prompt == true)
            {
                reset_form();
                location.reload();
            }

        }else if(simple_clear == true){
            reset_form();
            location.reload();
        }else{

            reset_form();
            if(update == true)
            {
                // Set flash data and refresh page
                var redirectUrl = '<?=site_url()?>consent-order/consent_order_ff/4?up=1';
                window.location.href = redirectUrl;
                
                
            }else{
                $('#system-message').append('<div class="alert alert-success">Property successfully added to the system.</div>');
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
        $('#section-2, #q1, #q2, #q3, #q4').addClass("hide-section");

        // Clear the modal title
        $('#property_number_text').html("");

        // Clear the row count hidden field
        $('#prop_num').val('');

        // Close the model
        $('#propertyModal').modal('hide');

        // Clear the modal mode
        $('#save_data').attr("data-modalmode","");
        $('#save_data').attr("data-rowid","");

        // Reset the fields
        $('#property_title').val('');
        $('#address_line_1, #address_line_2, #address_line_3').val('');
        $('#county').val('');
        $('#town_city').val('');
        $('#postcode').val('');
        $('#mortage_provider').val('');
        $('#already_transfered_details').val('');
        $('#already_transfered_equity_split_app').val('');
        $('#already_transfered_equity_split_res').val('');
        $('#selling_matrimonial_home_details').val('');

        //Reset all the dropdowns
        $('#is_subjected_mortage, #is_transfering_prop_one_name, #selling_matrimonial_home, #already_transfered').prop('selectedIndex',0);

    }


    function add_property()
    {

        // Make sure the save button is hidden
        $('#save_data_update').hide();

        // We need to find out how many rows we have in the table and +1 to the row counter as this is what we will use in the database
        var rowCount = ( $('#property-table-cont tbody >tr').length == 0 ) ? 1 : $('#property-table-cont tbody >tr').length  + 1;

        // Clear the modal mode
        $('#save_data').attr("data-modalmode","");
        $('#save_data').attr("data-rowid","");

        // We need to check if the number of rows would be greater than the dropdown value
        var current_prop_count = $('#how_many_for_sale option:selected').val();

        if(current_prop_count < rowCount)
        {
            // Close the modal and show error
            $('#system-message').append('<div class="alert alert-danger">Please increase the amount of propeties you are selling from the dropdown provided to add an extra property.</div>');
             setTimeout(function()
             { 
                // Clear the success div
                $('#system-message').empty();
             }, 10000);

            // Close the model
            $('#propertyModal').modal('hide');

        }else{          
            //Update the dynamic content
            $('#propertyModal').find("#prop_num").val(rowCount);
            $('#propertyModal').find("#property_number_text").text("Adding Information For Property "+rowCount+"");

            $('#propertyModal').modal('show');
        }

        
    }


    function hide_property_dropdown()
    {

        // Prompt user
        var user_msg = confirm("Please press 'OK' to confirm you wish to remove all property data, this information can NOT be recovered once deleted.");

        if(user_msg == true)
        {
            // Delete data from database
            $.ajax(
            {
                type: "POST",
                url: "<?=site_url();?>account/delete_prop_data",
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
                        $('#property-table').empty().hide();

                        // Show success message
                        $('#system-message').append('<div class="alert alert-success">Property information has been successfully removed.</div>');
                         setTimeout(function()
                         { 
                            // Clear the success div
                            $('#system-message').empty();
                         }, 8000);

                         // reset the dropdown
                         $('#how_many_for_sale').prop('selectedIndex',0);

                         // Hide the property div
                         $('#for_sale_cont').addClass("hide-section");
                    }

                }
            });
        }
            

    }

    $(document).ready(function()
    {

        $(document).on('change','#noTitle', function()
        {
                var isChecked = ( $('#noTitle').is(":checked") == true ) ? 1 : 0;
                if(isChecked == 1)
                {
                    $('#property_title').val("No property title");
                }else{

                    // We only want to set the value to blank if the value is no property title
                    var currentVal = $('#property_title').val();
                    if(currentVal == "No property title")
                    {
                        $('#property_title').val("");
                    }
                }
        });

        $(document).on('click','#save_data_update',function()
        {
            $('#save_data').trigger("click");
        });


        $(document).on('change','#selling_properties',function()
        {
            // Yes or no ?
            var optionSelected = $("#selling_properties option:selected").val();
            if(optionSelected == 0)
            {
                // No propeties
                hide_property_dropdown();
            }else{
                // Show the next part of the form
                $('#for_sale_cont').show();
            }
        });


        $(document).on("change",'#how_many_for_sale',function()
        {   
            // Get the selected value and how many rows we have
            var selectedVal = $('#how_many_for_sale option:selected').val(),
                rowCount    = $('#property-table-cont tbody >tr').length;


            // if we selected amount for sale is less than the rows throw error
            if(rowCount > selectedVal)
            {
                // Reselect the value from the drpdown
                $('#how_many_for_sale').val(rowCount);

                $('#system-message').append('<div class="alert alert-danger">If you wish to decrease the amount of properties you are selling you must delete a property from the table below and try again.</div>');
                 setTimeout(function()
                 { 
                    // Clear the success div
                    $('#system-message').empty();
                 }, 10000);
            }
        });


        


        $(document).on("change",'#already_transfered',function()
        {   
            // Get the selected value and how many rows we have
            var selectedVal = $('#already_transfered option:selected').val();

            if(selectedVal == 1)
            {
                $('.q4-conts').removeClass("hide-section");

            }else if(selectedVal == 0){

                $('.q4-conts').addClass("hide-section");
            }else{
                // Nothing to do
            }
        });


        $(document).ready(function () 
        {
          $('.modal [data-dismiss="modal"]').click(function () {
            
            // If it's a add trigger function
            var is_update = $('#save_data').attr("data-modalmode");

            if(!is_update)
            {
                clear_modal_data(false, false, false);
            }

          });


          $(document).on("change",'#estimated_transfer_time',function(){

            var dropdown_val = $("#estimated_transfer_time option:selected").val();
            if(dropdown_val == "other"){
                $('#estimated_other_container').show();
            }else{
                $('#estimated_other_container').hide();
                $('#estimated_other').val('');
            }

          });





        });


        $(".modal").on("hidden.bs.modal", function(){
           clear_modal_data(false,false,false);
        });

        $(".modal").appendTo("body");
        
        $('.modal').on('shown.bs.modal', function (e) {
            $('html').css('overflow-y', 'hidden');
        });

    });

    function clean_number_string($elm){

      $($elm).on('input', function() {
      var c = this.selectionStart,
          r = /[^0-9-\.,]/gi,
          v = $(this).val();
      if(r.test(v)) {
        $(this).val(v.replace(r, ''));
        c--;
      }
      this.setSelectionRange(c, c);
    });
    }


    check_num_of_properties();
    function check_num_of_properties()
    {

        $nums = $('#selling_properties option:selected').val();

        if($nums == 1)
        {
            $('#conveyancer-info').show();
        }else{
            $('#conveyancer-info').hide();
        }
    }

</script>



<?php $this->endSection() ;?>