<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Login <?= $this->endSection();?>
<?= $this->section("content");?>
    
   
<div class="col-8">
   <div class="panel panel-primary">
      <div class="panel-heading">
         <h2>Password Reset</h2>
      </div>
      <div class="panel-body">

         <h4>Please enter your email address in the box below to reset your password</h4><br>
         <br>

            <?php 
                if (session()->has("errors")){
                   echo ' <div class="alert alert-danger">
                               <button class="close" data-dismiss="alert"></button>';
                               foreach( session("errors") as $e) :
                                   echo $e. "<br />";
                               endforeach;

                   echo '</div>';                               
               }

                if(session()->has("ERR") != ""){
                        echo ' <div class="alert alert-danger">';
                                echo session("ERR");
                        echo '</div>'; 
                }

                if(session()->has("SUCC") != ""){
                        echo ' <div class="alert alert-success">';
                                echo session("SUCC");
                        echo '</div>'; 

                    if(isset($_SESSION['SUCC'])){
                       unset($_SESSION['SUCC']);
                    }
                }          
            ?>


          <?=form_open('',array('role' => 'form'));?>

            <div class="control-group form-group">
                   <div class="controls">
                        <label for="email">Email Address: <span class="required">*</span></label>
                  <input type="email" name="email" class="form-control" id="email" value="<?php if(isset($_POST['email'])) { echo htmlentities($_POST['email']);} ?>">
                   </div>
               </div>

               <div class="control-group form-group">
                   <div class="controls">
                  <input type="submit" name="password_reset" class="btn btn-primary" id="password_reset" value="Reset Password">
                   </div>
               </div>

          <?=form_close();?>


      </div>
   </div>
</div>

<?= $this->endSection(); ?>