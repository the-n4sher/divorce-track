<?php $this->extend("Layouts/main_template"); ?>
<?php $this->section("title"); ?> <?= $title; ?> :: Divorce Track <?php $this->endSection(); ?>

<?php $this->section("content"); ?>
<style>
    .heading-letterauth {
        font-weight: bold;
        font-size: 20px;
    }

    #docusign-cont {
        padding: 20px;
        border: 1px solid #ccc;
    }

    #signatureCanvas,
    #signatureCanvas2 {
        border: 1px solid #ccc;
        width: 100%;
        height: 200px;
    }

    .buttons {
        margin-top: 10px;
    }
</style>
<p>&nbsp;</p>
<div class="row">
    <div class="col-8">
        <br>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" id="title-panel-text">Previous or Existing Divorce Proceedings</h3>
            </div>
            <div class="panel-body">



                <?= form_open_multipart(); ?>


                <?php if (is_array($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger "><?= $errors['error']; ?></div>
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



                if (session()->has("ERR") != "" || session()->has("FAIL") != "") {
                    $err = "";
                    if (session()->has("ERR")) {
                        $err = session("ERR");
                    } else {
                        if (session()->has("FAIL")) {
                            $err = session("FAIL");
                        }
                    }

                    if (is_array($err)) {
                        // If ERR is an array, loop through and display each message
                        foreach ($err as $message) {
                            echo '<div class="alert alert-danger">';
                            echo '<p>' . esc($message) . '</p>';
                            echo '</div>';
                        }
                    }
                }

                if (session()->has("SUCC") != "") {
                    echo ' <div class="alert alert-success">';
                    echo session("SUCC");
                    echo '</div>';
                }
                ?>


                <!-- display the success and error messages in one list styled nicely using bootstrap -->
                <?php if (!empty($successMultiFile)): ?>
                    <div class="alert alert-success">
                        <?php if (is_array($successMultiFile)): ?>
                            <?php foreach ($successMultiFile as $s) : ?>
                                <p><?= $s; ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?= $successMultiFile; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errorsMultiFile)): ?>
                    <div class="alert alert-danger">
                        <?php if (is_array($errorsMultiFile)): ?>
                            <?php foreach ($errorsMultiFile as $e) : ?>
                                <p><?= $e; ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?= $errorsMultiFile; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php $style = ($has_signed_authority == true) ? "style='display:none;'" : ""; ?>
                <div class="court-action alert alert-info" <?= $style; ?>>

                    <p>Prior to starting your divorce with Divorce Online, the court needs to know if there are any other legal proceedings related to your marriage. For example, another court case which has already dealt with ending this marriage. This includes any court cases which are ongoing, finished or abandoned, either in the UK or overseas.</p> <br>

                    <p><b>Are there, or have there ever been, any other court cases relating to this marriage?</b> Not including legal proceedings that may happen in the future.</p>

                    <input type="radio" name="court_action" id="court_action" value="1"> Yes <br>
                    <input type="radio" name="court_action" id="court_action" value="0"> No <br>

                </div>

                <div id="call-in-msg" class="alert alert-warning" style="display: none;" <?= $style; ?>>
                    <p>As you have answered <b>Yes</b> to the above question, you are not able to continue with this fact finder and we urge you to call the office on 01793 211211 between 9.00-4.00 Monday to Friday to discuss this further.</p>
                </div>



                <?php $auth_hide_section = ($has_signed_authority == false) ? "" : "display:none;"; ?>

                <div id="auth_section" style="<?= $auth_hide_section; ?>">
                    <div class="alert alert-warning">
                        <?php if ($split_application === true) : ?>
                            In order for Divorce Online to manage the Joint Application we will need both parties consent to do so. In order for this, please sign the correct boxes by holding down your left mouse button and sign or if on a mobile hold finger down and sign
                        <?php else : ?>
                            Please download the letter of authority below to continue your application.
                        <?php endif; ?>
                    </div>

                    <p>
                        <?php if ($split_application === false) : ?>
                            <b class="heading-letterauth">Letter of Authority</b><br>
                            <a href="<?= base_url(); ?>documents/OTHER/Letter_of_Authority.pdf" target="_blank">View Letter Of Authority</a><br><br>
                        <?php else : ?>
                            <b class="heading-letterauth">Letter of Authority</b><br>
                            <a href="<?= base_url(); ?>documents/OTHER/Letter_of_Authority.pdf" style="margin-bottom:5px;" target="_blank">View Applicant 1 Letter Of Authority</a><br>
                            <a style="line-height: 30px;" href="<?= base_url(); ?>documents/OTHER/Letter_Of_Authority_Respondent.pdf" target="_blank">View Applicant 2 Letter Of Authority</a><br>

                            <br>
                            <b>Applicant 1 Signature <b>(You)</b></b>
                            <canvas id="signatureCanvas"></canvas>
                    <div class="buttons">
                        <button id="clearButton">Clear</button>
                        <button id="saveButton">Save</button>
                    </div>
                    <b>
                        <div id="message"></div>
                    </b>
                    <br><br>


                    <b>Applicant 2 Signature <b>(Your Spouse)</b></b>
                    <canvas id="signatureCanvas2"></canvas>
                    <div class="buttons">
                        <button id="clearButton2">Clear</button>
                        <button id="saveButton2">Save</button>
                    </div>
                    <b>
                        <div id="message2"></div>
                    </b>
                    <br><br>
                <?php endif; ?>

                <div id="give_consent_msg">
                    <?php if ($split_application === false) : ?>
                        In order for Divorce Online to manage the service for you; the Regional Divorce Centre will require consent from you granting Divorce Online access to the case once issued.

                        In order to do so, please click the <b>"I give my Consent to Divorce Online"</b> button below.<br /><br /> We will then generate an e-signed document which will be sent to the Court for their records.<br><br>

                    <?php else : ?>

                    <?php endif; ?>



                    <?php if ($split_application === true) : ?>
                        <p>Clicking <b>"We give our consent to Divorce Online"</b> below we grant Divorce Online the authority to manage the Divorce for you both.</p>
                    <?php endif; ?>


                    <?php if ($split_application === false) : ?>
                        <input type="submit" name="portal_consent" class="btn btn-primary" value="I give my consent to Divorce Online" onclick="return confirm('By clicking OK you are giving Divorce Online authority to manage your divorce case in its entirety.');" />
                    <?php else : ?>
                        <input type="submit" id="btn-jointapp" name="portal_consent" class="btn btn-primary" value="We give our consent to Divorce Online" onclick="return checkSigned();" />
                    <?php endif; ?>

                </div>

                </p>
                <br>
                </div>

                <?php
                $hide_section = ($has_signed_authority == true) ? "" : "display:none;";
                $hide_marriage_cert_hide = ($has_marriage_cert == false) ? "display:none;" : "";
                $hide_marriage_cert_top = ($has_marriage_cert == true) ? "display:none;" : "";
                $hide_till_auth = ($has_signed_authority == false) ? "display:none;" : "";
                ?>
                <div style="<?= $hide_till_auth; ?>">

                    <p><b class="heading-letterauth">Marriage Certificate</b><br>
                        To file for a divorce, the Court will need to see an image of your marriage certificate. <br><br>

                        You will not be able to continue with the online fact finder until you have uploaded this PLUS any translation that may be needed. Please ensure the image is in colour, clear and ensure the whole document fits the screen and not has any parts missing. <br><br>

                        Your case manager will check this for you before submitting to the Court<br>
                    <p style="<?= $hide_marriage_cert_top; ?>"></p><br>

                    <?php if ($has_marriage_cert == true) : ?>
                        <div class="alert alert-success">
                            You have already uploaded a marriage certificate, as per the image below.
                        </div>
                        <?php if ($marriage_cert_base64) : ?>
                            <?php if ($marriage_cert_base64 == "Unable to display PDF") : ?>
                                <p>Sorry, we are unable to display the marriage certificate as it is a PDF file.</p>
                            <?php else : ?>
                                <img src="data:image/png;base64,<?= $marriage_cert_base64; ?>" alt="Marriage Certificate" style="width: auto; height: 300px;">
                                <br>
                            <?php endif; ?>

                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="marriage-cert-section-top" style="<?= $hide_marriage_cert_top; ?>">
                        <div class="alert alert-info">
                            <a href="<?= base_url(); ?>documents/OTHER/Certificate guide.pub" target="_blank">Click here to download a guide to uploading your marriage certificate</a>
                        </div>
                        <div class="form-group">
                            <label for="userfile">Marriage Certificate (allowed extensions jpg|png|jpeg|pdf)</label>
                            <input type="file" name="userfile" id="userfile" class="form-control"><br>
                            <?php if ($has_marriage_cert == false) : ?>
                                <input type="submit" name="upload_marriage_certificate" value="Upload Marriage Certificate" class="btn btn-primary">
                            <?php endif; ?>
                        </div>
                    </div>

                    <br>
                    <div class="alert alert-info" style="<?= $hide_marriage_cert_hide; ?>">
                        Do you have any other documents you would like to upload such as additional pages, or a translated version of your marriage certificate? <br />
                        <button class="btn btn-primary" onclick="showExtraFiles(0, event);">No</button> | <button class="btn btn-primary" onclick="showExtraFiles(1, event);">Yes</button>
                    </div>

                    <?php
                    $hide_extra_files = (is_array($extra_files) && count($extra_files) > 0) ? "" : "display: none;";
                    ?>

                    <div id="extra_files_div" style="<?= $hide_extra_files; ?> <?= $hide_marriage_cert_hide; ?> border: 1px solid #ddd; border-radius: 5px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; background-color: #f9f9f9;">
                        <!-- If we have extra files, display a list of files in a stripped table with a delete button next to each file, 
						 the delete should do a post request to /questionnaire/deleteFile/{FILE_NAME} -->
                        <?php if (!empty($extra_files)): ?>
                            <p>The following files have been uploaded:</p>
                            <table class="table table-striped" border="1" cellpadding="10" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($extra_files as $file): ?>
                                        <tr>
                                            <td><?= $file; ?></td>
                                            <?php $link = str_replace('=', '~', base64_encode($file)); ?>
                                            <td><a href="<?= site_url(); ?>questionnaire/deleteFile/<?= session("user_id"); ?>/<?= $link; ?>" onclick="return confirm('Are you sure you want to delete this file?');" class="btn btn-danger">Delete</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <br>
                        <?php endif; ?>


                        <p>Do you have any other documents you would like to upload such as additional pages, or a translated version of your marriage certificate?</p>
                        <p>You can upload multiple files at once by holding down the <b>CTRL key</b> and selecting multiple files.</p>
                        <?= form_open_multipart(); ?>
                        <input type="file" name="extraFiles[]" id="extraFiles" multiple><br>
                        <input type="submit" class="btn btn-primary" name="upload_extra_files" value="Upload Documents">
                        <?= form_close(); ?> <br>
                    </div>

                    <div class="end-section" style="<?= $hide_marriage_cert_hide; ?>">
                        <p>
                        <div class="alert alert-warning">
                            You must select one of the options below to continue.
                        </div>

                        <b class="heading-letterauth">Court Fee Information</b>
                        <p>There is a mandatory Court Fee to file a Divorce of £628.00 – your case manager will ask you for this payment once they have prepared the 1st stage paperwork for you.</p>
                        <p>Please tick this box if you are willing to pay this by Credit/Debit Card <input type="checkbox" name="pay_portal_cc" id="pay_portal_cc"></p>
                        </p> <br>

                        <p>
                            However. If you are on a low income or claiming any benefits; you may be entitled to have an exemption/reduction of the fees.<br />
                            You can request this here - <a href="https://www.gov.uk/get-help-with-court-fees" target="_blank">https://www.gov.uk/get-help-with-court-fees</a> <br><br>

                            Once you have the reference code, please add this information below e.g. HWF-123-456:<br>
                            <input type="text" name="portal_fee_ref" id="portal_fee_ref" class="form-control" value="<?= set_value('portal_fee_ref'); ?>">
                        <div style="display: none;" class="alert alert-danger" id="reference_error"></div>
                        </p>
                        <br>

                        <div class="form-group">
                            <input type="submit" name="save_portal" value="Save" class="btn btn-primary">
                        </div>
                    </div>

                </div>
                <?= form_close(); ?>

            </div>
        </div>

    </div>
