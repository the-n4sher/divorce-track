<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>


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
		            	<h1>Consent Order Question(s)</h1>
		            </div>
					<div class="panel-body">

						<?php 

							if(session()->has("SUCC") != ""){
			                    echo ' <div class="alert alert-success">';
			                        echo session("SUCC");
			                    echo '</div>'; 
			                }

			                if(session()->has("user_msg_error") != ""){
			                    echo ' <div class="alert alert-danger">';
			                        echo session("user_msg_error");
			                    echo '</div>'; 
			                } 

	                         if (session()->has("errors") ){

	                            echo ' <div class="alert alert-danger">
	                                        <button class="close" data-dismiss="alert"></button>';
	                                        foreach(session("errors") as $e){
	                                            echo $e . "<br />";
	                                        }
	                            echo '</div>';                          
	                        }
	              

			                    
		                ?>

		                <?php if(!session()->has("SUCC")) : ?>
							<div class="well">
								<?php if($complete == false) : ?>

									<p>Please answer the following question(s) in the box provided below.<br />
										<b>Q: </b><i><b><?=$question;?></b></i>
									</p>

									<?=form_open_multipart();?>
										<div class="form-group">
											<label for="client_response">Response</label>
											<textarea name="client_response" id="client_response" cols="30" required rows="10" class="form-control" placeholder="Please type your response in this box."><?=old('client_response');?></textarea>
										</div> <br>

										<div class="form-grou">
											<label for="file">Upload Document (Allowed extensions .doc, .docx, .pdf, .csv)</label>
											<input type="file" name="file" id="file">
										</div><br>

										<div class="form-group">
											<input type="submit" name="question_answered" value="Submit" class="btn btn-primary">
										</div>
									<?=form_close();?>
								<?php else : ?>
										<p>This question has already been answered.</p>
								<?php endif;?>
							</div>
						<?php endif;?>
	        		</div>
	    		</div>
	 		</div>
	 	</div>
	</div>
	<!-- /#page-wrapper -->

<?php $this->endSection() ;?>
