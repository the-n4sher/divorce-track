<?php

  $qdata = $qdata[0];

  #case number
  $case_number = $qdata['case_number'];

  //create the applicant name
  $app_name = $qdata['app_forename'] . " ";
  $app_name .= ( $qdata['app_other_names'] != "" ) ? $qdata['app_other_names'] . " " : "";
  $app_name .= $qdata['app_surname'];
  $app_name = ucwords($app_name);

  //create the respondent name
  $res_name = $qdata['res_forename'] . " ";
  $res_name .= ( $qdata['res_other_names'] != "" ) ? $qdata['res_other_names'] . " " : "";
  $res_name .= $qdata['res_surname'];
  $res_name = ucwords($res_name);

  #court name
  $court_name = ucfirst($qdata['name_of_court'] );

  
  // Set default values for the a_postcode
  // Manually set Page 5
  $a_postcode1 = "S";
  $a_postcode2 = "N";
  $a_postcode3 = "5";
  $a_postcode4 = "";
  $a_postcode5 = "7";
  $a_postcode6 = "Y";
  $a_postcode7 = "J";

  $app_address_line1 = "Divorce-Online Unit 3 The Meads Business Centre";
  $app_address_line2 = "Ashworth Road";
  $app_address_town = "Swindon";
  $app_address_county = "Wiltshire";
  $app_address_country = "";
 
  // If the package is managed by DOL OR OLSS then we set the address, else we set it to what the client set
  $exclude = ['CBC', 'CBCS', 'DDPCBCS'];

  if($package_code === null || !in_array($package_code , $exclude))
  {
  	// Manually set Page 5
 		$a_postcode1 = "S";
		$a_postcode2 = "N";
		$a_postcode3 = "5";
		$a_postcode4 = "";
		$a_postcode5 = "7";
		$a_postcode6 = "Y";
		$a_postcode7 = "J";

		$app_address_line1 = "Divorce-Online Unit 3 The Meads Business Centre";
		$app_address_line2 = "Ashworth Road";
		$app_address_town = "Swindon";
		$app_address_county = "Wiltshire";
		$app_address_country = "";

  }else if ($package_code !== null && in_array($package_code, $exclude)){

  	$app_address_line1 = $qdata["app_address_line1"];
		$app_address_line2 = $qdata["app_address_line2"];
		$app_address_town = $qdata["app_address_town_city"];
		$app_address_county = $qdata["app_address_county"];
		$app_address_country = $qdata["country_name_app"];


		//we now need to find out if the post has 2, 3 or 4 characters in the first section for eg DB, ADFS YTR OR  TYG JUYH
		$app_postcode_pieces = preg_split('/\s+/', $qdata["app_address_postcode"]);
		$pos = strrpos($qdata["app_address_postcode"], " ");

		if($pos == "")
		{
			if(strlen($qdata["app_address_postcode"]) == 6)
			{
				$app_postcode_pieces = str_split($qdata["app_address_postcode"], 3);
				$qdata["app_address_postcode"] = $app_postcode_pieces[0]." ".$app_postcode_pieces[1];

			}elseif(strlen($qdata["app_address_postcode"]) > 6){
				$app_postcode_pieces = str_split($qdata["app_address_postcode"], 4);
				$qdata["app_address_postcode"] = $app_postcode_pieces[0]." ".$app_postcode_pieces[1];
			}
		}



		if(strlen($app_postcode_pieces[0]) == 2)
		{

			$app_postcode_pieces = str_split($qdata["app_address_postcode"]);
			$a_postcode1 = isset($qdata["app_address_postcode"][0]) ? strtoupper($qdata["app_address_postcode"][0]) : "";
			$a_postcode2 = isset($qdata["app_address_postcode"][1]) ? strtoupper($qdata["app_address_postcode"][1]) : "";
			$a_postcode3 = isset($qdata["app_address_postcode"][2]) ? strtoupper($qdata["app_address_postcode"][2]) : "";
			$a_postcode4 = isset($qdata["app_address_postcode"][3]) ? strtoupper($qdata["app_address_postcode"][3]) : "";
			$a_postcode5 = isset($qdata["app_address_postcode"][4]) ? strtoupper($qdata["app_address_postcode"][4]) : "";
			$a_postcode6 = isset($qdata["app_address_postcode"][5]) ? strtoupper($qdata["app_address_postcode"][5]) : "";
			$a_postcode7 = ( isset($qdata["app_address_postcode"][6]) && $qdata["app_address_postcode"][6] != "") ? strtoupper($qdata["app_address_postcode"][6]) : "";
			
		}


		if(strlen($app_postcode_pieces[0]) == 3)
		{
			$a_postcode1 = isset($qdata["app_address_postcode"][0]) ? strtoupper($qdata["app_address_postcode"][0]) : "";
			$a_postcode2 = isset($qdata["app_address_postcode"][1]) ? strtoupper($qdata["app_address_postcode"][1]) : "";
			$a_postcode3 = isset($qdata["app_address_postcode"][2]) ? strtoupper($qdata["app_address_postcode"][2]) : "";
			$a_postcode4 = isset($qdata["app_address_postcode"][4]) ? strtoupper($qdata["app_address_postcode"][4]) : "";
			$a_postcode5 = isset($qdata["app_address_postcode"][5]) ? strtoupper($qdata["app_address_postcode"][5]) : "";
			$a_postcode6 = isset($qdata["app_address_postcode"][6]) ? strtoupper($qdata["app_address_postcode"][6]) : "";
			$a_postcode7 = ( isset($qdata["app_address_postcode"][7]) && $qdata["app_address_postcode"][7] != "") ? strtoupper($qdata["app_address_postcode"][7]) : "";


		}elseif(strlen($app_postcode_pieces[0]) == 4){

			$a_postcode1 = isset($qdata["app_address_postcode"][0]) ? strtoupper($qdata["app_address_postcode"][0]) : "";
			$a_postcode2 = isset($qdata["app_address_postcode"][1]) ? strtoupper($qdata["app_address_postcode"][1]) : "";
			$a_postcode3 = isset($qdata["app_address_postcode"][2]) ? strtoupper($qdata["app_address_postcode"][2]) : "";
			$a_postcode4 = isset($qdata["app_address_postcode"][3]) ? strtoupper($qdata["app_address_postcode"][3]) : "";
			$a_postcode5 = isset($qdata["app_address_postcode"][5]) ? strtoupper($qdata["app_address_postcode"][5]) : "";
			$a_postcode6 = isset($qdata["app_address_postcode"][6]) ? strtoupper($qdata["app_address_postcode"][6]) : "";
			$a_postcode7 = isset($qdata["app_address_postcode"][7]) ? strtoupper($qdata["app_address_postcode"][7]) : "";

		}

  }
 	


  // Get the respondent address
  $res_address_line1 = $qdata["res_address_line1"];
  $res_address_line2 = $qdata["res_address_line2"];
  $res_address_town = $qdata["res_address_town_city"];
  $res_address_county = $qdata["res_address_county"];

  // Get the country
	$res_address_country = $qdata["country_name_res"];

	//we now need to find out if the post has 3 or 4 characters in the first section for eg ADFS YTR OR  TYG JUYH
	$res_postcode_pieces = preg_split('/\s+/', $qdata["res_address_postcode"]);

	$pos = strrpos($qdata["res_address_postcode"], " ");
	if($pos == "")
	{
		if(strlen($qdata["res_address_postcode"]) == 6)
		{
			$res_postcode_pieces = str_split($qdata["res_address_postcode"], 3);
			$qdata["res_address_postcode"] = $res_postcode_pieces[0]." ".$res_postcode_pieces[1];

		}elseif(strlen($qdata["res_address_postcode"]) > 6){
			$res_postcode_pieces = str_split($qdata["res_address_postcode"], 4);
			$qdata["res_address_postcode"] = $res_postcode_pieces[0]." ".$res_postcode_pieces[1];
		}
	}

	$r_postcode1 = "";
	$r_postcode2 = "";
	$r_postcode3 = "";
	$r_postcode4 = "";
	$r_postcode5 = "";
	$r_postcode6 = "";
	$r_postcode7 = "";
	$r_postcode8 = "";
	
	if($qdata['res_address_postcode'] != "")
	{
		
		if(strlen($res_postcode_pieces[0]) == 3){
			$r_postcode1 = isset($qdata["res_address_postcode"][0]) ? strtoupper($qdata["res_address_postcode"][0]) : "";
			$r_postcode2 = isset($qdata["res_address_postcode"][1]) ? strtoupper($qdata["res_address_postcode"][1]) : "";
			$r_postcode3 = isset($qdata["res_address_postcode"][2]) ? strtoupper($qdata["res_address_postcode"][2]) : "";
			$r_postcode4 = isset($qdata["res_address_postcode"][4]) ? strtoupper($qdata["res_address_postcode"][4]) : "";
			$r_postcode5 = isset($qdata["res_address_postcode"][5]) ? strtoupper($qdata["res_address_postcode"][5]) : "";
			$r_postcode6 = isset($qdata["res_address_postcode"][6]) ? strtoupper($qdata["res_address_postcode"][6]) : "";
			$r_postcode7 = ( isset($qdata["res_address_postcode"][7]) && $qdata["res_address_postcode"][7] != "")  ? strtoupper($qdata["res_address_postcode"][7]) : "";

		}elseif(strlen($res_postcode_pieces[0]) == 4){

			$r_postcode1 = isset($qdata["res_address_postcode"][0]) ? strtoupper($qdata["res_address_postcode"][0]) : "";
			$r_postcode2 = isset($qdata["res_address_postcode"][1]) ? strtoupper($qdata["res_address_postcode"][1]) : "";
			$r_postcode3 = isset($qdata["res_address_postcode"][2]) ? strtoupper($qdata["res_address_postcode"][2]) : "";
			$r_postcode4 = isset($qdata["res_address_postcode"][3]) ? strtoupper($qdata["res_address_postcode"][3]) : "";
			$r_postcode5 = isset($qdata["res_address_postcode"][5]) ? strtoupper($qdata["res_address_postcode"][5]) : "";
			$r_postcode6 = isset($qdata["res_address_postcode"][6]) ? strtoupper($qdata["res_address_postcode"][6]) : "";
			$r_postcode7 = isset($qdata["res_address_postcode"][7]) ? strtoupper($qdata["res_address_postcode"][7]) : "";
		}
	}

	$path = getcwd() . "/images/case_document_images/tick.jpg";
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$tickImage = 'data:image/' . $type . ';base64,' . base64_encode($data);
 ?>

