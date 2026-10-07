<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Login <?= $this->endSection();?>
<?= $this->section("content");?>


<style>

h2 {
font-size: 30px;

}

h2, h3 {
margin-bottom: 20px;
text-align: center;
}

@media only screen and (max-width: 768px) {

    h2 {
        font-size: 30px;
    }

    .navbar-fixed-side {
        display: none;
    }
    
    .navbar-brand {
        margin-top: 0px;
    }

}

</style>

<div class="row">

    <div class="col-8">
        
        <div class="panel panel-primary">
      <div class="panel-heading"><h2>Respondent Login Portal</h2></div>
      <div class="panel-body">
         <div id="content">
            <h3>Please login using the email and access code we sent you via email</h3>
            <br>
                        <?php if(session()->has("user_msg_ok") ) : ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="alert alert-success">
                                    <?=htmlentities(session("user_msg_ok"));?>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>

                        <?php if(session()->has("user_msg_error") ) : ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="alert alert-danger">
                                    <?=htmlentities(session("user_msg_error"));?>
                                </div>
                            </div>
                        </div>
                     <?php endif;?>

                     
         </div>


                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">

                           <?php 

                                if(session()->has("ERR") != ""){
                                        echo ' <div class="alert alert-danger">';
                                                echo session("ERR");
                                        echo '</div>'; 
                                }       
                            ?>


                           <?php  if(validation_errors()) : ?>
                                    <div class="alert alert-danger">
                                        <button class="close" data-dismiss="alert"></button>
                                        <?php foreach(validation_errors() as $e) : ?>
                                            <?=$e . "<br />"; ?>
                                        <?php endforeach;?>   
                                    </div>
                           <?php endif;?>


                            <?=form_open();?>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 form-control-label">Email</label>
                                    <div class="col-sm-9">
                                      <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?=set_value('email');?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="access_code" class="col-sm-3 form-control-label">Access Code<br><!-- <a href="<?=base_url();?>resend_access_code">Can't find your access code?</a> --></label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" name="access_code" id="access_code" placeholder="" value="<?=set_value('access_code');?>">
                                    </div>
                                </div>

                                <div style="margin:10px 0px;">
                                    
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                      <input type="submit" name="login_submit" class="btn btn-danger" value="LOGIN">
                                    </div>
                                </div>

                            <?=form_close();?>
                        </div>

                    </div>
                </div>


    </div>

</div>



<?= $this->endSection(); ?>