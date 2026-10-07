<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>


<br /><br />
<div class="row">
	<div class="col-8">

        <div class="panel panel-primary">
		<div class="panel-heading"><h1>Update Your Account Details</h1></div>
    		<div class="panel-body">

                    <div class="container what-you-do"> 

                    <h2>Welcome to Divorce Track</h2><br>
                    <p><b>All your Divorce Documents and updates will be managed through this service</b></p>
    
                    <b>For security reasons you have been sent an automatically generated password, in order to continue, please enter a new password that you will remember.
                    <br><br>For further security please add your date of birth and select a secret question and answer.</b><br><br>


                      <?php 
                       if (session()->has("errors")){
                            echo ' <div class="alert alert-danger">
                                        <button class="close" data-dismiss="alert"></button>';
                                        foreach( session("errors") as $e) :
                                            echo $e. "<br />";
                                        endforeach;
        
                            echo '</div>';                               
                        }

                        if(session()->has("ERR") != ""){
                                echo ' <div class="alert alert-danger">';
                                        echo session("ERR");
                                echo '</div>'; 
                        }          
                    ?>

                    <?=form_open('',array('role' => 'form'));?>

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
                                <label for="password">Password: <span class="required">*</span></password>
                                <input type="password" name="password" class="form-control" id="password" value="<?=esc(old('password'));?>">
                                <p style="color:red;"><b>Your password must be over 6 characters in length.</b></p>
                            </div>
                        </div>

                        <div class="control-group form-group">
                            <div class="controls">
                                <label for="conf_password">Confirm Password: <span class="required">*</span></password>
                                <input type="password" name="conf_password" class="form-control" id="conf_password" value="<?=esc(old('conf_password'));?>">
                            </div>
                            Show Password <input type="checkbox" name="showPassword" id="showPassword">
                        </div>

                        <div class="control-group form-group">
                            <div class="controls">
                                <label for="Dob">DOB: <span class="required">*</span></password>
                                <input type="text" name="Dob" class="form-control" id="Dob" value="<?=esc(old('Dob'));?>">
                            </div>
                        </div>

                        <div class="control-group form-group">
                            <div class="controls">
                                <label for="SecertQuestion">Secret Question: <span class="required">*</span></password>
                                <select name="SecertQuestion" id="SecertQuestion" class="form-control">
                                    <option value="">--Please Select--</option>
                                    <?php if(is_array($secert_questions) && count($secert_questions) > 0) :?>
                                        <?php foreach( $secert_questions as $sc) : ?>
                                            <option value="<?=$sc['id'];?>" <?php if(isset($_POST['SecertQuestion']) && $_POST['SecertQuestion'] == $sc['id']){echo "selected";} ?>><?=$sc['question'];?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                        </div>

                         <div class="control-group form-group">
                            <div class="controls">
                                <label for="SecertAnswer">Secret Question Answer: <span class="required">*</span></password>
                                <input type="password" name="SecertAnswer" class="form-control" id="SecertAnswer" value="<?php if(isset($_POST['SecertAnswer'])) { echo esc(old("SecertAnswer"));} ?>"><br>
                                <b>Show Secret Answer</b><input type="checkbox" name="hiddenAnswer" id="hiddenAnswer">
                            </div>
                        </div>

                        <hr>
                        <h2>Marketing Preferences</h2>
                        <p>Please select how you would like to receive marketing material from us, please leave blank if you don't wish to receive any marketing material.</p>
                        <p><b>Please note these can be changed at any time from your account page.</b></p>  

                        <input type="checkbox" name="email_optin" id="email_optin" value="1" <?php if(isset($_POST['email_optin']) && $_POST['email_optin'] == "1"){echo "checked";} ?>> Email<br>
                        <input type="checkbox" name="sms_optin" id="sms_optin" value="1" <?php if(isset($_POST['sms_optin']) && $_POST['sms_optin'] == "1"){echo "checked";} ?>> SMS<br>
                        <input type="checkbox" name="phone_optin" id="phone_optin" value="1" <?php if(isset($_POST['phone_optin']) && $_POST['phone_optin'] == "1"){echo "checked";} ?>> Phone
                        <br><br>    


                        <div class="control-group form-group">
                            <div class="controls">
                                <input type="submit" class="btn btn-primary" name="update_details" id="update_details" value="Save">
                            </div>
                        </div>
                    <?=form_close();?>
                
            </div>
        </div>
	</div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
       
       //clear the secert answer field when the secert questioion field is changed
       $(document).on('change','#SecertQuestion',function(){
            $('#SecertAnswer').val('');
       });


        $("#Dob").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: '1916:<?=date("Y");?>',
            dateFormat:'dd-mm-yy'
        });

    $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#SecertAnswer",
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
    field: '#SecertAnswer',
    control: '#hiddenAnswer'
});


    $.toggleShowPassword2 = function (options) {
        var settings = $.extend({
            field: "#password,#conf_password",
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

    $.toggleShowPassword({
      field: '#password,#conf_password',
      control: '#showPassword'
   });



    });
</script>

<?php $this->endSection() ;?>