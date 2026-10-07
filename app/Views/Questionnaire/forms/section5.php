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

<?php  

	//if the user has entered any details about children then we need to send them back to 4/1
	$url = ( array($get_children_by_other) && count($get_children_by_other) > 0 ||  array($children_by_spouse) && count($children_by_spouse) > 0 ) 
	? site_url()."questionnaire/stage1/4/1" :  site_url()."questionnaire/stage1/4"; 

	$hide_section = ( $use_portal == 0 ) ? "" : "display:none;";
?>


<?php if($use_portal == false) : ?>


	<?=form_open('', array('style' => ''.$hide_section.''));?>

		<p>Please specify under which ground you wish to file for divorce</p>
	        
		 <table class="table">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</thead>

			 <tbody>
				

			 	<?php foreach($divorce_grounds as $dg) : ?>

			 	<?php 

			 		// Skip unused grounds
			 		if($dg['id'] == 2 || $dg['id'] == 3)
			 		{
			 			continue;
			 		}


			 		$style = "";
			 		$newStyle = "background-color:#ccc;";

			 		//731 = 2 years and 1 day

			 		if($dg['id'] == 3 && $date_difference < 730 ){$style=''.$newStyle.'';}

			 		if($dg['id'] == 4 && $date_difference < 1826 ){$style=''.$newStyle.'';}


			 		if($dg['id'] == 1 && $is_civil_partnership == 1){$style=''.$newStyle.'';}
			 		if($dg['id'] == 1 && $same_sex_marriage == 1){$style=''.$newStyle.'';}
			 	?>
				<tr style="<?=$style;?>">
					<td>
					  <?php if($dg['description'] != "Desertion") : ?>
					  	 <?php if($dg['description'] == "Behaviour") : ?>
						  <?= "Unreasonable " .$dg['description'];?>
						 <?php else: ?>
	                       <?=$dg['description'];?>
						 <?php endif;?>

					  <?php endif;?>
							
					</td>
					<td>

						<?php if($dg['id'] == 3 && $date_difference < 730): ?>
							&nbsp;
						<?php elseif($dg['id'] == 4 && $date_difference < 1826) : ?>
							&nbsp;
						<?php elseif($dg['id'] == 1 && $is_civil_partnership == 1) : ?>
							&nbsp;
						<?php elseif($dg['id'] == 1 && $same_sex_marriage == 1) : ?>
							&nbsp;
						<?php else: ?>
							
							<?php $radio_checked = ( $qdata['on_grounds'] == $dg['id'] || isset($_POST['divorce_grounds'] ) &&  $_POST['divorce_grounds'] == $dg['id'] ) ? "checked" : "" ; ?>
	                      <?php if($dg['description'] != "Desertion") : ?>
							<input type="radio" name="divorce_grounds" id="divorce_grounds" <?=$radio_checked;?> value="<?=$dg['id'];?>">
						  <?php endif;?>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			 </tbody>
		 </table>

		<a href="<?=$url;?>" class="btn btn-primary" role="button">Previous</a>
		<input type="submit" class="btn btn-primary" name="stage5_submit" value="Continue">
	<?= form_close(); ?>

<?php else: ?>

	<?=form_open();?>

		<h1>Grounds for Divorce</h1>
		<p>
			No fault divorce is due to come into effect in England and Wales on 6 April 2022. From this date couples will be able to get divorced without one person needing to blame the other.<br /><br />

			This change will also apply to civil partnership dissolution..<br /><br />

			The Divorce, Dissolution and Separation Act 2020 will be the biggest reform of divorce law in fifty years and means that you’ll no longer have to make allegations about your partner’s conduct to obtain a divorce. Currently one spouse must allege adultery or unreasonable behaviour to prove the irretrievable breakdown of the marriage or wait at least two or 5 years..<br /><br />

			Under the new law irretrievable breakdown will remain the sole reason for divorce but the need to give a reason will be removed..<br /><br />
		</p>
		
		<p>Please review the information supplied before submitting the questionnaire, you can review the questionnaire by clicking <a href="<?=$url;?>">here</a>. Once you are happy all the information supplied is correct, click the <b>"Submit Fact Finder"</b> button below.</p> <br>
		<input type="submit" class="btn btn-primary" name="stage5_submit_portal" value="Submit Fact Finder" onclick="return confirm('By pressing OK you are confirming that the information supplied is accurate. If the fact finder contains any errors this may cause a delay to your case. Press OK to continue or CANCEL to review the fact finder.');">
	<?=form_close();?>
		

<?php endif;?>