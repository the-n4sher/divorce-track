<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>

<style>
    .ui-accordion-content{height:auto!important;}
</style>

<br /><br />
<div class="row">
	<div class="col-8">

        <div class="panel panel-primary">
		<div class="panel-heading"><h1>Upgrade</h1></div>
    		<div class="panel-body">

                <?php if(isset($_GET['s']) && $_GET['s'] == 1) : ?>
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-success">
                           Thank you for your payment. You will receive a confirmation email shortly to confirm your purchase. Your upgrade will show in your account once the order has been processed, this may take a few minutes.
                        </div>
                    </div>
                </div>
                <?php endif;?>

                <?php if($has_upgrades == TRUE) : ?>

                    <?php if(is_array($upgrade_options) && count($upgrade_options) > 0):?>

                        <h3>You have <b><?=count($upgrade_options);?></b> Upgrades Available</h3>
                        <p>“Below are a range of upgrade options for your current account. Please click below on each option to find out further information and how upgrading to this additional service may benefit you.</p><br><br>


                        <?php if(session()->has("user_msg_error") ) : ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger">
                                            <?=htmlentities(session("user_msg_error"));?>
                                        </div>
                                    </div>
                                </div>
                        <?php endif;?>

                        <div id="accordion">

                          <?php foreach($upgrade_options as $uo) : ?>

                            <?php 
                                $removeCutOffDate = "2025-03-10 08:59:59";
                                if($package_code == "DDP"){ 
                                    if($orderPlaced < $removeCutOffDate && $uo['PackageCode'] == "DDP199TODPPCO")
                                    {
                                        continue;
                                    }

                                    // If the DDP was purchased after then we need to remove DDP249TODPPCO  from the options array
                                    if($orderPlaced > $removeCutOffDate && $uo['PackageCode'] == "DDP249TODPPCO")
                                    {
                                        continue;
                                    }
                                }
                            ?>


                              <h3>Upgrade to - <?=$uo['PackageName'];?> (&pound;<?=$uo['DigitalPrice'];?>)</h3>
                              <div>
                                <p><?=$uo['upgrade_reason'];?></p>
                              </div>
                          <?php endforeach;?>
                    <?php endif;?>
                    </div>
                    <br><br>
                    <hr>


                    <?php if(is_array($upgrade_options) && count($upgrade_options) > 0):?>

                        <?php if(session()->has("user_msg_ok") ) : ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-success">
                                        <?=htmlentities(session("user_msg_ok"));?>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>

                            <?php if(session()->has("user_msg_error") ) : ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger">
                                            <?=htmlentities(session("user_msg_error"));?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>

                        <div id="details-cont">

                            <?= form_open('',array('role' => 'form'));?>
                                <div class="form-group">
                                    <label for="upgrade_package">Package:</label>
                                    <select class="form-control" name="upgrade_package" id="upgrade_package" class="form-control">
                                        
                                        <?php if(is_array($upgrade_options) && count($upgrade_options) > 0):?>
                                                <option value="">--Select Package--</option>
                                                <?php foreach($upgrade_options as $uo) : ?>
                                                    <?php if(@$_POST['upgrade_package'] == $uo['upgradeable_to_package_id']) : ?>
                                                        <option data-price="<?=$uo['DigitalPrice'];?>" data-desc="<?=$uo['PackageName'];?>" value="<?=$uo['upgradeable_to_package_id'];?>" selected><b><?=$uo['PackageName'];?> (&pound;<?=$uo['DigitalPrice'];?>)</option>
                                                    <?php else : ?>
                                                        <option data-price="<?=$uo['DigitalPrice'];?>" data-desc="<?=$uo['PackageName'];?>" value="<?=$uo['upgradeable_to_package_id'];?>"><b><?=$uo['PackageName'];?> (&pound;<?=$uo['DigitalPrice'];?>)</option>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                        <?php else : ?>
                                                <option value="">--No Upgrades Available--</option>
                                        <?php endif;?>
                                    </select>
                                </div>


                                <input type="hidden" name="case_id" id="case_id" value="<?=$case_id;?>">
                                <input type="submit" name="upgrade_package_payment" id="upgrade_package_payment" value="Make Payment" class="btn btn-primary btn-lg">
                                <br>
                                <img class="accepted-payment-methods" src="<?=site_url();?>images/accepted-payment-methods.png" />

                            <?=form_close();?>
                            <div id="paymentoutput"></div>
                        </div>
                    <?php endif;?>

            <?php else : ?>

                <div class="col-8">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <p>There are no upgrades available for this case.</p>
                            </div>
                        </div>
                    </div>

            <?php endif;?>

            </div>
        </div>
	</div>
</div>

<?php $this->endSection() ;?>