<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>


<br /><br />
<div class="row">
	<div class="col-8">

        <div class="panel panel-primary">
		<div class="panel-heading"><h1>Pay Fee</h1></div>
    		<div class="panel-body">

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


                <?php if(isset($_GET['s']) && $_GET['s'] == 1) : ?>
                      <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-success">
                               Thank you for your payment. You will receive a confirmation email shortly to confirm your purchase. Your fee payment will show in your account once the order has been processed, this may take a few minutes.
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <?php if($is_complete === false) : ?>
                    <div id="details-cont">

                        <h2>Make your fee payment below</h2>

                        <div class="pay-fees-container">
                            <div style="width:98%!important">
                                <button class="btn btn-primary btn-lg" style="margin-bottom:10px;" onclick="showOption('cc')">Make Payment</button>
                                <img class="accepted-payment-methods" src="<?=base_url();?>images/accepted-payment-methods.png" />
                            </div>
                        </div>

                        <div id="fee_select">
                         <?= form_open('',array('role' => 'form'));?>
                            <div class="form-group">
                                <label for="fee_id">Fees:</label>
                                <select class="form-control" name="fee_id" id="fee_id" class="form-control" required>
                                    
                                    <?php if(is_array($fee_options) && count($fee_options) > 0):?>
                                            <option data-price="0.00" value="0">--Select Fee--</option>
                                            <?php foreach($fee_options as $fo) : ?>

                                                <?php if($fo['PackageCode'] != "COC") : //CM's have requested that this is hidden from the client?>
                                                 <option data-price="<?=$fo['DigitalPrice'];?>" data-desc="<?=$fo['PackageName'];?>" value="<?=$fo['ID'];?>"><b><?=$fo['PackageName'];?> (&pound;<?=$fo['DigitalPrice'];?>)</option>
                                                <?php endif;?>

                                            <?php endforeach;?>
                                    <?php else : ?>
                                            <option value="">--No Fees Available--</option>
                                    <?php endif;?>
                                </select>
                            </div>
                          <?=form_close();?>
                         </div>
                     
                        <div id="fee_cc_cont">
                            <?=form_open();?>

                                <input type="hidden" name="total" id="total" value="0">
                                <input type="hidden" name="fee_id_check" id="fee_id_check" value="0">
                                
                                <br>
                                <div>Process payment of <b>&pound;<span id="payment_amount">0.00</span></b></div>
                                <br>
                                <input type="submit" name="submit_payment" id="submit_payment" class="btn btn-primary" value="Make Payment">
                                
                            <?=form_close();?>
                        </div>

                        <div id="paymentoutput"></div>
                    </div>
                <?php endif;?>

                <div class="panel panel-primary">
                    <div class="panel-heading"><h2>Fee Payment History</h2></div>
                    <div class="panel-body">

                            <table class="table table-row">
                                <thead>
                                    <tr>
                                        <th>Fee Paid</th>
                                        <th>Merchant ID</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php if(is_array($fees_paid) && count($fees_paid) > 0) : ?>
                                        <?php foreach($fees_paid as $fp) : ?>
                                            <tr>
                                                <td><?=$fp['PackageName'];?> (<?=$fp['PackageCode'];?>)</td>
                                                <td><?=$fp['merchant_id'];?></td>
                                                <td><?= date("d-m-Y H:i",strtotime($fp['date_added']));?></td>
                                                <td><?= ( $fp['PackageCode'] == "MCS") ? "<a href='".base_url()."documents/Marriage Certificate Fact Finder.docx"."'>Download Fact Finder</a>" : "&nbsp;";?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    <?php else : ?>
                                        <tr>
                                            <td>No payments on record.</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                </tbody>
                            </table>
                        <?php endif;?>
                    </div>
                </div>

        </div>
      </div>
	</div>
</div>



<?php
   
   if(!isset($_POST['submit_payment'])){
     echo "<script>$('#fee_select,#fee_cc_cont,#pp_cont').hide();</script>";
   }else{
         echo "<script>$('#pp_cont').hide();</script>";
   }

?>

<script>

    $(document).ready(function()
    {
        $(document).on('change','select#fee_id',function(){

            var currentSelected = $("#fee_id option:selected"),
                price_before_format = currentSelected.data("price"),
                f = currentSelected.val(),
                price_before_format = currentSelected.data("price"),
                price_to_string =  price_before_format.toString(), //any number with .99 come through as a INT which broke the code, convert to string
                price = price_to_string.replace(/[^0-9.]/, ''),
                payment_amount_display = $('span#payment_amount'),
                fee_id = $(this).val();

            //update the payment amount for client display
            payment_amount_display.text(price_before_format);
            //update the fee id
            $('#fee_id_check').val(fee_id);
            $('#fee_id_check2').val(fee_id);
            //update the total
            $('#total').val(price);
            $('#total2').val(price);

        });
    });




    function showOption(option){

       if(option == "cc"){
          $('#fee_select,#fee_cc_cont').show();
          $('#pp_cont').hide();
       }else{
          $('#fee_select,#pp_cont').show();
          $('#fee_cc_cont').hide();
       }

    }
</script>

<?php $this->endSection() ;?>