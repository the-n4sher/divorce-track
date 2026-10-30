<?php if ($res_logged_in === true) : ?>
    <?php $this->extend("Layouts/no_nav"); ?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template"); ?>
<?php endif; ?>


<?php $this->section("title"); ?> <?= $title; ?> :: Divorce Track <?php $this->endSection(); ?>

<?php $this->section("content"); ?>

<br /><br />
<div class="row">
    <div class="col-8">
        <?php
        if (isset($consent_ff_complete) && $consent_ff_complete === true && isset($package_consent_type) && ($package_consent_type == 1 || in_array($package_code, ["DCP", "DCPFT"], true))) : ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2>Court Details</h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="well" style="background-color: #f8f9fa; border-left: 4px solid #337ab7; padding: 15px; margin-bottom: 10px;">
                                <p style="margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase;"><strong>Court Name</strong></p>
                                <p style="margin: 0; font-size: 16px; color: #333;"><b><?= $court_name; ?></b></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="well" style="background-color: #f8f9fa; border-left: 4px solid #337ab7; padding: 15px; margin-bottom: 10px;">
                                <p style="margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase;"><strong>Case Number</strong></p>
                                <p style="margin: 0; font-size: 16px; color: #333;"><b><?= $case_number; ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1><?php echo ($package_code == "CBCS") ? "Clean Break Order Progress" : "Consent Order Progress"; ?></h1>
            </div>
            <div class="panel-body">

                <?php if (!empty($questionnaire_data) && $questionnaire_data['has_contact'] == 0 && $questionnaire_data['applicant_single_app_complete'] == 1 && $questionnaire_data['respondent_completed_ff'] == 0) : ?>

                    <div class="alert alert-info">
                        This is a split application. We have received the Applicants information but have yet to receive the Respondents. We are unable to proceed until we have both parties’ information.
                    </div>

                <?php elseif ($consent_ff_complete == false) : ?>
                    <div class="alert alert-warning">
                        <?php if ($package_code != "CBCS") : ?>
                            Please complete your consent order fact finder by clicking <a href="<?= base_url(); ?>consent-order/consent_order_ff/1">here.</a>
                        <?php else : ?>
                            Please complete your clean break order fact finder by clicking <a href="<?= base_url(); ?>consent-order/consent_order_ff/1">here.</a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <?php if (is_array($all_notifications) && count($all_notifications) > 0) : ?>
                        <?php
                        $counter = 0;
                        ?>
                        <?php foreach ($all_notifications as $an) : ?>
                            <?php
                            $style = ($counter != 0) ? "background-color:#ccc" : "";
                            ?>
                            <div class="panel panel-primary notification-item" style="<?= $style; ?>">
                                <div class="panel-body">

                                    <?php
                                    $notification = str_replace("{FIRST_NAME}", @$notification_first_name, $an['notification_wording']);
                                    $notification = str_replace("{ISSUE_DATE}", @$notification_issue_date, $notification);
                                    $notification = str_replace("{CASE_NUMBER}", @$notification_case_number, $notification);
                                    $notification = str_replace("{NISI_DATE}", @$notification_nisi_app_date, $notification);
                                    ?>

                                    <h3 class="notification-title"><?= $an['step']; ?></h3>
                                    <p class="notification-date"><strong>Date: </strong><span><?= date("jS F Y", strtotime($an['date_added'])); ?></span></p>
                                    <div class="notification-full-content">
                                        <p><?= $notification; ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>


<?php $this->endSection(); ?>
