<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

###############################
########### LOGIN #############
###############################
$routes->get('logout', 'Login::delete');
$routes->get("reset-password/(:any)/(:any)", "Login::resetPassword/$1/$2");
$routes->post("reset-password/(:any)/(:any)", "Login::resetPassword/$1/$2");
$routes->get('to-many-attempts', "Login::toManyAttempts");
$routes->get('on-hold', "Login::onHold");
$routes->get('reactivate', "Login::reactivate");
$routes->get('forgot-password', 'Login::forgotPassword');
$routes->post('forgot-password', 'Login::forgotPassword');

$routes->group('login', function ($routes) {
    $routes->post('create', 'Login::create');
    $routes->get('showLogoutMessage', 'Login::showLogoutMessage');
    $routes->get('', 'Login::new');
});


###############################
########### ACCOUNT ###########
###############################
$routes->group('account', function ($routes) {
    $routes->get('your_payments', 'Account::payments');
    $routes->get('my-account', 'Account::myAccount');
    $routes->post('updateMyAccount', 'Account::updateMyAccount');
    $routes->get('notifications', 'Account::notifications');
    $routes->get('payments/fees', "Account::payFees");
    $routes->post('payments/fees', "Account::payFees");
    $routes->get('upgrade', "Account::upgrade");
    $routes->post("upgrade", "Account::upgrade");
    $routes->get('update-details', "Account::firstLogin");
    $routes->post('update-details', "Account::firstLogin");
    $routes->get('', 'Account::index');
    $routes->get('portal', "Questionnaire::portal");
    $routes->post('portal', "Questionnaire::portal");
    $routes->get("answer_question/(:any)", "ConsentOrder::answerQuestion/$1");
    $routes->match(['GET', 'POST'], "verify", "Account::verify");
    $routes->get('resend-verification-code', "Account::resendVerificationCode");
});


##################################
######### CONSENT ORDER ##########
##################################
$routes->group('consent-order', function ($routes) {
    $routes->get('consent_order_ff/(:any)', 'ConsentOrder::consentOrderFf/$1');
    $routes->post('consent_order_ff/(:any)', 'ConsentOrder::consentOrderFf/$1');
    $routes->get('consent_ff_finish', 'ConsentOrder::consentOrderFinished');
    $routes->get('consent_ff_finish_app_only', 'ConsentOrder::consentFinishAppOnly');
    $routes->get('client_review_d81/(:any)', 'ConsentOrder::clientReviewD81/$1');
    $routes->post("addPropertyCo", "ConsentOrder::addPropertyCo");
    $routes->post("fetchPropertyData", "ConsentOrder::fetchPropertyData");
    $routes->post("deletePropData", "ConsentOrder::deletePropData");
    $routes->post("removeProperty", "ConsentOrder::removeProperty");
    $routes->post("fetchPensionData", "ConsentOrder::fetchPensionData");
    $routes->post("addPensionCo", "ConsentOrder::addPensionCo");
    $routes->post("removePension", "ConsentOrder::removePension");
    $routes->get('view-supporting-document/(:any)', "ConsentOrder::viewSupportingDocument/$1");
    $routes->get('delete-supporting-document/(:any)', "ConsentOrder::deleteSupportingDocument/$1");

    $routes->get("consent_questions", "ConsentOrder::consentQuestions");
    $routes->get("answer_question/(:any)", "ConsentOrder::answerQuestion/$1");
    $routes->post("answer_question/(:any)", "ConsentOrder::answerQuestion/$1");
    $routes->get('consent-order-progress', "ConsentOrder::consentOrderProgress");
    $routes->get('consent_order_docs', "ConsentOrder::documents");
    $routes->get('download-client-doc/(:any)', 'ConsentOrder::downloadClientDoc/$1');
    $routes->get('stream-file/(:any)', 'ConsentOrder::streamFile/$1');
    $routes->get('stream-file-clean-break/(:any)', 'ConsentOrder::streamFileCleanBreak/$1');
});


##################################
######### QUESTIONNAIRE ##########
##################################
$routes->group('questionnaire', function ($routes) {
    $routes->get('stage1/(:any)/(:any)/(:any)/(:any)', 'Questionnaire::stage1/$1/$2/$3/$4');
    $routes->get('stage1/(:any)/(:any)/(:any)', 'Questionnaire::stage1/$1/$2/$3');
    $routes->get('stage1/(:any)/(:any)', 'Questionnaire::stage1/$1/$2');
    $routes->get('stage1/(:any)', 'Questionnaire::stage1/$1');
    $routes->get('stage1', 'Questionnaire::stage1');

    $routes->post('stage1/(:any)/(:any)/(:any)/(:any)', 'Questionnaire::stage1/$1/$2/$3/$4');
    $routes->post('stage1/(:any)/(:any)/(:any)', 'Questionnaire::stage1/$1/$2/$3');
    $routes->post('stage1/(:any)/(:any)', 'Questionnaire::stage1/$1/$2');
    $routes->post('stage1/(:any)', 'Questionnaire::stage1/$1');
    $routes->post('stage1', 'Questionnaire::stage1');
    $routes->post('saveSignature', 'Questionnaire::saveSignature');
    $routes->post("checkIsSigned", "Questionnaire::checkIsSigned");
    $routes->get('deleteFile/(:num)/(:any)', 'Questionnaire::deleteFile/$1/$2');
});



##################################
######### CREATE PDFS   ##########
##################################

$routes->group('createpdf', function ($routes) {
    $routes->get('create_co_pdf/(:any)/(:any)/(:any)/(:any)', 'CreatePdf::generateCOPdfFile/$1/$2/$3/$4');
    $routes->get('create_co_pdf/(:any)/(:any)/(:any)', 'CreatePdf::generateCOPdfFile/$1/$2/$3');
    $routes->get('create_legal_advise_word_doc/(:any)/(:any)/(:any)', "CreatePdf::createLegalAdviseWordDoc/$1/$2/$3");
    $routes->get('create_co_word_doc/(:any)', 'CreatePdf::createCoWordDoc/$1');
});


################################
############# CRON #############
################################
$routes->group('cron', function ($routes) {
    $routes->get('remove_client_file', "Cron::remove_client_file");
    $routes->post("getTodayErrorLogEntries", "Cron::getTodayErrorLogEntries");
    $routes->get("getTodayErrorLogEntries", "Cron::getTodayErrorLogEntries");
    $routes->get("deleteOldLogFiles", "Cron::deleteOldLogFiles");
    $routes->get("archiveCases", "Cron::archiveCases");
    $routes->post("getAdditionalFiles", "Cron::getAdditionalFiles");
    $routes->get("writeTextFile", "Cron::writeTextFile");
});


################################
############# PCI  #############
################################
$routes->group('pci', function ($routes) {
    $routes->get('hashFile', "Pci::hashFile");
    $routes->get('compareHash', "Pci::compareHash");
});



################################
########### SINGLE ROUTES ######
################################
$routes->get("application_login", "ConsentOrder::applicationLogin");
$routes->post("application_login", "ConsentOrder::applicationLogin");
$routes->get('vip', "Questionnaire::vipClient");
$routes->get('/', function () {
    return redirect()->to('/login');
});
