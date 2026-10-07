<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>


<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>
    
<?php  $hide_respondent = ( $page_data[0]['has_contact'] == "0" ) ? TRUE : FALSE; ?>
    
<br /><br />
<div class="row consent-page-12">
    <div class="col-8 coff">
        <div class="panel panel-primary">
            <div class="panel-heading"><h2></h2></div>
            <div class="panel-body">


                <div id="stage-image">
                        <?php if(session()->has("user_msg_error") ) : ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="alert alert-danger">
                                        <?=htmlentities(session("user_msg_error"));?>
                                    </div>
                                </div>
                            </div>
                         <?php endif;?>
            

                        <?php if(session()->has("SUCC") != "") : ?>
                             <div class="alert alert-success">
                                <?=htmlentities(session("SUCC"));?>
                            </div>
                        <?php endif;?>

                    <?php if(!in_array($package_code, $codesToCheck)) : ?>       
                        <h2><?= ( $package_code != "CBO" && $package_code != "DPPCBO") ? "Consent Order Fact Finder" : "Clean Break Order Fact Finder" ;?></h2>
                    <?php else : ?>
                        <h2>Clean Break Order Fact Finder</h2>
                    <?php endif; ?>
                    


                    <?php if( $package_code != "CBO" && $package_code != "DPPCBO") : ?>
                        <div class="container">

                            <?php if($success_files && is_array($success_files) && count($success_files) > 0) : ?>
                                <div class="alert alert-success">
                                    <?php foreach($success_files as $sf) : ?>
                                        <div><?=$sf;?></div>
                                    <?php endforeach;?>
                                </div>
                            <?php endif;?>

                            <?php if($failed_files && is_array($failed_files) && count($failed_files) > 0) : ?>
                                <div class="alert alert-danger">
                                    <?php foreach($failed_files as $ff) : ?>
                                        <div><?=$ff;?></div>
                                    <?php endforeach;?>
                                </div>
                            <?php endif;?>
                             
                            <?php if(!empty($package_code) && !in_array($package_code, $codesToCheck)) : ?>
                                <p>Would you like to upload any supporting files to your fact finder?</p>
                                <?=form_open_multipart();?>
                                    <label for="">Select file(s)</label>
                                    <input type="file"  multiple="multiple"  name="images[]" id="files">
                                    <input type="submit" name="upload_files" value="Upload File(s)" onclick="return confirm('Are you sure you want to upload this file?');">

                                    <?php if(is_array($supporting_documents) && count($supporting_documents) > 0) : ?>
                                        <br><br>
                                        <h3 style="text-align: left;">Uploaded Supporting Documents</h3>
                                        <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Download</th>
                                            <th scope="col">Date Uploaded</th>
                                            <th scope="col"><?=($res_logged_in === false) ? "Delete" : "&nbsp;";?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($supporting_documents as $sd): ?>
                                                <tr>
                                                    <td><?=$sd['document_name']?></td>
                                                    <td><a href="<?=site_url("consent-order/view-supporting-document/".$sd['uuid']);?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span></a></td>
                                                    <td><?=date("d-m-Y H:i",strtotime($sd['document_added']));?></td>

                                                    <?php if ($res_logged_in === false) : ?>
                                                        <td><a href="<?=site_url("consent-order/delete-supporting-document/".$sd['uuid']);?>" onclick="return confirm('Are you sure you want to delete this file?');"><span class="glyphicon glyphicon-remove"></span></a></td>
                                                    <?php else : ?>
                                                        <td>&nbsp;</td>
                                                    <?php endif;?>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                        </table>
                                    <?php endif;?>
                                <?=form_close();?>
                            <?php endif;?>
                            
                        </div>

                    <?php endif;?>
                

                    <br><br>
                    <p>You have now reached the end of the Fact Finder, please press <b>'Complete'</b> to continue</p>
                    <br>

                    <?php
                       $wording = "Warning Please be aware once the fact finder has been submitted to us any further changes or amendments to the final documents that differ from the fact finder could be subject to a fee of up to £125.
                Please make sure that you have completed the fact finder to the best of your knowledge and the information you have submitted you believe to be correct."

                    ?>
                    <div class="coff-nav">

                        <?php   $previous_page_number = ( !empty($package_code) && in_array($package_code, $codesToCheck) ) ? 3 : 6; ?>
                        
                        <?php if($hide_respondent == TRUE && $res_logged_in == FALSE ) : ?>
                            
                            <a class="next-btn btn btn-primary" href="<?=site_url();?>consent-order/consent_ff_finish_app_only" onclick="return confirm('Please be aware once the fact finder has been submitted to us any further changes or amendments to the final documents that differ from the fact finder could be subject to a fee of up to £125.\n\nPlease make sure that you have completed the fact finder to the best of your knowledge and the information you have submitted you believe to be correct.\n\nPlease note, You have opted to complete the fact finder separately which means we can not process your application until the respondent has completed their part of the fact finder.\n\nAn email will be sent to the respondent once you have confirmed you are happy with the details supplied.\n\nIf you are ready to submit your information, click OK or click CANCEL to review your submission.');">Complete</a>

                            <a class="prev-btn btn" href="<?=site_url('consent-order/consent_order_ff/'.$previous_page_number);?>">Previous</a>

                        <?php else : ?>
                            

                            <?php if(!empty($package_code) && in_array($package_code, $codesToCheck)) : ?>
                                <a class="next-btn btn btn-primary" href="<?=site_url();?>consent-order/consent_ff_finish" onclick="return confirm('Please be aware once the fact finder has been submitted any changes or amendments to the final documents that differ from the fact finder could be subject to a fee of up to £125, or potentially you may need to upgrade your service.\n\nPlease make sure that you have completed the fact finder to the best of your knowledge and the information you have submitted you believe to be correct.');">Complete</a>
                            <?php else : ?>
                                <a class="next-btn btn btn-primary" href="<?=site_url();?>consent-order/consent_ff_finish" onclick="return confirm('Please be aware once the fact finder has been submitted to us any further changes or amendments to the final documents that differ from the fact finder could be subject to a fee of up to £125.\n\nPlease make sure that you have completed the fact finder to the best of your knowledge and the information you have submitted you believe to be correct.');">Complete</a>
                            <?php endif; ?>

                            <a class="prev-btn btn" href="<?=site_url('consent-order/consent_order_ff/'.$previous_page_number);?>">Previous</a>

                        <?php endif;?>


                        
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


<?php $this->endSection() ;?>