<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>
	
	<br /><br />
	<div class="row">
		<div class="col-8 coff">
			<div class="panel panel-primary">
				<div class="panel-heading"><h2></h2></div>
				<div class="panel-body">
					<h1>Questionnaire Complete</h1><br>
					<p>
						Thank you for completing your Consent Order Fact Finder.<br><br>

						We have sent an email to the respondent asking them to complete their part of the fact finder, we will update you via email once the respondent has completed the fact finder. <br><br>

						Should you wish to FAST TRACK this service to have the Consent Order drafted within 7 working days for £200.00, then please make contact on 01793 384029.<br /><br />
						
						If you fast tracked your application today, the due date would be the <b><?=$date_if_fast_tracked;?></b>.
					</p>

					<br>
					<div style="width:100%;">
						<a href="<?=site_url('account');?>" class="btn btn-primary btn-lg btn-block">Close</a>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php $this->endSection() ;?>