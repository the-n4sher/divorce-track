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
        
	<div class="checkbox">
	  <label>
	  <input type="checkbox" id="court_proceedings" name="court_proceedings" value="1"
	  <?php if($qdata['court_proceedings'] == 1 || isset($_POST['court_proceedings']) &&  $_POST['court_proceedings'] == 1 ){echo "checked";}?>>
	  Are there any existing or previous court proceedings relating to your marriage/civil partnership, property or children?<br />
	  Please provide details of these proceedings below including case number and court name.<br /><br />

	  If no please click "complete" to finalize your application.
	  </label>
	</div>

     <div class="control-group form-group">
        <div class="controls">
        	<label for="court_proceedings_information">Please provide details of the proceedings that currently exist abroad?:</label>
        	<textarea name="court_proceedings_information" id="court_proceedings_information" maxlength="200" style="width:100%;height:200px;margin-top:10px;border:1px solid #ccc;"><?php if(isset($qdata['court_proceedings_information']) && !isset($_POST['court_proceedings_information']) && $qdata['court_proceedings_information'] != "") { echo $qdata['court_proceedings_information']; }elseif(isset($_POST['court_proceedings_information'])){ echo set_value('court_proceedings_information');} ?></textarea>
        </div>
         <strong><span id="max_chars">200</span> characters remaining</strong>
    </div>
	
	<?php if(in_array($segment3,array(3,4))) : ?>
	 	<a href="<?=base_url();?>account/questionnaire/stage1/5" class="btn btn-primary" role="button">Previous</a>
	<?php else : ?>
    	<a href="<?=base_url();?>questionnaire/stage1/5/<?=$segment3;?>" class="btn btn-primary" role="button">Previous</a>
    <?php endif;?>
	<input type="submit" class="btn btn-primary" name="stage5_submit_court_proceddings" value="Complete" onclick="return confirm('You are now at the very end of the fact finder - If you click OK then you are confirming that you have reviewed your information and are happy to continue.')">
<?=form_close(); ?>


<script>	
 $(document).ready(function(){

 	var maxLength = 200;
 	//count the amount of chars already in the box
    current_length = $('#court_proceedings_information').val().length;
    if(current_length > 0){
    	//minus the current amount from 900 (maxLength)
    	$('#max_chars').text(maxLength - current_length);
    }

	$('#court_proceedings_information').keyup(function() {
	  var length = $(this).val().length;
	  var length = maxLength-length;
	  $('#max_chars').text(length);
	});
 });
</script>