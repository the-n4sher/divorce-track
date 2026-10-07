<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Verify Account <?= $this->endSection();?>
<?= $this->section("content");?>
    
   
<div class="col-8">
   <div class="panel panel-primary">
      <div class="panel-heading">
         <h2>We have sent you a verification code</h2>
      </div>
      <div class="panel-body">

         <div class="row">
            <div class="col-sm-8 col-sm-offset-2">

            <?php 
                if(session()->has("SUCC") != ""){
                        echo ' <div class="alert alert-success">';
                                echo session("SUCC");
                        echo '</div>'; 
                }

                if(session()->has("ERR") != ""){
                    echo ' <div class="alert alert-danger">';
                            echo session("ERR");
                    echo '</div>'; 
                }
            ?>

            <p>Please check your email for your 6 digit verification code</p>
              <?=form_open('account/verify'); ?>                                                                                                                
                  <div class="form-group">
                     <div class="">
                        <input type="text" maxlength="6" minlength="6" pattern="[0-9]*" class="form-control" name="verification_code" id="verification_code" required placeholder="Verification Code">
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="">
                        <input type="submit" name="verify_submit" class="btn btn-danger" value="VERIFY">
                     </div>
                  </div>
               <?=form_close(); ?>
               <br>
               <a href="<?=site_url('account/resend-verification-code');?>">Resend Verification Code</a>
             
            </div>
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>