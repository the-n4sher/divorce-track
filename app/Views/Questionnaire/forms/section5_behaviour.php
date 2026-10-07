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

<?=form_open('',array('novalidate' => ''));?>
		  
		<p>Please provide details of the unreasonable behaviour that has caused your marriage to break down, based on just what the respondent did.<br /><br>
		
		<p><b>PLEASE DO NOT JUST CUT AND PASTE THESE EXAMPLES AS EACH CASE IS DIFFERENT AND UNIQUE:</b></p>
		<a href="<?=site_url();?>documents/UB_Particulars.pdf" target="_blank">View examples</a></p>

        <div class="control-group form-group">
	        <div class="controls">
	        	<label for="behaviour_reason">What is the behaviour that is being complained of?<span class="required">*</span>:</label>
	        	<br />Please enter no more than 800 characters.<br /><br />
	        	<textarea name="behaviour_reason" style="resize:none;width:555px;height:400px; white-space:pre-wrap;"  maxlength="800" id="behaviour_reason" style="width:100%;height:200px;margin-top:10px;border:1px solid #ccc;"><?php if(isset($qdata['behaviour_reason']) && !isset($_POST['behaviour_reason']) && $qdata['behaviour_reason'] != "") { echo $qdata['behaviour_reason']; }elseif(isset($_POST['behaviour_reason'])){ echo set_value('behaviour_reason');} ?></textarea><br>	
	        	<div style="clear:both;"></div>
	        </div>
	        <strong><span id="max_chars">800</span> characters remaining</strong>
	    </div>

		<a href="<?=base_url();?>questionnaire/stage1/5" class="btn btn-primary" role="button">Previous</a>
        <input type="submit" class="btn btn-primary" name="stage5_submit_behaviour_step1" value="Continue">
<?=form_close();?>

<script>	
 $(document).ready(function(){

 	var maxLength = 800;
 	//count the amount of chars already in the box
    current_length = $('#behaviour_reason').val().length;
    if(current_length > 0){
    	//minus the current amount from 900 (maxLength)
    	$('#max_chars').text(maxLength - current_length);
    }

	$('#behaviour_reason').keyup(function() {
	  var length = $(this).val().length;
	  var length = maxLength-length;
	  $('#max_chars').text(length);
	});
 });
</script>