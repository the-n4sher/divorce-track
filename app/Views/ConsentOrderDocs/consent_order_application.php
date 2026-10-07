<?php
   
   //print_r($qdata);

  //create the applicant name
  $app_name = $qdata[0]['app_forename'] . " ";
  $app_name .= ( $qdata[0]['app_other_names'] != "" ) ? $qdata[0]['app_other_names'] . " " : "";
  $app_name .= $qdata[0]['app_surname'];
  $app_name = ucwords($app_name);

  //create the respondent name
  $res_name = $qdata[0]['res_forename'] . " ";
  $res_name .= ( $qdata[0]['res_other_names'] != "" ) ? $qdata[0]['res_other_names'] . " " : "";
  $res_name .= $qdata[0]['res_surname'];
  $res_name = ucwords($res_name);

  #court name
  $court_name = ucfirst($qdata[0]['name_of_court'] );

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Consent Order Application</title>
</head>

<style>
	html * {
				font-family: 'Times New Roman', serif;
			}
			
			html, body {
				margin: 0;
				padding: 0;
				position: relative;
			}

			body > div {
				background-color: #fff;
				width: 100%;
				text-align: center;
				position: relative;
				margin: 0;
				padding: 0;
			}

			img {
				width: 85%;
				height:auto;
				margin: 0 3%;
				position: relative;
				z-index: 0;
			}

			.text,.textarea {
				position: absolute;
				z-index: 10;
				border: none;
				text-align: left;
				font-size: 15px;
			}

			.tick {
				width: 10px;
				height: 10px;
			}

			.small-character {
				line-height: 0.9;
				width: 10px;
				height: 10px;
			}

			#full-name{
				top:160px;
				left:112px;
				width:350px;
				height: 21px;
			}

			#address{
				top:179px;
				left:112px;
				width:350px;
				height: 112px;
				font-size:14px!important;
				font-family: inherit;
				font-size: inherit;
				font-weight: inherit;
			}

			#petitioner-vs-respondent{
				top: 455px;
				left:112px;
				width:600px;
				height: 21px;
			}

			#signed-petitioner-fullname{
				top: 755px;
				left:112px;
				width:350px;
				height: 21px;
			}

			.text,.textarea {
				position: absolute;
				z-index: 10;
				border: none;
				text-align: left;
				background: none;
			}

			#case_number{
				top:90px;
				left:560px;
				font-weight: bold;
			}

			#app_name{
				top:130;
				left:235;
				padding:0px;
				width:200;
				font-size:20px;
			}

			#res_name{
				top:260px;
				left:235;
				padding:0px;
				width:200;
				font-size:20px;
			}

			#court_name{
				font-size:18px;
				top:48px;
				left:310px;
				width:400px;
				font-weight:bold;
			}


</style>


<body>

     <input type="text" name="case_number" id="case_number" value="<?=$qdata[0]['case_number'];?>" class="text">
     <input type="text" name="court_name" id="court_name" value="<?=$court_name;?>" class="text">
     <input type="text" name="app_name" id="app_name" value="<?=$app_name;?>" class="text">
     <input type="text" name="res_name" id="res_name" value="<?=$res_name;?>" class="text">


    <?php 
    	$path = getcwd() . "/images/consent_order_docs/Consent_Order_Application/page1.jpg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
   ?>
	<img src="<?=$base64;?>" alt="">
</body>
</html>