<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>



<style>
	.hide-section{
		display: none;
	}
</style>
<br /><br />
<div class="row">

	<?php  

		if(session()->has("user_error") != ""){
                echo ' <div class="alert alert-danger">';
                        echo session("user_error");
                echo '</div>'; 
        }      

	?>


	<div class="col-8">
        
	    <?php  
        // If isset package_consent_type and package type is null or 0 or 2 - this means is a managed service from us or standard divorce service
		if(isset($package_consent_type) && ($package_consent_type == null || $package_consent_type == 0 || $package_consent_type == 2) || $package_code == "DDPCBCS") :
		?>
		    <!-- If is_issued is set and is true then show the court details -->
			<?php if(isset($is_issued) && $is_issued == true) : ?>
				<div class="panel panel-primary">
					<div class="panel-heading"><h2>Court Details</h2></div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="well" style="background-color: #f8f9fa; border-left: 4px solid #337ab7; padding: 15px; margin-bottom: 10px;">
									<p style="margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase;"><strong>Court Name</strong></p>
									<p style="margin: 0; font-size: 16px; color: #333;"><b>CTSC</b></p>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="well" style="background-color: #f8f9fa; border-left: 4px solid #337ab7; padding: 15px; margin-bottom: 10px;">
									<p style="margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase;"><strong>Case Number</strong></p>
									<p style="margin: 0; font-size: 16px; color: #333;"><?= ($current_case_number != "") ? "<b>".$current_case_number."</b>" : "<span class='text-muted'>Not available</span>" ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

        <!-- if the consent type is 1 and consent_ff_complete is set and is true - then we show this message -->
		<?php elseif($package_consent_type == 1 && isset($consent_ff_complete) && $consent_ff_complete === true) : ?>
			<div class="panel panel-primary">
					<div class="panel-heading"><h2>Court Details</h2></div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="well" style="background-color: #f8f9fa; border-left: 4px solid #337ab7; padding: 15px; margin-bottom: 10px;">
									<p style="margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase;"><strong>Court Name</strong></p>
									<p style="margin: 0; font-size: 16px; color: #333;"><b>COURT FROM FF</b></p>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="well" style="background-color: #f8f9fa; border-left: 4px solid #337ab7; padding: 15px; margin-bottom: 10px;">
									<p style="margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase;"><strong>Case Number</strong></p>
									<p style="margin: 0; font-size: 16px; color: #333;">CASE NUMBER FROM FF</p>
								</div>
							</div>
						</div>
					</div>
				</div>
		<?php else : ?>

		<?php endif; ?>


	<?php if($is_sol_managed == 1 ) : ?>
		
		<div class="panel panel-primary">
				<div class="panel-heading"><h2></h2></div>
				<div class="panel-body">
					<p>
						Your service is provided by our partner company OLS Solicitors.<br /><br />
                        They have been notified of your purchase and will be in touch shortly to start working for you.<br /><br />

                        In the meantime their contact details are<br /><br />

                        Email: <a href="mailto:general@olssolicitors.co.uk">general@olssolicitors.co.uk</a><br />
                        Phone: 01554 756952.<br />
				    </p>
				</div>
			</div>


	<?php elseif($use_portal == true) :?>

		<?php if($package_consent_type == "2") : ?>

			<?php if($consent_ff_complete === true ) : ?>

				<div class="panel panel-primary">
				      <div class="panel-heading"><h2>Progress Of My Consent Order</h2></div>
					<div class="panel-body"> 		
					Thank you for completeing your consent order fact finder. <a href="<?=site_url("consent-order/consent-order-progress");?>"><span>Click here to check for account updates</span></a>
				     </div>
				</div>

			<?php else : ?>

				<?php if($is_issued === true && $orderPlaced >= "2023-12-12 11:30:00") : ?>
					<div class="panel panel-primary">
					    <div class="panel-heading"><h2>Progress Of My Consent Order</h2></div>

						<div class="panel-body">
                            <p id="assets-question">Before you start to complete the Consent Order Fact Finder - can you please confirm if your COMBINED assets are over £3,000.000 (£3 Million) </p>
        
                            <div id="assets-question-options">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assets-question" id="assets-question-yes" value="1">
                                    <label class="form-check-label" for="assets-question-yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assets-question" id="assets-question-no" value="0">
                                    <label class="form-check-label" for="assets-question-no">No</label>
                                </div>
                            </div>
                        </div>

						<p id="upgrade-message" style="display: none!important;font-weight: bold;margin-left:40px;">
                            Thank you for confirming your assets are over £3,000.000 (£3 Million) - as such your service will need to be managed by our partner Solicitors to manage this for you - please contact the team on 01793 384029 to discuss this further
                        </p>

						<div class="panel-body" id="start-co-ff-button" style="display: none!important;">		
						  <a href="<?=site_url("consent-order/consent_order_ff/1");?>" class="btn btn-primary"><span>Please complete the consent order fact finding questionnaire.</span></a>
					    </div>

					</div>
				<?php endif;?>
			<?php endif;?>

		<?php endif;?>
		
		<div class="panel panel-primary">
				<div class="panel-heading"><h2>Progress of my divorce application</h2></div>
				<div class="panel-body">
					<h4 style="text-align: center">Welcome to Divorce Track! See below for the current progress of your no-fault divorce application and what to do next.</h4>

					<?php if($current_step_wording == "" ) : ?>

					   <?php if($is_vip === true && $cert_found === true) : ?>

					   	<a href="<?=site_url("questionnaire/stage1");?>" onclick="return confirm('Your dedicated case manager will be in contact shortly to go through the online Fact Finder with you - however if you wish to continue yourself please click `OK` otherwise please click `cancel` and wait for your case manger.');" class="btn btn-primary"><span>Please complete the fact finding questionnaire.</span></a>

					   <?php else : ?>

						<a href="<?=site_url("questionnaire/stage1");?>" class="btn btn-primary"><span>Please complete the fact finding questionnaire..</span></a>

					   <?php endif;?>


					<?php else: ?>
						<div class="alert alert-info" style="text-align: center">
							<b><i>Divorce Process Update:</i></b> <?=$current_step_wording;?>

							<?php if($conditional_order_date != null) : ?>
								<br /><br />
								<p>Please note that we cannot proceed with this application until after <b><?=$conditional_order_date;?></b> due to the 20-week cooling off period.
								<br /><br />You will receive an email on the above date for you to reply to confirm you are in agreement for Divorce Online to continue with the application.</p>
							<?php endif;?>
						</div>
					<?php endif;?>
				</div>
			</div>

	<?php else : ?>


		<?php if($requires_q) : ?>


			<?php if($orderPlaced >= $date_for_condition ) : ?>
				
				
				<div class="panel panel-primary">
				       <div class="panel-heading"><h2>Progress of my divorce application.</h2></div>
					<div class="panel-body">
						<h4 style="text-align: center">Welcome to Divorce Track! See below for the current progress of your no-fault divorce application and what to do next.</h4>

						<?php if($current_step_wording == "" ) : ?>
						<a href="<?=base_url();?>questionnaire/stage1" class="btn btn-primary"><span>Please complete the fact finding questionnaire...</span></a>
						<?php else: ?>
							<div class="alert alert-info" style="text-align: center"><b><i>Divorce Process Update:</i></b> <?=$current_step_wording;?></div>
						<?php endif;?>
					</div>
				</div>

			<?php else : ?>

				<div class="panel panel-primary">
					<div class="panel-heading"><h2></h2></div>
					<div class="panel-body">
						<h1>Divorce Process</h1>
						<p>Since <b>April 2022</b> the law has changed in regards to Divorce.</p>
						<p>However, as you started your divorce prior to this, you can obtain an update of where your Divorce is by clicking <b><a href="<?=site_url('account/notifications');?>">here.</a></b></p>
					</div>
				</div>

			<?php endif;?>

		<?php else : ?>

			<?php   

			  $customDocs = [
			  	"SCN" => [
			  	  "title" => "Non Simplified Scottish Divorce Service",
			  	  "wording" => "	
						Thank you for purchasing the Ordinary Scottish Divorce Service<br />
						In order to continue, please down load the fact finder and follow the instructions.",
			  	  "document" => site_url("documents/FACT_FINDERS/ScottishDivorceQuestionnaire.doc")
			  	]
			  ];

			  $title = ( array_key_exists($package_code, $customDocs) ) ? $customDocs[$package_code]['title'] : "";
			  $wording = ( array_key_exists($package_code, $customDocs) ) ? $customDocs[$package_code]['wording'] : "";
			  $document = ( array_key_exists($package_code, $customDocs) ) ? $customDocs[$package_code]['document'] : "";


			?>

			<?php if($title != "" && $wording != "") : ?>

				<div class="col-8">
				   <div class="panel panel-primary">
				            <div class="panel-heading">
				            	<h1><?=$title;?></h1>
				            </div>
					     <div class="panel-body">
						<p><?=$wording;?></p>
						<?php if($document != "") : ?>
							<br /><a class="btn btn-primary" href="<?=$document;?>">Download Fact Finder</a>
						<?php endif;?>
		        		     </div>
		    		   </div>
		 		</div>

				
			<?php endif;?>

		<?php endif;?>

    	          

	<?php endif;?>

	</div>
</div>


<script>
    $(document).ready(function() {
        $("#assets-question-yes").click(function() {
            // show upgrade-message div
            $("#upgrade-message").show();
            // hide start-co-ff-button div
            $("#start-co-ff-button").hide();
        });

        $("#assets-question-no").click(function() {
            // hide upgrade-message div
            $("#upgrade-message").hide();
            // show start-co-ff-button div
            $("#start-co-ff-button").show();
        });
    });
</script>


<?php $this->endSection() ;?>