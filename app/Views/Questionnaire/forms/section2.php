<?php 
	// Get the nationalities for the dropdowns
	include(APPPATH."Views/Questionnaire/nationalities.php");
?>



<?= form_open('') ;?>

			<?php

			 if (session()->has('errors')) 
			 {
			    $errors = session('errors');
			    if (is_array($errors)) {
			        echo '<div class="alert alert-danger">
			                <button class="close" data-dismiss="alert">&times;</button>';
			        foreach ($errors as $e) {
			            echo $e . '<br />';
			        }
			        echo '</div>';
			    } else {
			        // Handle cases where errors might be a single string
			        echo '<div class="alert alert-danger">
			                <button class="close" data-dismiss="alert">&times;</button>';
			        echo $errors;
			        echo '</div>';
			    }
			}

			?>

			<strong>RESPONDENT IDENTITY INFORMATION</strong><br /><br />
			<div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_forename">Spouse's First Name<span class="required">*</span>:</label>

                	<?php  
				  $val = ( $qdata['respondent_forename'] !== null && old("respondent_forename") === null || old("respondent_forename") == "" ) ? $qdata['respondent_forename'] : old("respondent_forename");
				?>
                	<input id="respondent_forename" name="respondent_forename" class="form-control" required type="text" value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_middle_name">Spouse's Middle Name(s):</label>

                	<?php  
				  $val = ( $qdata['respondent_middle_name'] !== null && old("respondent_middle_name") === null || old("respondent_middle_name") == "" ) ? $qdata['respondent_middle_name'] : old("respondent_middle_name");
				?>
                	<input id="respondent_middle_name" name="respondent_middle_name" class="form-control" type="text" value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_last_name">Spouse's Surname<span class="required">*</span>:</label>

                	<?php  
				  $val = ( $qdata['respondent_last_name'] !== null && old("respondent_last_name") === null || old("respondent_last_name") == "" ) ? $qdata['respondent_last_name'] : old("respondent_last_name");
				?>
                	<input id="respondent_last_name" name="respondent_last_name" class="form-control" required type="text" 
                	value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_occupation">Spouse's Occupation<span class="required">*</span>:</label>


                	<?php  
				  $val = ( $qdata['respondent_occupation'] !== null && old("respondent_occupation") === null || old("respondent_occupation") == "" ) ? $qdata['respondent_occupation'] : old("respondent_occupation");
				?>
                	<input id="respondent_occupation" name="respondent_occupation" class="form-control" required type="text" value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_dob">Spouse's Date of birth<span class="required">*</span>:</label>

                	<?php  
				  $val = ( $qdata['respondent_dob'] !== null && old("respondent_dob") === null || old("respondent_dob") == "" ) ? $qdata['respondent_dob'] : old("respondent_dob");
				?>
                	<input id="respondent_dob" name="respondent_dob" class="form-control" required type="text"
                	value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_gender">Spouse's Gender<span class="required">*</span>:</label>

                	<?php 

				     $maleSelected ="";
					if( $qdata['respondent_gender'] !== null && old("respondent_gender") === null && $qdata['respondent_gender'] == "male" ) {
					  $maleSelected = "selected";
					}elseif(old("respondent_gender") !== null && old("respondent_gender") == "male"){
					   $maleSelected = "selected";
					}

					$femaleSelected ="";
					if( $qdata['respondent_gender'] !== null && old("respondent_gender") === null && $qdata['respondent_gender'] == "female" ) {
					  $femaleSelected = "selected";
					}elseif(old("respondent_gender") !== null && old("respondent_gender") == "female"){
					   $femaleSelected = "selected";
					}

				?>
                <select name="respondent_gender" id="respondent_gender" class="form-control" required>
                		<option value="">--Please Select--</option>
                		<option value="male" <?=$maleSelected;?>>Male</option>
                		<option value="female" <?=$femaleSelected;?>>Female</option>
                	</select>
                </div> <br>

                <label class="form-label" for="respondent_nationality">Nationality*</label>
				<select name="respondent_nationality" id="respondent_nationality" class="form-control" required>
				  <option value="">-- select one --</option>
				  <?php if(is_array($nationalities) && count($nationalities) > 0) : ?>
				  		<?php foreach($nationalities as $key => $n) : ?>

				  			<?php 
								$respondentNatSelected = "";
								// Post data not set and the respondent_nationality has a value and is equal to the looped value of $n
								if( !old("respondent_nationality") && $qdata['respondent_nationality'] != null && $qdata['respondent_nationality'] == $key ){
									$respondentNatSelected = "selected";
								}

								// We have posted data - use this option selected instead
								if( old("respondent_nationality") && old("respondent_nationality") == $key ){
									$respondentNatSelected = "selected";
								}
							?>
				  			<option <?=$respondentNatSelected;?> value="<?=$key;?>"><?=$n;?></option>
				  		<?php endforeach;?>
				  <?php endif;?>
				</select>



            </div>

	            <?php if($split_application === false) : ?>
	            <div class="form-group control-group">
	            	<div class="controls">
	            		<label for="respondent_email">Do you know your spouse's email address? If so, please enter it below, otherwise you can leave the field empty.</label> <br>

	            		<?php  
					  $val = ( $qdata['respondent_email'] !== null && old("respondent_email") === null || old("respondent_email") == "" ) ? $qdata['respondent_email'] : old("respondent_email");
					?>
	            		<input type="email" name="respondent_email" class="form-control" id="respondent_email" value="<?=$val;?>" placeholder="example@email.com">
	            	</div>
	            </div>
	            <br />
	         <?php endif;?>

            
		  	
            <strong>RESPONDENT ADDRESS INFORMATION</strong><br /><br />
			
			<p>If you are outside of the United Kingdom please enter your address manually below.</p>
			<div class="control-group form-group">
			  <div class="controls">
            	<div id="postcode_lookup_field_stage1_respondent"></div>
              </div>
            </div>
            <br>
            <b>If the respondent's address is unknown please tick this box</b> <input type="checkbox" name="res_unknown" id="res_unknown"> <br><br>

		
            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_address_line1">Address line 1<span class="required">*</span>:</label>

                	<?php  
				  $val = ( $qdata['respondent_address_line1'] !== null && old("respondent_address_line1") === null || old("respondent_address_line1") == "" ) ? $qdata['respondent_address_line1'] : old("respondent_address_line1");
				?>

                	<input id="respondent_address_line1" name="respondent_address_line1" class="form-control" required type="text" value="<?=$val;?>">
                </div>
            </div>


            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_address_line2">Address line 2:</label>

                	<?php  
				  $val = ( $qdata['respondent_address_line2'] !== null && old("respondent_address_line2") === null || old("respondent_address_line2") == "" ) ? $qdata['respondent_address_line2'] : old("respondent_address_line2");
				?>
                	<input id="respondent_address_line2" name="respondent_address_line2" class="form-control" type="text" value="<?=$val;?>">
                </div>
            </div>


            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_address_line3">Address line 3:</label>

                	<?php  
				  $val = ( $qdata['respondent_address_line3'] !== null && old("respondent_address_line3") === null || old("respondent_address_line3") == "" ) ? $qdata['respondent_address_line3'] : old("respondent_address_line3");
				?>

                	<input id="respondent_address_line3" name="respondent_address_line3" class="form-control" type="text" value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_town">Town/City<span class="required">*</span>:</label>

                	<?php  
				  $val = ( $qdata['respondent_town'] !== null && old("respondent_town") === null || old("respondent_town") == "" ) ? $qdata['respondent_town'] : old("respondent_town");
				?>
                	<input id="respondent_town" name="respondent_town" class="form-control" required type="text" value="<?=$val;?>">
                </div>
            </div>

             <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_postcode">Postcode<span class="required">*</span>:</label>

                	<?php  
				  $val = ( $qdata['respondent_postcode'] !== null && old("respondent_postcode") === null || old("respondent_postcode") == "" ) ? $qdata['respondent_postcode'] : old("respondent_postcode");
				?>
                	<input id="respondent_postcode" name="respondent_postcode" class="form-control" required type="text" value="<?=$val;?>">
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_county">County:</label>

                	<?php  
				  $val = ( $qdata['respondent_county'] !== null && old("respondent_county") === null || old("respondent_county") == "" ) ? $qdata['respondent_county'] : old("respondent_county");
				?>
                	<input id="respondent_county" name="respondent_county" class="form-control" type="text" value="<?=$val;?>">
                </div>
            </div>


            <div class="control-group form-group">
                <div class="controls">
                	<label for="respondent_country">Country:<span class="required">*</span></label>
                	<select name="respondent_country" id="respondent_country" class="form-control">
                		<option value="">--Select Country--</option>
                		<?php foreach($country as $c) : ?>
                			<?php 
						  $selected ="";
						  if( $qdata['respondent_country'] !== null && old("respondent_country") === null && $qdata['respondent_country'] == $c['id'] ) {
						  	$selected = "selected";
						  }elseif(old("respondent_country") !== null && old("respondent_country") == $c['id']){
						   	$selected = "selected";
						  }

						?>
						<option value="<?=$c['id'];?>" <?=$selected;?>><?=$c['country'];?></option>
                		<?php endforeach;?>
                	</select>
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                	<a href="<?=site_url();?>questionnaire/stage1/" class="btn btn-primary" role="button">Previous</a>
                	<input id="stage2_submit" class="btn btn-primary" name="stage2_submit" class="form-control" type="submit" value="Next">
                </div>
            </div>

		<?=form_close();?>


	<script>

		$(document).ready(function() {
		    const select = document.getElementById('respondent_nationality');
		    const britishOption = select.querySelector('option[value="british"]');

		    if (britishOption) {
		        select.removeChild(britishOption);             
		        select.insertBefore(britishOption, select.options[1]);  
		    }
		}); 


	</script>