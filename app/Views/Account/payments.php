<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>
	
	<style>
	.well{
		border: 2px solid #16A184!important;
	}
</style>

<div id="page-wrapper" class="notifications-page">

    <div class="row">
        <div class="col-8">
			<div class="panel panel-primary">
	            <div class="panel-heading">
	            	<h1>Payments</h1>
	            </div>
				<div class="panel-body">

					<p>Here you can find a list of all payments made to us over the course of your divorce/financial order process. This will include the service fee, any court fees paid, and any upgrades added to your account. </p>

					<p>Upgrades can include adding legal advice onto your service or perhaps including a financial order/clean break if you proceed with a divorce only service. Should you wish to see what upgrades are available to your case - either check the upgrades tab on the left of your account or give us a call on 01793 384029 to discuss with an advisor.</p> <br>


					Thank you for your purchase of the <b><i><?=$package_details->packageName;?></i></b> service on the <b><i><?=date("d-m-Y H:i",strtotime($package_details->orderPlaced));?></i></b>, this payment was for £<?=$package_details->DigitalPrice;?>. <br><br>
					
					<div class="well">
					  <h3>Upgrades</h3> <br>

					    <?php if(is_array($upgrades) && count($upgrades) > 0) : ?>

					  		<table class="table table-striped table-dark">
						  <thead class="">
						    <tr>
						      <th scope="col">Service Paid</th>
						      <th scope="col">Purchase Price</th>
						      <th scope="col">Purchase Date</th>
						    </tr>
						  </thead>
						  <tbody>
					  		<?php foreach($upgrades as $u) : ?>
					  			<tr>
							      <th scope="row"><?=$u['PackageName'];?></th>

								   <?php  
								   // If price_paid is set and not empty, null or 0.00 then use that,else use DigitalPrice
								   if(isset($u['price_paid']) && $u['price_paid'] != '' && $u['price_paid'] != null && $u['price_paid'] != 0.00) {
								    $price = $u['price_paid'];
								   } else {
								    $price = $u['DigitalPrice'];
								   }
								   ?>
							      <td>&pound;<?=$price;?></td>
							      <td><?=date("d-m-Y H:i",strtotime($u['date_added']));?></td>
						      </tr>
					  		<?php endforeach;?>
						  </tbody>
						</table>


						<?php else : ?>
							<p>No upgrade payments found in our system.</p>
						<?php endif;?>


					</div>

					<div class="well">
					  <h3>Fees</h3><br>
					  <?php if(is_array($fees) && count($fees) > 0 || $add_step_msg != "") : ?>

					  		<table class="table table-striped table-dark">
						  <thead class="">
						    <tr>
						      <th scope="col">Fee Paid</th>
						      <th scope="col">Purchase Price</th>
						      <th scope="col">Purchase Date</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php  
						  		if($add_step_msg != ""){
						  			echo '
						  				<tr>
						  					<td><b>Court Fee Payment</b></td>
						  					<td>&pound;593.00</td>
						  					<td>'.$add_step_msg.'</td>
						  				</tr>
						  			';
						  		}
						  	?>
					  		<?php foreach($fees as $f) : ?>
					  			<tr>
							      <th scope="row"><?=$f['PackageName'];?></th>

                                   <?php  
                                      // If price_paid is set and not empty, null or 0.00 then use that,else use DigitalPrice
                                      if(isset($f['price_paid']) && $f['price_paid'] != '' && $f['price_paid'] != null && $f['price_paid'] != 0.00) {
                                        $price = $f['price_paid'];
                                      } else {
                                        $price = $f['DigitalPrice'];
                                      }
								   ?>

							      <td>&pound;<?=$price;?></td>
							      <td><?=date("d-m-Y H:i",strtotime($f['date_added']));?></td>
						      </tr>
					  		<?php endforeach;?>
						  </tbody>
						</table>


						<?php else : ?>
							<p>No fee payments found in our system.</p>
						<?php endif;?>
					</div>


					<div class="well">
					  <h3>Additional Services</h3><br>
					   <?php if(is_array($services) && count($services) > 0) : ?>

					  		<table class="table table-striped table-dark">
						  <thead class="">
						    <tr>
						      <th scope="col">Service Paid</th>
						      <th scope="col">Purchase Price</th>
						      <th scope="col">Purchase Date</th>
						    </tr>
						  </thead>
						  <tbody>
					  		<?php foreach($services as $s) : ?>
					  			<tr>
							      <th scope="row"><?=$s['PackageName'];?></th>

								   <?php  
								   // If price_paid is set and not empty, null or 0.00 then use that,else use DigitalPrice
								   if(isset($s['price_paid']) && $s['price_paid'] != '' && $s['price_paid'] != null && $s['price_paid'] != 0.00) {
								    $price = $s['price_paid'];
								   } else {
								    $price = $s['DigitalPrice'];
								   }
								   ?>
							      <td>&pound;<?=$price;?></td>
							      <td><?=date("d-m-Y H:i",strtotime($s['date_added']));?></td>
						      </tr>
					  		<?php endforeach;?>
						  </tbody>
						</table>


						<?php else : ?>
							<p>No additional service payments found in our system.</p>
						<?php endif;?>

					</div>

        		</div>
    		</div>
 		</div>
 	</div>
</div>
<!-- /#page-wrapper -->

<?php $this->endSection() ;?>