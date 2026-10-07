<?php if($uri_segment1 == "account" || $uri_segment1 == "questionnaire" || $uri_segment1 == "consent-order" || $uri_segment1 == "vip") : ?>

  <?php  

    $consentManagedCutOff = "2023-12-12 11:30:00";
    $hideManagedConsentMenu = ( $orderPlaced >= $consentManagedCutOff ) ? false : true;

    /* What type of case is this */
    $case_type = "";
    if( $is_sol_managed == 0 && $is_package_managed == 1 && $package_consent_type == 0 ){$case_type = "divorce_only";}
    if( $is_sol_managed == 0 && $is_package_managed == 1 && $package_consent_type == 2 ){$case_type = "divorce_and_consent_order";}
  
    if( $is_sol_managed == 1 && $is_package_managed == 0 && $package_consent_type == 2 ){$case_type = "solicitor_divorce_and_consent_order";}

    if( $is_sol_managed == 0 && $is_package_managed == 0 && $package_consent_type == 1 ){$case_type = "consent_only";}
    if( $is_sol_managed == 0 && $is_package_managed == 1 && $package_consent_type == 1 ){$case_type = "consent_only";}

    // for cbcs we need to add a custom rule DPPCO
    if( $is_sol_managed == 0 && $is_package_managed == 1 && $package_consent_type == 1 && !empty($package_code) && $package_code == "DDPCBCS" ){$case_type = "divorce_and_consent_order";}
    
    // If the package code is DDPCBCS then we need to check if the date issued is over three months old
    $is_issued_over_three_months = false;

    // Prefer the model passed in from the controller; if it's missing/invalid, instantiate it safely.
    if (
      !isset($questionnaireModel)
      || !is_object($questionnaireModel)
      || !method_exists($questionnaireModel, 'checkIfNisiDateDDPCBCS')
    ) {
      if (class_exists('\App\Models\QuestionnaireModel')) {
        $questionnaireModel = function_exists('model')
          ? model(\App\Models\QuestionnaireModel::class)
          : new \App\Models\QuestionnaireModel();
      }
    }

    if (isset($questionnaireModel) && is_object($questionnaireModel) && method_exists($questionnaireModel, 'checkIfNisiDateDDPCBCS')) {
      $is_issued_over_three_months = $questionnaireModel->checkIfNisiDateDDPCBCS($package_code);
    }
  ?>



  <div class="notification-icon">
    <i class="fa fa-bell"></i>
    <?php if($notification_count > 0 ) : ?>
     <span class='notification-number'><?=$notification_count;?></span>
    <?php endif;?>

  </div>

    <?php if($notification_count > 0 ) : ?>
        <div class="notification-holder">
          <div class="notification-popup">
            <div class="notification-close"><span id="update-notification" data-smbid="<?=$latest_notification['id'];?>">X</span></div>
            <div class="notification-popup-item">
              <h3 class="notification-title">Step Complete</h3>
              <p class="notification-date"><strong>Date: </strong><span><?=date("jS F Y",strtotime($latest_notification['date_added']));?></span></p>
              <div class="notification-short-content">

              
                <?php
                  $notification = str_replace("{FIRST_NAME}", $notification_data['forename'], $latest_notification['notification_wording']);
                  $notification = str_replace("{ISSUE_DATE}", $notification_data['date_issued'], $notification);
                  $notification = str_replace("{CASE_NUMBER}", $notification_data['case_number'], $notification);
                  $notification = str_replace("{NISI_DATE}", $notification_data['nisi_date'], $notification);
                ?>
                <p><b><?=$latest_notification['step'];?></b></p>
                <p><?=$notification ;?></p>
              </div>
              <a href="<?=site_url();?>account/notifications">Read More</a>
            </div>
          </div>
        </div>

      <?php else : ?>
        
          <div class="notification-holder">
          <div class="notification-popup">
            <div class="notification-close">X</div>
            <div class="notification-popup-item">
              <h3 class="notification-title">No Updates</h3>
              <p class="notification-date"></p>
              <div class="notification-short-content">
                <p><b>There are no new notifications available</b></p>
              </div>
              <a href="<?=site_url();?>account/notifications">Read More</a>
            </div>
          </div>
        </div>

      <?php endif;?>