</div>


<script type="text/javascript" src="<?= site_url('js/sig.js'); ?>"></script>
<script>
    function checkSigned() {
        $.ajax({
            url: '<?= site_url(); ?>questionnaire/checkIsSigned',
            type: 'POST',
            data: {},
            success: function(response) {
                if (response == true) {
                    const confirmed = confirm("By clicking OK, you are giving Divorce Online authority to manage your divorce case in its entirety.");
                    if (confirmed) {
                        // Get the submit button element
                        const submitButton = document.getElementById('btn-jointapp');

                        // Check if the button exists before interacting with it
                        if (submitButton) {
                            submitButton.removeAttribute('onclick');
                            submitButton.click(); // Programmatically click the button to submit the form
                        }
                    }
                } else {
                    alert("Both parties must supply a signature to continue.");
                    return false;
                }
            },
            error: function() {
                alert("We have encountered an error, please try again or contact support.");
                return false;
            }
        });

        return false;
    }


    $(document).ready(function() {

        const canvas = document.getElementById('signatureCanvas');
        const canvas2 = document.getElementById('signatureCanvas2');

        let signaturePad = null;
        let signaturePad2 = null;

        if (canvas) {
            signaturePad = new SignaturePad(canvas);
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
        }

        if (canvas2) {
            signaturePad2 = new SignaturePad(canvas2);
            canvas2.width = canvas2.offsetWidth;
            canvas2.height = canvas2.offsetHeight;
        }

        $(document).ready(function() {
            // Clear the signature pad
            $('#clearButton').on('click', function(event) {
                event.preventDefault();
                signaturePad.clear();
            });

            $('#clearButton2').on('click', function(event) {
                event.preventDefault();
                signaturePad2.clear();
            });

            // Save the signature
            $('#saveButton').on('click', function(event) {
                if (signaturePad.isEmpty()) {
                    event.preventDefault();
                    alert("Please provide a signature first.");
                } else {
                    const signatureData = signaturePad.toDataURL('image/png');
                    event.preventDefault();
                    $.ajax({
                        url: '<?= site_url(); ?>questionnaire/saveSignature',
                        type: 'POST',
                        data: {
                            signature: signatureData,
                            applicant: 1
                        },
                        success: function(response) {
                            $('#message').html(`<p>${response}</p>`);
                            return false;
                        },
                        error: function() {
                            $('#message').html("<p>Error saving signature.</p>");
                            return false;
                        }
                    });
                }
            });


            $('#saveButton2').on('click', function(event) {
                if (signaturePad.isEmpty()) {
                    event.preventDefault();
                    alert("Please provide a signature first.");
                } else {
                    const signatureData = signaturePad2.toDataURL('image/png');
                    event.preventDefault();
                    $.ajax({
                        url: '<?= site_url(); ?>questionnaire/saveSignature',
                        type: 'POST',
                        data: {
                            signature: signatureData,
                            applicant: 2
                        },
                        success: function(response) {
                            $('#message2').html(`<p>${response}</p>`);
                            return false;
                        },
                        error: function() {
                            $('#message2').html("<p>Error saving signature.</p>");
                            return false;
                        }
                    });
                }
            });
        });


        // disable the save button
        $('input[name=save_portal]').attr("disabled", true);


        var reference_field = $('#portal_fee_ref'),
            save_btn = $('input[name=save_portal]'),
            cc_checkbox = $('#pay_portal_cc');


        $(document).on("change", '#pay_portal_cc', function() {
            if ($('#pay_portal_cc').prop('checked')) {
                // Clear the reference field
                reference_field.val("");
                reference_field.prop("disabled", true);
                save_btn.prop("disabled", false);

            } else {

                // Remove the disabled attr
                reference_field.prop("disabled", false);
                save_btn.prop("disabled", true);
            }

        });


        $("#portal_fee_ref").keyup(function() {

            // Get the current value
            var curVal = reference_field.val();
            $('#reference_error').hide();

            if (curVal == "") {
                cc_checkbox.prop("disabled", false);
            } else {
                // Disabled the checkbox
                cc_checkbox.prop("disabled", true);
                save_btn.prop("disabled", true);

                // If we have enough characters we will show the save button
                if (curVal.length == 11 && curVal != "HWF-123-456") {
                    var ref_pattern = new RegExp('^[A-Za-z]{3}\-{1}[a-zA-z0-9]{3}\-{1}[a-zA-z0-9]{3}$');
                    if (ref_pattern.test(curVal)) {
                        $('#reference_error').empty();
                        $('#reference_error').hide();
                        save_btn.prop("disabled", false);
                    } else {
                        $('#reference_error').text("Please enter a valid reference code");
                        $('#reference_error').show();
                    }
                }
            }


        });


        // Hide .auth_section on load so that the client has to answer the court question
        $('#auth_section').hide();

        $(document).on("change", '#court_action', function() {
            var selectedVal = $('#court_action:checked').val();

            if (selectedVal == "0") {
                $('#auth_section').show();
                $('#call-in-msg').hide();
                $('#title-panel-text').text("Marriage Certificate And Letter Of Authority");
            } else {
                $('#auth_section').hide();
                $('#call-in-msg').show();
                $('#title-panel-text').text("Previous or Existing Divorce Proceedings");
            }

        });

    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        // move the extra_files_div after the userfile_br
        $("#userfile_br").after($("#extra_files_div"));

        $(document).on('click', '#delete_cert', function() {

            var clicked = ($(this).is(":checked")) ? true : false;
            if (clicked == true) {
                alert("Are you sure you want to permanently delete this image?");
            }
        });
    });

    function showExtraFiles(show, event) {
        if (event) event.preventDefault();
        // If show is 1 then show the extra_files_div, prevent the default action and toggle the visibility

        if (show == 1) {
            $("#extra_files_div").show();
        } else {
            $("#extra_files_div").hide();
        }

    }
</script>

<?php $this->endSection(); ?>