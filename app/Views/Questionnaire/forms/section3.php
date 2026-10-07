<?= form_open_multipart(''); ?>

<?php

if (session()->has('errors')) {
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

if (session()->has('SUCC')) {
	$succ = session('SUCC');
	echo '<div class="alert alert-success">';
	echo $succ;
	echo '</div>';
}

if (session()->has('FAIL')) {
	$fail = session('FAIL');
	echo '<div class="alert alert-danger">';
	echo $fail;
	echo '</div>';
}


?>

<!-- display the success and error messages in one list styled nicely using bootstrap -->
<?php if (!empty($success)): ?>
	<div class="alert alert-success">
		<?php if (is_array($success)): ?>
			<?php foreach ($success as $s) : ?>
				<p><?= $s; ?></p>
			<?php endforeach; ?>
		<?php else: ?>
			<p><?= $success; ?></p>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
	<div class="alert alert-danger">
		<?php if (is_array($errors)): ?>
			<?php foreach ($errors as $e) : ?>
				<p><?= $e; ?></p>
			<?php endforeach; ?>
		<?php else: ?>
			<p><?= $errors; ?></p>
		<?php endif; ?>
	</div>
<?php endif; ?>


<?php
$yes_checked = "";
$no_checked = "";
//the first part of this code is checking for a database value, if it doesn't exist and there are errors the second section will be used
if (isset($qdata['marriage_took_place_outside_uk']) && !isset($_POST['marriage_took_place_outside_uk']) && $qdata['marriage_took_place_outside_uk'] != "") {
	$yes_checked = ($qdata['marriage_took_place_outside_uk'] == 1) ? "checked" : "";
	$no_checked = ($qdata['marriage_took_place_outside_uk'] == 0) ? "checked" : "";
} elseif (isset($_POST['marriage_took_place_outside_uk'])) {
	$yes_checked = ($_POST['marriage_took_place_outside_uk'] == 1) ? "checked" : "";
	$no_checked = ($_POST['marriage_took_place_outside_uk'] == 0) ? "checked" : "";
}
?>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_took_place_outside_uk">Did the marriage take place outside of the UK?<span class="">*</span>:</label><br>
		NO <input type="radio" name="marriage_took_place_outside_uk" <?= $no_checked; ?> value="0" id="">
		YES <input type="radio" name="marriage_took_place_outside_uk" value="1" <?= $yes_checked; ?> id=""><br>
	</div>
</div>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_place_of_marriage">Place of Marriage<span class="">*</span>:</label>
		<input id="marriage_place_of_marriage" name="marriage_place_of_marriage" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_place_of_marriage']) && !isset($_POST['marriage_place_of_marriage']) && $qdata['marriage_place_of_marriage'] != "") {
						echo $qdata['marriage_place_of_marriage'];
					} elseif (isset($_POST['marriage_place_of_marriage'])) {
						echo set_value('marriage_place_of_marriage');
					} ?>">
	</div>
</div>

<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_date_of_marriage">Date of Marriage<span class="">*</span>:</label>
		<input id="marriage_date_of_marriage" name="marriage_date_of_marriage" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_date_of_marriage']) && !isset($_POST['marriage_date_of_marriage']) && $qdata['marriage_date_of_marriage'] != "") {
						echo date("d-m-Y", strtotime($qdata['marriage_date_of_marriage']));
					} elseif (isset($_POST['marriage_date_of_marriage'])) {
						echo set_value('marriage_date_of_marriage');
					} ?>">
	</div>
</div>

<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_date_of_separation">Date of Separation<span class="">*</span>:</label>
		<input id="marriage_date_of_separation" name="marriage_date_of_separation" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_date_of_separation']) && !isset($_POST['marriage_date_of_separation']) && $qdata['marriage_date_of_separation'] != "") {
						echo date("d-m-Y", strtotime($qdata['marriage_date_of_separation']));
					} elseif (isset($_POST['marriage_date_of_separation'])) {
						echo set_value('marriage_date_of_separation');
					} ?>">
	</div>
</div>

