<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>


<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>


<?php $hide_respondent = ( $page_data[0]['has_contact'] == 0 ) ? TRUE : FALSE;?>

 <style>
    select#app_address_country option:nth-child(1), select#res_address_country option:nth-child(1){
        font-weight:bold;font-size: 16px;
    }
 </style>

<br /><br />
<div class="row">
    <div class="col-8 coff">
        <div class="panel panel-primary">
            <div class="panel-heading"><h2></h2></div>
            <div class="panel-body">

                <?php  if(validation_errors()) : ?>
                        <div class="alert alert-danger">
                            <button class="close" data-dismiss="alert"></button>
                            <?php foreach(validation_errors() as $e) : ?>
                                <?=$e . "<br />"; ?>
                            <?php endforeach;?>   
                        </div>
                <?php endif;?>

                <?php if(session()->has("user_msg_error") ) : ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger">
                                <?=htmlentities(session("user_msg_error"));?>
                            </div>
                        </div>
                    </div>
                <?php endif;?>



                <div id="stage-image">

                    <div class="ff_info_box">
                        <b>BASIC DETAILS</b><br>
                        This section will ask you to complete your basic details; such as your full name and addresses, dates of birth and contact numbers. Please ensure you are using upper and lower case when adding this information.<br><br>
                        <span class="lrg-text">Warning: You will not be able to begin completing this Fact Finder until you have a Divorce Case Number.</span>
                    </div>

                    <?=form_open();?>

                    <?php if($hide_respondent == FALSE || $this->data['res_logged_in'] == FALSE) : ?>


                            <div class="alert alert-info">
                                <?php 
                                   $alert_wording = ($package_code && $package_code == "CBCS" ) ? "Clean Break orders": "Consent Orders";
                                ?>
                                As of April 2022 ALL <?=$alert_wording;?> now go through the same processing centre therefore the Court Name on the documents will show as CTSC (irrespective of which Court managed your divorce)
                            </div>


                            <table class="table table-ff">
                                
                                <tr>

                                    <td width="30%">Please select a court from the dropdown, if the court your looking for isn't in the list please enter it manually in the <b>'Name of Court'</b> field below</td>
                                    <td width="30%">
                                        <select name="court_other" id="court_other" class="form-control">
                                            <option value="">--Select Court--</option>

                                            <option value="CTSC" <?php if(isset($page_data[0]['name_of_court']) && $page_data[0]['name_of_court'] == "CTSC") {echo 'selected';}?> <?php if(isset($_POST['name_of_court']) && $_POST['name_of_court'] == "CTSC" ) {echo "selected";};?> >CTSC Government Online Portal (cases issued with 16 digit case numbers)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Name of Court*<div title="Enter the name of the court here" data-tooltip class="tooltip-icon fa fa-info-circle"></div></td>
                                    <td width="70%"><input type="text" name="name_of_court" class="form-control" id="name_of_court" value="<?php if(isset($page_data[0]['name_of_court'])) {echo $page_data[0]['name_of_court'];}else{echo set_value('name_of_court');}?>"/></td>
                                </tr>
                                <tr>
                                    <td>Divorce Case Number*<div title="Enter your divorce case number here" data-tooltip class="tooltip-icon fa fa-info-circle"></div></td>
                                    <td><input type="text" name="case_number" class="form-control" id="case_number" value="<?php if(isset($page_data[0]['case_number'])) {echo $page_data[0]['case_number'];}else{echo set_value('case_number');}?>"></td>
                                </tr>
                                <tr>
                                    <td>Marriage or Civil Partnership*<div title="Select between marriage or civil partnership" data-tooltip class="tooltip-icon fa fa-info-circle"></div></td>
                                    <td>
                                    <select name="marr_or_civ" id="marr_or_civ" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="marriage" <?php if(isset($page_data[0]['marr_or_civ']) && $page_data[0]['marr_or_civ'] == "marriage") {echo 'selected';}?> <?php if(isset($_POST['marr_or_civ']) && $_POST['marr_or_civ'] == "marriage" ) {echo "selected";};?>>Marriage</option>
                                            <option value="civil" <?php if(isset($page_data[0]['marr_or_civ']) && $page_data[0]['marr_or_civ'] == "civil") {echo 'selected';}?> <?php if(isset($_POST['marr_or_civ']) && $_POST['marr_or_civ'] == "civil" ) {echo "selected";};?>>Civil Partnership</option>

                                            <option value="samesex" <?php if(isset($page_data[0]['marr_or_civ']) && $page_data[0]['marr_or_civ'] == "samesex") {echo 'selected';}?> <?php if(isset($_POST['marr_or_civ']) && $_POST['marr_or_civ'] == "samesex" ) {echo "selected";};?>>Same Sex Marriage</option>
                                    </select>
                                   </td>
                                </tr>
                            </table>

                    
                            <table class="table table-ff">
                                <tr>
                                    <td colspan="2" class="ff-colspan">APPLICANT DETAILS</td>
                                </tr>
                                <tr>
                                    <td width="30%">Surname*</td>
                                    <td width="70%"><input type="text" class="form-control" name="app_surname" id="app_surname" value="<?php if(isset($page_data[0]['app_surname'])) {echo $page_data[0]['app_surname'];}else{echo set_value('app_surname');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Forename*</td>
                                    <td width="70%"><input type="text" class="form-control" name="app_forename" id="app_forename" value="<?php if(isset($page_data[0]['app_forename'])) {echo $page_data[0]['app_forename'];}else{echo set_value('app_forename');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Other Name(s)</td>
                                    <td width="70%"><input type="text" class="form-control" name="app_other_names" id="app_other_names" value="<?php if(isset($page_data[0]['app_other_names'])) {echo $page_data[0]['app_other_names'];}else{echo set_value('app_other_names');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Date of Birth*</td>
                                    <td width="70%"><input required type="text" name="app_dob" class="form-control dpik" id="app_dob" value="<?php if(isset($page_data[0]['app_dob'])) {echo date('d-m-Y',strtotime($page_data[0]['app_dob']));}else{echo set_value('app_dob');}?>"/></td></td>
                                </tr>

                                <tr>
                                    <td width="30%">Gender (male/female)*</td>
                                    <td width="70%">
                                        <select name="app_gender" id="app_gender" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="male" <?php if(isset($page_data[0]['app_gender']) && $page_data[0]['app_gender'] == "male") {echo 'selected';}?> <?php if(isset($_POST['app_gender']) && $_POST['app_gender'] == "male" ) {echo "selected";}?>>Male</option>
                                            <option value="female" <?php if(isset($page_data[0]['app_gender']) && $page_data[0]['app_gender'] == "female") {echo 'selected';}?> <?php if(isset($_POST['app_gender']) && $_POST['app_gender'] == "female" ) {echo "selected";}?>>Female</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="30%">Contact Number*</td>
                                    <td width="70%"><input type="text" class="form-control" name="app_number" id="app_number" value="<?php if(isset($page_data[0]['app_number'])) {echo $page_data[0]['app_number'];}else{echo set_value('app_number');}?>"></td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2" style="text-align: center;font-weight: bold;">Applicant Address</td>
                                </tr>
                                <tr>
                                    <td width="30%">Address Line 1*</td>                            
                                    <td><input type="text" name="app_address_line1" id="app_address_line1" class="form-control" value="<?php if(isset($page_data[0]['app_address_line1'])) {echo $page_data[0]['app_address_line1'];}else{echo set_value('app_address_line1');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Address Line 2</td>                         
                                    <td><input type="text" name="app_address_line2" id="app_address_line2" class="form-control" value="<?php if(isset($page_data[0]['app_address_line2'])) {echo $page_data[0]['app_address_line2'];}else{echo set_value('app_address_line2');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Address Line 3</td>                         
                                    <td><input type="text" name="app_address_line3" id="app_address_line3" class="form-control" value="<?php if(isset($page_data[0]['app_address_line3'])) {echo $page_data[0]['app_address_line3'];}else{echo set_value('app_address_line3');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Town/City*</td>                         
                                    <td><input type="text" name="app_address_town_city" id="app_address_town_city" class="form-control" value="<?php if(isset($page_data[0]['app_address_town_city'])) {echo $page_data[0]['app_address_town_city'];}else{echo set_value('app_address_town_city');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">County</td>                         
                                    <td><input type="text" name="app_address_county" id="app_address_county" class="form-control" value="<?php if(isset($page_data[0]['app_address_county'])) {echo $page_data[0]['app_address_county'];}else{echo set_value('app_address_county');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Postcode*</td>                          
                                    <td><input type="text" name="app_address_postcode" id="app_address_postcode" class="form-control" value="<?php if(isset($page_data[0]['app_address_postcode'])) {echo $page_data[0]['app_address_postcode'];}else{echo set_value('app_address_postcode');}?>"></td>
                                </tr>

                                <tr>
                                    <td width="30%">Country*</td>
                                    <td>
                                        <select name="app_address_country" id="app_address_country" class="form-control">
                                            <option value="">---Select---</option>                       
                                            <?php if(!isset($_POST['app_address_country'])) : ?>
                                                     <?php foreach($countries as $c) : ?>
                                                           <option value="<?=$c['id'];?>" <?php if(!empty($page_data[0]['app_address_country']) && $page_data[0]['app_address_country'] == $c['id']) {echo "selected";} ?> ><?=$c['country'];?></option>
                                                    <?php endforeach;?>
                                            <?php else : ?>
                                                    <?php foreach($countries as $c) : ?>
                                                        <option value="<?=$c['id'];?>" <?php if(isset($_POST['app_address_country']) && $_POST['app_address_country'] == $c['id']) {echo "selected";} ?> ><?=$c['country'];?></option>
                                                    <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </td>
                                </tr>


                                <?php if(!empty($package_code) && $package_code != "CBCS") : ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="ff_info_box">
                                            <b>Property Ownership</b><br>
                                            This section is in relation to the ownership of your property - for example; are you a tenant in the property, do you you live with your parent/friends, or is the property mortgaged
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="30%">Basis of Property Ownership*</td>
                                    <td width="70%">
                                      <select name="app_owned_type" id="app_owned_type" class="form-control">
                                    
                                            <option value="">--Select--</option>

                                            <option value="Mortgaged" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Mortgaged") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Mortgaged" ) {echo "selected";};?>>Mortgaged</option>

                                            <option value="Mortgaged shared with new partner" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Mortgaged shared with new partner") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Mortgaged shared with new partner" ) {echo "selected";};?>>Mortgaged shared with new partner</option>

                                            <option value="Owned Outright" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Owned Outright") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Owned Outright" ) {echo "selected";};?>>Owned Outright</option>

                                            <option value="Rented/Tenant" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Rented/Tenant") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Rented/Tenant" ) {echo "selected";};?>>Rented/Tenant (sole tenant or renting with partner/friends)</option>

                                            <option value="Council Owned" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Council Owned") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Council Owned" ) {echo "selected";};?>>Council Owned</option>

                                            <option value="Living with Friends" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Living with Friends") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Living with Friends" ) {echo "selected";};?>>Living with Friends</option>

                                            <option value="Living with Parents" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "Living with Parents") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "Living with Parents" ) {echo "selected";};?>>Living with Parents</option>

                                            <option value="living with new partner - NOT paying mortgage" <?php if(isset($page_data[0]['app_owned_type']) && $page_data[0]['app_owned_type'] == "living with new partner - NOT paying mortgage") {echo 'selected';}?>  <?php if(isset($_POST['app_owned_type']) && $_POST['app_owned_type'] == "living with new partner - NOT paying mortgage" ) {echo "selected";};?>>Living with new partner - NOT paying mortgage</option>

                                      </select>
                                   </td>
                                </tr>
                                <?php endif;?>

                            </table>

                    <?php endif;?>

                    
                    
                    <?php if($hide_respondent == FALSE || $this->data['res_logged_in'] == TRUE) : ?>

                         <table class="table table-ff">
                            <tr>
                                <td colspan="2" class="ff-colspan">RESPONDENT DETAILS</td>
                            </tr>
                            <tr>
                                <td width="30%">Surname*</td>
                                <td width="70%"><input type="text" class="form-control" name="res_surname" id="res_surname" value="<?php if(isset($page_data[0]['res_surname'])) {echo $page_data[0]['res_surname'];}else{echo set_value('res_surname');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Forename*</td>
                                <td width="70%"><input type="text" class="form-control" name="res_forename" id="res_forename" value="<?php if(isset($page_data[0]['res_forename'])) {echo $page_data[0]['res_forename'];}else{echo set_value('res_forename');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Other Name(s)</td>
                                <td width="70%"><input type="text" class="form-control" name="res_other_names" id="res_other_names" value="<?php if(isset($page_data[0]['res_other_names'])) {echo $page_data[0]['res_other_names'];}else{echo set_value('res_other_names');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Date of Birth*</td>
                                <td width="70%"><input required type="text" name="res_dob" class="form-control dpik" id="res_dob" value="<?php if(isset($page_data[0]['res_dob'])) {echo date('d-m-Y',strtotime($page_data[0]['res_dob']));}else{echo set_value('res_dob');}?>"/></td></td>
                            </tr>

                            <tr>
                                <td width="30%">Gender (male/female)*</td>
                                <td width="70%">
                                    <select name="res_gender" id="res_gender" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="male" <?php if(isset($page_data[0]['res_gender']) && $page_data[0]['res_gender'] == "male") {echo 'selected';}?> <?php if(isset($_POST['res_gender']) && $_POST['res_gender'] == "male" ) {echo "selected";}?>>Male</option>
                                        <option value="female" <?php if(isset($page_data[0]['res_gender']) && $page_data[0]['res_gender'] == "female") {echo 'selected';}?> <?php if(isset($_POST['res_gender']) && $_POST['res_gender'] == "female" ) {echo "selected";}?>>Female</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td width="30%">Contact Number*</td>
                                <td width="70%"><input type="text" class="form-control" name="res_number" id="res_number" value="<?php if(isset($page_data[0]['res_number'])) {echo $page_data[0]['res_number'];}else{echo set_value('res_number');}?>"></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;">Respondent Address</td>
                            </tr>
                            <tr>
                                <td width="30%">Address Line 1*</td>                            
                                <td><input type="text" name="res_address_line1" id="res_address_line1" class="form-control" value="<?php if(isset($page_data[0]['res_address_line1'])) {echo $page_data[0]['res_address_line1'];}else{echo set_value('res_address_line1');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Address Line 2</td>                         
                                <td><input type="text" name="res_address_line2" id="res_address_line2" class="form-control" value="<?php if(isset($page_data[0]['res_address_line2'])) {echo $page_data[0]['res_address_line2'];}else{echo set_value('res_address_line2');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Address Line 3</td>                         
                                <td><input type="text" name="res_address_line3" id="res_address_line3" class="form-control" value="<?php if(isset($page_data[0]['res_address_line3'])) {echo $page_data[0]['res_address_line3'];}else{echo set_value('res_address_line3');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Town/City*</td>                         
                                <td><input type="text" name="res_address_town_city" id="res_address_town_city" class="form-control" value="<?php if(isset($page_data[0]['res_address_town_city'])) {echo $page_data[0]['res_address_town_city'];}else{echo set_value('res_address_town_city');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">County</td>                         
                                <td><input type="text" name="res_address_county" id="res_address_county" class="form-control" value="<?php if(isset($page_data[0]['res_address_county'])) {echo $page_data[0]['res_address_county'];}else{echo set_value('res_address_county');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Postcode*</td>                          
                                <td><input type="text" name="res_address_postcode" id="res_address_postcode" class="form-control" value="<?php if(isset($page_data[0]['res_address_postcode'])) {echo $page_data[0]['res_address_postcode'];}else{echo set_value('res_address_postcode');}?>"></td>
                            </tr>

                            <tr>
                                <td width="30%">Country*</td>
                                <td>
                                    <select name="res_address_country" id="res_address_country" class="form-control">
                                        <option value="">---Select---</option>                  
                                        <?php if(!isset($_POST['res_address_country'])) : ?>
                                                 <?php foreach($countries as $c) : ?>
                                                       <option value="<?=$c['id'];?>" <?php if($page_data[0]['res_address_country'] == $c['id']) {echo "selected";} ?> ><?=$c['country'];?></option>
                                                <?php endforeach;?>
                                        <?php else : ?>
                                                <?php foreach($countries as $c) : ?>
                                                    <option value="<?=$c['id'];?>" <?php if(isset($_POST['res_address_country']) && $_POST['res_address_country'] == $c['id']) {echo "selected";} ?> ><?=$c['country'];?></option>
                                                <?php endforeach;?>
                                        <?php endif;?>
                                    </select>
                                </td>
                            </tr>

                            <?php if(!empty($package_code) && $package_code != "CBCS") : ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="ff_info_box">
                                            <b>Basis of Property Ownership</b><br>
                                            This section is in relation to the ownership of your property - for example; are you a tenant in the property, do you you live with your parent/friends, or is the property mortgaged
                                        </div>
                                    </td>
                                </tr>

                                <tr>


                                    <td width="30%">Basis of Property Ownership*</td>
                                    <td width="70%">
                                    <select name="res_owned_type" id="res_owned_type" class="form-control">

                                            <option value="">--Select--</option>

                                            <option value="Mortgaged" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Mortgaged") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Mortgaged" ) {echo "selected";};?>>Mortgaged</option>

                                            <option value="Mortgaged shared with new partner" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Mortgaged shared with new partner") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Mortgaged shared with new partner" ) {echo "selected";};?>>Mortgaged shared with new partner</option>

                                            <option value="Owned Outright" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Owned Outright") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Owned Outright" ) {echo "selected";};?>>Owned Outright</option>

                                            <option value="Rented/Tenant" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Rented/Tenant") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Rented/Tenant" ) {echo "selected";};?>>Rented/Tenant (sole tenant or renting with partner/friends)</option>

                                            <option value="Council Owned" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Council Owned") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Council Owned" ) {echo "selected";};?>>Council Owned</option>

                                            <option value="Living with Friends" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Living with Friends") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Living with Friends" ) {echo "selected";};?>>Living with Friends</option>

                                            <option value="Living with Parents" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "Living with Parents") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "Living with Parents" ) {echo "selected";};?>>Living with Parents</option>

                                            <option value="living with new partner - NOT paying mortgage" <?php if(isset($page_data[0]['res_owned_type']) && $page_data[0]['res_owned_type'] == "living with new partner - NOT paying mortgage") {echo 'selected';}?>  <?php if(isset($_POST['res_owned_type']) && $_POST['res_owned_type'] == "living with new partner - NOT paying mortgage" ) {echo "selected";};?>>Living with new partner - NOT paying mortgage</option>
                                            
                                    </select>
                                </td>
                                </tr>
                            <?php endif;?>

                        </table>
                    <?php endif;?>


                    <br><br>
                    
                    
                    <div class="coff-nav">
                        <input type="submit" name="page2_submit"  value="Next" class="next-btn btn btn-primary">
                        <a class="prev-btn btn" href="<?=site_url();?>consent-order/consent_order_ff/1">Previous</a>
                    </div>


                    <?=form_close();?>

                    
                </div>

            </div>
        </div>
    </div>
