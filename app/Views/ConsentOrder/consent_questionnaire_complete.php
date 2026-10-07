<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>

<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>

	<br /><br />
	<div class="row">
		<div class="col-8 coff">
			<div class="panel panel-primary">
				<div class="panel-heading"><h2></h2></div>
				<div class="panel-body">

					<?php 
						$wording = ( in_array($package_code, ["CBO", "CBC", "DPPCBO"]) ) ? "Clean Break Order" : "Consent Order application" ; 
					    
						//If CBCS the title needs to be "Basic Clean Break Questionnaire"
						if(isset($this->package_code) && in_array($this->package_code, $codesToCheck)){
							$wording = "Basic Clean Break Questionnaire";
						}
					?>
					
					
					<h1><?php echo (in_array($package_code, $codesToCheck)) ? "Basic Clean Break Questionnaire Complete" : "Questionnaire Complete"; ?></h1><br>
                     
					<?php if(isset($package_code) && in_array($package_code, $codesToCheck)):?>
						<p>
							Thank you for completing your Clean Break Order Questionnaire. <br /><br />
							If you refresh your screen you will see that your paperwork is ready for you download from the 'Simple Clean Break Documents' menu item on the left hand side of your screen. <br /><br />
							We would urge you to download, print and read the Guide to Procedure before anything else.<br /><br />
							If you have any questions, please email <a href="mailto:support@divorce-online.co.uk" target="_blank">support@divorce-online.co.uk</a><br /><br />
						</p>

						<!-- add a button that reloads the current page -->
						<br />
						<button class="btn btn-primary" onclick="window.location.reload();">Refresh Page</button>
						
					<?php else:?>
						<p>
							Thank you for completing your <?=$wording;?> Fact Finder; this will be placed on file to forward to our Solicitor to begin drafting within 1-2 days. <br><br>

							<?php 
								// Need to check if the order was placed before 20th May 2025 and if its not a fast track service
								if($is_ft == false && $orderPlaced < "2025-05-20 00:00:00"){
									$num_days = 28;
								// if purchased between 20th May 2025 and 30th June 2025, we want to use 14 days
								}else if($is_ft == false && $orderPlaced >= "2025-05-20 00:00:00" && $orderPlaced <= "2025-06-30 23:59:59"){
									$num_days = 14;
								}else{
									$num_days = $is_ft == true ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : getenv("CONSENT_ORDER_DUE_DAYS_STANDARD");
								}
							?>

							Once submitted for drafting this process can take up to <?=$num_days;?> working days; therefore we would prefer that you do not enquire about the current status until after the <?=$num_days;?> working days have passed - the due date will be the <b><?=$future_date;?></b>. <br><br>
							
							<?php if($is_ft == false):?>
								Should you wish to FAST TRACK this service to have the <?=$wording;?> drafted within 7 working days for £200.00, then please make contact on 01793 384029.<br /><br />
								If you fast tracked your application today, the due date would be the <b><?=$date_if_fast_tracked;?></b>.
							<?php endif;?>
						</p>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>

<?php $this->endSection() ;?>