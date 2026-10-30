<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;


class ConsentOrder extends BaseController
{
    protected $user;
    protected $model;
    protected $authService;
    protected $validation;
    protected $response;
    protected $userModel;
    protected $accountModel;
    protected $questionnaireModel;
    protected $consentManagedCutOff;
    protected $use_portal;
    protected $res_logged_in;
    protected $imageURL;

    protected $uri_segment1;
    protected $uri_segment2;
    protected $uri_segment3;
    protected $package_consent_type;
    protected $is_package_managed;
    protected $is_sol_managed;
    protected $notification_count;
    protected $latest_notification;
    protected $notification_data;
    protected $is_issued;
    protected $show_consent_order_menu;
    protected $package_code;
    protected $allUserPackageData;
    protected $show_upload_step;
    protected $requires_q;
    protected $hide_for_portal;
    protected $consent_questions_count;
    protected $orderPlaced;
    protected $consentOrderFFComplete;
    protected $date_for_condition;
    protected $codesToCheck;

    public function __construct()
    {
        $this->authService = service("auth");
        $this->user = $this->authService->getCurrentuser();


        $router = service("router");
        // make sure the user is logged in
        if ($this->user === null && $router->methodName() != "applicationLogin") {
            // Store current URL in session
            session()->set('redirect_url', current_url(true));

            // Redirect to login page
            return redirect()->to(site_url('login'));
        }


        $this->model = model("ConsentOrderModel");
        $this->questionnaireModel = model("QuestionnaireModel");
        $this->userModel = model("userModel");
        $this->accountModel = model("AccountModel");
        $this->validation = service("validation");
        $this->response = service("response");

        $this->use_portal = false;
        $this->consentManagedCutOff = "2023-12-12 11:30:00";

        // This is the date and time to base the portal switch on
        $this->date_for_condition = "2022-03-31 08:45:00";

        $this->res_logged_in = (session()->has("respondent_only")) ? true : false;
        $this->imageURL = site_url() . "images/";

        $this->codesToCheck = ["CBCS", "DDPCBCS"];

        if ($router->methodName() != "applicationLogin") {
            // See if this is the users first login
            $this->checkFirstLogin();
            $this->setSegments();
            $this->setCoType();
            $this->setIsManaged();
            $this->setIsSolManaged();
            $this->setIsIssued();
            $this->setMenuPackageData();
            $this->setUploadStep();
            $this->setRequiresQuestionnaire();
            $this->setIsPortal();
            $this->setStandardNotifications();
            $this->setHideForPortal();
            $this->setConsentQuestionCount();
            $this->setOrderPlaced();
            $this->setConsentOrderCompleteStatus();
        }
    }


    /* If we need to send a param to every page then we want to update the array below */
    public function fetchExtraViewData()
    {
        return [
            'uri_segment1' => $this->uri_segment1,
            'url_segment2' => $this->uri_segment2,
            'url_segment3' => $this->uri_segment3,
            'package_consent_type' => $this->package_consent_type,
            'is_package_managed' => $this->is_package_managed,
            'is_sol_managed' => $this->is_sol_managed,
            'notification_count' => $this->notification_count,
            'latest_notification' => $this->latest_notification,
            'notification_data' => $this->notification_data,
            'res_logged_in' => $this->res_logged_in,
            'is_issued' => $this->is_issued,
            'show_consent_order_menu' => $this->show_consent_order_menu,
            'package_code' => $this->package_code,
            'show_upload_step' => $this->show_upload_step,
            'requires_q' => $this->requires_q,
            'use_portal' => $this->use_portal,
            'hide_for_portal' => $this->hide_for_portal,
            'consent_questions_count' => $this->consent_questions_count,
            'orderPlaced' => $this->orderPlaced,
            'consent_ff_complete' => $this->consentOrderFFComplete,
            'date_for_condition' => $this->date_for_condition,
            'codesToCheck' => $this->codesToCheck
        ];
    }

    public function setConsentOrderCompleteStatus()
    {
        $user_id = session("user_id");
        $this->consentOrderFFComplete = $this->model->isConsentOrderFFComplete($user_id);
    }


