<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>


<br /><br />
<div class="row">
	<div class="col-8">

        <div class="panel panel-primary">
		<div class="panel-heading"><h1>My Account</h1></div>
		<div class="panel-body">

                    <?php 

                         if(session()->has("ERR")){
                                echo ' <div class="alert alert-danger">';
                                        echo session("ERR");
                                echo '</div>'; 
                        }         
                 
                    
                        if(session()->has("SUCC") != ""){
                                echo ' <div class="alert alert-success">';
                                        echo session("SUCC");
                                echo '</div>'; 
                        } 

                        if (session()->has("errors") ){
        
                            echo ' <div class="alert alert-danger">
                                        <button class="close" data-dismiss="alert"></button>';
                                        foreach(session("errors") as $e){
                                            echo $e . "<br />";
                                        }
                            echo '</div>';                               
                        }
                ?>

		     
        		 <?=form_open('account/updateMyAccount',array('role' => 'form'));?>


                    <input type="hidden" name="split_application" value="<?=$split_application;?>">

                    <?php if($split_application === false) : ?>
                        <div class="control-group form-group">
                            <div class="controls">
                                <label for="UserEmail">Email Address: <span class="required">*</span></label>
                                <input type="email" name="UserEmail" class="form-control" id="UserEmail" 
                                value="<?php if(!isset($_POST['UserEmail']) && $user_data['UserEmail'] != "") { echo trim(esc($user_data['UserEmail']));}elseif(isset($_POST['UserEmail'])){echo htmlentities($_POST['UserEmail']);} ?>">
                            </div>
                        </div>

                    <?php else : ?>

                        <?php  
                            // because this is a split application and the email address is example@hotmail.com, example2@hotmail.com., the email address needs to be split up so each part of the email address can be updated.

                            if( isset($user_data['UserEmail']) && !empty($user_data['UserEmail']) )
                            {
                                $parts = explode(",", $user_data['UserEmail']);
                                $user_data['UserEmail'] = $parts[0];
                            }

                        ?>


                        <div class="control-group form-group">
                            <div class="controls">
                                <label for="UserEmail">Email Address: <span class="required">*</span></label>
                                <input type="email" name="UserEmail" class="form-control" id="UserEmail" 
                                value="<?php if(!isset($_POST['UserEmail']) && $user_data['UserEmail'] != "") { echo trim(esc($user_data['UserEmail']));}elseif(isset($_POST['UserEmail'])){echo esc(old('UserEmail'));} ?>">
                            </div>
                        </div>


                         <div class="control-group form-group">
                            <div class="controls">
                                <label for="RespondentEmail">Respondent Email Address: <span class="required">*</span></label>
                                <input type="email" name="RespondentEmail" class="form-control" id="RespondentEmail" 
                                value="<?php if(!isset($_POST['RespondentEmail']) && $user_data['RespondentEmail'] != "") { echo trim(esc($user_data['RespondentEmail']));}elseif(isset($_POST['RespondentEmail'])){echo esc(old('RespondentEmail'));} ?>">
                            </div>
                        </div>

                    <?php endif;?>


                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="TelNum">Contact Number:</label>
                            <input type="text" name="TelNum" class="form-control" id="TelNum" value="<?php if(!isset($_POST['TelNum']) && $user_data['TelNum'] != "") { echo trim(esc($user_data['TelNum']));}elseif(isset($_POST['TelNum'])){echo esc(old('TelNum'));} ?>">
                        </div>
                    </div>
                    <hr>

        			<div class="control-group form-group">
                        <div class="controls">
                          	<label for="Forename">Forename: <span class="required">*</span></label>
        					<input type="text" name="Forename" class="form-control" id="Forename" value="<?php if(!isset($_POST['Forename']) && $user_data['Forename'] != "") { echo trim(esc($user_data['Forename']));}elseif(isset($_POST['Forename'])){echo esc(old('Forename'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Othernames">Middle Name:</label>
                            <input type="text" name="Othernames" class="form-control" id="Othernames" value="<?php if(!isset($_POST['Othernames']) && $user_data['Othernames'] != "") { echo trim(esc($user_data['Othernames']));}elseif(isset($_POST['Othernames'])){echo esc(old('Othernames'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Surname">Surname: <span class="required">*</span></label>
                            <input type="text" name="Surname" class="form-control" id="Surname" value="<?php if(!isset($_POST['Surname']) && $user_data['Surname'] != "") { echo trim(esc($user_data['Surname']));}elseif(isset($_POST['Surname'])){echo esc(old('Surname'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Address1">Address Line 1: <span class="required">*</span></label>
                            <input type="text" name="Address1" class="form-control" id="Address1" value="<?php if(!isset($_POST['Address1']) && $user_data['Address1'] != "") { echo trim(esc($user_data['Address1']));}elseif(isset($_POST['Address1'])){echo esc(old('Address1'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Address2">Address Line 2:</label>
                            <input type="text" name="Address2" class="form-control" id="Address2" value="<?php if(!isset($_POST['Address2']) && $user_data['Address2'] != "") { echo trim(esc($user_data['Address2']));}elseif(isset($_POST['Address2'])){echo esc(old('Address2'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Address3">Address Line 3:</label>
                            <input type="text" name="Address3" class="form-control" id="Address3" value="<?php if(!isset($_POST['Address3']) && $user_data['Address3'] != "") { echo trim(esc($user_data['Address3']));}elseif(isset($_POST['Address3'])){echo esc(old('Address3'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Town">Town/City: <span class="required">*</span></label>
                            <input type="text" name="Town" class="form-control" id="Town" value="<?php if(!isset($_POST['Town']) && $user_data['Town'] != "") { echo trim(esc($user_data['Town']));}elseif(isset($_POST['Town'])){echo esc(old('Town'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="County">County:</label>
                            <input type="text" name="County" class="form-control" id="County" value="<?php if(!isset($_POST['County']) && $user_data['County'] != "") { echo trim(esc($user_data['County']));}elseif(isset($_POST['County'])){echo esc(old('County'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="Postcode">Postcode: <span class="required">*</span></label>
                            <input type="text" name="Postcode" class="form-control" id="Postcode" value="<?php if(!isset($_POST['Postcode']) && $user_data['Postcode'] != "") { echo trim(esc($user_data['Postcode']));}elseif(isset($_POST['Postcode'])){echo esc(old('Postcode'));} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                           <label for="CountryId">Country: <span class="required">*</span></label>
                           <select name="CountryId" id="CountryId" class="form-control">
                               <option value="">--Please Select--</option>
                               <?php if(is_array($countries) && count($countries) > 0):?>

                                    <?php if(!isset($_POST['CountryId'])) : ?>
                                         <?php foreach($countries as $c) : ?>
                                                <option value="<?=$c['id'];?>" <?php if($user_data['CountryId'] == $c['id']) {echo "selected";} ?> ><?=$c['country'];?></option>
                                        <?php endforeach;?>
                                    <?php else : ?>
                                        <?php foreach($countries as $c) : ?>
                                                <option value="<?=$c['id'];?>" <?php if(isset($_POST['CountryId']) && $_POST['CountryId'] == $c['id']) {echo "selected";} ?> ><?=$c['country'];?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                               <?php endif;?>
                           </select>
                        </div>
                    </div>

                    <hr> 

                     <div class="control-group form-group">
                        <div class="controls">
                            <label for="Dob">DOB: <span class="required">*</span></label>
                            <input type="text" name="Dob" class="form-control" id="myaccount-Dob" value="<?php if(!isset($_POST['Dob']) && $user_data['Dob'] != "") { echo trim(date("d-m-Y",strtotime(esc($user_data['Dob']))));}elseif(isset($_POST['Dob'])){echo htmlentities($_POST['Dob']);} ?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                           <label for="SecertQuestion">Secret Question: <span class="required">*</span></label>
                           <select name="SecertQuestion" id="SecertQuestion" class="form-control">
                               <option value="">--Please Select--</option>
                               <?php if(is_array($secret_questions) && count($secret_questions) > 0):?>
                                   
                                    <?php if(!isset($_POST['SecertQuestion'])) : ?>
                                         <?php foreach($secret_questions as $sq) : ?>
                                                <option value="<?=$sq['id'];?>" <?php if($user_data['SecertQuestion'] == $sq['id']) {echo "selected";} ?> ><?=$sq['question'];?></option>
                                        <?php endforeach;?>
                                    <?php else : ?>
                                        <?php foreach($secret_questions as $sq) : ?>
                                                <option value="<?=$sq['id'];?>" <?php if(isset($_POST['SecertQuestion']) && $_POST['SecertQuestion'] == $sq['id']) {echo "selected";} ?> ><?=$sq['question'];?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                    

                               <?php endif;?>
                           </select>
                        </div>
                    </div>

                     <div class="control-group form-group">
                        <div class="controls">
                            <label for="SecertAnswer">Secret Answer: <span class="required">*</span></label>
                            <input type="password" autocomplete="new-password" name="SecertAnswer" data-lpignore="true" class="form-control" id="SecertAnswer" value="<?php if(!isset($_POST['SecertAnswer']) && $user_data['SecertAnswer'] != "") { echo trim($user_data['SecertAnswer']);}elseif(isset($_POST['SecertAnswer'])){echo htmlentities($_POST['SecertAnswer']);} ?>">
                        </div>
                         <b>Show Secret Answer:</b> <input type="checkbox" name="showSecretAnswer" id="showSecretAnswer">
                    </div>


                    <hr>
                        <h2>Marketing Preferences</h2>
                        <p>Please select how you would like to receive marketing material from us, please leave blank if you don't wish to receive any marketing material.</p>

                        <input type="checkbox" name="email_optin" id="email_optin" value="1" <?php if(!isset($_POST['email_optin']) && $user_data['email_optin'] == "1") { echo "checked";}elseif(isset($_POST['email_optin']) == 1 && $_POST['email_optin'] == 1){echo "selected";} ?>> Email<br>

                        <input type="checkbox" name="sms_optin" id="sms_optin" value="1" <?php if(!isset($_POST['sms_optin']) && $user_data['sms_optin'] == 1) { echo "checked";}elseif(isset($_POST['sms_optin']) == 1 && $_POST['sms_optin'] == 1){echo "selected";} ?>> SMS<br>

                        <input type="checkbox" name="phone_optin" id="phone_optin" value="1" <?php if(!isset($_POST['phone_optin']) && $user_data['phone_optin'] == 1) { echo "checked";}elseif(isset($_POST['phone_optin']) == 1 && $_POST['phone_optin'] == 1){echo "selected";} ?>> Phone
                        <br><br>    

                    <hr> 

                    <h4>If you wish to update your password please fill in both fields below.</h4><br>
                    <p>If the new password field is left blank your password will remain unchanged after your details have been updated.</p>

                    <div class="alert alert-info">
                        Password must meet the following rules.
                        <ul>
                            <li>6 or more characters</li>
                            <li>1 or more uppercase characters</li>
                            <li>1 or more numbers</li>
                            <li>1 or more special characters e.g. (@, !, _)</li>
                        </ul>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="password">Password:</label>
                            <input type="password" name="password" class="form-control" id="password" value="<?=esc(old('password'));?>">
                        </div>
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="conf_password">Confirm Password:</label>
                            <input type="password" name="conf_password" class="form-control" id="conf_password" value="<?=esc(old('conf_password'));?>">
                        </div>

                        <b>Show Password:</b> <input type="checkbox" name="showPassword" id="showPassword">
                    </div>

                    <div class="control-group form-group">
                        <div class="controls">
        					<input type="submit" name="details_update" class="btn btn-primary" id="details_update" value="Update">
                        </div>
                    </div>

        		 <?=form_close();?>

</div>
</div>

	</div>
</div>


<script>

$(document).ready(function(){
     $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#password,#conf_password",
            control: "#toggle_show_password",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind('click', function () {
            if (control.is(':checked')) {
                field.attr('type', 'text');
            } else {
                field.attr('type', 'password');
            }
        })
    };

    $.toggleShowPassword({
      field: '#password,#conf_password',
      control: '#showPassword'
   });



     $.toggleShowPassword2 = function (options) {
        var settings = $.extend({
            field: "#SecertAnswer",
            control: "#toggle_show_password2",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind('click', function () {
            if (control.is(':checked')) {
                field.attr('type', 'text');
            } else {
                field.attr('type', 'password');
            }
        })
    };

    $.toggleShowPassword2({
      field: '#SecertAnswer',
      control: '#showSecretAnswer'
   });

});
</script>

	
<?php $this->endSection() ;?>