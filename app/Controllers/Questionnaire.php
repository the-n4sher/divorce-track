<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;


class Questionnaire extends BaseController
{
    protected $user;
    protected $model;
    protected $accountModel;
    protected $consentModel;
    protected $authService;
    protected $validation;
    protected $response;
    protected $date_for_condition;
    protected $use_portal;
    protected $res_logged_in;

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
    protected $vipPackages;


    public function __construct()
    {

        $this->authService = service("auth");
        $this->user = $this->authService->getCurrentuser();
        // make sure the user is logged in
        if ($this->user === null) {
            // Store current URL in session
            session()->set('redirect_url', current_url(true));

            // Redirect to login page
            return redirect()->to(site_url('login'));
        }

        $this->model = model("QuestionnaireModel");
        $this->accountModel = model("AccountModel");
        $this->consentModel = model("ConsentOrderModel");
        $this->validation = service("validation");
        $this->response = service("response");

        $this->use_portal = false;

        $this->vipPackages = ["DDPV"];

        // This is the date and time to base the portal switch on
        $this->date_for_condition = "2022-03-31 08:45:00";

        $this->res_logged_in = (session()->has("respondent_only")) ? true : false;
        $this->checkRespondentLoggedIn();

        $this->setSegments();
        $this->setCoType();
        $this->setIsManaged();
        $this->setIsSolManaged();
        $this->setStandardNotifications();
        $this->setIsIssued();
        $this->setMenuPackageData();
        $this->setUploadStep();
        $this->setRequiresQuestionnaire();
        $this->setIsPortal();
        $this->setHideForPortal();
        $this->setConsentQuestionCount();
        $this->setOrderPlaced();
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
            'split_application' => ($this->package_code != "" && in_array($this->package_code, $this->jointAppProducts)) ? true : false,
            'show_upload_step' => $this->show_upload_step,
            'requires_q' => $this->requires_q,
            'use_portal' => $this->use_portal,
            'hide_for_portal' => $this->hide_for_portal,
            'consent_questions_count' => $this->consent_questions_count,
            'orderPlaced' => $this->orderPlaced,
            'date_for_condition' => $this->date_for_condition
        ];
    }


    public function setOrderPlaced()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->orderPlaced = $this->accountModel->getOrderPlaced($case_id);
    }


    public function returnCaseID($user_id)
    {
        return $this->consentModel->returnCasesIdFromUserId($user_id);
    }


    public function setConsentQuestionCount()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->consent_questions_count = $this->consentModel->checkForConsentQuestions($case_id);
    }

    public function setHideForPortal()
    {
        $this->hide_for_portal = (date("Y-m-d 00:00:00") >= $this->date_for_condition) ? "display:none;" : "";
    }


    public function setIsPortal()
    {
        $res = $this->model->returnRow(session("user_id"));
        $this->use_portal = ($res && $res->portal_case == 1) ? true : false;
    }


    public function setUploadStep()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->show_upload_step = $this->model->checkStepComplete(7, $case_id);
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
        $this->is_issued = $this->model->hasDateIssued($case_id);
    }


    public function setStandardNotifications()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->notification_count = $this->model->getNotifications($case_id);
        $this->latest_notification = $this->model->getNotifications($case_id, true);
        $this->notification_data = $this->model->getNotificationReplacements(session("user_id"));
    }


    public function setIsSolManaged()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->is_sol_managed  = $this->consentModel->isSolicitorManaged($case_id);
    }


    public function setIsManaged()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->is_package_managed = $this->consentModel->isManaged($case_id);
    }

    // Set the consent order type, we need to check if they have upgraded as well
    public function setCoType()
    {
        // Is this a standalone or managed case?
        $case_id = $this->returnCaseID(session("user_id"));
        $check_co_type = $this->consentModel->returnCOType($case_id);
        $this->package_consent_type = $check_co_type->consent_order_type;

        // Has the case been upgraded?
        list($hasUpgraded, $type) = $this->consentModel->checkForUpgrade($case_id);

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

    public function getUserID()
    {
        return (int)session("user_id");
    }

    // The respondent shouldn't be able to do anything apart from the consent order questionnaire
    public function checkRespondentLoggedIn()
    {
        if ($this->res_logged_in === true) {
            return $this->response->redirect(site_url('logout'));
            exit();
        }
    }


    public function stage1($section = 1, $show_extra_1 = FALSE, $show_extra_2 = FALSE)
    {

        $user_id = $this->getUserID();
        $errors = "";

        // If user id is empty, redirect
        if (empty($user_id)) {
            return redirect()->to(site_url() . "account");
        }

        // Section 6 is the success page so we want to allow that
        if ($section != 6) {
            // This check for ff complete
            $check_if_completed = $this->model->db->table("questionnaire")
                ->where('user_id', $user_id)
                ->where('date_completed IS NOT NULL')->countAllResults();

            if ($check_if_completed == 1) {
                return redirect()->to(site_url() . "account");
            }
        }

        // If this package is a court package, we need to make sure the user has uploaded the marriage certificate and docu signed the document before being allowed to see the questionnanire
        $casesBuilder = $this->accountModel->getCasesTable();
        $get_package_details = $casesBuilder->select('PackageId, OrderPlaced')->where('UserId', $user_id)->get()->getRow();

        $package_id = $get_package_details->PackageId;

        // When was the order placed
        $order_placed = $get_package_details->OrderPlaced;

        // Is this a managed package?
        $packageBuilder = $this->accountModel->getPackageTable();
        $is_managed = $packageBuilder->where('ID', $package_id)->countAllResults();


        if ($this->model->checkForAccountLock($user_id) === true) {
            session()->setFlashdata("usr_error", "This case has been locked - Case papers have been drafted.");
            return redirect()->to('account');
        }

        //we need to check if this user already has a record in the questionnaire table
        $check_questionnaire = $this->model->checkQuestionnaireRow($user_id);

        // Do we have any data?
        $check_for_data = $this->model->returnRow($user_id);
        $is_started = ($check_for_data->petitoner_forename != "") ? true : false;
        $is_complete = false;


        // We want to now see if the user has completed their questionnaire
        if ($is_started == TRUE) {
            $is_complete = $this->model->hasCompletedStandardQuestionnaire($user_id);
        }


        //IF THE USER PURCHASED BEFORE THE CUT OFF DATE BUT HASN'T COMPLETED THE QUESTIONNAIRE THEN WE USE THE OLD METHOD
        if (date("Y-m-d 00:00:00") >= $this->date_for_condition) {
            if ($is_managed > 0 && $order_placed >= $this->date_for_condition || $is_managed > 0 && $order_placed < $this->date_for_condition && $is_complete == false  || $user_id == 2020807) {
                // Has the user uploaded there marriage cert and signed the agreement through docusign
                $check_if_complete = $this->model->hasSigned($user_id);

                if ($check_if_complete === false) {
                    //they still need to complete the required fields
                    $this->use_portal = true;

                    // Set this case as a portal case, we need to do this because if the user purchased before the cut off date but didn't start their questionnaire
                    // on the second time running this page we wouldn't know this information because they know would have started the questionnaire bypassing this check
                    // We need this information to update different steps and remove the grounds for no fault
                    $update = $this->model->setAsPortalCase($user_id);
                    if ($update) {
                        return redirect()->to("account/portal");
                    } else {
                        session()->setFlashdata("usr_error", "We have encountered an error while trying to update your information, please try again or contact support.");
                        return redirect()->to('account');
                    }
                }
            }
        }

        // Check if we are dealing with a portal client
        $check_is_portal = $this->model->checkIfPortal($user_id);
        $this->use_portal = $check_is_portal;

        //get the questionnaire details
        $qdata = (array)$this->model->returnRow($user_id);
        $children_by_spouse = $this->model->getChildrenBySpouse($user_id);
        $children_by_other = $this->model->getChildrenByOther($user_id);

        $divorce_grounds = $this->model->getDivorceGrounds();
        $date_difference = $this->model->dateDifference($user_id);

        $is_civil_partnership = $this->model->checkIsCivilPartnership($user_id);
        $same_sex_marriage = $this->model->checkIsSameSex($user_id);

        $questionnaire_id = $check_questionnaire;
        $progress_status = $this->model->getQuestionnaireStatus($questionnaire_id);

        $segments = $this->request->getUri()->getSegments();
        $segment = (!isset($segments[2]))  ? 1 : (int)$segments[2];


        $segment2 = (!isset($segments[2]))  ? null : $segments[2];
        $segment3 = (!isset($segments[3]))  ? null : $segments[3];
        $segment4 = (!isset($segments[4]))  ? null : $segments[4];
        $segment5 = (!isset($segments[5]))  ? null : $segments[5];
        $segment6 = (!isset($segments[6]))  ? null : $segments[6];


        $current_stage = ($segment == "" || !in_array($segment, array(1, 2, 3, 4, 5, 6))) ? 1 : (int)$segment;

        //stage 6 url is /stage1/5/2/2 so we need to update it to 6
        $current_stage = (!isset($segments[6])) ? $current_stage : "stage6";

        $country = $this->model->returnCountries();
        $section_wording = $this->model->getSectionWording($current_stage);


        /*
            Handle the form submissions
        */
        if ($this->request->getPost("stage1_submit")) {
            $rules = $this->model->stage1Rules;
            $this->validation->setRules($rules);

            if ($this->validation->withRequest($this->request)->run()) {
                if ($this->model->saveStage(1, $this->request->getPost()) === true) {
                    return redirect()->to('questionnaire/stage1/2');
                } else {
                    session()->setFlashdata("usr_error", "We have encountered an error while trying to update your order, please try again.");
                    return redirect()->back()->withInput();
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }

        if ($this->request->getPost("stage2_submit")) {

            $rules = $this->model->stage2Rules;
            $this->validation->setRules($rules);

            if ($this->validation->withRequest($this->request)->run()) {
                if ($this->model->saveStage(2, $this->request->getPost()) === true) {
                    return redirect()->to('questionnaire/stage1/3');
                } else {
                    session()->setFlashdata("usr_error", "We have encountered an error while trying to update your order, please try again.");
                    return redirect()->to('questionnaire/stage1/2')->withInput();
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }


        if ($this->request->getPost("stage3_submit")) {

            $data = $this->request->getPost();
            $rules = $this->model->stage3Rules;
            $deleteImg = (isset($data["delete_cert"])) ? true : false;

            // We need to set these two custom rules here rather than the model
            $marriage_date_of_separation = $data['marriage_date_of_separation'];
            $marriage_date_of_marriage = $data['marriage_date_of_marriage'];

            if ($marriage_date_of_marriage != "" && $marriage_date_of_separation != "") {
                $rules['marriage_date_of_marriage']["rules"] = $rules['marriage_date_of_marriage']["rules"] . '|checkDates[' . $marriage_date_of_separation . ', ' . $marriage_date_of_marriage . ']';
                $rules['marriage_date_of_separation']["rules"] = $rules['marriage_date_of_separation']["rules"] . '|checkDates[' . $marriage_date_of_separation . ', ' . $marriage_date_of_marriage . ']';
            }


            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run()) {
                // Get the file object
                $file = $this->request->getFile("userfile");

                //check to see if there is already a file uploaded, if there is then we want to remove it
                $current_image_details = $this->model->getMarriageCert($user_id);

                //see if we need to unlink the current document, the user has selcted to delete the certificate
                if ($deleteImg === true) {
                    $this->model->deleteCert($user_id, $current_image_details);
                }

                // We want to try and upload the image first, if any errors then we will show them to the client
                if ($file->isValid()) {

                    //if the validation has run then we want to upload the document if it exsits before running the insert
                    list($uploadStatus, $uploadMsg) = $this->model->getMarriageUploadErrors($user_id, $file);

                    // Do we have an error?
                    if ($uploadStatus == "error") {
                        session()->setFlashdata("errors", $uploadMsg);
                        return redirect()->back()->withInput();
                    } else {

                        // We have no errors, do the upload
                        $newFileName = session("user_id") . "." . $file->getExtension();
                        $ext = $file->getExtension();
                        $path = ROOTPATH . 'questionnaire_files/';
                        $uploadedFile = $file->move($path, $newFileName, true);

                        if ($uploadedFile) {
                            //unlink the old image f the names are different
                            if ($ext != "") {
                                $this->model->unlinkOldCert($current_image_details, session("user_id") . "." . $ext);
                            }

                            $file_name = $newFileName;
                            $upload_path = '/questionnaire_files/';
                            $file_type = $ext;

                            if ($this->model->saveStage(3, $this->request->getPost(), $upload_path, $file_name, $file_type) === true) {
                                return redirect()->to('questionnaire/stage1/4');
                            } else {
                                session()->setFlashdata("usr_error", "We have encountered an error while trying to update your information, please try again.");
                                return redirect()->to('questionnaire/stage1/3')->withInput();
                            }
                        } else {
                            session()->setFlashdata("usr_error", "We have encountered an error while trying to upload your image, please try again.");
                            return redirect()->to('questionnaire/stage1/3')->withInput();
                        }
                    }
                } else {
                    if ($this->model->saveStage(3, $this->request->getPost()) === true) {
                        return redirect()->to('questionnaire/stage1/4');
                    } else {
                        session()->setFlashdata("usr_error", "We have encountered an error while trying to update your order, please try again.");
                        return redirect()->to('questionnaire/stage1/3')->withInput();
                    }
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }


        // Upload extra files for the marriage certificate
        $success = [];
        $errors = [];
        if ($this->request->getPost("upload_extra_files")) {
            $file = $this->request->getFiles("extraFiles");
            ['success' => $success, 'errors' => $errors] = $this->model->uploadExtraFiles($user_id, $file);
        }


        if ($this->request->getPost("stage4_submit")) {

            //get the posted value
            $data = $this->request->getPost();

            $data['children_by_spouse'] =  (array_key_exists("children_by_spouse", $data)) ? 1 : 0;
            $data['num_of_children_by_spouse'] =  (array_key_exists("num_of_children_by_spouse", $data)) ? $data['num_of_children_by_spouse'] : 0;


            $data['children_by_other'] =  (array_key_exists("children_by_other", $data)) ? 1 : 0;
            $data['num_of_children_by_other'] =  (array_key_exists("num_of_children_by_other", $data)) ? $data['num_of_children_by_other'] : 0;

            //if the user doesnt have any children then send them to stage 5
            if (isset($data['children_by_spouse']) && $data['children_by_spouse'] == 1 || isset($data['children_by_other']) && $data['children_by_other'] == 1) {
                if ($this->model->saveStage(4, $this->request->getPost(), null, null, null, true) === TRUE) {
                    return redirect()->to('questionnaire/stage1/4/1');
                } else {
                    $this->model->updateStandardFFStatus(session("user_id"), 6);
                    session()->setFlashdata("usr_error", "We have encountered an error while trying to update your order, please try again.");
                    return redirect()->to('/questionnaire/stage1/4');
                }
            } else {
                if ($this->model->saveStage(4, $this->request->getPost()) === true) {
                    return redirect()->to('questionnaire/stage1/5');
                } else {
                    session()->setFlashdata("usr_error", "We have encountered an error while trying to update your order, please try again.");
                    return redirect()->to('questionnaire/stage1/4');
                }
            }
        }


        if ($this->request->getPost("children_submit_stage_4_submit")) {

            //We need to create the rules dynamically
            $data = $this->request->getPost();
            $rules = array();

            if (array_key_exists("spouse_child_details", $data)) {
                // Loop each child row and validate it
                $errors = [];
                foreach ($data['spouse_child_details'] as $key => $d) {
                    $this->validation->reset();
                    $rules = [
                        'dob' => ["label" => "Date of birth", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|trim"],
                        'gender' => ["label" => "Gender", "rules" => "required|trim"],
                        'in_edc_train_ftwok' => ["label" => "Work and traning option required", "rules" => "required|trim"],
                        'spouse_child_forename' => ["label" => "Forename", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
                        'spouse_child_surname' => ["label" => "Surname", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"]
                    ];
                    $this->validation->setRules($rules);

                    // check what errors we have an add them to the main array
                    if (!$this->validation->run($d)) {
                        $errors[$key + 1] = [$this->validation->getErrors()];
                    }
                }

                if (count($errors) > 0) {
                    session()->setFlashdata("child_errrors", $errors);
                    return redirect()->back()->withInput();
                }
            }

            if (array_key_exists("other_child_details", $data)) {

                $errors = [];
                foreach ($data['other_child_details'] as $key => $d) {
                    $this->validation->reset();
                    $rules = [
                        'dob' => ["label" => "Date of birth", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|trim"],
                        'gender' => ["label" => "Gender", "rules" => "required|trim"],
                        'born_or_adopted_by' => ["label" => "Born or adopted to", "rules" => "required|trim"]
                    ];
                    $this->validation->setRules($rules);

                    // check what errors we have an add them to the main array
                    if (!$this->validation->run($d)) {
                        $errors[$key + 1] = [$this->validation->getErrors()];
                    }
                }

                if (count($errors) > 0) {
                    session()->setFlashdata("child_errrors", $errors);
                    return redirect()->back()->withInput();
                }
            }

            if ($this->model->saveStage(4, $this->request->getPost(), null, null, null, false, true)) {

                $this->model->updateStandardFFStatus(session("user_id"), 8);
                return redirect()->to('questionnaire/stage1/5');
            } else {
                session()->setFlashdata("usr_error", "We have encountered an error while trying to update your order, please try again.");
                return redirect()->to('questionnaire/stage1/4/1');
            }
        }


        /* ON SUBMIT OF STAGE 2,IF VALIDATION FAILS THEN WE SET PROGRESS BACK TO 4*/
        if ($this->request->getPost("stage5_submit")) {
            $rules = $this->model->stage5Rules;
            $this->validation->setRules($rules);

            if ($this->validation->withRequest($this->request)->run()) {
                $data = $this->request->getPost();
                $upd = array();
                $upd['on_grounds'] = $data["divorce_grounds"];
                $this->model->where('user_id', $user_id)->set($upd)->update();

                if (in_array($data["divorce_grounds"], array(3, 4, 5))) {
                    //remove any details from the adultery and behaviour db fields as the user may have entered those details by accident or changed there reason for divorce...
                    $this->account_model->resetStep5Details($user_id, 3);
                    $this->account_model->resetStep5Details($user_id, 4);
                    $this->account_model->resetStep5Details($user_id, 5);
                }
                return redirect()->to('questionnaire/stage1/5/' . $data["divorce_grounds"]);
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }


        if ($this->request->getPost("stage5_submit_adultery_step1")) {

            $data = $this->request->getPost();
            $rules = $this->model->stage5Step1Rules;
            $data['adultery_lived_with'] = (array_key_exists("adultery_lived_with", $data)) ? 1 : 0;

            $this->validation->setRules($rules);
            if ($this->validation->run($data) === TRUE) {

                //save the details..
                if ($this->model->saveStage(5, $this->request->getPost(), null, null, null, false, false, 1) === true) {
                    return redirect()->to('questionnaire/stage1/5/' . $segment3 . "/2");
                } else {
                    session()->setFlashdata("usr_error", "We have encountered an error, please try again.");
                    return redirect()->to('questionnaire/stage1/5/' . $segment3 . "");
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
            //show errors inline in view
        }

        if ($this->request->getPost("stage5_submit_behaviour_step1")) {
            $rules = $this->model->stage5Step1BehaviourRules;
            $this->validation->setRules($rules);

            $data = $this->request->getPost();
            if ($this->validation->run($data) === true) {
                if ($this->model->saveStage(5, $this->request->getPost(), null, null, null, false, false, 2) === true) {
                    return redirect()->to('questionnaire/stage1/5/' . $segment3 . "/2");
                } else {
                    session()->setFlashdata("usr_error", "We have encountered an error, please try again.");
                    return redirect()->to('questionnaire/stage1/5/' . $segment3 . "");
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }


        if ($this->request->getPost("stage5_submit_court_proceddings")) {

            $data = $this->request->getPost();
            $rules = $this->model->stage5CourtRules;

            //we want to remove the details rule if the checkbox isnt checked
            if (!array_key_exists("court_proceedings", $data) || $data["court_proceedings"] == "") {
                unset($rules["court_proceedings_information"]);
            }

            //set the rules after doing the above check
            $this->validation->setRules($rules);

            if (count($rules) > 0) {
                if ($this->validation->run($data) === true) {
                    if ($this->model->saveStage(5, $data, null, null, null, false, false, 3) === true) {
                        return redirect()->to('questionnaire/stage1/6/');
                    } else {
                        session()->setFlashdata("errors", ["We have encountered an error while trying to update your order, please try again."]);
                        return redirect()->to('questionnaire/stage1/5/' . $segment3 . "/2");
                    }
                } else {
                    session()->setFlashdata("errors", $this->validation->getErrors());
                    return redirect()->back()->withInput();
                }
            } elseif (count($rules) == 0) {

                $this->model->saveStage(5, $this->request->getPost(), null, null, null, false, false, 4);
                return redirect()->to('questionnaire/stage1/6/');
            }
            //show errors
        }


        if ($this->request->getPost("stage5_submit_portal")) {
            if ($this->model->saveStage5Court($user_id, false, [], true)) {
                // Complete
                return redirect()->to('questionnaire/stage1/6');
            } else {
                session()->setFlashdata("usr_error", "We have encountered an error, please try again.");
                return redirect()->to("questionnaire/stage1/5");
            }
        }

        // Fetch the extra divorce certificate files
        $extra_files = $this->model->getExtraDivorceCertFiles($user_id);

        $view_data = [
            'title' => "Customer Questionnaire",
            'progress_status' => $progress_status,
            'section_wording' => $section_wording,
            'current_stage' => $current_stage,
            'segment2' => $segment2,
            'segment3' => $segment3,
            'segment4' => $segment4,
            'segment5' => $segment5,
            'segment6' => $segment6,
            'country' => $country,
            'is_civilpartnership' => $is_civil_partnership,
            'validation' => $this->validation,
            'qdata' => $qdata,
            'package_id' => $package_id,
            'image_url' => $this->model->fetchMarriageCert(session("user_id")),
            'children_by_spouse' => $children_by_spouse,
            'get_children_by_other' => $children_by_other,
            'use_portal' => $this->use_portal,
            'divorce_grounds' => $divorce_grounds,
            'date_difference' => $date_difference,
            'is_civil_partnership' => $is_civil_partnership,
            'same_sex_marriage' => $same_sex_marriage,
            'success' => $success,
            'errors' => $errors,
            'extra_files' => $extra_files
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());

        return view("Questionnaire/stage1", $view_data);
    }



    public function portal()
    {
        $user_id = $this->getUserID();
        $errors = "";

        // If user id is empty, redirect
        if (empty($user_id)) {
            return redirect()->to(site_url() . "account");
        }

        // This check for ff complete
        $check_if_completed = $this->model->db->table("questionnaire")
            ->where('user_id', $user_id)
            ->where('date_completed IS NOT NULL')->countAllResults();

        if ($check_if_completed == 1) {
            return redirect()->to(site_url() . "account");
        }

        // Has the user uploaded there marriage cert and signed the agreement through docusign, if so redirect to page 1
        $check_if_complete = $this->model->hasSigned($user_id, true);

        if ($check_if_complete && $check_if_complete->portal_yes_no == 1) {
            return redirect()->to(site_url() . "questionnaire/stage1");
        }

        // Check if the user is giving us consent
        if ($this->request->getPost("portal_consent")) {
            $this->givePortalConsent($user_id);
        }

        // Has the user signed the letter of authority
        $check_has_signed_letter = $this->model->select('letter_signed')->where('user_id', $user_id)->where('letter_signed', 1)->countAllResults();
        $has_signed_authority = ($check_has_signed_letter > 0) ? TRUE : FALSE;


        if ($this->request->getPost("save_portal") || $this->request->getPost("upload_marriage_certificate")) {
            // If its just the marriage cert being upload then we want to set a variable to true
            $is_marriage_cert_upload = ($this->request->getPost("upload_marriage_certificate")) ? true : false;
            $data = $this->request->getPost();

            // If we are only completing the last section of the form then we can skip fetching the file object
            if ($is_marriage_cert_upload === true) {
                // Get the file object
                $file = $this->request->getFile("userfile");
                //check to see if there is already a file uploaded, if there is then we want to remove it
                $current_image_details = $this->model->getMarriageCert($user_id);
            }

            // We want to try and upload the image first, if any errors then we will show them to the client
            // if we are completing the last section of the form then we need to skip the validation
            if (!empty($file) && $file->isValid() || $is_marriage_cert_upload === false) {

                //if the validation has run then we want to upload the document if it exsits before running the insert
                if ($is_marriage_cert_upload === true) {
                    list($uploadStatus, $uploadMsg) = $this->model->getMarriageUploadErrors($user_id, $file);
                }

                // Do we have an error - only do this the first time the marriage cert is being uploaded
                if ($is_marriage_cert_upload === true && $uploadStatus == "error") {
                    session()->setFlashdata("errors", [$uploadMsg]);
                    return redirect()->back()->withInput();
                } else {

                    // If we are uploading the marriage cert then we need to do the upload
                    if ($is_marriage_cert_upload === true) {
                        // We have no errors, do the upload
                        $newFileName = session("user_id") . "." . $file->getExtension();
                        $ext = $file->getExtension();
                        $path = ROOTPATH . 'questionnaire_files/';
                        $uploadedFile = $file->move($path, $newFileName, true);

                        // if the file has uploaded we unlink the old file
                        if ($uploadedFile) {
                            //unlink the old image f the names are different
                            if ($ext != "") {
                                $this->model->unlinkOldCert($current_image_details, session("user_id") . "." . $ext);
                            }
                        }

                        $file_name = $newFileName;
                        $upload_path = '/questionnaire_files/';
                        $file_type = $ext;
                    }

                    // Bypass the upload if we aren't uploading the marriage cert
                    if ($is_marriage_cert_upload === false || ($is_marriage_cert_upload === true && $this->model->saveMarriageCertPortal($upload_path, $file_name, $file_type, $data) === true)) {
                        if ($is_marriage_cert_upload === true) {
                            session()->setFlashdata("SUCC", "Marriage certificate uploaded successfully");
                            return redirect()->to("account/portal");
                        } else {
                            if (!array_key_exists("pay_portal_cc", $data)) {
                                $data["pay_portal_cc"] = 0;
                            } else {
                                $data["pay_portal_cc"] = 1;
                            }

                            if (!array_key_exists("portal_fee_ref", $data)) {
                                $data["portal_fee_ref"] = "";
                            }

                            $res = $this->model->savePortalData($user_id, $data);
                            if ($res === true) {
                                // If the update was successfuly and its a VIP package then we want email Stu and redirect the user to the holding page
                                if (in_array($this->package_code, $this->vipPackages)) {
                                    // Redirect to page 1 of the questionnaire
                                    $this->model->sendVipEmail($this->package_code);
                                    return redirect()->to("vip");
                                }

                                // Redirect to page 1 of the questionnaire
                                return redirect()->to("questionnaire/stage1/1");
                            } else {
                                // Redirect and show error
                                session()->setFlashdata("ERR", "We have encountered an error while trying to update your information, please try again.");
                                return redirect()->to('/account/portal');
                            }
                        }
                    } else {
                        session()->setFlashdata("ERR", "We have encountered an error while trying to update your information, please try again.");
                        return redirect()->to('account/portal');
                    }
                }
            } else {
                session()->setFlashdata("ERR", ["You must upload a file"]);
                return redirect()->back()->withInput();
            }
        }

        // Upload extra files for the marriage certificate
        $successMultiFile = [];
        $errorsMultiFile = [];
        if ($this->request->getPost("upload_extra_files")) {
            $file = $this->request->getFiles("extraFiles");
            ['success' => $successMultiFile, 'errors' => $errorsMultiFile] = $this->model->uploadExtraFiles($user_id, $file);
        }

        // Fetch the extra divorce certificate files
        $extra_files = $this->model->getExtraDivorceCertFiles($user_id);

        // Does the user have a marriage certificate?
        $check_marriage_cert = $this->model->checkIfUserHasMarriageCertificate($user_id);
        $has_marriage_cert = ($check_marriage_cert) ? true : false;

        // If we have a marriage cert then we want to fetch it as base64
        $marriage_cert_base64 = ($has_marriage_cert) ? $this->model->returnCertAsBase64($user_id) : null;

        $view_data = [
            'title' => "Portal",
            'errors' => $errors,
            'has_signed_authority' => $has_signed_authority,
            'successMultiFile' => $successMultiFile,
            'errorsMultiFile' => $errorsMultiFile,
            'extra_files' => $extra_files,
            'has_marriage_cert' => $has_marriage_cert,
            'marriage_cert_base64' => $marriage_cert_base64
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Questionnaire/portalinformation", $view_data);
    }

    public function vipClient()
    {
        $view_data = [
            'title' => "VIP Express Divorce Service ",
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Questionnaire/vip", $view_data);
    }


    public function givePortalConsent($user_id)
    {

        // Check if the user has already signed, if they have, redirect to the portal page
        $check_if_signed = $this->model->where('user_id', $user_id)->where('letter_signed', 1)->countAllResults();

        if ($check_if_signed == 1) {
            return redirect()->to(site_url() . "account/portal");
        } else {
            // Update the letter_signed and letter_signed_date fields in the questionnaire DB
            $update = $this->model->where('user_id', $user_id)->set(['letter_signed' => 1, 'letter_signed_date' => date('Y-m-d H:i:s')])->update();

            // Redirect to the portal page with either a success or error message
            if ($update) {
                session()->setFlashdata("SUCC", "We have successfully registered your consent in our system, you may now continue your divorce application.");
                return redirect()->to(site_url() . 'account/portal');
            } else {
                session()->setFlashdata("usr_error", "We have encountered an error while trying to update your information, please try again.");
                return redirect()->to(site_url() . 'account/portal');
            }
        }
    }


    public function saveSignature()
    {
        $postData = $this->request->getPost();
        $user_id = $this->getUserID();

        if ($postData) {
            if (!empty($postData['signature'])) {
                $signature = $postData['signature'];

                // Decode the base64 image data
                $signatureData = base64_decode(str_replace('data:image/png;base64,', '', $signature));

                $allowed = [1, 2];
                $applicantNumber = (!empty($postData['applicant']) && in_array($postData['applicant'], $allowed)) ? (int)$postData['applicant'] : null;

                // We need to have an applicant number
                if ($applicantNumber === null) {
                    echo "Failed to save signature.";
                    die();
                }

                if (empty($user_id)) {
                    echo "Failed to save signature. (NOUID)";
                    die();
                }

                // Generate a unique file name
                $fileName = 'signature_applicant' . $applicantNumber . '.png';
                $dirPath = getcwd() . "/questionnaire_files/signatures/" . $user_id;

                // Does the dir exist?
                if (!is_dir($dirPath)) {
                    // Make dir
                    mkdir($dirPath, 0777, true);
                }

                if (file_put_contents($dirPath . "/" . $fileName, $signatureData)) {
                    echo "Signature saved successfully";
                    //if successfull we want to update the database to reflect the signature has been provided
                    $upd = ['applicant_' . $applicantNumber . "_signed" => 1];
                    $this->model->db->table("questionnaire")->where('user_id', $user_id)->set($upd)->update();
                    die();
                } else {
                    echo "Failed to save signature.";
                    die();
                }
            } else {
                echo "No signature data received.";
                die();
            }
        } else {
            echo "Invalid request method.";
            die();
        }
    }


    public function checkIsSigned()
    {
        $user_id = $this->getUserID();
        if (empty($user_id)) {
            echo "Error: Failed to match user id.";
            die();
        }


        $checkSigned = $this->model->db->table("questionnaire")
            ->where('user_id', $user_id)
            ->where('applicant_1_signed', 1)
            ->where('applicant_2_signed', 1)
            ->countAllResults();

        $isSigned = ($checkSigned > 0) ? true : false;
        echo (bool)$isSigned;
    }

    public function deleteFile($user_id, $file)
    {
        // urldecode $file
        $encodedFile = str_replace('~', '=', $file);
        $file = base64_decode($encodedFile);

        $res = $this->model->deleteFile($user_id, $file);
        if ($res['success']) {
            session()->setFlashdata("SUCC", "File deleted successfully");
            return redirect()->back();
        } else {
            session()->setFlashdata("FAIL", "File could not be deleted");
            return redirect()->back();
        }
        return redirect()->back();
    }
}
