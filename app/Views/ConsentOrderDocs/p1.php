<?php

	$edata = $edit_data;
	$qdata = $qdata;
	$doc_src = ( $doc_path == "0" ) ? getcwd() ."/" : base_url();
	echo '<span style="color:#fff;">$doc_src</span>';

	// Normalize $edata to an associative array
	if (!empty($edata)) {
		if ($edata instanceof stdClass) {
			$edata = (array) $edata;
		} elseif (is_array($edata) && isset($edata[0]) && $edata[0] instanceof stdClass) {
			$edata = (array) $edata[0];
		}
	}

	// Page 1
	$court_name = strtoupper(strtolower($edata['family_court'] ?? $qdata['name_of_court']));
	$case_number = '';
	if (is_array($edata) && count($edata) > 0 && isset($edata['case_number'])) {
		$case_number = $edata['case_number'];
	} else if (isset($qdata['case_number'])) {
		$case_number = $qdata['case_number'];
	}

	// Create the appliants name
	$pet_name = $qdata["app_forename"] . " ";
	$pet_name .= ( $qdata["app_other_names"] != "" ) ? $qdata["app_other_names"] . " " : "";
	$pet_name .= $qdata["app_surname"];
	$pet_name = ucwords(strtolower($pet_name));

	$res_name = "";
	$res_name = $qdata["res_forename"] . " ";
	$res_name .= ( $qdata["res_other_names"] != "" ) ? $qdata["res_other_names"] . " " : "";
	$res_name .= $qdata["res_surname"];
	$res_name = ucwords(strtolower($res_name));

	$a_transferor_name = $qdata["a_transferor_name"];
	$a_transferor_all_names = $qdata["a_transferor_all_names"];

	// explode the a_transferor_dob field - remove the - to start with
	$a_transferor_dob = str_replace("-", "", date("d-m-Y",strtotime($qdata["a_transferor_dob"])));
	$a_transferor_dob = str_split($a_transferor_dob);

	$a_dob_day1 = $a_transferor_dob[0];
	$a_dob_day2 = $a_transferor_dob[1];
	$a_dob_month1 = $a_transferor_dob[2];
	$a_dob_month2 = $a_transferor_dob[3];
	$a_dob_year1 = $a_transferor_dob[4];
	$a_dob_year2 = $a_transferor_dob[5];
	$a_dob_year3 = $a_transferor_dob[6];
	$a_dob_year4 = $a_transferor_dob[7];

	$a_transferor_address = $qdata["a_transferor_address"];
	$a_transferor_address_nat_num = strtoupper($qdata["a_transferor_address_nat_num"]);

	$court_directs_strike_page1 = "";
	$court_directs_strike_page2 = "";
	$court_directs_strike_page3 = "";

	$b_transferor_name = $qdata["b_transferor_name"];
	$b_transferor_all_names = $qdata["b_transferor_all_names"];


	if(is_array($edata) && count($edata) > 0 )
	{
		$edit_pet_name = isset($edata['pet_name']) ? $edata['pet_name'] : '';
		$pet_name = ($edit_pet_name == "" ) ? $pet_name : $edit_pet_name;

		$edit_res_name = isset($edata['res_name']) ? $edata['res_name'] : '';
		$res_name = ($edit_res_name == "") ? $res_name : $edit_res_name;

		$a_transferor_name = isset($edata["transferor_name"]) ? $edata["transferor_name"] : $a_transferor_name;
		$a_transferor_all_names = isset($edata["transferor_all_names"]) ? $edata["transferor_all_names"] : $a_transferor_all_names;

		$a_dob_day1   = isset($edata["transferor_day_1"])   ? $edata["transferor_day_1"]   : $a_dob_day1;
		$a_dob_day2   = isset($edata["transferor_day_2"])   ? $edata["transferor_day_2"]   : $a_dob_day2;
		$a_dob_month1 = isset($edata["transferor_month_1"]) ? $edata["transferor_month_1"] : $a_dob_month1;
		$a_dob_month2 = isset($edata["transferor_month_2"]) ? $edata["transferor_month_2"] : $a_dob_month2;
		$a_dob_year1  = isset($edata["transferor_year_1"])  ? $edata["transferor_year_1"]  : $a_dob_year1;
		$a_dob_year2  = isset($edata["transferor_year_2"])  ? $edata["transferor_year_2"]  : $a_dob_year2;
		$a_dob_year3  = isset($edata["transferor_year_3"])  ? $edata["transferor_year_3"]  : $a_dob_year3;
		$a_dob_year4  = isset($edata["transferor_year_4"])  ? $edata["transferor_year_4"]  : $a_dob_year4;

		$a_transferor_address = isset($edata["transferor_address"]) ? $edata["transferor_address"] : $a_transferor_address;
		$a_transferor_address_nat_num = isset($edata["transferor_ni"]) ? strtoupper($edata["transferor_ni"]) : strtoupper($a_transferor_address_nat_num);

		$court_directs_strike_page1 = (isset($edata["court_directs_strike_page1"]) && $edata["court_directs_strike_page1"] == 0) ? "elmHidden" : "";
		$court_directs_strike_page2 = (isset($edata["court_directs_strike_page2"]) && $edata["court_directs_strike_page2"] == 0) ? "elmHidden" : "";
		$court_directs_strike_page3 = (isset($edata["court_directs_strike_page3"]) && $edata["court_directs_strike_page3"] == 0) ? "elmHidden" : "";

		$b_transferor_name = isset($edata["transferee_name"]) ? $edata["transferee_name"] : $b_transferor_name;
		$b_transferor_all_names = isset($edata["transferee_all_names"]) ? $edata["transferee_all_names"] : $b_transferor_all_names;

	}
	



	/* Page 1 END*/

	/* PAGE 2 */

	$pension_wording_50_50 = "";
	// explode the b_transferor_dob field - remove the - to start with
	$b_transferor_dob = str_replace("-", "", date("d-m-Y",strtotime($qdata["b_transferor_dob"])));
	$b_transferor_dob = str_split($b_transferor_dob);

	$b_transferor_address = $qdata["b_transferor_address"];
	$b_transferor_address_nat_num = strtoupper($qdata["b_transferor_address_nat_num"]);

	$b_dob_day1 = $b_transferor_dob[0];
	$b_dob_day2 = $b_transferor_dob[1];
	$b_dob_month1 = $b_transferor_dob[2];
	$b_dob_month2 = $b_transferor_dob[3];
	$b_dob_year1 = $b_transferor_dob[4];
	$b_dob_year2 = $b_transferor_dob[5];
	$b_dob_year3 = $b_transferor_dob[6];
	$b_dob_year4 = $b_transferor_dob[7];

	$name_of_arrangements = $qdata["name_of_arrangements"];
	$name_and_address = $qdata["name_and_address"];
	$ref_number = $qdata["ref_number"];

	// We need to split the pencent_of_pension field on the .
	// Ensure the key exists and is not empty
	if (!empty($qdata["pencent_of_pension"])) 
	{
		// Split the pencent_of_pension field on the .
		$pencent_of_pension = explode(".", $qdata["pencent_of_pension"]);

		// Assign values safely
		$percent_start = $pencent_of_pension[0] ?? '0'; // Default to '0' if not present
		$percent_end = $pencent_of_pension[1] ?? '0';   // Default to '0' if not present
	} else {
		// Default values if the key does not exist or is empty
		$percent_start = '0';
		$percent_end = '0';
	}

	$pension_5050_strike = ( $qdata["how_pension_paid"] == "50/50" ) ? "elmHidden" : "";
	$pension_5050_strike_2 = ( $qdata["how_pension_paid"] == "50/50" ) ? "elmHidden" : "";

	$pension_sharing_strike = ( $qdata["how_pension_paid"] == "50/50" ) ? "" : "elmHidden";
	$pension_sharing_strike_2 = ( $qdata["how_pension_paid"] == "50/50" ) ? "" : "elmHidden";


	if($qdata["how_pension_paid"] == "50/50"){
		$pension_sharing_other_wording = "";
		$pension_wording_50_50 = "50/50";
	}else{
		$pension_sharing_other_wording = ( $qdata["how_pension_paid"] == "Transferor" ) ?  "Transferor" : "Transferee";
	}

	if(is_array($edata) && count($edata) > 0 )
	{
		$b_dob_day1 = !empty($edata["transferee_day_1"]) ? $edata["transferee_day_1"] : $b_dob_day1;
		$b_dob_day2 = !empty($edata["transferee_day_2"]) ? $edata["transferee_day_2"] : $b_dob_day2;
		$b_dob_month1 = !empty($edata["transferee_month_1"]) ? $edata["transferee_month_1"] : $b_dob_month1;
		$b_dob_month2 = !empty($edata["transferee_month_2"]) ? $edata["transferee_month_2"] : $b_dob_month2;
		$b_dob_year1 = !empty($edata["transferee_year_1"]) ? $edata["transferee_year_1"] : $b_dob_year1;
		$b_dob_year2 = !empty($edata["transferee_year_2"]) ? $edata["transferee_year_2"] : $b_dob_year2;
		$b_dob_year3 = !empty($edata["transferee_year_3"]) ? $edata["transferee_year_3"] : $b_dob_year3;
		$b_dob_year4 = !empty($edata["transferee_year_4"]) ? $edata["transferee_year_4"] : $b_dob_year4;

		$b_transferor_address = !empty($edata["transferee_address"]) ? $edata["transferee_address"] : $b_transferor_address;
		$b_transferor_address_nat_num = !empty($edata["transferee_ni"]) ? strtoupper($edata["transferee_ni"]) : strtoupper($b_transferor_address_nat_num);

		$name_of_arrangements = !empty($edata["name_of_agreement"]) ? $edata["name_of_agreement"] : $name_of_arrangements;
		$name_and_address = !empty($edata["pension_name_address"]) ? $edata["pension_name_address"] : $name_and_address;
		$ref_number = !empty($edata["reference_number"]) ? $edata["reference_number"] : $ref_number;

		$percent_start = !empty($edata["percent_start"]) ? $edata["percent_start"] : $percent_start;
		$percent_end = !empty($edata["percent_end"]) ? $edata["percent_end"] : $percent_end;

		$pension_5050_strike = (isset($edata["pension_5050_strike"]) && $edata["pension_5050_strike"] == 0) ? "elmHidden" : "";
		$pension_5050_strike_2 = (isset($edata["pension_5050_strike_2"]) && $edata["pension_5050_strike_2"] == 0) ? "elmHidden" : "";

		$pension_sharing_strike = (isset($edata["pension_sharing_strike"]) && $edata["pension_sharing_strike"] == 0) ? "elmHidden" : "";
		$pension_sharing_strike_2 = (isset($edata["pension_sharing_strike_2"]) && $edata["pension_sharing_strike_2"] == 0) ? "elmHidden" : "";

		$pension_sharing_other_wording = !empty($edata['pension_sharing_other_wording']) ? $edata['pension_sharing_other_wording'] : '';

	}
	/* PAGE 2 END*/


	/* PAGE 3 */


	$filed_d81_yes = "";
	$filed_d81_no = "elmHidden";
	$filed_strike_1 = "";
	$filed_strike_2 = "";
	$filed_strike_3 = "";
	$filed_strike_4 = "";
	$filed_strike_5 = "";
	$filed_strike_6 = "";
	$filed_strike_7 = "";
	$filed_strike_8 = "";
	$filed_strike_9 = "";

	$internal_transfer = ( $qdata["is_internal_transfer"] == 0) ? "" : "elmHidden";
	$external_transfer = ( $qdata["is_internal_transfer"] == 1) ? "" : "elmHidden";

	$name_of_agreement = $qdata["qualifying_arrangement_name"];
	$qualifying_arrangement_address = $qdata["qualifying_arrangement_address"];
	$qualifying_arrangement_policy_num = $qdata["qualifying_arrangement_policy_num"];


	if(is_array($edata) && count($edata) > 0)
	{
		
		$filed_d81_yes = (isset($edata["filed_d81_yes"]) && $edata["filed_d81_yes"] == 0) ? "elmHidden" : "";
		$filed_d81_no = (isset($edata["filed_d81_no"]) && $edata["filed_d81_no"] == 0) ? "elmHidden" : "";

		$filed_strike_1 = (isset($edata["filed_strike_1"]) && $edata["filed_strike_1"] == 0) ? "elmHidden" : "";
		$filed_strike_2 = (isset($edata["filed_strike_2"]) && $edata["filed_strike_2"] == 0) ? "elmHidden" : "";
		$filed_strike_3 = (isset($edata["filed_strike_3"]) && $edata["filed_strike_3"] == 0) ? "elmHidden" : "";
		$filed_strike_4 = (isset($edata["filed_strike_4"]) && $edata["filed_strike_4"] == 0) ? "elmHidden" : "";
		$filed_strike_5 = (isset($edata["filed_strike_5"]) && $edata["filed_strike_5"] == 0) ? "elmHidden" : "";
		$filed_strike_6 = (isset($edata["filed_strike_6"]) && $edata["filed_strike_6"] == 0) ? "elmHidden" : "";
		$filed_strike_7 = (isset($edata["filed_strike_7"]) && $edata["filed_strike_7"] == 0) ? "elmHidden" : "";
		$filed_strike_8 = (isset($edata["filed_strike_8"]) && $edata["filed_strike_8"] == 0) ? "elmHidden" : "";
		$filed_strike_9 = (isset($edata["filed_strike_9"]) && $edata["filed_strike_9"] == 0) ? "elmHidden" : "";

		$internal_transfer = (isset($edata["internal_transfer"]) && $edata["internal_transfer"] == 1) ? "" : "elmHidden";
		$external_transfer = (isset($edata["external_transfer"]) && $edata["external_transfer"] == 1) ? "" : "elmHidden";

		$name_of_agreement = (!empty($edata["qual_name_of_argreement"])) ? $edata["qual_name_of_argreement"] : $name_of_agreement;
		$qualifying_arrangement_address = (!empty($edata["qual_address"])) ? $edata["qual_address"] : $qualifying_arrangement_address;
		$qualifying_arrangement_policy_num = (!empty($edata["transferee_mem_num"])) ? $edata["transferee_mem_num"] : $qualifying_arrangement_policy_num;

	}



	/* PAGE 3 END*/


	/* PAGE 4 */
	$transferor_health_yes = "elmHidden";
	$transferor_health_no = "elmHidden";

	$copy_atatched = "elmHidden";
	$credit_derived_yes = "elmHidden";
	$credit_derived_no = "elmHidden";

	$info_requested_yes = "elmHidden";
	$info_requested_no = "elmHidden";

	if(is_array($edata) && count($edata) > 0)
	{

		$copy_atatched = (isset($edata["copy_atatched"]) && $edata["copy_atatched"] == 0) ? "elmHidden" : "";
		$credit_derived_yes = (isset($edata["credit_derived_yes"]) && $edata["credit_derived_yes"] == 0) ? "elmHidden" : "";
		$credit_derived_no = (isset($edata["credit_derived_no"]) && $edata["credit_derived_no"] == 0) ? "elmHidden" : "";

		$transferor_health_yes = (isset($edata["transferor_health_yes"]) && $edata["transferor_health_yes"] == 0) ? "elmHidden" : "";
		$transferor_health_no = (isset($edata["transferor_health_no"]) && $edata["transferor_health_no"] == 0) ? "elmHidden" : "";

		$info_requested_yes = (isset($edata["info_requested_yes"]) && $edata["info_requested_yes"] == 0) ? "elmHidden" : "";
		$info_requested_no = (isset($edata["info_requested_no"]) && $edata["info_requested_no"] == 0) ? "elmHidden" : "";
	}

	/* PAGE 4 END */