    public function setOrderPlaced()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->orderPlaced = $this->accountModel->getOrderPlaced($case_id);
    }


    public function returnCaseID($user_id)
    {
        return $this->model->returnCasesIdFromUserId($user_id);
    }


    public function setConsentQuestionCount()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->consent_questions_count = $this->model->checkForConsentQuestions($case_id);
    }

    public function setHideForPortal()
    {
        $this->hide_for_portal = (date("Y-m-d 00:00:00") >= $this->date_for_condition) ? "display:none;" : "";
    }


    public function setIsPortal()
    {
        if ($this->isNonPortalConsentPackage()) {
            $this->use_portal = false;
            return;
        }

        $res = $this->questionnaireModel->returnRow(session("user_id"));
        $this->use_portal = ($res && $res->portal_case == 1) ? true : false;
    }


    public function setUploadStep()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->show_upload_step = $this->questionnaireModel->checkStepComplete(7, $case_id);
    }

    public function setRequiresQuestionnaire()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->requires_q = $this->accountModel->checkPackage($case_id);
    }


    public function setMenuPackageData()
    {
        $this->allUserPackageData = $this->accountModel->getPackagedata(session("user_id"));
        $this->package_code = $this->allUserPackageData->PackageCode;
    }


    public function setIsIssued()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->is_issued = $this->questionnaireModel->hasDateIssued($case_id);
    }


    public function setStandardNotifications()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->notification_count = $this->questionnaireModel->getNotifications($case_id, false, $this->use_portal);
        $this->latest_notification = $this->questionnaireModel->getNotifications($case_id, true, $this->use_portal);
        $this->notification_data = $this->questionnaireModel->getNotificationReplacements(session("user_id"));
    }


    public function setIsSolManaged()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->is_sol_managed  = $this->model->isSolicitorManaged($case_id);
    }


    public function setIsManaged()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->is_package_managed = $this->model->isManaged($case_id);
    }

    // Set the consent order type, we need to check if they have upgraded as well
    public function setCoType()
    {
        // Is this a standalone or managed case?
        $case_id = $this->returnCaseID(session("user_id"));
        $check_co_type = $this->model->returnCOType($case_id);
        $this->package_consent_type = $check_co_type->consent_order_type;

        // Has the case been upgraded?
        list($hasUpgraded, $type) = $this->model->checkForUpgrade($case_id);

        // Set the new type if upgraded
        if ($hasUpgraded) {
            $this->package_consent_type = $type;
        }
    }


    // Set the URI segments so we can use them in the nav
    public function setSegments()
    {
        $request = service("request");
        $segments = $request->getUri()->getSegments();

        $this->uri_segment1 = (!isset($segments[0]))  ? null : $segments[0];
        $this->uri_segment2 = (!isset($segments[1]))  ? null : $segments[1];
        $this->uri_segment3 = (!isset($segments[2]))  ? null : $segments[2];
    }


    public function checkFirstLogin()
    {
        $request = service('request');
        $controller = $request->getUri()->getSegment(1);
        $method = $request->getUri()->getSegment(2);

        if ($method != "update-details" && $controller != "logout") {
            $firstLogin = $this->userModel->isFirstLogin(session("user_id"));
            if ($firstLogin === true) {
                return $this->response->redirect(site_url('/account/update-details'));
                exit();
            } else {
                // Mark setup complete in session so nav shows
                session()->set('account_setup', true);
            }
        }
    }


    public function index() {}

    public function consentOrderProgress()
    {

        // We only redirect here if the CO  hasn't been issed and its a managed service
        if ($this->package_consent_type == 2 && $this->use_portal === true && $this->is_issued === false) {
            return redirect()->to("account");
        }

        $user_id = session("user_id");
        $case_id = $this->returnCaseID($user_id);

        // Check if complete
        $check_for_row = $this->model->getQuestionnaireData(session("user_id"));
        $consent_ff_complete = (!empty($check_for_row) && $check_for_row[0]['complete'] == "1") ? true : false;

        // if this is a split application and the appliant has completed their side we need to change the wording on the dashboard
        $questionnaire_data = (count($check_for_row) === 0) ? [] : $check_for_row[0];
        $allNotifications = $this->model->returnAllNotifications($user_id, $case_id, $this->use_portal);
        // Get the court details
        $court_details = $this->model->getCourtDetails($user_id);
        $court_name = $court_details['court_name'] ?? '';
        $case_number = $court_details['case_number'] ?? '';

        $view_data = [
            'title' => "Consent Order progress",
            'consent_ff_complete' => $consent_ff_complete,
            'questionnaire_data' => $questionnaire_data,
            'all_notifications' => $allNotifications,
            'court_name' => $court_name,
            'case_number' => $case_number
        ];

        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/consent_order_progress", $view_data);
    }


    public function documents()
    {

        if ($this->is_issued === false && $this->package_consent_type == 2 && !$this->isNonPortalConsentPackage()) {
            return redirect()->to("account");
        }

        $user_id = session("user_id");
        $case_id = $this->returnCaseID($user_id);

        $ffData = $this->model->getQuestionnaireData($user_id);

        // Get the package type, is it standalone or managed
        $consent_type = $this->package_consent_type;

        // get the custom documents
        $custom_documents = $this->model->getCustomDocuments($case_id);

        // fetch the D81 document from the custom documetns tab           
        $d81_document = $this->model->checkForD81($case_id);

        // fetch the consent order document from the custom documetns tab
        $co_document = $this->model->checkForCO($case_id);

        // fetch the consent order document from the custom documetns tab
        $charge_document = $this->model->checkForChargeDoc($case_id);

        //get the email of the case manager that is dealing with this
        list($assignedTo, $cmData) = $this->model->getAssignedCMData($case_id);
        $cm_assigned_email = $cmData->email;

        //are the documents ready?
        $consent_order_drafted =  (!empty($ffData) && $ffData[0]['documents_released'] != null) ? $ffData[0]['documents_released'] : "";

        $app_legal =  (!empty($ffData) && $ffData[0]['legal_app_add'] == 1) ? 1 : 0;
        $res_legal =  (!empty($ffData) && $ffData[0]['legal_res_add'] == 1) ? 1 : 0;


        // Check to see if the user has pensions for the consent order
        $pension_data = $this->model->getPensionData($user_id);
        $has_completed_cff = $this->model->checkStepComplete($case_id, 20);


        $view_data = [
            'title' => "Consent Order Documents",
            'custom_documents' => $custom_documents,
            'd81_document' => $d81_document,
            'co_document' => $co_document,
            'charge_document' => $charge_document,
            'consent_order_drafted' => $consent_order_drafted,
            'case_id' => $case_id,
            'consent_type' => $consent_type,
            'user_id' => $user_id,
            'app_legal' => $app_legal,
            'res_legal' => $res_legal,
            'pension_data' => $pension_data,
            'has_completed_cff' => $has_completed_cff
        ];

        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/documents", $view_data);
    }

    public function streamFile($user_id)
    {
        // Firstly does the user id match the session user id?
        if (!$user_id || $user_id == "" || $user_id == null || $user_id != session("user_id")) {
            die("Sorry we cant process your request. Please contact support.[ERRSF:1]");
        }

        // Lets check if the file exists
        $path = ROOTPATH . "questionnaire_files/consent_order/D81_" . $user_id . ".pdf";
        if (file_exists($path)) {
            return $this->response->download($path, null);
        } else {
            session()->setFlashdata("ERR", "File does not exist");
            return redirect()->back()->withInput();
        }
    }

    public function streamFileCleanBreak($user_id)
    {

        // Firstly does the user id match the session user id?
        if (!$user_id || $user_id == "" || $user_id == null || $user_id != session("user_id")) {
            die("Sorry we cant process your request. Please contact support.[ERRSFC:1]");
        }

        $questionnaireData = $this->model->fetchCBCSData($user_id);
        if ($questionnaireData === false) {
            die("Sorry, there was an error generating the clean break consent order document. Please contact support.");
        }

        include_once(getcwd() . '/tbs_class.php');
        include_once(getcwd() . '/tbs_plugin_opentbs.php');

        $TBS = new \clsTinyButStrong; // new instance of TBS
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
        $TBS->SetOption(array('noerr' => true));

        // We want to create a word document on the fly
        $templatePath = getcwd() . DIRECTORY_SEPARATOR . "documents" . DIRECTORY_SEPARATOR . "CONSENT_ORDER" . DIRECTORY_SEPARATOR . "CBCS_Clean_Break.docx";

        //create the applicant name
        $app_name = (isset($questionnaireData->app_forename) ? $questionnaireData->app_forename : '') . " ";
        $app_name .= (isset($questionnaireData->app_other_names) && $questionnaireData->app_other_names != "") ? $questionnaireData->app_other_names . " " : "";
        $app_name .= isset($questionnaireData->app_surname) ? $questionnaireData->app_surname : '';
        $app_name = ucwords($app_name);

        //create the respondent name
        $res_name = (isset($questionnaireData->res_forename) ? $questionnaireData->res_forename : '') . " ";
        $res_name .= (isset($questionnaireData->res_other_names) && $questionnaireData->res_other_names != "") ? $questionnaireData->res_other_names . " " : "";
        $res_name .= isset($questionnaireData->res_surname) ? $questionnaireData->res_surname : '';
        $res_name = ucwords($res_name);

        // replace the data
        $case_number = (isset($questionnaireData->case_number) ? $questionnaireData->case_number : '');
        $TBS->VarRef["case_number"] = $case_number;
        $TBS->VarRef["app_name"] = $app_name;
        $TBS->VarRef["res_name"] = $res_name;

        $app_gender = ($questionnaireData->app_gender == "male") ? "he" : "she";
        $TBS->VarRef["app_gender"] =  $app_gender;

        $res_gender = ($questionnaireData->res_gender == "male") ? "he" : "she";
        $TBS->VarRef["res_gender"] =  $res_gender;

        $template = $templatePath;
        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        // Append a random number to the file name to bust caching
        $random_number = substr((string)(time() + rand(100000, 999999)), -6);
        $output_file_name = "CBCS_Clean_Break_" . $random_number;
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name . ".docx");
        exit();
    }


    public function downloadClientDoc($uuid, $stream_replaced_file = 0)
    {
        //get user ID
        $user_id = session("user_id");
        $stream_replaced_file = (int)$stream_replaced_file;
        $uuid = htmlentities($uuid);

        $desPath = (getenv("CI_ENVIRONMENT") == "production") ? "https://www.olstools.co.uk/case-manager/" : "https://www.case-manager-dev.co.uk/";
        $destination_url = $desPath . "Dtdownloads/stream_file_DT/" . $user_id . "/" . $uuid . "/" . $stream_replaced_file;

        if ($user_id == 0 || $user_id == "" || $uuid == "") {
            die("Mising data");
        } else {

            // Delete the temp hash row for this user
            $this->model->deleteTempFileHash($user_id);

            //create random hash
            $random_hash = sha1(time() . rand(900, 10000) . md5(time()));
            //append the random hash to the end, add a random string to the end to confuse potentional hacker as this para does nothing
            $destination_url = $destination_url . "/" . $random_hash . "/" . md5(time() . rand(400, 1000));

            //update database with UUID and hash + ID
            $this->model->addTempFileHash($user_id, $random_hash, $uuid);

            return redirect()->to($destination_url);
        }
    }


    public function applicationLogin()
    {

        //check to see if the login form has been submitted
        if ($this->request->getPost("login_submit")) {
            $rules = $this->model->respondentLoginRules;
            $this->validation->setRules($rules);

            if ($this->validation->withRequest($this->request)->run()) {

                $res_login = $this->model->tryRespondetLogin($this->request->getPost());
                $data = $this->request->getPost();
                $email = trim($data['email']);
                $access_code = trim($data['access_code']);

                switch ($res_login) {
                    case 'success':

                        //set session
                        $user_id = session("user_id");
                        session()->set("loggedin_user", 1);
                        session()->set("respondent_only", 1);

                        //check if the applicant hasn't been informed that the respondent has logged in
                        $checkIfInformed = $this->model->hasBeenInformed($access_code);

                        if ($checkIfInformed === false) {

                            //get the case type (standard,non-standard,ddp/dpp)
                            list($case_id, $user_email, $forename) = $this->model->getCaseDetails($user_id);


                            //send email
                            $email_template_details = $this->model->returnEmailTemplate('cc_app_started');
                            $subject = $email_template_details->subject;

                            $main_body = $email_template_details->body;
                            $msg = str_replace('{APP_NAME}', $forename, $main_body);

                            //get the email wrapper template
                            $main_email_template = $this->model->getEmailWrapper($case_id);

                            //str replace the details into placeholders
                            $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
                            $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
                            $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

                            if ($this->model->sendEmail($user_email, $subject, $body)) {
                                //update respondent_started = 1
                                $update = array('respondent_started' => 1);
                                $this->model->updateQuestionnaire($user_id, $update);
                            }
                        }

                        //redirect
                        return redirect()->to("consent-order/consent_order_ff/2");
                        break;

                    case 'login_failed':
                        session()->setFlashdata("ERR", "Login failed. Email or access code incorrect");
                        return redirect()->back()->withInput();
                        break;

                    case 'failed':
                        session()->setFlashdata("ERR", "Login failed. Email or access code incorrect");
                        return redirect()->back()->withInput();
                        break;

                    case 'not_active':
                        session()->setFlashdata("ERR", "Unable to log in. Error with main account. (NA)");
                        return redirect()->back()->withInput();
                        break;

                    case 'requires_reactivation':
                        session()->setFlashdata("ERR", "Unable to log in. Error with main account. (RACC)");
                        return redirect()->back()->withInput();
                        break;
                    case 'on_hold':
                        session()->setFlashdata("ERR", "Unable to log in. Error with main account. (OH)");
                        return redirect()->back()->withInput();
                        break;
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }


        return view("ConsentOrder/application_login", [
            'title' => "Respondent Login"
        ]);
    }


    public function consentOrderFf($page = 1)
    {

        //get the user ID
        $user_id = session("user_id");
        $case_id = $this->model->returnCasesIdFromUserId($user_id);
        $successFiles = [];
        $failedFiles = [];

        // If package code is CBCS or DPPCBCS and page is 5,6 - redirect to page 4
        if (!empty($this->package_code) && in_array($this->package_code, $this->codesToCheck) && ($page == 5 || $page == 6)) {
            return redirect()->to("consent-order/consent_order_ff/4");
        }

        // If the package code is either 'DPPCO','CBC','CBCFT','CBO and purchased after the 4th Jan 2026 
        $pension_share_remove_codes = ["DPPCO", "CBC", "CBCFT", "CBO"];
        $pension_cut_off = "2026-01-04 23:59:59";

        if (!empty($this->package_code) && in_array($this->package_code, $pension_share_remove_codes) && !empty($this->orderPlaced) && $this->orderPlaced > $pension_cut_off && $page == 5) {
            return redirect()->to("consent-order/consent_order_ff/6");
        }

        // For the CBC we want to skip some pages as we dont need the information
        if (
            (in_array($this->package_code, $this->codesToCheck) && ($page == 4 || $page == 5)) ||
            ($this->package_code == "CBCS" && $page == 4) ||
            ($this->package_code == "CBO" && ($page == 4 || $page == 5)) ||
            ($this->package_code == "DPPCBO" && ($page == 4 || $page == 5))
        ) {

            if ($page == 4) {
                // if the user tries to get to page 4 we will send them to page 6
                $page = (in_array($this->package_code, $this->codesToCheck)) ? 7 : 6;
                return redirect()->to("consent-order/consent_order_ff/{$page}");
            }

            // If the previous button is pressed on page 6 send them to page 3
            if ($page == 5) {
                // if the user tries to get to page 4 we will send them to page 3
                return redirect()->to("consent-order/consent_order_ff/3");
            }
        }

        // has the user completed the previous page, if its page 2 then we dont need to check page 1
        // On page 7 we will make sure we have a D81 file, if the respondent has logged in then we check for a respondent d81 file
        $pages_to_check = [3, 4, 7];
        if (in_array($page, $pages_to_check)) {
            // Check the previous page was completed - we need to be mindful that this may be a split application
            $checkPreviousPage = $this->model->checkPreviousPage($user_id, $page, $this->res_logged_in, $this->package_code);

            if ($checkPreviousPage[0] == false && !empty($this->package_code)) {
                // Set a session to say there is missing data on the previous page
                session()->setFlashdata("user_msg_error", "Please complete the the required fields on this page before continuing.");
                return redirect()->to("consent-order/consent_order_ff/" . $checkPreviousPage[1]);
            }
        }

        // Set blank arrays
        list($success_files, $failed_files) = [[], []];

        // Get the post data && Set the application user ID
        $application_uid = $user_id;
        $data = $this->request->getPost();

        // Is this a standalone or managed case?
        $check_co_type = $this->model->returnCOType($case_id);


        if ($this->request->getPost("save_only") && $this->request->getPost("respondent_separate_email") != "") {
            $this->model->addStage1FF(1, $data, $user_id);
            return redirect()->back()->withInput();
        }


        // Not a CO case at all, redirect
        if ($check_co_type->consent_order_type === null) {
            return redirect()->to('account');
        } else {

            // This is a managed CO
            if ($check_co_type->consent_order_type == 2) {
                // We have a managed case, we firstly need to check that the product was purchased on the or after the cutoff date
                if ($check_co_type->OrderPlaced < $this->consentManagedCutOff) {
                    return redirect()->to('account');
                }
                // Check to see if this is a managed C only

                // Has the issue step been completed for this case
                $has_completed_step = $this->questionnaireModel->hasDateIssued($case_id);
                // The Divorce step for issue_date HASN'T been completed yet
                if ($has_completed_step == 0 && !$this->isNonPortalConsentPackage()) {
                    return redirect()->to('account');
                }
            }
        }


        // Fetch uploaded files from the custom db file
        $supportingDocuments = $this->model->getSupportingDocuments($case_id);
        $consent_type = $check_co_type->consent_order_type;

        // Has the case been upgraded?
        list($hasUpgraded, $type) = $this->model->checkForUpgrade($case_id);

        // Set the new type if upgraded
        if ($hasUpgraded) {
            $consent_type = $type;
        }

        //get a list of countries
        $countries = $this->accountModel->getCountries();

        // Get and set the package code linked to this user
        $get_pode = $this->model->getUserPackageDetails($user_id);
        $pid = $get_pode->PackageId;

        // Does this package have joint fees
        $check_joint_fees = $this->model->hasJointFees($pid);
        $show_joint_fields = ($check_joint_fees === 0) ? false : true;

        // Get a list of properties if the user has any
        $user_properties = $this->model->fetchCCProperties($user_id);
        $user_pensions = $this->model->fetchCCPensions($user_id);
        $has_missing_pension_info = $this->model->missingConsentInfo($user_id);

        //check if the user has already completed
        $check_for_complete = $this->model->checkIfComplete($user_id);
        if ($check_for_complete === true) {
            //we want to redirect the user as they have already completed the questionnaire and don't have access to edit the information
            return redirect()->to("consent-order/consent_ff_finish");
        }

        //get the users row of data if they have it
        $page_data = $this->model->getQuestionnaireData($user_id);

        // Is this a split application?
        $is_split_app = (is_array($page_data) && count($page_data) > 0) ? $page_data[0]['has_contact'] : "";


        if (is_array($page_data) && count($page_data) > 0) {
            if ($page_data[0]['applicant_single_app_complete'] == 1 && $this->res_logged_in === false) {
                return redirect()->to("consent-order/consent_ff_finish_app_only");
            }
        }

        // Set a session so we know the respondent is logged in
        $hide_save = FALSE;
        if ($this->res_logged_in === true) {
            session()->set("RES_LOGGEDIN", 1);
            // This will be used to hide the save button
            $hide_save = TRUE;
        }


        if ($page == 1 && $this->res_logged_in === true) {
            return redirect()->to("consent-order/consent_order_ff/2");
        }

        //handle the clients preference for if they have contact or not with the resondent
        if ($this->request->getPost("has_contact_submit") || $this->request->getPost("start_ff")) {

            $postedData = $this->request->getPost();
            if (array_key_exists("respondent_separate_email", $postedData)) {

                // We need to set an error and redirect
                $this->validation->setRules([
                    'respondent_separate_email' => [
                        'label' => 'Respondent Email',
                        'rules' => 'required|valid_email',
                        'errors' => [
                            'required' => 'The respondent email address is required if you are not in contact.',
                            'valid_email' => 'Please provide a valid respondent email address.'
                        ]
                    ]
                ]);

                if (!$this->validation->withRequest($this->request)->run()) {
                    session()->setFlashdata("errors", $this->validation->getErrors());
                    return redirect()->back()->withInput();
                }
            }


            // based on if this is a split app or not we need to change the value sent to the function
            $option = ($this->request->getPost("start_ff")) ? 1 : 0;
            $this->model->addStage1FF($option, $data, $user_id);
            return redirect()->to("consent-order/consent_order_ff/2");
        }



        #################################################
        ############### FORM SUBMISSIONS ################
        #################################################

        if ($this->request->getPost("page2_submit")) {

            //do validation
            $rules = $this->model->page2Rules;

            // // We need to swap out the validation based on the court selected
            $posted_data = $this->request->getPost();
            if (isset($posted_data['court_other']) && $posted_data['court_other'] != "CTSC") {
                // Update the case number validation rule
                $rules['case_number'] = 'required|regex_match[/^[a-zA-Z]{2}[0-9]{2}[D|CPD]{1,3}[0-9]{5}$/i]';
            }

            //remove rules if this is the applicant filling out their part
            if ($page_data[0]['has_contact'] == 0 && $this->res_logged_in === false) {

                foreach ($rules as $key => $value) {
                    if (strpos($key, 'res_') !== false) {
                        unset($rules[$key]);
                    }
                }
            }

            //remove rules if this is the applicant filling out their part
            if ($page_data[0]['has_contact'] == 0 && $this->res_logged_in === true) {

                foreach ($rules as $key => $value) {
                    if (strpos($key, 'app_') !== false) {
                        unset($rules[$key]);
                    }
                }

                unset($rules["name_of_court"], $rules["case_number"], $rules["marr_or_civ"]);
            }

            // If the package is CBCS then we need to remove the rules for app_owned_type and res_owned_type
            if (!empty($this->package_code) && in_array($this->package_code, $this->codesToCheck)) {
                unset($rules["app_owned_type"], $rules["res_owned_type"]);
            }


            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run()) {
                //update the database
                $this->model->addStage2FF($user_id, $data, $this->res_logged_in);
                return redirect()->to("consent-order/consent_order_ff/3");
            }
        }



        if ($this->request->getPost("page3_submit")) {

            // Get the file object
            $file = $this->request->getFile("userfile");

            // If the respondent has logged in then we want a different file name
            $d81FileName = ($this->res_logged_in === false) ? "D81_" . $user_id . ".pdf" : "D81_" . $user_id . "_respondent.pdf";

            $config = ['upload_path' => ROOTPATH . '/questionnaire_files/consent_order', 'file_name' => $d81FileName];

            // Check if the user has already uploaded a file, if they have, let them pass through validation
            $file_found = false;
            if (file_exists($config['upload_path'] . "/" . $config['file_name'])) {
                $file_found = true;
            }

            // If we have a valid file OR the user hasn't uploaded a new file BUT has already submitted one
            $alreadySubmitted = (isset($file) &&  !$file->isValid() && $file_found === true) ? true : false;

            if ($file->isValid() || $alreadySubmitted === true) {

                // Try the upload
                if ($alreadySubmitted === true) {
                    $uploadStatus = "success";
                    $uploadMsg = "F";
                } else {
                    list($uploadStatus, $uploadMsg) = $this->model->getD81UploadErrors($file);
                }


                if ($uploadStatus == "error") {
                    session()->setFlashdata("errors", [$uploadMsg]);
                    return redirect()->back()->withInput();
                } else {

                    if ($alreadySubmitted === true) {
                        $uploadedFile = true;
                    } else {
                        // Do the upload
                        $newFileName = $config["file_name"];

                        $ext = $file->getExtension();
                        $path = $config['upload_path'];
                        $uploadedFile = $file->move($path, $newFileName, true);
                    }

                    if ($uploadedFile) {
                        //do validation
                        $rules = $this->model->page3Rules;

                        // If the product is CBCS then we need to remove the rules for legal_app_add and legal_res_add
                        if (!empty($this->package_code) && in_array($this->package_code, $this->codesToCheck)) {
                            unset($rules["legal_app_add"], $rules["legal_res_add"]);
                        }

                        if ($this->res_logged_in === true) {
                            // Remove the applicant rule
                            if ($page_data[0]['has_contact'] == 0 && $this->res_logged_in == true) {
                                unset($rules["legal_app_add"]);
                            }

                            // Remove the less than 250 rule
                            if ($page_data[0]['has_contact'] == 0 && $this->res_logged_in == true) {
                                unset($rules["less_than_250"]);
                            }

                            $this->validation->setRules($rules);
                            if ($this->validation->withRequest($this->request)->run()) {
                                //move to next page, update the database
                                $this->model->addStage3FF($user_id, $data);
                                return redirect()->to("consent-order/consent_order_ff/4");
                            } else {
                                session()->setFlashdata("errors", $this->validation->getErrors());
                                return redirect()->back()->withInput();
                            }
                        }


                        // Remove the responded rule
                        if ($page_data[0]['has_contact'] == 0 && $this->res_logged_in == FALSE) {
                            unset($rules["legal_res_add"]);
                        }

                        // Remove the applicant rule
                        if ($page_data[0]['has_contact'] == 0 && $this->res_logged_in == true) {
                            unset($rules["legal_app_add"]);
                            unset($rules["less_than_250"]);
                        }

                        $this->validation->setRules($rules);
                        if ($this->validation->withRequest($this->request)->run()) {
                            //move to next page, update the database
                            $this->model->addStage3FF($user_id, $data);
                            return redirect()->to("consent-order/consent_order_ff/4");
                        } else {
                            session()->setFlashdata("errors", $this->validation->getErrors());
                            return redirect()->back()->withInput();
                        }
                    } else {
                        session()->setFlashdata("errors", ["Failed to upload document. Please try again or contact support."]);
                        return redirect()->back()->withInput();
                    }
                }
            } else {
                session()->setFlashdata("errors", ["You must upload a file."]);
                return redirect()->to("consent-order/consent_order_ff/3")->withInput();
            }
        }


        if ($this->request->getPost("page6_submit")) {
            //do validation
            $rules = $this->model->page6Rules;
            $data = $this->request->getPost();

            // Set validation for the "ALREADY ACTIONED"
            if ($this->package_code == "CBO" || $this->package_code == "DPPCBO") {
                $rules = [
                    'already_actioned' => [
                        'rules' => 'required|min_length[20]',
                        'errors' => [
                            'required' => 'The already actioned text box is required.',
                            'min_length' => 'Please enter at least 20 characters.',
                        ],
                    ],
                ];
            }

            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run()) {
                //update the database
                $this->model->addStage5FF($user_id, $data);
                return redirect()->to("consent-order/consent_order_ff/7");
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }


        // Supporting documents
        if ($this->request->getPost("upload_files")) {
            $files = $this->request->getFiles();
            $savePath = ROOTPATH . "questionnaire_files/consent_order/" . $case_id;

            // make sure the user has a dir to upload into
            if (!is_dir($savePath)) {
                mkdir($savePath);
            }

            foreach ($files['images'] as $file) {
                if ($file->isValid() && ! $file->hasMoved()) {
                    list($errorStatus, $errorMsg) = $this->model->getMuliUploadError($file);
                    if ($errorStatus == "error") {
                        // Add to the failedFiles array
                        array_push($failedFiles, "<b>File {$file->getName()}:</b> " . $errorMsg);
                    } else {
                        // We try and move the file, check for upload error
                        $uploadedFile = $file->move($savePath, $file->getName(), true);

                        if (!$uploadedFile) {
                            array_push($failedFiles, "<b>File {$file->getName()}:</b> Failed to upload file. Please try again.");
                        } else {
                            array_push($successFiles, "<b>File {$file->getName()}:</b> File uploaded successfully.");

                            $customSavePath = "questionnaire_files/consent_order/" . $case_id;
                            $this->model->addSupportingFile($customSavePath, $file->getName(), $case_id);
                        }
                    }
                }
            }

            return redirect()->back()->withInput();
        }


        $view_data = [
            'title' => "Consent Order",
            'countries' => $countries,
            'pid' => $pid,
            'data' => $data,
            'show_joint_fields' => $show_joint_fields,
            'user_properties' => $user_properties,
            'user_pensions' => $user_pensions,
            'has_missing_pension_info' => $has_missing_pension_info,
            'page_data' => $page_data,
            'is_split_app' => $is_split_app,
            'hide_save' => $hide_save,
            'res_logged_in' => $this->res_logged_in,
            'application_uid' => $application_uid,
            'consent_model' => $this->model,
            'consent_type' => $consent_type,
            'supporting_documents' => $supportingDocuments,
            'success_files' => $successFiles,
            'failed_files' => $failedFiles
        ];

        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/consent_order_ff_page_{$page}", $view_data);
    }




    public function viewSupportingDocument($uuid)
    {
        // If no UUID
        if ($uuid == "") {
            die("File Not Found. Please close this tab and try again.");
        }

        $getFile = $this->model->getSupportingDoc($uuid, session("user_id"));

        if ($getFile == "PERMERROR") {
            die("You don't have permission to view this asset.");
        }

        if ($getFile === null) {
            die("File not found");
        }

        // The file belongs to the user so we can direct them to the file
        return redirect()->to($getFile);
    }

    public function deleteSupportingDocument($uuid)
    {
        // If no UUID
        if ($uuid == "") {
            die("File Not Found. Please close this tab and try again.");
        }

        $deleteFile = $this->model->removeSupportingDoc($uuid, session("user_id"));

        if ($deleteFile == "PERMERROR") {
            die("You don't have permission to delete this asset.");
        }

        if ($deleteFile == "SUCCESS" || $deleteFile == "FAILED" || $deleteFile == "FILENOTFOUND") {
            return redirect()->to("consent-order/consent_order_ff/7");
        }
    }


    public function clientReviewD81($joint = 1)
    {
        $user_id = session("user_id");
        switch ($joint) {
            case 1:
                // make sure the file exists, if not rediect to page 3 with error message
                if (!file_exists(ROOTPATH . "/questionnaire_files/consent_order/D81_" . $user_id . ".pdf")) {
                    session()->setFlashdata("errors", ["The file you are trying to download does not exist. Please contact support if you believe this is an error."]);
                    return redirect()->to("consent-order/consent_order_ff/3");
                }

                $data = file_get_contents(ROOTPATH . "/questionnaire_files/consent_order/D81_" . $user_id . ".pdf");

                // Clear output buffers to prevent any accidental output
                if (ob_get_level()) {
                    ob_end_clean();
                }

                // for security we dont want to expose the user id in the file name
                $randomFileName = uniqid() . ".pdf";
                header("Content-Type: application/pdf");
                header("Content-Disposition: attachment; filename=" . $randomFileName);
                header("Content-Length: " . strlen($data));
                header("Pragma: public");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Expires: 0");

                echo $data;
                exit;
                break;


            case 2:
                //  make sure the file exists, if not rediect to page 3 with error message
                if (!file_exists(ROOTPATH . "/questionnaire_files/consent_order/D81_" . $user_id . "_respondent.pdf")) {
                    session()->setFlashdata("errors", ["The file you are trying to download does not exist. Please contact support if you believe this is an error."]);
                    return redirect()->back();
                }

                $data = file_get_contents(ROOTPATH . "/questionnaire_files/consent_order/D81_" . $user_id . "_respondent.pdf");
                // Clear output buffers to prevent any accidental output
                if (ob_get_level()) {
                    ob_end_clean();
                }

                header("Content-Type: application/pdf");
                header("Content-disposition: attachment;filename=D81_" . $user_id . "_respondent.pdf");
                header("Content-Length: " . strlen($data));
                header("Pragma: public");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Expires: 0");
                echo $data;
                exit;

                break;
        }
    }


    public function consentOrderFinished()
    {
        $user_id = session("user_id");
        $case_id = $this->model->returnCasesIdFromUserId($user_id);

        // //mark the CBO questionnaire as complete which will lock it
        $completed_date = date("Y-m-d H:i:s");
        $update = array('complete' => 1, 'date_completed' => '' . $completed_date . '');

        if ($this->res_logged_in === true) {
            $update['respondent_completed_ff'] = 1;
        }

        //is complete already? we only want to update if it hasnt been updated already
        $ff_details = $this->model->getQuestionnaireData($user_id);
        $ff_details = $ff_details[0];
        $has_contact = ($ff_details['has_contact'] == 1) ? true : false; // Split application


        // //only update if it hasnt been completed already
        // This is a join application  - do the update
        if ($has_contact === true) {
            if ($ff_details['complete'] == "0") {
                $this->model->updateQuestionnaire($user_id, $update);
            }
        }

        // This is a split application - only set as complete when the respondent has completed
        if ($has_contact === false && $this->res_logged_in === true) {
            if ($ff_details['complete'] == "0") {
                $this->model->updateQuestionnaire($user_id, $update);
            }
        }


        // We need to check if the date_complete is null, its null set it to the current date ($completed_date), else use the database value for date_completed
        if ($ff_details['date_completed'] == null) {
            $ff_details['date_completed'] = $completed_date;
        }

        //update the step for CO FF complete + get client details + workout if this is a FT service
        $is_ft = $this->model->isFastTrack($case_id, true);

        // check addit services
        if ($is_ft == false) {
            // We then need to check the addition services table
            $is_ft = $is_ft = $this->model->isFastTrack($case_id, false);
        }

        // We need to see if the package its self contains the fast track service - only if the above is false
        if ($is_ft == false) {
            $is_ft = ($this->model->checkIfPackageContainsFastTrack($user_id) == true) ? true : false;
        }

        //send the complete date through
        $date_to_format = date("Y-m-d", strtotime($completed_date));

        // We want to show the client what there due date would be if they fast tracked their application
        $date_if_fast_tracked = date("Y-m-d", strtotime($completed_date));
        $date_if_fast_tracked =  $this->model->addBusinessDays($date_if_fast_tracked, getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK"));


        //we will use these numbers to add 7 or 28 working days into the email that is sent to the client
        $days_to_increase = ($is_ft == TRUE) ? (int)getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : (int)getenv("CONSENT_ORDER_DUE_DAYS_STANDARD");
        // Based on the OrderPlaced date we want to overwrite days_to_increase to 28 if its before 20th May 2025 and NOT a fast track service

        // get order placed date
        $orderPlacedDate = $this->model->db->table("cases")->select("OrderPlaced")->where("ID", $case_id)->get()->getRow()->OrderPlaced;

        // Its not a fast track and the service was ordered before 20th May 2025 so we need to change the dates to 28 days and not the current env settings
        $overWriteDaysToIncrease = false;
        $customDaysToIncrease = 0;

        // Anything before the 20th of May 2025 and NOT a fast track service, we need to change the days to increase to 28
        if ($orderPlacedDate && $orderPlacedDate < "2025-05-20 00:00:00" && $is_ft == false) {
            $days_to_increase = 28;
            $overWriteDaysToIncrease = true;
            $customDaysToIncrease = $days_to_increase;
        }


        // We now need to see if the package was purchased between the 20th of may 2025 and the 30th of June 2025, we want to set this to 14 days as that was their purchase terms
        if ($orderPlacedDate && $orderPlacedDate >= "2025-05-20 00:00:00" && $orderPlacedDate <= "2025-06-30 23:59:59" && $is_ft == false) {
            $days_to_increase = 14;
            $overWriteDaysToIncrease = true;
            $customDaysToIncrease = $days_to_increase;
        }

        $date_to_format =  $this->model->addBusinessDays($date_to_format, $days_to_increase);

        //date('m/d/Y', strtotime("+5 weekdays", $today));
        $db_due_date = date('Y-m-d H:i:s', strtotime($date_to_format));
        $future_date = date('dS F Y', strtotime($date_to_format));

        // Send email to CM
        $client_details = $this->model->getUserDetails($user_id);

        // If the product is CBCS do the following
        if (isset($this->package_code) && in_array($this->package_code, $this->codesToCheck) && $ff_details['ff_complete_email_sent'] == 0) {
            // Set the ff_complete_email_sent = 1 to stop this script being run again
            $this->model->updateQuestionnaire($user_id, ['ff_complete_email_sent' => 1]);

            //insert a new row stand alone bridge table the step ID also changes at this stage for step 1, 2, ,3
            $table = "step_manager_cc_standalone_bridge";
            $now   = date('Y-m-d H:i:s');
            $user  = 37;

            $data = [];
            foreach ([1, 2, 3] as $stepId) {
                $data[] = [
                    'case_id'        => $case_id,
                    'step_id'        => $stepId,
                    'status'         => 1,
                    'completed_by'   => $user,
                    'date_completed' => $now,
                ];
            }
            $this->model->db->table($table)->insertBatch($data);

            // Set the documents as realsed and drafted automatically
            // this also ensures this case is picked up by the standalone clean up script
            $updateReleasedDocuments = [
                'documents_released' => 1,
                'drafted_by_ols_consento' => 1,
                'documents_release_date' => $now,
            ];
            // Update the consent_ff_questionnaire table
            $this->model->updateQuestionnaire($user_id, $updateReleasedDocuments);

            // Exclude the CBCS from the consent order listings upon completion of the fact finder
            // set exclude_from_listings = 1 for the user_id
            $this->model->updateQuestionnaire($user_id, ['exclude_from_listings' => 1]);
        }


        //we only want to send the completed email once
        if ($ff_details['ff_complete_email_sent'] == 0 && isset($this->package_code) && !in_array($this->package_code, $this->codesToCheck)) {

            //get the email of the case manager that is dealing with this
            list($get_assigned_to, $get_cm_details) = $this->model->getAssignedCMData($case_id);

            $cm_assigned_email = ($get_cm_details->email != "" || $get_cm_details->email != NULL) ? $get_cm_details->email : "stuart.may@divorce-online.co.uk";

            //send the user a confirmation email
            $email_template_details = $this->model->returnEmailTemplate('consent_ff_ff_complete');

            $to = (ENVIRONMENT == "production") ? "dolco@olssolicitors.co.uk,lara.hooper@olssolicitors.co.uk" : "ian.zammit@divorce-online.co.uk";

            $subject = $email_template_details->subject;
            $main_body = $email_template_details->body;
            $msg = str_replace('{CASE_ID}', $case_id, $main_body);

            $main_email_template = $this->model->getEmailWrapper($case_id);

            //str replace the details into placeholders
            $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
            $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
            $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

            // send the email
            $this->model->sendEmail($to, $subject, $body);

            // Set a new format type for the emal
            $due_date = date('l jS F Y', strtotime($date_to_format));

            //ADD CASE NOTE TO SAY QUESTIONNAIRE COMPLETE
            $note = 'Client has completed consent order fact finder. The assigned case manager and OLS have been notified and the due date is ' . $future_date . '.';
            $this->questionnaireModel->addCaseNote($case_id, $note, null, 1);


            ########################################
            ############## SEND SALES INVOICE ######
            ########################################

            $email_template_details_cc = $this->model->getNotificationWording('cc_ff_complete');

            $main_body = $email_template_details_cc->notification_message;
            $msg = str_replace('{FIRST_NAME}', $client_details->Forename, $main_body);
            $msg = str_replace('{CLIENT_EMAIL}', $client_details->UserEmail, $msg);
            if ($overWriteDaysToIncrease == true) {
                $msg = str_replace('{WORKING_DAYS}', $customDaysToIncrease, $msg);
            } else {
                $msg = str_replace('{WORKING_DAYS}', ($is_ft == TRUE) ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : getenv("CONSENT_ORDER_DUE_DAYS_STANDARD"), $msg);
            }
            $msg = str_replace('{FF_WORDING}', ($is_ft == FALSE) ? " should you wish to FAST TRACK this service please call 01793 384029 " : "", $msg);


            $client_fname = ucfirst(strtolower($client_details->Forename));
            $msg = str_replace('{CLIENT_NAME}', ($is_ft == TRUE) ? $client_details->Forename : "", $msg);

            // If this is a splilt applation we need to change the wording on the email in steps_manger (ref:cc_ff_complete) )
            if ($this->res_logged_in === true) {
                // Change wording
                $msg = str_replace("{SPLIT_APP_WORDING}", "The Respondent has now completed their part of the Fact Finder and therefore", $msg);
            } else {
                // Use standard email wording
                $msg = str_replace("{SPLIT_APP_WORDING}", "Thank you for your financial information;", $msg);
            }

            $msg = str_replace('{DUE_DATE}', $future_date, $msg);

            //str replace the details into placeholders
            $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
            $body = str_replace('{MAIN_SUBJECT}', "Case Update", $body);
            $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

            // Send the email
            $this->model->sendEmail($client_details->UserEmail, "Case Update", $body);


            // Check if the package is a standalone or not, if standalone we need to use a different steps manager
            $check_co_type = $this->model->returnCOType($case_id);

            $steps_bridge_table = ($check_co_type->consent_order_type == 1) ? "step_manager_cc_standalone_bridge" : "step_manager_bridge";
            $consent_type = ($check_co_type->consent_order_type == 1) ? "standalone" : "managed";

            // Has the case been upgraded, if so, Get the latest package code and consent type
            list($check_for_upgrade, $type, $newPackageID) = $this->model->checkForUpgrade($case_id);

            if ($check_for_upgrade === true) {
                $steps_bridge_table = ($type == 1) ? "step_manager_cc_standalone_bridge" : "step_manager_bridge";
                $consent_type = ($type == 1) ? "standalone" : "managed";
            }

            //insert a new row into the steps manager or stand alone bridge table the step ID also changes at this stage
            $this->model->updateBridgeTable($steps_bridge_table, $case_id, $consent_type);


            // If this is a NOT split application send the process email and mark the correct steps as complete
            if ($has_contact === true || $has_contact === false && $this->res_logged_in === true) {
                $this->model->sendInProcessEmail($client_details->UserEmail, $client_details->Forename, $is_ft, $steps_bridge_table, $consent_type, $case_id, $future_date, $user_id);
            }
        }


        // We now want to send the respondent their emails and complete the in process steps now that the respondent has completed the FF
        if ($this->res_logged_in === true && $ff_details['applicant_single_email_sent'] == 1 && $ff_details['ff_split_email_sent'] == 0) {
            //send invoice email
            $main_email_template = $this->model->getEmailWrapper($case_id);
            $email_template_details_cc = $this->model->getNotificationWording('cc_ff_complete');

            // get the respondent first name from the DB
            $get_respondent_name = $this->model->returnRespondentDetails($user_id);
            $main_body = $email_template_details_cc->notification_message;
            $msg = str_replace('{FIRST_NAME}', $get_respondent_name->res_forename, $main_body);
            $msg = str_replace('{CLIENT_EMAIL}', $client_details->UserEmail, $msg);

            //str replace the details into placeholders
            $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
            $body = str_replace('{MAIN_SUBJECT}', "Case Update", $body);
            $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

            $this->model->sendEmail($ff_details['respondent_separate_email'], "Case Update", $body);
            $this->model->updateQuestionnaire($user_id, ['ff_split_email_sent' => 1, 'ff_complete_email_sent' => 1]);
        }



        // dont do this if CBCS as we dont use the date at all
        if (isset($this->package_code) && !empty($this->package_code) && !in_array($this->package_code, $this->codesToCheck)) {
            //We dont want to change the due date IF the application was already completed on a different date than date_completed, it need to be between the date and 00:00:00 and 23:59:59 of that date
            $targetDate = date('Y-m-d', strtotime($completed_date));
            $completedDate = date('Y-m-d', strtotime($ff_details['date_completed']));

            // Check if the completed date is the same day as the target
            $isSameDay = ($completedDate === $targetDate) ? true : false;

            if ($isSameDay) {
                // We also need to log the due_date from whats being sent via email and whats being saved in the DB
                $this->model->logDueDate($db_due_date, $future_date, $case_id);
                // Update the due date in the database
                $this->model->updateDueDate($user_id, $db_due_date);
            }
        }


        $view_data = [
            'future_date' => $future_date,
            'date_if_fast_tracked' => date('dS F Y', strtotime($date_if_fast_tracked)),
            'title' => "Consent Order Fact Finder Complete",
            'is_ft' => $is_ft
        ];

        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/consent_questionnaire_complete", $view_data);
    }

    public function consentFinishAppOnly()
    {

        $user_id = session("user_id");
        $case_id = $this->model->returnCasesIdFromUserId($user_id);

        //update the database to show that the applicant has completed their part
        $this->model->updateQuestionnaire($user_id, ['applicant_single_app_complete' => 1]);

        //send email to the respondent
        //has the email already been sent
        $ffData = $this->model->getQuestionnaireData($user_id);

        // If the client was to fast track, we want to get the new due date
        $date_if_fast_tracked = date('dS F Y', strtotime($this->model->addBusinessDays(date("Y-m-d"), getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK"))));

        if ($ffData[0]["applicant_single_email_sent"] == "0") {

            //get the email address of the respondent
            $respondentEmail = $ffData[0]['respondent_separate_email'];

            if ($respondentEmail != "") {
                //send the email with the code
                $code = rand(1999, 9889);
                $client_details = $this->model->getUserDetails($user_id);
                $login_url =  (ENVIRONMENT != "production") ? site_url() : "https://www.divorce-track.com/";

                $email_template_details = $this->model->returnEmailTemplate('cc_applicant_ff_only');
                $subject = $email_template_details->subject;

                $main_body = $email_template_details->body;
                $msg = str_replace('{APP_NAME}', $client_details->Forename . " " . $client_details->Surname, $main_body);
                $msg = str_replace('{LOGIN_URL}', '<a href="' . $login_url . 'application_login">' . $login_url . 'application_login</a>', $msg);
                $msg = str_replace('{EMAIL_ADDRESS}', $respondentEmail, $msg);
                $msg = str_replace('{ACCESS_CODE}', $code, $msg);


                //get the email wrapper template
                $main_email_template = $this->model->getEmailWrapper($case_id);

                //str replace the details into placeholders
                $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
                $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
                $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

                //send the email
                if ($this->model->sendEmail($respondentEmail, $subject, $body)) {
                    //email sent
                    $update = ['respondent_single_code' => $code, 'applicant_single_email_sent' => 1];
                    $this->model->updateQuestionnaire($user_id, $update);

                    // If the email is sent to the respondent we want to add a case note to say that the applicant has completed their part of the fact finder
                    $this->questionnaireModel->addCaseNote($case_id, "The applicant has completed their part of the fact finder, the respondent has been notified.", null, 1);
                }
            }
        }


        $view_data = [
            'title' => "Consent Order Fact Finder Complete",
            'date_if_fast_tracked' => $date_if_fast_tracked,
            'is_ft' => $this->model->checkIfPackageContainsFastTrack($user_id)
        ];

        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/consent_ff_complete_app_only", $view_data);
    }



    public function consentQuestions()
    {
        $case_id = $this->model->returnCasesIdFromUserId(session("user_id"));
        $view_data = [
            'title' => "Consent Order :: Questions",
            'all_questions' => $this->model->getAllQuestions($case_id)
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/questions", $view_data);
    }


    public function answerQuestion($questionID)
    {

        $case_id = $this->model->returnCasesIdFromUserId(session("user_id"));

        if ($this->request->getPost("question_answered")) {

            // make sure a file has been uploaded
            $file = $this->request->getFile("file");
            if ($file->isValid() && ! $file->hasMoved()) {
                // Set the save path
                $savePath =  ROOTPATH . '/questionnaire_files/consent_order/' . $case_id;

                // Does the directory exist already?
                if (!is_dir($savePath)) {
                    mkdir($savePath);
                }

                list($errorStatus, $errorMsg) = $this->model->getQuestionUploadError($file);
                if ($errorStatus == "error") {
                    session()->setFlashdata('user_msg_error', $errorMsg);
                    return redirect()->back()->withInput();
                } else {

                    // File validation passed, now we want to attempt to save the file
                    $newFileName = str_replace(" ", "_", $file->getName());
                    $uploadedFile = $file->move($savePath, $newFileName, true);

                    if (!$uploadedFile) {
                        session()->setFlashdata('user_msg_error', "Failed to upload file. Please try again.");
                        return redirect()->back()->withInput();
                    } else {
                        // File uploaded , update the datbase with the path
                        $data = ['uploaded_file_path' => 'questionnaire_files/consent_order/' . $case_id . "/" . $newFileName];
                        $this->model->updateQuestionRow($questionID, $case_id, $data);
                    }
                }
            }

            // Process the form
            $rule = $this->model->answerQuestion;
            $this->validation->setRules($rule);

            if ($this->validation->withRequest($this->request)->run()) {
                // Process the answer
                $data = $this->request->getPost();
                $process = $this->model->processCoFfAnswer($questionID, $data, $case_id);

                if (!$process) {
                    session()->setFlashdata("user_msg_error", "Sorry, we've encountered an error, please try again.");
                    return redirect()->to("consent-order/answer_question/" . $questionID);
                } else {
                    session()->setFlashdata("SUCC", "Thank you for submitting your answer(s). This has now been passed back to our Legal Team to continue the drafting process.");
                    return redirect()->to("consent-order/answer_question/" . $questionID);
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }

        // get the question, allow the user to answer if they haven't already answered, does the question belong to this user?
        $check_user = $this->model->doesQuestionBelongToUser($case_id, (int)$questionID);

        if ($check_user === false) {
            // The user doesn't have access
            session()->setFlashdata("user_msg_error", "You don't have access to this resource.");
            return redirect()->to("account");
        }

        // We now want to retreive the question data
        list($question, $complete) = $this->model->getQuestionData($case_id, $questionID);

        $view_data = [
            'title' => "Consent Order :: Answer Question",
            'question' => $question,
            'complete' => $complete
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("ConsentOrder/answer_question", $view_data);
    }





    ###################################################
    ################### AJAX FUNCTIONS ################
    ###################################################

    public function addPropertyCo()
    {
        $data = $this->request->getPost();
        $res = $this->model->addPropertyCo($data, session("user_id"));
        echo $res;
        exit();
    }

    public function fetchPropertyData()
    {
        $data = $this->request->getPost();
        $res = $this->model->fetchPropertyData($data, session("user_id"));
        echo $res;
        exit();
    }

    public function removeProperty()
    {
        $data = $this->request->getPost();
        $res = $this->model->removeProperty($data, session("user_id"));
        echo $res;
        exit();
    }

    public function deletePropData()
    {
        $data = $this->request->getPost();
        $res = $this->model->deletePropData($data, session("user_id"));
        echo $res;
        exit();
    }

    public function fetchPensionData()
    {
        $data = $this->request->getPost();
        $res = $this->model->fetchPensionData($data, session("user_id"));
        echo $res;
        exit();
    }

    public function addPensionCo()
    {
        $data = $this->request->getPost();
        $res = $this->model->addPensionCo($data, session("user_id"));
        echo $res;
        exit();
    }


    public function removePension()
    {
        $data = $this->request->getPost();
        $res = $this->model->removePension($data, session("user_id"));
        echo $res;
        exit();
    }
}
