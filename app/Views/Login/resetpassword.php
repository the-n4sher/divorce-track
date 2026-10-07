<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Login <?= $this->endSection();?>
<?= $this->section("content");?>
    
   
<div class="col-8">
   <div class="panel panel-primary">
      <div class="panel-heading">
         <h2>Password Reset</h2>
      </div>
      <div class="panel-body">

             <?php 
                if(session()->has("ERR") != "" && !session()->has("SUCC")){
                        echo ' <div class="alert alert-danger">';
                                echo session("ERR");
                        echo '</div>'; 
                }

                if (session()->has("errors") && !session()->has("SUCC")){
                   echo ' <div class="alert alert-danger">
                               <button class="close" data-dismiss="alert"></button>';
                               foreach( session("errors") as $e) :
                                   echo $e. "<br />";
                               endforeach;

                   echo '</div>';                               
               }

                if($cred_errors != "" && !session()->has("SUCC")){
                        echo ' <div class="alert alert-danger">';
                                echo $cred_errors;
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


            <?php if(session()->has("SUCC") && session("SUCC") != "") : ?>
                     <div class="alert alert-success">
                            <?=session("SUCC");?>
                     </div>
            <?php else : ?>
            
                    <?php if($reset_errors === false) : ?>

                        <div class="alert alert-info">
                            Password must meet the following rules.
                            <ul>
                                <li>6 or more characters</li>
                                <li>1 or more uppercase characters</li>
                                <li>1 or more numbers</li>
                                <li>1 or more special characters e.g. (@, !, _)</li>
                            </ul>
                        </div>

                         <?=form_open('',array('role' => 'form'));?>

                            <div class="control-group form-group">
                                <div class="controls">
                                    <label for="password">Password: <span class="required">*</span></label>
                                    <input type="password" name="password" class="form-control" id="password" value="<?=old('password');?>">
                                </div>
                            </div>

                            <div class="control-group form-group">
                                <div class="controls">
                                    <label for="conf_password">Confirm Password: <span class="required">*</span></label>
                                    <input type="password" name="conf_password" class="form-control" id="conf_password" value="<?=old('conf_password');?>">
                                </div>
                            </div>

                            <div class="control-group form-group">
                                <div class="controls">
                                    <input type="submit" name="password_reset_final" class="btn btn-primary" id="password_reset_final" value="Reset Password">
                                </div>
                            </div>
                         <?=form_close();?>
                    <?php endif;?>

            <?php endif;?>
      </div>
   </div>
</div>

<?= $this->endSection(); ?>