<style>
		html * {font-family: 'Times New Roman', serif;} 
		html, body {margin: 0;padding: 0;position: relative;}
		body > div {background-color: #fff;width: 100%;	page-break-after: always;text-align: center;position: relative;margin: 0;padding: 0;z-index: 0;}
		body > *:last-child {page-break-after: auto;}
		img {width: 85%;height:auto;margin: 0 3%;position: relative;z-index: 0;}
		.text,.textarea {position: absolute;z-index: 10;border: none;text-align: left;font-size: 15px;}
		.tick {width: 10px;height: 10px;}
		.text small-character {line-height: 0.9;width: 10px;height: 10px;}
		.text,.textarea {position: absolute;z-index: 10;border: none;text-align: left;background: none;}
		.elmHidden{display:none;}

		/* PAGE 1 */
		#court_name{top:75px;right:232px;font-size:14px;}
		#case_number{top:74px;right:55px;font-size: 13px;}

		#app_name{top:430px;left:100px;font-size:18px;width:300px;}
		#res_name{top:500px;left:100px;font-size:18px;width:300px;}

		/*#in_connection_with_tick{top:477px;left:69px;width:9px;height:9px;}
		#to_proceed_with_tick{top:618px;left:69px;width:9px;height:9px;}
		#more_info{top:897px;left:411px;width:9px;height:9px;}
*/
		#not_presented_by_tick{top:78px;left:104px;width:9px;height:9px;}

		#res_name2{top:99px;left:300px;font-size:15px;width:200px;}

		/* Page 2 */
		#less_than_250_yes{top:114px;left:118px;width:9px;height:9px;}
		#less_than_250_no{top:137px;left:118px;width:9px;height:9px;}


		/* Page 2 */
		<?php if( isset($qdata['less_than_250']) ) : ?>
			<?php if($qdata['less_than_250'] == "1") : ?>
				#less_than_250_no{display:none!important;}
			<?php else : ?>
				#less_than_250_yes{display:none!important;}
			<?php endif; ?>
		<?php else : ?>
			#less_than_250_yes, #less_than_250_no{display:none!important;}
		<?php endif; ?>

		/* PAGE 5*/
		#app_address_line1{top:415px;left:125px;font-size:15px;width:420px;}
		#app_address_line2{top:468px;left:125px;font-size:15px;width:420px;}
		#app_address_town{top:525px;left:125px;font-size:15px;width:235px;}
		#app_address_county{top:581px;left:125px;font-size:15px;width:235px;}
		#app_address_country{top:638px;left:125px;font-size:15px;width:235px;}

		#a_postcode1{top:690px;left:124px;}
		#a_postcode2{top:690px;left:150px;}
		#a_postcode3{top:690px;left:174px;}
		#a_postcode4{top:690px;left:198px;}
		#a_postcode5{top:690px;left:222px;}
		#a_postcode6{top:690px;left:246px;}
		#a_postcode7{top:690px;left:270px;}


		/* PAGE 6 */
		#res_address_line1{top:570px;left:122px;font-size:16px;width:420px;}
		#res_address_line2{top:624px;left:122px;font-size:16px;width:420px;}

		#res_address_town{top:680px;left:122px;font-size:16px;width:235px;}
		#res_address_county{top:735px;left:122px;font-size:16px;width:235px;}
		#res_address_country{top:793px;left:122px;font-size:16px;width:235px;}

		#r_postcode1{top:855px;left:124px;}
		#r_postcode2{top:855px;left:150px;}
		#r_postcode3{top:855px;left:174px;}
		#r_postcode4{top:855px;left:198px;}
		#r_postcode5{top:855px;left:222px;}
		#r_postcode6{top:855px;left:246px;}
		#r_postcode7{top:855px;left:270px;}

		/* Page 16 */
		#signed_app_name{top:510px;left:155px;font-size:18px;width: 420px;}

		
		
</style>

 <div>
         <input type="text" name="court_name" id="court_name" class="text" value="<?=$court_name;?>">
         <input type="text" name="case_number" id="case_number" class="text" value="<?=$case_number;?>">
 
         <input type="text" name="app_name" id="app_name" class="text" value="<?=$app_name;?>">
         <input type="text" name="res_name" id="res_name" class="text" value="<?=$res_name;?>">

    <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page1.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);

			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>
 		<img src="<?=$base64;?>" alt="">
 	</div>
 	

 	<?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page2.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
	 	<img id="less_than_250_yes" class="text tick" src="<?=$tickImage;?>"> 
	 	<img id="less_than_250_no" class="text tick" src="<?=$tickImage;?>"> 
 		<img src="<?=$base64;?>" alt="">
 	</div>
 


  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page3.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
 		<img src="<?=$base64;?>" alt="">
 	</div> 
 


 <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page4.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
 		<img src="<?=$base64;?>" alt="">
 	</div>
 	

 	
 	<?php 
 	    $customFile = ( $package_code !== null && $package_code == "CBCS" || $package_code == "DDPCBCS" ) ? "page5_cbcs.jpg" : "page5.jpg";
    	$path = getcwd() . "/images/consent_order_docs/Form_A/{$customFile}";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>

 		<?php if($package_code !== null && ($package_code == "CBCS" || $package_code == "DDPCBCS")) : ?>
	 		<input id="app_address_line1" name="app_address_line1" type="text" class="text" value="<?=$app_address_line1;?>" />
	 		<input id="app_address_line2" name="app_address_line2" type="text" class="text" value="<?=$app_address_line2;?>" />

	 		<input id="app_address_town" name="app_address_town" type="text" class="text" value="<?=$app_address_town;?>" />
	 		<input id="app_address_county" name="app_address_county" type="text" class="text" value="<?=$app_address_county;?>" />
	 		<input id="app_address_country" name="app_address_country" type="text" class="text" value="<?=$app_address_country;?>" />


	 		<input id="a_postcode1" class="text small-character" value="<?=$a_postcode1;?>" type="text" maxlength="1">
	 		<input id="a_postcode2" class="text small-character" value="<?=$a_postcode2;?>" type="text" maxlength="1">
	 		<input id="a_postcode3" class="text small-character" value="<?=$a_postcode3;?>" type="text" maxlength="1">
	 		<input id="a_postcode4" class="text small-character" value="<?=$a_postcode4;?>" type="text" maxlength="1">
	 		<input id="a_postcode5" class="text small-character" value="<?=$a_postcode5;?>" type="text" maxlength="1">
	 		<input id="a_postcode6" class="text small-character" value="<?=$a_postcode6;?>" type="text" maxlength="1">
	 		<input id="a_postcode7" class="text small-character" value="<?=$a_postcode7;?>" type="text" maxlength="1">
	 	<?php endif;?>

 		<img src="<?=$base64;?>" alt="">
 	</div>
 


  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page6.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>

 		<input type="text" name="res_address_line1" id="res_address_line1" class="text" value="<?=$res_address_line1;?>">
 		<input type="text" name="res_address_line2" id="res_address_line2" class="text" value="<?=$res_address_line2;?>">

 		<input type="text" name="res_address_town" id="res_address_town" class="text" value="<?=$res_address_town;?>">
 		<input type="text" name="res_address_county" id="res_address_county" class="text" value="<?=$res_address_county;?>">
 		<input type="text" name="res_address_country" id="res_address_country" class="text" value="<?=$res_address_country;?>">


 		<input id="r_postcode1" class="text small-character" value="<?=$r_postcode1;?>" type="text" maxlength="1">
 		<input id="r_postcode2" class="text small-character" value="<?=$r_postcode2;?>" type="text" maxlength="1">
 		<input id="r_postcode3" class="text small-character" value="<?=$r_postcode3;?>" type="text" maxlength="1">
 		<input id="r_postcode4" class="text small-character" value="<?=$r_postcode4;?>" type="text" maxlength="1">
 		<input id="r_postcode5" class="text small-character" value="<?=$r_postcode5;?>" type="text" maxlength="1">
 		<input id="r_postcode6" class="text small-character" value="<?=$r_postcode6;?>" type="text" maxlength="1">
 		<input id="r_postcode7" class="text small-character" value="<?=$r_postcode7;?>" type="text" maxlength="1">


 		<img src="<?=$base64;?>" alt="">
 	</div>
 

 <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page7.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
 		<img src="<?=$base64;?>" alt="">
 	</div>
 

  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page8.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
 		<img src="<?=$base64;?>" alt="">
 	</div>
 

  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page9.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
 		<img src="<?=$base64;?>" alt="">
 	</div>
 


  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page10.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
 	<div>
 		<img src="<?=$base64;?>" alt="">
 	</div>


  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page11.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>



  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page12.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>



  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page13.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>



  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page14.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>



  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page15.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>


  <?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page16.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<input type="text" name="signed_app_name" id="signed_app_name" class="text" value="<?=$app_name;?>">
		<img src="<?=$base64;?>" alt="">
	</div>


	<?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page17.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>

	<?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page18.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>

	<?php 
    	$path = getcwd() . "/images/consent_order_docs/Form_A/page19.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<div>
		<img src="<?=$base64;?>" alt="">
	</div>