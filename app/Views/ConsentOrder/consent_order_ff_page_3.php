<?php if ($res_logged_in === true) : ?>
    <?php $this->extend("Layouts/no_nav"); ?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template"); ?>
<?php endif; ?>

<?php $this->section("title"); ?> <?= $title; ?> :: Divorce Track <?php $this->endSection(); ?>

<?php $this->section("content"); ?>

<?php $hide_respondent = ($page_data[0]['has_contact'] == 0) ? TRUE : FALSE; ?>

<br /><br />
<div class="row">
    <div class="col-8 coff">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2></h2>
            </div>
            <div class="panel-body">


                <div id="stage-image">

                    <?php if (session()->has("user_msg_error")) : ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-danger">
                                    <?= htmlentities(session("user_msg_error")); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (session()->has("errors")) {

                        echo ' <div class="alert alert-danger">
                                        <button class="close" data-dismiss="alert"></button>';
                        foreach (session("errors") as $e) {
                            echo $e . "<br />";
                        }
                        echo '</div>';
                    }
                    ?>

                    <?= form_open_multipart(); ?>

                    <br><br>
                    <?php if(!empty($package_code) && !in_array($package_code, $codesToCheck)) : ?>
                        <div style="text-align:center;">
                            <b><u>LEGAL ADVICE</u></b><br><br>
                            <p>Have either party sought legal advice in relation to the consent order? (please tick)<br />
                                (Both parties are entitled to take legal advice with regards to the consent order, but this not compulsory)
                            </p>
                        </div><br><br>

                        <table class="table table-ff">

                            <?php if ($hide_respondent == FALSE || $res_logged_in == FALSE) : ?>
                                <tr>
                                    <td><b>APPLICANT</b>*</td>
                                    <td>YES <input type="radio" name="legal_app_add" id="legal_app_add" value="1" <?php if (isset($page_data[0]['legal_app_add']) && $page_data[0]['legal_app_add'] == "1") {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> <?php if (old('legal_app_add') !== null && old('legal_app_add') == "1") {
                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                }; ?>></td>

                                    <td>NO <input type="radio" name="legal_app_add" id="legal_app_add" value="0" <?php if (isset($page_data[0]['legal_app_add']) && $page_data[0]['legal_app_add'] == "0") {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> <?php if (old('legal_app_add') !== null && old('legal_app_add') == "0") {
                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                }; ?>></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($hide_respondent == FALSE || $res_logged_in == TRUE) : ?>
                                <tr>
                                    <td><b>RESPONDENT</b>*</td>
                                    <td>YES <input type="radio" name="legal_res_add" id="legal_res_add" value="1" <?php if (isset($page_data[0]['legal_res_add']) && $page_data[0]['legal_res_add'] == "1") {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> <?php if (old('legal_res_add') !== null && old('legal_res_add') == "1") {
                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                }; ?>></td>

                                    <td>NO <input type="radio" name="legal_res_add" id="legal_res_add" value="0" <?php if (isset($page_data[0]['legal_res_add']) && $page_data[0]['legal_res_add'] == "0") {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> <?php if (old('legal_res_add') !== null && old('legal_res_add') == "0") {
                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                }; ?>></td>
                                </tr>
                            <?php endif; ?>

                        </table>
                    
                    <?php endif;?>
                    <br>

                    <!-- if the respondent is not logged in we want to ask them about there over all asssets total with a radio yes or now: DB less_than_250 -->
                    <?php if ($res_logged_in == FALSE) : ?>

                        <div class="form-group" style="text-align: left;">
                            <label class="control-label alert alert-info" for="less_than_250">To the best of your knowledge, do you believe that your total combined net assets (that is, your and the respondent’s total net assets together) excluding pensions are less than £250,000? If you are unsure of your total, please complete the D81 in full below, before answering this question.</label>
                            Yes <input type="radio" name="less_than_250" id="less_than_250" value="1" <?php if (isset($page_data[0]['less_than_250']) && $page_data[0]['less_than_250'] == "1") {
                                                                                                            echo 'checked';
                                                                                                        } ?> <?php if (old('less_than_250') !== null && old('less_than_250') == "1") {
                                                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                                                    }; ?>> <br>
                            No <input type="radio" name="less_than_250" id="less_than_250" value="0" <?php if (isset($page_data[0]['less_than_250']) && $page_data[0]['less_than_250'] == "0") {
                                                                                                            echo 'checked';
                                                                                                        } ?> <?php if (old('less_than_250') !== null && old('less_than_250') == "0") {
                                                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                                                    }; ?>>
                        </div>

                        <br>
                    <?php endif; ?>


                    <?php if ($res_logged_in === FALSE) : ?>

                        <div class="ff_info_box">
                            <h1>Statement Of Information - D81</h1>
                            <p style="text-align: left;">
                                Getting a divorce does not end the financial obligations of couples. So, unless you obtain a consent order to finalise the finances, there would be nothing stopping the other party from bringing a financial claim at any point in the future (unless they remarry). When the court is asked to approve a consent order, they will need to review the elements making up the couple’s financial circumstances who are divorcing or dissolving their civil partnership. The judge can then assess whether the agreement that the parties have reached is fair and reasonable in all the circumstances of the case. <br><br>

                                If your device doesn't support PDF files please download <a href="https://get.adobe.com/uk/reader/" target="_blank">Adobe Acrobat Reader</a><br /><br />

                                Please download our D81 guide by clicking <a target="_blank" href="<?= site_url(); ?>documents/CONSENT_ORDER/D81_Statement_Of_Information_Guide.docx">here</a> <br><br>

                                Form D81 is part of this final process. It supports an application for a consent order or a financial remedy order. <br>
                                You will need to download and complete in full the D81 form <a href="<?= site_url(); ?>documents/D81.pdf" target="_blank"><u>here.</u></a> <br><br>

                                <b>PLEASE NOTE: Page 23 onwards on the D81 you are downloading are JUST the signature pages which we will send to you AFTER the Consent Order has been drafted.</b>
                            </p>

                            <?php $hide = ($res_logged_in === FALSE && $hide_respondent === TRUE) ? "" : 'display:none;'; ?>
                            <p style="<?= $hide; ?> text-align: left;">If you are unable to complete the other parties information you will need to complete your part of the form as the applicant and then send this to the Respondent to get them to complete their part.<br /><br />

                                Once both sides have been completed you can then upload this via the upload option below and continue with the rest of the Fact Finder Without both parties full information we will not be able to draft the Order in full for you.
                            </p>
                        </div>
                    <?php else : ?>

                        <div class="ff_info_box">
                            <h1>Statement Of Information - D81</h1>
                            <p style="text-align: left;">

                                Getting a divorce does not end the financial obligations of couples. So, unless you obtain a consent order to finalise the finances, there would be nothing stopping the other party from bringing a financial claim at any point in the future (unless they remarry).<br /><br />

                                When the court is asked to approve a consent order, they will need to review the elements making up the couple’s financial circumstances who are divorcing or dissolving their civil partnership. The judge can then assess whether the agreement that the parties have reached is fair and reasonable in all the circumstances of the case.<br /><br />

                                We have attached the D81 part completed by our client for you to <a href="<?= site_url(); ?>consent-order/client_review_d81/1" target="_blank">View.</a><br /><br />

                                However, you will also need to complete your part of the D81 by clicking <a target="_blank" href="<?= site_url('documents/CONSENT_ORDER/D81_respondent_template.pdf'); ?>"><b>here</b></a> you will notice that the boxes which do NOT need answering have been marked with an X.


                            </p>

                            <p style="text-align: left;">
                                <a href="<?= site_url(); ?>consent-order/client_review_d81/1"><b>You can view our client's part completed D81 here</b></a>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if ($res_logged_in == false) : ?>
                        <div class="alert alert-danger">
                            Please ensure that you complete the names and dates of birth for all children of the family under the age of 18.<br />If you have ongoing financial arrangements for children over the age of 18 that you wish to include in the order, please also record their names and dates of birth as these will be required if they are to be referred to in the prepared documents.
                        </div>
                    <?php endif; ?>

                    <br><br>
                    <?php if (file_exists(ROOTPATH . "/questionnaire_files/consent_order/D81_" . $application_uid . ".pdf") && $res_logged_in == FALSE) : ?>
                        <div class="alert alert-warning">
                            You have already uploaded your D81, you can review this document by clicking <a href="<?= site_url(); ?>consent-order/client_review_d81/1" target=""><b>here</b></a> or click "Next" to continue.<br />If you need to replace the D81 you have uploaded please click 'Choose File' below and this will replace the previous upload.
                        </div>
                        <br>
                    <?php endif; ?>


                    <?php if (file_exists(ROOTPATH . "/questionnaire_files/consent_order/D81_" . $application_uid . "_respondent.pdf") && $res_logged_in == TRUE) : ?>
                        <div class="alert alert-warning">
                            You have already uploaded your D81, you can review this document by clicking <a href="<?= site_url(); ?>consent-order/client_review_d81/2" target="">here</a> or click "Next" to continue.
                        </div>
                        <br>
                    <?php endif; ?>

                    <?php if ($res_logged_in === false) : ?>
                        <label>Please complete and upload the D81 document which can be downloaded from the link above or by clicking <a href="<?= site_url(); ?>documents/D81.pdf" target="_blank"><u>here</u></a></label>
                    <?php endif; ?>


                    <input type="file" name="userfile" id="userfile">

                    <div class="coff-nav">
                        <input type="submit" name="page3_submit" value="Next" class="next-btn btn btn-primary">
                        <a class="prev-btn btn" href="<?= site_url(); ?>consent-order/consent_order_ff/2">Previous</a>
                    </div>
                    <?= form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>



<?php $this->endSection(); ?>