<strong>Marriage Certificate Details</strong><br />
<p style="color:red;font-weight:bold;font-size:20px;">Please enter the full name of petitioner and respondent exactly as it appears on marriage certificate.</p>
<br>

<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_petitioner_full_name">Petitioner Name<span class="">*</span>:</label>
		<input id="marriage_petitioner_full_name" name="marriage_petitioner_full_name" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_petitioner_full_name']) && !isset($_POST['marriage_petitioner_full_name']) && $qdata['marriage_petitioner_full_name'] != "") {
						echo $qdata['marriage_petitioner_full_name'];
					} elseif (isset($_POST['marriage_petitioner_full_name'])) {
						echo set_value('marriage_petitioner_full_name');
					} ?>">
	</div>
</div>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_respondent_full_name">Respondent Name<span class="">*</span>:</label>
		<input id="marriage_respondent_full_name" name="marriage_respondent_full_name" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_respondent_full_name']) && !isset($_POST['marriage_respondent_full_name']) && $qdata['marriage_respondent_full_name'] != "") {
						echo $qdata['marriage_respondent_full_name'];
					} elseif (isset($_POST['marriage_respondent_full_name'])) {
						echo set_value('marriage_respondent_full_name');
					} ?>">
	</div>
</div>

<?php if ($image_url !== null) : ?>
	<br>
	<h3 style="text-align: left;">Your current marriage certificate is below:</h3>
	<img src="<?= $image_url; ?>" alt="marriage certificate" style="width: 400px; height: auto;"><br /><br />

	<div class="alert alert-info">If you wish to upload a new certificate, please tick the "Delete Certificate" checkbox above and upload a new marriage certificate using the select file button below.
		Your new marriage certificate will be uploaded and replace the existing one once you have submitted the form.
	</div>
	Delete Certificate <input type="checkbox" name="delete_cert" id="delete_cert" /><br><br>

<?php else : ?>
	<b><u>You may upload an image of your marriage certificate for checking purposes.</u></b><br><br>
<?php endif; ?>

<input type="file" name="userfile" id="userfile">
<hr>
<span id="userfile_br"></span>

<hr>
<strong>Address you last lived together at, as husband and wife</strong><br /><br />
<?php if (ENVIRONMENT != "production") : ?>
	<p><strong>FOR TESTING ONLY<br />ID1 1QD Returns a successful postcode lookup response 2000</strong></p>
<?php endif; ?>

<p>If you are outside of the United Kingdom please enter your address manually below.</p>
<div class="control-group form-group">
	<div class="controls">
		<div id="postcode_lookup_field_stage1_marriage"></div>
	</div>
</div>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_address_line1">Address line 1<span class="">*</span>:</label>
		<input id="marriage_last_address_line1" name="marriage_last_address_line1" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_last_address_line1']) && !isset($_POST['marriage_last_address_line1']) && $qdata['marriage_last_address_line1'] != "") {
						echo $qdata['marriage_last_address_line1'];
					} elseif (isset($_POST['marriage_last_address_line1'])) {
						echo set_value('marriage_last_address_line1');
					} ?>">
	</div>
</div>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_address_line2">Address line 2:</label>
		<input id="marriage_last_address_line2" name="marriage_last_address_line2" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_last_address_line2']) && !isset($_POST['marriage_last_address_line2']) && $qdata['marriage_last_address_line2'] != "") {
						echo $qdata['marriage_last_address_line2'];
					} elseif (isset($_POST['marriage_last_address_line2'])) {
						echo set_value('marriage_last_address_line2');
					} ?>">
	</div>
</div>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_address_line3">Address line 3:</label>
		<input id="marriage_last_address_line3" name="marriage_last_address_line3" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_last_address_line3']) && !isset($_POST['marriage_last_address_line3']) && $qdata['marriage_last_address_line3'] != "") {
						echo $qdata['marriage_last_address_line3'];
					} elseif (isset($_POST['marriage_last_address_line3'])) {
						echo set_value('marriage_last_address_line3');
					} ?>">
	</div>
</div>

