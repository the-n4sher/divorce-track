<?= form_open('',array('novalidate' => '')) ;?>

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

	// Get the nationalities for the dropdowns
	include(APPPATH."Views/Questionnaire/nationalities.php");

?>

<div class="control-group form-group">
   <div class="controls">
	<b>Please select the type of divorce you are entering into below.</b><br><br>
	 <?php
	 	$standard_divorce = "";
	 	$civil_divorce = "";
	 	$same_sex = "";

		if($is_civilpartnership === 0){
			$standard_divorce = "checked";
		}elseif($is_civilpartnership == 1){
			$civil_divorce = "checked";
		}elseif($is_civilpartnership == 3){
			$same_sex = "checked";
		}
	 
	 ?>

	 <span style="font-size:18px;">Is this a divorce between a male and a female</span> <input type="radio" name="is_civilpartnership" id="" value="0" <?=$standard_divorce;?>><br>
	 <span style="font-size:18px;">or is this a civil partnership</span> <input type="radio" name="is_civilpartnership" id="is_civilpartnership" value="1" <?=$civil_divorce;?>><br>
	 <span style="font-size:18px;">or is a same sex marriage</span> <input type="radio" name="is_civilpartnership" id="is_civilpartnership" value="3" <?=$same_sex;?>><br><br>
  </div>
</div>


	<strong>PETITIONER IDENTITY INFORMATION</strong><br /><br />
	<div class="control-group form-group">
		<div class="controls">
			<label for="petitoner_forename">First Name<span class="required">*</span>:</label>

			<?php  
			  $val = ( $qdata['petitoner_forename'] !== null && old("petitoner_forename") === null || old("petitoner_forename") == "" ) ? $qdata['petitoner_forename'] : old("petitoner_forename");
			?>
			<input id="petitoner_forename" name="petitoner_forename" class="form-control" required type="text" value="<?=$val;?>">
		</div>
	</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_middle_name">Middle Name(s):</label>

	<?php  
	  $val = ( $qdata['petitoner_middle_name'] !== null && old("petitoner_middle_name") === null || old("petitoner_middle_name") == "" ) ? $qdata['petitoner_middle_name'] : old("petitoner_middle_name");
	?>
	<input id="petitoner_middle_name" name="petitoner_middle_name" class="form-control" type="text" value="<?=$val?>">
</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_last_name">Surname<span class="required">*</span>:</label>

	<?php  
	  $val = ( $qdata['petitoner_last_name'] !== null && old("petitoner_last_name") === null || old("petitoner_last_name") == "" ) ? $qdata['petitoner_last_name'] : old("petitoner_last_name");
	?>
	<input id="petitoner_last_name" name="petitoner_last_name" class="form-control" required type="text" 
	value="<?=$val;?>">

</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_occupation">Occupation<span class="required">*</span>:</label>

	<?php  
	  $val = ( $qdata['petitoner_occupation'] !== null && old("petitoner_occupation") === null || old("petitoner_occupation") == "" ) ? $qdata['petitoner_occupation'] : old("petitoner_occupation");
	?>
	<input id="petitoner_occupation" name="petitoner_occupation" class="form-control" required type="text" value="<?=$val;?>">
</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_dob">Date of birth<span class="required">*</span>:</label>

	<?php  
	  $val = ( $qdata['petitoner_dob'] !== null && old("petitoner_dob") === null || old("petitoner_dob") == "" ) ? $qdata['petitoner_dob'] : old("petitoner_dob");
	?>
	<input id="petitoner_dob" name="petitoner_dob" class="form-control" required type="text" value="<?=$val;?>">

</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_gender">Gender<span class="required">*</span>:</label>


	<?php 

	   $maleSelected ="";
		if( $qdata['petitoner_gender'] !== null && old("petitoner_gender") === null && $qdata['petitoner_gender'] == "male" ) {
		  $maleSelected = "selected";
		}elseif(old("petitoner_gender") !== null && old("petitoner_gender") == "male"){
		   $maleSelected = "selected";
		}

		$femaleSelected ="";
		if( $qdata['petitoner_gender'] !== null && old("petitoner_gender") === null && $qdata['petitoner_gender'] == "female" ) {
		  $femaleSelected = "selected";
		}elseif(old("petitoner_gender") !== null && old("petitoner_gender") == "female"){
		   $femaleSelected = "selected";
		}

	?>

	<select name="petitoner_gender" id="petitoner_gender" class="form-control" required>
		<option value="">--Please Select--</option>
		<option value="male" <?=$maleSelected;?>>Male</option>
		<option value="female" <?=$femaleSelected;?>>Female</option>
	</select> <br>

	<label class="form-label" for="petitoner_nationality">Nationality*</label>
	<select name="petitoner_nationality" id="petitoner_nationality" class="form-control" required>
	  <option value="">-- select one --</option>
	  <?php if(is_array($nationalities) && count($nationalities) > 0) : ?>
	  		<?php foreach($nationalities as $key => $n) : ?>

	  			<?php 
					$petitonerNatSelected = "";
					// Post data not set and the petitoner_nationality has a value and is equal to the looped value of $n
					if( !old("petitoner_nationality") && $qdata['petitoner_nationality'] != null && $qdata['petitoner_nationality'] == $key ){
						$petitonerNatSelected = "selected";
					}

					// We have posted data - use this option selected instead
					if( old("petitoner_nationality") && old("petitoner_nationality") == $key ){
						$petitonerNatSelected = "selected";
					}
				?>

	  			<option <?=$petitonerNatSelected;?> value="<?=$key;?>"><?=$n;?></option>
	  		<?php endforeach;?>
	  <?php endif;?>
	</select>
	