<?php endif;?>


<div id="the-side-nav" class="col-sm-2">
    <a class="navbar-brand mobile" href="/"><img src="<?=site_url();?>images/divorcetrack-logo.png" style="width: 100%" alt="Divorce Track Logo"></a>
    <nav class="navbar navbar-inverse navbar-fixed-side" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand desktop" href="/"><img src="<?=site_url();?>images/divorcetrack-logo.png" style="width: 100%" alt="Divorce Track Logo"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <?php $navHide = ( session("account_setup") ) ? false : true ;?>
            <?php if($navHide === false):?>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">

                <?php if($uri_segment1 == "admin") : ?>

                    <li><a href="<?=site_url();?>admin">Home</a></li>
                    <li><a href="<?=site_url();?>admin/pages">Pages</a></li>
                    <li><a href="<?=site_url();?>admin/manage_users">Admin Users</a></li>
                    <?php if($this->session->userdata("loggedin") == 1) : ?>
                        <li><a href="<?=site_url();?>admin/logout">Logout</a></li>
                    <?php endif;?>

                <?php elseif($uri_segment1 == "account" || $uri_segment1 == "questionnaire" || $uri_segment1 == "consent-order" || $uri_segment1 == "vip" && $res_logged_in == FALSE) : ?>

                        
                        <?php if($case_type == "divorce_only") : ?>

                              <li class><a href="<?=site_url();?>account"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>

                        <?php elseif($case_type == "divorce_and_consent_order" || $case_type == "consent_only" || $case_type  == "solicitor_divorce_and_consent_order") : ?>

                              <?php if($case_type != "consent_only") : ?>

                                <li class=""><a href="<?=site_url();?>account"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
                                <li><a href="<?=site_url();?>account"><i class="fa fa-home" aria-hidden="true"></i>Divorce Progress</a></li>

                              <?php endif;?>

                              <?php if($case_type == "consent_only") : ?>

                                  <li class=""><a href="<?=site_url();?>account"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>

                                  <li class="<?=($url_segment2 == "consent-order-progress" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>consent-order/consent-order-progress"><i class="fa fa-home" aria-hidden="true"></i>Consent Order Progress</a></li>

                                  <?php if($consent_ff_complete === true ) : ?>
                                    <li class="<?=($url_segment2 == "consent_order_docs" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>consent-order/consent_order_docs"><i class="fa fa-file-text-o" aria-hidden="true"></i>Consent Order Documents</a></li>
                                  <?php endif;?>

                              <?php endif;?>

                              <?php if($package_consent_type != "")  : ?>

                                <?php if( ($case_type == "divorce_and_consent_order" || $case_type == "solicitor_divorce_and_consent_order") && $package_consent_type == 2 && $is_issued === true && $hideManagedConsentMenu === false ||  ($case_type == "divorce_and_consent_order" || $case_type == "solicitor_divorce_and_consent_order") && $package_consent_type == 1 && $is_issued === true && $hideManagedConsentMenu === false && $package_code && $package_code == "DDPCBCS") : ?>

                                      <!-- If the package code is DDPCBCS and the date issued is over three months old then show the consent order progress page -->
                                      <?php if($is_issued_over_three_months === true && $package_code == "DDPCBCS") : ?>
                                        <li class="<?=($url_segment2 == "consent-order-progress" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>consent-order/consent-order-progress"><i class="fa fa-home" aria-hidden="true"></i>Simple Clean Break Progress</a></li>
                                      <?php endif;?>
                                      
                                      <!-- If the package code is not DDPCBCS then show the consent order progress page -->
                                      <?php if($package_code != "DDPCBCS") : ?>

                                        <?php 
                                          // If this is a CBCS we want to use different link wording
                                          $consent_order_progress_url = ($package_code == "CBCS") ? "Simple Clean Break Progress" : "Consent Order Progress";
                                        ?>

                                      <li class="<?=($url_segment2 == "consent-order-progress" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>consent-order/consent-order-progress"><i class="fa fa-home" aria-hidden="true"></i><?=$consent_order_progress_url;?></a></li>
                                      <?php endif;?>


                                  <?php if( isset($consent_ff_complete) && $consent_ff_complete === true ) : ?>
                                     
                                    <?php 
                                      // If CBCS or DDPCBCS then we want to use a different document link
                                      $consent_order_docs_url = ($package_code == "CBCS" || $package_code == "DDPCBCS") ? "Simple Clean Break Documents" : "Consent Order Documents";
                                    ?>

                                      <li class="<?=($url_segment2 == "consent_order_docs" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>consent-order/consent_order_docs"><i class="fa fa-file-text-o" aria-hidden="true"></i><?=$consent_order_docs_url;?></a></li>
                                    <?php endif;?>
                                  
                              <?php endif;?>
                          <?php endif;?>

                        
                        <?php else : ?>

                           <li class=""><a href="<?=site_url();?>account"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>

                        <?php endif;?>


                        <?php if(session("user_id")) : ?>


                          <li class="<?=($url_segment2 == "your_payments" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>account/your_payments"><i class="fa fa-solid fa-credit-card"></i>Your Payments</a></li>

                         <?php if($is_sol_managed == 0) : ?>

                            <li class="<?=($url_segment2 == "my-account" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>account/my-account"><i class="fa fa-cog" aria-hidden="true"></i>My Account</a></li>

                              <li id="notification-nav-item" class="<?=($url_segment2 == "notifications" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>account/notifications"><i class="fa fa-bell"></i>Notifications <?php if($notification_count > 0 ) : ?> <span class='notification-number'><?=$notification_count;?></span> <?php endif;?></a></li>

                              <?php if($show_upload_step == TRUE && $requires_q == TRUE) : ?>
                                    <li><a href="<?=site_url();?>account/client_upload"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i>Upload Document To Case Manager</a></li>
                              <?php endif;?>
                          
                            <?php $hideItems = ["SCN"] ; ?>
                            <?php if( !in_array($package_code, $hideItems) ) : ?>
                              <li class="<?=($url_segment2 == "payments" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>account/payments/fees"><i class="fa fa-shopping-cart" aria-hidden="true"></i>Pay Fees</a></li>
                              <li class="<?=($url_segment2 == "upgrade" ) ? 'active-page' : '';?>"><a href="<?=site_url();?>account/upgrade"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i>Upgrade</a></li>
                            <?php endif;?>

                            <?php if($use_portal == false) : ?>
                            <li style="<?=$hide_for_portal;?>"><a href="<?=site_url();?>account/my-documents"><i class="fa fa-file-text-o" aria-hidden="true"></i>Divorce Documents <?= ( @$total_documents > 0 ) ? "<span class='consent-doc-number'>{$total_documents}</span>" : "";?></a></li>
                            <?php endif;?>


                          <?php if($consent_questions_count > 0) : ?>
                                <li style="background-color: red;"><a href="<?=site_url();?>consent-order/consent_questions"><i class="fa fa-envelope"></i>Consent Order Questions (<?=$consent_questions_count ;?>)</a></li>
                          <?php endif;?>

                        <?php endif;?>
                            <li><a href="mailto:support@divorce-online.co.uk"><i class="fa fa-envelope"></i>Email Us</a></li>
                            <li><a href="<?=site_url();?>logout"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
                        <?php endif;?>
                <?php endif;?>

                </ul>

            </div>
          <?php endif;?>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <?php //endif;?>
</div>