?>




<!DOCTYPE html>
<html lang="en">
<head>
	
	<style>
		<?php include(ROOTPATH."document_styles/p1.css"); ?>
	</style>

	<?php if($doc_path == 1) : ?>
		<style>
			<?php include(ROOTPATH."document_styles/editor.css"); ?>
		</style>

	<?php else: ?>
		<style>.elmHidden{display:none;}</style>
	<?php endif;?>



	<meta charset="UTF-8">
	<title>Pension Sharing Agreement</title>
</head>

<body>

	<?php 
 		$path = getcwd() . "/images/consent_order_docs/P1/page1.jpg";
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>

	<div>
		

		<input type="text" name="court_name" id="court_name" class="text" value="<?=$court_name;?>">
		<input type="text" name="case_number" id="case_number" class="text" value="<?=$case_number;?>">

		<input type="text" name="pet_name" id="pet_name" class="text" value="<?=$pet_name;?>">
		<input type="text" name="res_name" id="res_name" class="text" value="<?=$res_name;?>">

		<input type="text" name="a_transferor_name" id="a_transferor_name" class="text" value="<?=$a_transferor_name;?>">
		<input type="text" name="a_transferor_all_names" id="a_transferor_all_names" class="text" value="<?=$a_transferor_all_names;?>">

		<input type="text" name="a_dob_day1" id="a_dob_day1" class="text small-character" value="<?=$a_dob_day1;?>">
		<input type="text" name="a_dob_day2" id="a_dob_day2" class="text small-character" value="<?=$a_dob_day2;?>">
		<input type="text" name="a_dob_month1" id="a_dob_month1" class="text small-character" value="<?=$a_dob_month1;?>">
		<input type="text" name="a_dob_month2" id="a_dob_month2" class="text small-character" value="<?=$a_dob_month2;?>">
		<input type="text" name="a_dob_year1" id="a_dob_year1" class="text small-character" value="<?=$a_dob_year1;?>">
		<input type="text" name="a_dob_year2" id="a_dob_year2" class="text small-character" value="<?=$a_dob_year2;?>">
		<input type="text" name="a_dob_year3" id="a_dob_year3" class="text small-character" value="<?=$a_dob_year3;?>">
		<input type="text" name="a_dob_year4" id="a_dob_year4" class="text small-character" value="<?=$a_dob_year4;?>">

		<textarea name="a_transferor_address" id="a_transferor_address" class="textarea"><?=wordwrap(nl2br($a_transferor_address),45,"<br />",true);?></textarea>
		<input type="text" name="a_transferor_address_nat_num" id="a_transferor_address_nat_num" class="text" value="<?=$a_transferor_address_nat_num;?>">

		<img src="<?=$base64;?>" alt="">
	</div> 
	


	<?php 
 		$path = getcwd() . "/images/consent_order_docs/P1/page2.jpg";
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>


		<input type="text" name="b_transferor_name" id="b_transferor_name" class="text" value="<?=$b_transferor_name;?>">
		<input type="text" name="b_transferor_all_names" id="b_transferor_all_names" class="text" value="<?=$b_transferor_all_names;?>">
		
		<input type="text" name="b_dob_day1" id="b_dob_day1" class="text small-character" value="<?=$b_dob_day1;?>">
		<input type="text" name="b_dob_day2" id="b_dob_day2" class="text small-character" value="<?=$b_dob_day2;?>">
		<input type="text" name="b_dob_month1" id="b_dob_month1" class="text small-character" value="<?=$b_dob_month1;?>">
		<input type="text" name="b_dob_month2" id="b_dob_month2" class="text small-character" value="<?=$b_dob_month2;?>">
		<input type="text" name="b_dob_year1" id="b_dob_year1" class="text small-character" value="<?=$b_dob_year1;?>">
		<input type="text" name="b_dob_year2" id="b_dob_year2" class="text small-character" value="<?=$b_dob_year2;?>">
		<input type="text" name="b_dob_year3" id="b_dob_year3" class="text small-character" value="<?=$b_dob_year3;?>">
		<input type="text" name="b_dob_year4" id="b_dob_year4" class="text small-character" value="<?=$b_dob_year4;?>">

		<textarea name="b_transferor_address" id="b_transferor_address" class="textarea"><?=wordwrap(nl2br($b_transferor_address),45,"<br />",true);?></textarea>
		<input type="text" name="b_transferor_address_nat_num" id="b_transferor_address_nat_num" class="text" value="<?=$b_transferor_address_nat_num;?>">
		
		<input type="text" name="name_of_arrangements" id="name_of_arrangements" class="text" value="<?=$name_of_arrangements;?>">
		<textarea name="name_and_address" id="name_and_address" class="textarea"><?=wordwrap(nl2br($name_and_address),45,"<br />",true);?></textarea>

		<input type="text" name="ref_number" id="ref_number" class="text" value="<?=$ref_number;?>">

		<input type="text" name="percent_start" id="percent_start" class="text small-character" value="<?=$percent_start;?>">
		<input type="text" name="percent_end" id="percent_end" class="text small-character" value="<?=$percent_end;?>">

		
		<img src="<?=$base64;?>" alt="">
	</div> 


	<?php 
 		$path = getcwd() . "/images/consent_order_docs/P1/page3.jpg";
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


 		$path = getcwd() . "/images/case_document_images/tick.jpg";
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$checkImage = 'data:image/' . $type . ';base64,' . base64_encode($data);

   ?>
	<div>


		<input type="text" name="pension_wording_50_50" id="pension_wording_50_50" class="text" value="<?=$pension_wording_50_50;?>">

		<?php if($pension_5050_strike != "elmHidden") : ?>
			<div class="strike <?=$pension_5050_strike;?>" id="pension_5050_strike"></div>
		<?php endif; ?>

		<?php if($pension_5050_strike_2 != "elmHidden") : ?>
			<div class="strike <?=$pension_5050_strike_2;?>" id="pension_5050_strike_2"></div>
		<?php endif; ?>
			
		<div class="strike <?=$pension_sharing_strike;?>" id="pension_sharing_strike"></div>
		<div class="strike <?=$pension_sharing_strike_2;?>" id="pension_sharing_strike_2"></div>

		<input type="text" name="pension_sharing_other_wording" id="pension_sharing_other_wording" class="text" value="<?=$pension_sharing_other_wording;?>"> 


		<?php if($filed_d81_yes != "elmHidden") : ?>
			<img id="filed_d81_yes" class="text tick <?=$filed_d81_yes;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($filed_d81_no != "elmHidden") : ?>
			<img id="filed_d81_no" class="text tick <?=$filed_d81_no;?>" src="<?=$checkImage;?>">
		<?php endif; ?>

		<?php if($filed_strike_1 != "elmHidden") : ?>
		<div class="strike <?=$filed_strike_1;?>" id="filed_strike_1"></div>
		<?php endif; ?>

		<?php if($filed_strike_2 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_2;?>" id="filed_strike_2"></div>
		<?php endif; ?>

		<?php if($filed_strike_3 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_3;?>" id="filed_strike_3"></div>
		<?php endif; ?>

		<?php if($filed_strike_4 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_4;?>" id="filed_strike_4"></div>
		<?php endif; ?>

		<?php if($filed_strike_5 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_5;?>" id="filed_strike_5"></div>
		<?php endif; ?>

		<?php if($filed_strike_6 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_6;?>" id="filed_strike_6"></div>
		<?php endif; ?>

		<?php if($filed_strike_7 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_7;?>" id="filed_strike_7"></div>
		<?php endif; ?>

		<?php if($filed_strike_8 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_8;?>" id="filed_strike_8"></div>
		<?php endif; ?>

		<?php if($filed_strike_9 != "elmHidden") : ?>
			<div class="strike <?=$filed_strike_9;?>" id="filed_strike_9"></div>
		<?php endif; ?>

		<?php if($internal_transfer != "elmHidden") : ?>
			<img id="internal_transfer" class="text tick <?=$internal_transfer;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($external_transfer != "elmHidden") : ?>
			<img id="external_transfer" class="text tick <?=$external_transfer;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<textarea name="name_of_agreement" id="name_of_agreement" class="textarea"><?=$name_of_agreement;?></textarea>
		<textarea name="qualifying_arrangement_address" id="qualifying_arrangement_address" class="textarea"><?=wordwrap(nl2br($qualifying_arrangement_address),45,"<br />",true);?></textarea>
		<textarea name="qualifying_arrangement_policy_num" id="qualifying_arrangement_policy_num" class="textarea"><?=$qualifying_arrangement_policy_num;?></textarea>


		<img src="<?=$base64;?>" alt="">
	</div>


	<?php 
 		$path = getcwd() . "/images/consent_order_docs/P1/page4.jpg";
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>	


		<?php if($copy_atatched != "elmHidden") : ?>
			<img id="copy_atatched" class="text tick <?=$copy_atatched;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($credit_derived_yes != "elmHidden") : ?>
			<img id="credit_derived_yes" class="text tick <?=$credit_derived_yes;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($credit_derived_no != "elmHidden") : ?>
			<img id="credit_derived_no" class="text tick <?=$credit_derived_no;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($transferor_health_yes != "elmHidden") : ?>
			<img id="transferor_health_yes" class="text tick <?=$transferor_health_yes;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($transferor_health_no != "elmHidden") : ?>
			<img id="transferor_health_no" class="text tick <?=$transferor_health_no;?>" src="<?=$checkImage;?>"> 
		<?php endif; ?>

		<?php if($info_requested_yes != "elmHidden") : ?>
			<img id="info_requested_yes" class="text tick <?=$info_requested_yes;?>" src="<?=$checkImage;?>">
		<?php endif; ?>

		<?php if($info_requested_no != "elmHidden") : ?>
			<img id="info_requested_no" class="text tick <?=$info_requested_no;?>" src="<?=$checkImage;?>">
		<?php endif; ?>
		
		<img src="<?=$base64;?>" alt="">
	</div>


	<div>
		<?php if ($court_directs_strike_page1 != "elmHidden" || $doc_path == 1) : ?>
			<div class="strike <?= $court_directs_strike_page1; ?>" id="court_directs_strike_page1"></div>
		<?php endif; ?>

		<?php if ($court_directs_strike_page2 != "elmHidden" || $doc_path == 1) : ?>
			<div class="strike <?= $court_directs_strike_page2; ?>" id="court_directs_strike_page2"></div>
		<?php endif; ?>

		<?php if ($court_directs_strike_page3 != "elmHidden" || $doc_path == 1) : ?>
			<div class="strike <?= $court_directs_strike_page3; ?>" id="court_directs_strike_page3"></div>
		<?php endif; ?>

		<?php
		$path = $doc_src . "/images/consent_order_docs/P1/page5.jpg";
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		?>
		<img src="<?= $base64; ?>">
	</div>

</body>
</html>