</div>
</div>
	<br />
<strong>PETITIONER IDENTITY INFORMATION</strong><br /><br />
	
	<p>If you are outside of the United Kingdom please enter your address manually below.</p>
	<div class="control-group form-group">
	  <div class="controls">
	<div id="postcode_lookup_field_stage1_petitoner"></div>
</div>
</div>


<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_address_line1">Address line 1<span class="required">*</span>:</label>

	<?php  
	  $val = ( $qdata['petitoner_address_line1'] !== null && old("petitoner_address_line1") === null || old("petitoner_address_line1") == "" ) ? $qdata['petitoner_address_line1'] : old("petitoner_address_line1");
	?>
	<input id="petitoner_address_line1" name="petitoner_address_line1" class="form-control" required type="text" value="<?=$val;?>">

</div>
</div>


<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_address_line2">Address line 2:</label>

	<?php  
	  $val = ( $qdata['petitoner_address_line2'] !== null && old("petitoner_address_line2") === null || old("petitoner_address_line2") == "" ) ? $qdata['petitoner_address_line2'] : old("petitoner_address_line2");
	?>
	<input id="petitoner_address_line2" name="petitoner_address_line2" class="form-control" type="text" value="<?=$val;?>">
</div>
</div>


<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_address_line3">Address line 3:</label>

	<?php  
	  $val = ( $qdata['petitoner_address_line3'] !== null && old("petitoner_address_line3") === null || old("petitoner_address_line3") == "" ) ? $qdata['petitoner_address_line3'] : old("petitoner_address_line3");
	?>
	<input id="petitoner_address_line3" name="petitoner_address_line3" class="form-control" type="text" value="<?=$val;?>">
</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_town">Town/City<span class="required">*</span>:</label>

	<?php  
	  $val = ( $qdata['petitoner_town'] !== null && old("petitoner_town") === null || old("petitoner_town") == "" ) ? $qdata['petitoner_town'] : old("petitoner_town");
	?>
	<input id="petitoner_town" name="petitoner_town" class="form-control" required type="text"value="<?=$val;?>">
</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_postcode">Postcode:<span class="required">*</span>:</label>

	<?php  
	  $val = ( $qdata['petitoner_postcode'] !== null && old("petitoner_postcode") === null || old("petitoner_postcode") == "" ) ? $qdata['petitoner_postcode'] : old("petitoner_postcode");
	?>
	<input id="petitoner_postcode" name="petitoner_postcode" class="form-control" required type="text" value="<?=$val;?>">
</div>
</div>

<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_county">County:</label>

	<?php  
	  $val = ( $qdata['petitoner_county'] !== null && old("petitoner_county") === null || old("petitoner_county") == "" ) ? $qdata['petitoner_county'] : old("petitoner_county");
	?>
	<input id="petitoner_county" name="petitoner_county" class="form-control" type="text" value="<?=$val;?>">
</div>
</div>


<div class="control-group form-group">
<div class="controls">
	<label for="petitoner_country">Country:<span class="required">*</span></label>
	<select name="petitoner_country" id="petitoner_country" class="form-control">
		<option value="">--Select Country--</option>
		<?php foreach($country as $c) : ?>

			<?php 
			  $selected ="";
			  if( $qdata['petitoner_country'] !== null && old("petitoner_country") === null && $qdata['petitoner_country'] == $c['id'] ) {
			  	$selected = "selected";
			  }elseif(old("petitoner_country") !== null && old("petitoner_country") == $c['id']){
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
	<input id="stage1_submit" class="btn btn-primary" name="stage1_submit" class="form-control" type="submit" value="Next">
</div>
</div>

<?=form_close();?>


<script>

	$(document).ready(function() {
	    const select = document.getElementById('petitoner_nationality');
	    const britishOption = select.querySelector('option[value="british"]');

	    if (britishOption) {
	        select.removeChild(britishOption);             
	        select.insertBefore(britishOption, select.options[1]);  
	    }
	}); 


</script>