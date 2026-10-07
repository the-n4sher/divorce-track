<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Login <?= $this->endSection();?>
<?= $this->section("content");?>
    
   
<div class="col-8">
   <div class="panel panel-primary">
      <div class="panel-heading">
         <h2>Welcome to Divorce Track.</h2>
      </div>
      <div class="panel-body">

         <div id="content" style="">
            <h3 style="text-align:center;applied:none;">The fast and easy way to track your online divorce application.</h3>
            <p style="">Please complete the two fields below with your email address and password to login to your case.</p>
            <p style="">If you do not yet have an account with us yet, <a href="https://www.divorce-online.co.uk/divorce-services" target="_blank">view our services</a> to find out more.</p>
            <br>
         </div>

         <div class="row">
            <div class="col-sm-8 col-sm-offset-2">

                <?php if( session()->has("warning") ) : ?>
                    <div class="alert alert-danger"><?=session("warning");?></div>
                <?php endif;?>

               <div style="">
                   <?=form_open('login/create'); ?>                                                                                                                
                     <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 form-control-label">Email</label>
                        <div class="col-sm-9">
                           <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?=old('email');?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 form-control-label">Password<br>
                           <a href="<?=site_url();?>forgot-password">Forgot password?</a></label>
                        <div class="col-sm-9">
                           <input type="password" class="form-control" name="password" id="password" placeholder="" value="<?=old('password');?>">
                        </div>
                     </div>
                     <div style="margin:10px 0px;">
                     </div>
                     <div class="form-group row" style="">
                        <div class="col-sm-offset-3 col-sm-9">
                           <input type="submit" name="login_submit" class="btn btn-danger" value="LOGIN">
                        </div>
                     </div>
                  <?=form_close(); ?>
               </div>
            </div>
         </div>

         <div style="display: none;">
            <h4>Divorce Track is currently offline for maintenance and will be back online shortly. If your enquiry is urgent, please contact your case manager.</h4>
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>