</div>


    <script type="text/javascript">

        $(document).ready(function(){


            if($('#res_dob').length >0 ){

                $('#res_dob').datepicker({
                    dateFormat:'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    maxDate: new Date()  ,
                    yearRange: "-100:+0", // last hundred years
                });
            }

            if($('#app_dob').length >0 ){

                $('#app_dob').datepicker({
                    dateFormat:'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    maxDate: new Date()  ,
                    yearRange: "-100:+0", // last hundred years
                });
             }

            // Validation for marriage type and gender
            $(document).on('change','#app_gender,#marr_or_civ',function(){
                filter_gender();
            });



              var $el = $("select[name=res_address_country]").find('option[value="1"]');
              $("select[name=res_address_country]").find('option[value="1"]').remove();
              $("select[name=res_address_country]").find('option:eq(0)').before($el);  

              var $el = $("select[name=app_address_country]").find('option[value="1"]');
              $("select[name=app_address_country]").find('option[value="1"]').remove();
              $("select[name=app_address_country]").find('option:eq(0)').before($el);  

        });

        function addHyphen () 
        {
            let ele = document.getElementById("case_number");
            ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.

            let finalVal = ele.match(/.{1,4}/g).join('-');
            document.getElementById("case_number").value = finalVal;
        }


        $(document).change('#court_other',function()
        {
            var selectedVal = $('#court_other option:selected').val();
            if(selectedVal != "")
            {
                $('#name_of_court').val(selectedVal);
            }
        });

        $(document).on("keyup",'#case_number', function()
        {
            var selectedVal = $('#court_other option:selected').val();
            if(selectedVal == "CTSC"){addHyphen(elm);}
        });

    </script>

 

<?php $this->endSection() ;?>