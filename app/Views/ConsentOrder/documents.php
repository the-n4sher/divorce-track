<?php if ($res_logged_in === true) : ?>
    <?php $this->extend("Layouts/no_nav"); ?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template"); ?>
<?php endif; ?>


<?php $this->section("title"); ?> <?= $title; ?> :: Divorce Track <?php $this->endSection(); ?>

<?php $this->section("content"); ?>

<div class="row">
    <div class="col-8">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1><?php echo ($package_code == "CBCS" || $package_code == "DDPCBCS") ? "Simple Clean Break Order" : "Consent Order"; ?> Documents</h1>
            </div>
            <div class="panel-body">


                <?php if ($consent_order_drafted == 0) : ?>

                    <p> Your Consent Order documents are currently being drafted by our legal team - however should you wish to FAST TRACK this service to have the Consent Order drafted within 7 working days for &pound;200.00, then please make contact on 01793 384029
                    </p>

                <?php else : ?>
                    
                    <?php // only clients that purchased on or after the 10th of February 2026 should see this content ?>
                    <?php if ($consent_type == 1 && $orderPlaced >= '2026-02-10 00:00:00') : ?>
                        
                        <?php if($package_code != "CBCS" && $package_code != "DDPCBCS") : ?>
                            Hello,<br /><br />

                            Your consent order documents have now been drafted by our legal team, these are now available to download below.<br /><br />

                            We have attached a guide for your reference, please read this carefully as this will assist you with the documents.<br /><br />

                            If you have referenced any of the following in your documents, then these will also need to be sent to the court;<br />
                            A. SEPARATION AGREEMENT<br />
                            B. MEMORANDUM OF UNDERSTANDING<br />
                            C. LETTER OF EXPLANATION<br /><br />

                            Please read through all documents carefully, if any of the forms require amending please email <a href="mailto:amend@divorce-online.co.uk">amend@divorce-online.co.uk</a> in the first instance and if we cannot resolve this via email we can then organise a mutually convenient time to call you back (some amendments may be subject to a fee if the information differs from what was originally submitted)<br /><br />

                            Once you are satisfied with the documents, please forward them directly to the court along with the court fee which can be via cheque, bankers draft or Postal Order for £62.00 payable to HMCTS to:-<br /><br />

                            HMCTS Financial Remedy<br />
                            PO Box 12746<br />
                            Harlow<br />
                            CM20 9QZ<br /><br />

                            Please note that you will need to send these documents as a large letter and will have to have them weighed. A first-class stamp will not be sufficient and the court will not accept underpaid mail.<br /><br />

                            <b>PLEASE BE AWARE THE COURT DO NOT ACCEPT ELECTRONIC SIGNATURES</b><br /><br />

                            Important notes: -<br />

                            NEW FORM A REGULATIONS STATE THAT YOU MUST ALSO INCLUDE A COPY OF YOUR DECREE NISI or CONDITIONAL ORDER CERTIFICATE AND YOUR DECREE ABSOLUTE or FINAL ORDER CERTIFICATE IF THIS HAS BEEN PRONOUNCED.<br /><br />
                            The documents attached are draft documents and you must check them thoroughly for errors or omissions before signing them and returning them to the court. Failure to do so could mean an incorrect order being filed at court for which we cannot be held responsible.<br /><br />

                            PLEASE DO NOT AMEND THE DRAFT ORDER. If you wish to change any of the information or else add or remove any of the clauses, please contact us and we will forward your request to an appropriate legal professional. This may incur a fee.<br /><br />

                            DO NOT WRITE ON THE DRAFT ORDER. If you need to add the case number or any missing information, please do it in your word processor, or let the us know and we can do this for you. <br />

                            <br />
                            <b>Disclaimer</b><br />
                            Your consent order documents have been drafted by our Solicitors, the documents have been drafted using the information you have provided in your fact finder.<br /><br />

                            As this is a remote drafting service, any order we initially provide to you for review may not be the final and correct version. We need your input to ensure that this happens.<br /><br />

                            Therefore, we require you and your spouse to fully read through the order to check everything in your agreement is included and that the order and supporting documents are correct if you or your spouse realise something is missing or incorrect or the order does not reflect your agreement, please do not hesitate to contact your case manager or the consent order team to request an amendment to be made. Please be aware we can only accept calls from you, our client. Therefore, if your spouse spots a problem, they can ask you to get in touch.<br /><br />

                            <b>DO NOT RETURN A SIGNED ORDER IF YOU BELIEVE THERE IS A MISTAKE OR IT IS INCORRECT.</b><br /><br />

                            <b>IF YOU DO NOT READ THE ORDER CAREFULLY DIVORCE ONLINE WILL NOT BE HELD RESPONSIBLE FOR AN INCORRECT ORDER BEING FILED WITH THE COURT.</b><br /><br />

                            On submitting the Consent Order to the Regional Divorce Centre, you are HEREBY confirming that the order is FULLY correct and ACCURATELY reflects your AGREEMENT and, ON THIS BASIS, you are happy for the order to be sent to the Regional Divorce Centre for consideration.<br /><br />

                            If you experience any difficulties or require further assistance please do not hesitate to contact us.
                        <?php else : ?>
                            Hello,<br /><br />

                            Your clean break order documents have now been drafted, these are now available to download below.<br /><br />

                            We have attached a guide for your reference, please read this carefully as this will assist you with the documents.<br /><br />

                            If you have referenced any of the following in your documents, then these will also need to be sent to the court;<br />
                            A. SEPARATION AGREEMENT<br />
                            B. MEMORANDUM OF UNDERSTANDING<br />
                            C. LETTER OF EXPLANATION<br /><br />

                            Please read through all documents carefully, if any of the forms require amending please email <a href="mailto:amend@divorce-online.co.uk">amend@divorce-online.co.uk</a> in the first instance and if we cannot resolve this via email we can then organise a mutually convenient time to call you back (some amendments may be subject to a fee if the information differs from what was originally submitted)<br /><br />

                            Once you are satisfied with the documents, please forward them directly to the court along with the court fee which can be via cheque, bankers draft or Postal Order for £62.00 payable to HMCTS to:-<br /><br />

                            HMCTS Financial Remedy<br />
                            PO Box 12746<br />
                            Harlow<br />
                            CM20 9QZ<br /><br />

                            Please note that you will need to send these documents as a large letter and will have to have them weighed. A first-class stamp will not be sufficient and the court will not accept underpaid mail.<br /><br />

                            <b>PLEASE BE AWARE THE COURT DO NOT ACCEPT ELECTRONIC SIGNATURES</b><br /><br />

                            Important notes: -<br />

                            NEW FORM A REGULATIONS STATE THAT YOU MUST ALSO INCLUDE A COPY OF YOUR DECREE NISI or CONDITIONAL ORDER CERTIFICATE AND YOUR DECREE ABSOLUTE or FINAL ORDER CERTIFICATE IF THIS HAS BEEN PRONOUNCED.<br /><br />
                            The documents attached are draft documents and you must check them thoroughly for errors or omissions before signing them and returning them to the court. Failure to do so could mean an incorrect order being filed at court for which we cannot be held responsible.<br /><br />

                            PLEASE DO NOT AMEND THE DRAFT ORDER. If you wish to change any of the information or else add or remove any of the clauses, please contact us and we will forward your request to an appropriate legal professional. This may incur a fee.<br /><br />

                            DO NOT WRITE ON THE DRAFT ORDER. If you need to add the case number or any missing information, please do it in your word processor, or let the us know and we can do this for you. <br />

                            <br />
                            <b>Disclaimer</b><br />
                            Your clean break documents have been drafted using the information you have provided in your fact finder.<br /><br />

                            As this is a remote drafting service, any order we initially provide to you for review may not be the final and correct version. We need your input to ensure that this happens.<br /><br />

                            Therefore, we require you and your spouse to fully read through the order to check everything in your agreement is included and that the order and supporting documents are correct if you or your spouse realise something is missing or incorrect or the order does not reflect your agreement, please do not hesitate to contact your case manager or the consent order team to request an amendment to be made. Please be aware we can only accept calls from you, our client. Therefore, if your spouse spots a problem, they can ask you to get in touch.<br /><br />

                            <b>DO NOT RETURN A SIGNED ORDER IF YOU BELIEVE THERE IS A MISTAKE OR IT IS INCORRECT.</b><br /><br />

                            <b>IF YOU DO NOT READ THE ORDER CAREFULLY DIVORCE ONLINE WILL NOT BE HELD RESPONSIBLE FOR AN INCORRECT ORDER BEING FILED WITH THE COURT.</b><br /><br />

                            On submitting the Clean Break Order to the Regional Divorce Centre, you are HEREBY confirming that the order is FULLY correct and ACCURATELY reflects your AGREEMENT and, ON THIS BASIS, you are happy for the order to be sent to the Regional Divorce Centre for consideration.<br /><br />

                            If you experience any difficulties or require further assistance please do not hesitate to contact us.
                        <?php endif; ?>

                    <?php else : ?>
                        <p>
                            Hello,<br /><br />

                            Your consent order documents have now been drafted by our legal team, these are now available to download below.
                            <br /><br />

                            We have attached a guide for your reference, please read these carefully as these will assist you with the documents.<br /><br />

                            Please be advised that there are two forms not mentioned in the guide;<br />

                            a. Legal Advice Disclaimer (this is for our records and just needs to be signed)<br />
                            b. HMCTS Digital Letter of Authority (this enables the court to put us on record for you and just requires a signature)<br /><br />


                            If you have referenced any of the following in your documents, then these will also need to be returned to us;<br />
                            A. SEPARATION AGREEMENT<br />
                            B. MEMORANDUM OF UNDERSTANDING<br />
                            C. LETTER OF EXPLANATION<br /><br />

                            Please read through all documents carefully, if any of the forms require amending please email amend@divorce-online.co.uk in the first instance and if we cannot resolve this via email we can then organise a mutually convenient time to call you back (some amendments may be subject to a fee if the information differs from what was originally submitted)<br /><br />
                            <?php  
                                // If the OrderPlaced date is before the cut off change the price from 65 tom 70
                                $cutOffForFeeChange = '2026-07-13 00:00:00';
                                $fee = '60.00';
                                if($orderPlaced && $orderPlaced >= $cutOffForFeeChange) {
                                    $fee = '62.00';
                                }
                                // Removed at the request of Stuart 12/08/2026
                                $fee = '62.00';

                            ?>
                            Once you are satisfied with the documents, please return them to our office at the address below for filing at court along with the court fee cheque/postal order for £<?= $fee; ?> made payable to HMCTS:-<br /><br />

                            Divorce Online<br />
                            Unit 3, the Meads Business Centre<br />
                            Ashworth Road<br />
                            Swindon, Wilts,<br />
                            SN5 7YJ<br /><br />

                            <?php  
                                // If the OrderPlaced date is before the cut off change the price from 65 tom 70
                                $cutOffForFeeChange = '2026-07-13 00:00:00';
                                $dTFee = '65.00';
                                if($orderPlaced && $orderPlaced >= $cutOffForFeeChange) {
                                    $dTFee = '70.00';
                                }
                                // Removed at the request of Stuart 12/08/2026
                                $dTFee = '70.00';
                            ?>
                            You can also make this payment via your Divorce Track or by calling the office on 01793 211 211 – this will incur an admin fee taking the total to £<?= $dTFee; ?><br /><br />


                            <b>PLEASE BE AWARE THE COURT DO NOT ACCEPT ELECTRONIC SIGNATURES</b><br /><br />

                            Please note that you will need to send these documents as a large letter and will have to have them weighed at the Post Office. A first-class stamp will not be sufficient and we will not accept underpaid mail.<br /><br />

                            Important notes: -<br />


                            · The documents attached are draft documents and you must check them thoroughly for errors or omissions before signing them and returning to us to file at court on your behalf. Failure to do so could mean an incorrect order being filed at court for which we cannot be held responsible for.<br /><br />

                            · PLEASE DO NOT AMEND THE DRAFT ORDER. If you wish to change any of the information or else add or remove any of the clauses, please contact us at amend@divorce-online.co.uk and we will forward your request to an appropriate legal professional. This may incur a fee.<br /><br />

                            · DO NOT WRITE ON THE DRAFT ORDER. If you need to add the case number or any missing information, please do it in your word processor, or let the us know and we can do this for you and resubmit.<br /><br />

                            · Once the Consent Order is submitted, should the Judge have a query about the Order, they will write to you directly. You must inform Divorce Online as soon as you receive this so that we may assist in the response. The Court will not send this to Divorce Online directly.<br />
                        </p>
                    <?php endif; ?>

                    <br>
                    <hr>

                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <?php

                                // if the client purchased on or after the 10th of February 2026, then use the new guide
                                if ($orderPlaced >= '2026-02-10 00:00:00') {
                                    $guide_document = "Consent_Order_Guide_Standalone.pdf";
                                } else {
                                    $guide_document = "Consent_Order_Guide_Standalone_Original.pdf";
                                }

                                $guide_document = ($consent_type == 1) ? $guide_document : "Consent_Order_Guide_Managed.docx";

                                // If produt code is CBCS or DDPCBCS, then use the CBCS guide
                                if (in_array($package_code, $codesToCheck)) {
                                    $guide_document = "Consent_order_guide_cbcs.pdf";
                                }
                                ?>
                                <td>Guide to Procedure</td>
                                <td><a href="<?= base_url(); ?>documents/CONSENT_ORDER/<?= $guide_document; ?>" class="cus_download" target="_blank">Download</a></td>
                            </tr>

                            <?php if ($d81_document != "") : ?>
                                <tr>
                                    <td>Statement of Information (D81)</td>
                                    <td><a href="<?= base_url() ?>consent-order/download-client-doc/<?= $d81_document; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td>Statement of Information (D81)</td>
                                    <td><a href="<?= base_url() ?>consent-order/stream-file/<?= $user_id; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <td>D81 - Signature Pages</td>
                                <td><a href="<?= base_url(); ?>documents/CONSENT_ORDER/D81 - Signature Pages.pdf" class="cus_download" target="_blank">Download</a></td>
                            </tr>

                            <tr>
                                <td>Form A</td>
                                <td><a href="<?= base_url(); ?>createpdf/create_co_pdf/form_a/<?= $user_id; ?>/<?= $case_id; ?>" class="cus_download" target="_blank">Download</a></td>
                            </tr>
                            
                            <?php if(in_array($package_code, $codesToCheck)) : ?>
                            <tr>
                                <td>Clean Break Order</td>
                                <td><a href="<?= base_url();?>consent-order/stream-file-clean-break/<?= $user_id; ?>" class="cus_download" target="_blank">Download</a></td>
                            </tr>
                            <?php endif; ?>

                            <?php $hide_section = ($consent_type == 1) ? "style='display:none;'" : ""; ?>
                            <tr <?= $hide_section; ?>>
                                <td>Legal Advice Disclaimer</td>
                                <td><a href="<?= base_url(); ?>createpdf/create_co_pdf/legal_disclaimer/<?= $user_id; ?>/<?= $case_id; ?>" class="cus_download" target="_blank">Download</a></td>
                            </tr>

                            <?php if ($consent_type == 1) : ?>
                                <tr>&nbsp;</tr>
                            <?php else : ?>
                                <tr>
                                    <td>Consent Order Checklist</td>
                                    <td><a href="<?= base_url(); ?>documents/CONSENT_ORDER/Consent_Order_Checklist.docx" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>

                            <?php if(!in_array($package_code, $codesToCheck)) : ?>
                                <tr>
                                    <td>Consent Order Application</td>
                                    <td><a href="<?= base_url(); ?>createpdf/create_co_pdf/consent_order_application/<?= $user_id; ?>/<?= $case_id; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($co_document != "") : ?>
                                <tr>
                                    <td>Consent Order</td>
                                    <td><a href="<?= base_url() ?>consent-order/download-client-doc/<?= $co_document; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($charge_document != "") : ?>
                                <tr>
                                    <td>Charge Document</td>
                                    <td><a href="<?= base_url() ?>consent-order/download-client-doc/<?= $charge_document; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>

                            <!-- no legal advise -->
                            <?php if (!in_array($package_code, $codesToCheck) && $app_legal == 0 && $res_legal == 0) : ?>
                                <tr>
                                    <td>Legal Advice Statement</td>
                                    <td><a href="<?= base_url(); ?>createpdf/create_legal_advise_word_doc/Legal_Advice_Statement_None/<?= $user_id; ?>/<?= $case_id; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>


                            <!-- one of them has legal advise -->
                            <?php if (!in_array($package_code, $codesToCheck) && $app_legal == 1 && $res_legal == 0 || $app_legal == 0 && $res_legal == 1) : ?>
                                <tr>
                                    <td>Legal Advice Statement</td>
                                    <td><a href="<?= base_url(); ?>createpdf/create_legal_advise_word_doc/Legal_Advice_Statement_Single/<?= $user_id; ?>/<?= $case_id; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>


                            <!-- both have legal advise -->
                            <?php if (!in_array($package_code, $codesToCheck) && $app_legal == 1 && $res_legal == 1) : ?>
                                <tr>
                                    <td>Legal Advice Statement</td>
                                    <td><a href="<?= base_url(); ?>createpdf/create_legal_advise_word_doc/Legal_Advice_Statement_Both/<?= $user_id; ?>/<?= $case_id; ?>" class="cus_download" target="_blank">Download</a></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($consent_type == 2) : ?>
                                <tr>
                                    <td>HMCTS Digital Divorce Letter of Authority</td>
                                    <td><a href="<?= base_url(); ?>documents/CONSENT_ORDER/HMCTS Digital Divorce Letter of Authority.docx" class="cus_download" target="_blank">Download</a></td>
                                </tr>

                            <?php endif; ?>


                            <?php if (is_array($pension_data) && count($pension_data) > 0) : ?>
                                <tr style="background-color:#ccc;">
                                    <td colspan="2" style="text-align: center;"><b>Pension Documents</b></td>
                                </tr>

                                <?php foreach ($pension_data as $p) : ?>
                                    <tr>
                                        <td><?= $p['name_of_arrangements']; ?></td>
                                        <td><a href="<?= base_url(); ?>createpdf/create_co_pdf/p1/<?= $user_id; ?>/<?= $case_id; ?>/<?= $p['id']; ?>" target="_blank">Download</a></td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php endif; ?>


                            <!-- Loop the P1 Documents -->

                        </tbody>
                    </table>


                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<?php $this->endSection(); ?>