<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_town">Town/City<span class="">*</span>:</label>
		<input id="marriage_last_town" name="marriage_last_town" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_last_town']) && !isset($_POST['marriage_last_town']) && $qdata['marriage_last_town'] != "") {
						echo $qdata['marriage_last_town'];
					} elseif (isset($_POST['marriage_last_town'])) {
						echo set_value('marriage_last_town');
					} ?>">
	</div>
</div>

<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_postcode">Postcode<span class="">*</span>:</label>
		<input id="marriage_last_postcode" name="marriage_last_postcode" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_last_postcode']) && !isset($_POST['marriage_last_postcode']) && $qdata['marriage_last_postcode'] != "") {
						echo $qdata['marriage_last_postcode'];
					} elseif (isset($_POST['marriage_last_postcode'])) {
						echo set_value('marriage_last_postcode');
					} ?>">
	</div>
</div>

<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_county">County:</label>
		<input id="marriage_last_county" name="marriage_last_county" class="form-control" type="text"
			value="<?php if (isset($qdata['marriage_last_county']) && !isset($_POST['marriage_last_county']) && $qdata['marriage_last_county'] != "") {
						echo $qdata['marriage_last_county'];
					} elseif (isset($_POST['marriage_last_county'])) {
						echo set_value('marriage_last_county');
					} ?>">
	</div>
</div>


<div class="control-group form-group">
	<div class="controls">
		<label for="marriage_last_country">Country:<span class="">*</span></label>
		<select name="marriage_last_country" id="marriage_last_country" class="form-control">
			<option value="">--Select Country--</option>
			<?php foreach ($country as $c) : ?>
				<option value="<?= $c['id']; ?>"
					<?php if (isset($qdata['marriage_last_country']) && !isset($_POST['marriage_last_country']) && $qdata['marriage_last_country'] == $c['id']) {
						echo  "selected";
					} ?>
					<?php if (isset($_POST['marriage_last_country']) && $_POST['marriage_last_country'] == $c['id']) {
						echo  "selected";
					} ?>><?= $c['country']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<div class="control-group form-group">
	<div class="controls">
		<a href="<?= base_url(); ?>questionnaire/stage1/2" class="btn btn-primary" role="button">Previous</a>
		<input id="stage3_submit" class="btn btn-primary" name="stage3_submit" class="form-control" type="submit" value="Next">
	</div>
</div>
<?= form_close(); ?>

<hr>

<div id="extra_files_div" style="border: 1px solid #ddd; border-radius: 5px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; background-color: #f9f9f9;">
	<!-- If we have extra files, display a list of files in a stripped table with a delete button next to each file, 
						 the delete should do a post request to /questionnaire/deleteFile/{FILE_NAME} -->
	<?php if (!empty($extra_files)): ?>
		<p>The following additional marriage certificate files have been uploaded:</p>
		<table class="table table-striped" border="1" cellpadding="10" cellspacing="0">
			<thead>
				<tr>
					<th>File</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($extra_files as $file): ?>
					<tr>
						<td><?= $file; ?></td>
						<?php $link = str_replace('=', '~', base64_encode($file)); ?>
						<td><a href="<?= site_url(); ?>questionnaire/deleteFile/<?= session("user_id"); ?>/<?= $link; ?>" onclick="return confirm('Are you sure you want to delete this file?');" class="btn btn-danger">Delete</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br>
	<?php endif; ?>


	<p>If you wish to upload any more additional marriage certificate files, please do so below.</p>
	<p>You can upload multiple files at once by holding down the <b>CTRL key</b> and selecting multiple files.</p>
	<?= form_open_multipart(); ?>
	<input type="file" name="extraFiles[]" id="extraFiles" multiple><br>
	<input type="submit" class="btn btn-primary" name="upload_extra_files" value="Upload Documents">
	<?= form_close(); ?> <br>
</div>



<script type="text/javascript">
	$(document).ready(function() {
		// move the extra_files_div after the userfile_br
		$("#userfile_br").after($("#extra_files_div"));

		$(document).on('click', '#delete_cert', function() {

			var clicked = ($(this).is(":checked")) ? true : false;
			if (clicked == true) {
				alert("Are you sure you want to permanently delete this image?");
			}
		});
	});
</script>