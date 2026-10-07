<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>


<br /><br />
<div class="row">
	<div class="col-8">

        <div class="panel panel-primary">
		<div class="panel-heading"><h1>Notifications</h1></div>
    		<div class="panel-body">

                  <h3>You have <b><?=$current_n_count;?></b> unread notifications</h3>
		          
                  <?php if(is_array($all_notifications) && count($all_notifications) > 0 ) : ?>
                  <?php foreach($all_notifications as $an) : ?>
                    <div class="panel panel-primary notification-item">
                        <div class="panel-body">

                            <!-- If joint app we dont want to show this notification -->
                            <?php if($an['step'] == "Respondent has replied to the Divorce" && $split_application === true) : ?>
                                <?php continue; ?>
                            <?php endif;?>

                            <?php

                                 $notification = str_replace("{FIRST_NAME}", @$notification_first_name, $an['notification_wording']);
                                 $notification = str_replace("{ISSUE_DATE}", @$notification_issue_date, $notification);
                                 $notification = str_replace("{CASE_NUMBER}", @$notification_case_number, $notification);
                                 $notification = str_replace("{NISI_DATE}", @$notification_nisi_app_date, $notification);
                            ?>

                            <h3 class="notification-title"><?=$an['step'];?></h3>
                            <p class="notification-date"><strong>Date: </strong><span><?=date("jS F Y",strtotime($an['date_added']));?></span></p>
                            <div class="notification-full-content">

                                <?php if($an['step'] == "Confirmation Of Issue" && $split_application === true) : ?>
                                    <p>
                                        Your divorce petition has now been issued and as you are filing a Joint Application we will be back in contact with you both after the 20 weeks. <br /><br />

                                        There is a standard 20 week waiting period from the date of issue before any further action can be taken to continue the divorce.
                                    </p>
                                <?php else : ?>
                                    <p><?=$notification;?></p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                  <?php endforeach;?>
            <?php endif;?>


            </div>
        </div>
	</div>
</div>

<?php $this->endSection() ;?>