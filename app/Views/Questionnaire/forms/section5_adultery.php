
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

		  <div class="control-group form-group">
            <div class="controls">
            	<label for="marriage_last_county">Date of separation:</label>
            	<?=date("dS F Y",strtotime($qdata['marriage_date_of_separation']));?>
            </div>
        </div>

         <div class="control-group form-group">
            <div class="controls">
            	<label for="adultery_when_discovered_date">When was the adultery discovered?<span class="required">*</span>:</label>
            	<input id="adultery_when_discovered_date" name="adultery_when_discovered_date" class="form-control" required type="text"
            	value="<?php if(isset($qdata['adultery_when_discovered_date']) && @$_POST['adultery_when_discovered_date'] == "" && $qdata['adultery_when_discovered_date'] != "") {echo $qdata['adultery_when_discovered_date']; }elseif(isset($_POST['adultery_when_discovered_date'])){ echo htmlentities(@$_POST['adultery_when_discovered_date']);} ?>">
            </div>
        </div>


        <div class="control-group form-group">
            <div class="controls">
            	<label for="adultery_where">Where was the adultery commited?<span class="required">*</span>:</label>
            	<input id="adultery_where" name="adultery_where" class="form-control" required type="text"
            	value="<?php if(isset($qdata['adultery_where']) && !isset($_POST['adultery_where']) && $qdata['adultery_where'] != "") { echo $qdata['adultery_where']; }elseif(isset($_POST['adultery_where'])){ echo set_value('adultery_where');} ?>" placeholder="E.g. Unknown or London">
            </div>
        </div>

         <div class="control-group form-group">
            <div class="controls">
            	<label for="adultery_gender">What sex is the respondent's lover?<span class="required">*</span>:</label>
            	<select name="adultery_gender" id="adultery_gender" class="form-control" required>    		
                    <?php if($qdata['respondent_gender'] == "male") : ?>
                        <option value="female"
                        <?php if(isset($qdata['adultery_gender']) && !isset($_POST['adultery_gender']) && $qdata['adultery_gender'] == "female") { echo  "selected";} ?>
                        <?php if(isset($_POST['adultery_gender']) && $_POST['adultery_gender'] == "female") { echo  "selected";} ?>
                        >Female</option>
                    <?php elseif($qdata['respondent_gender'] == "female"): ?>
                        <option value="male"
                        <?php if(isset($qdata['adultery_gender']) && !isset($_POST['adultery_gender']) && $qdata['adultery_gender'] == "male") { echo  "selected";} ?>
                        <?php if(isset($_POST['adultery_gender']) && $_POST['adultery_gender'] == "male") { echo  "selected";} ?>
                        >Male</option>
                    <?php endif;?>

            	</select>
            </div>
        </div>

		<div class="checkbox">
		  <label>
		  <input type="checkbox" id="adultery_lived_with" name="adultery_lived_with" value="1"
		  <?php if($qdata['adultery_lived_with'] == 1 || isset($_POST['adultery_lived_with']) &&  $_POST['adultery_lived_with'] == 1 ){echo "checked";}?>>Have you lived together for more than <b>6 months</b> from the date the adultery was admitted.
		  </label>
		</div>

		<a href="<?=base_url();?>questionnaire/stage1/5" class="btn btn-primary" role="button">Previous</a>
        <input type="submit" class="btn btn-primary" name="stage5_submit_adultery_step1" value="Continue">
<?=form_close();?>