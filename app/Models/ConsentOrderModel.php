<?php

namespace App\Models;

use Config\Services;
use Config\Encryption;
use App\Libraries\MicrosoftGraphMail;

class ConsentOrderModel extends \CodeIgniter\Model
{
    protected $table = 'cases';
    protected $dtrackDB;
    protected $adminDB;
    protected $allowedFields = [];

    protected $useTimestamps = true;

    protected $validationRules = [];
    protected $validationMessages = [];

    protected $beforeInsert = [""];
    protected $beforeUpdate = [""];

    protected $codesToCheck = ["CBCS", "DDPCBCS"];

    protected $page2Rules = [
        "name_of_court" => ["label" => "Name of court", "rules" => "required|min_length[3]|regex_match[/^[\p{L}0-9\s\-]+$/u]"],
        "case_number" => ["label" => "Case number", "rules" => "required|checkCourtNumber"],
        "marr_or_civ" => ["label" => "Marriage or Civil Partnership", "rules" => "required|in_list[marriage,civil,samesex]"],
        "app_surname" => ["label" => "Applicant surname", "rules" => "required|regex_match[/^[\p{L}\-\' ]{2,}$/u]"],
        "app_forename" => ["label" => "Applicant forename", "rules" => "required|regex_match[/^[\p{L}\-\' ]{2,}$/u]"],
        "app_other_names" => ["label" => "Applicant middle name", "rules" => "permit_empty|regex_match[/^[\p{L}\-\' ]{2,}$/u]"],
        "app_gender" => ["label" => "Applicant gender", "rules" => "required|in_list[male,female]"],
        "app_number" => ["label" => "Applicant number", "rules" => "required|regex_match[/^[0-9\-+ ]{11,}$/i]"],
        "app_address_line1" => ["label" => "Applicant address line 1", "rules" => "required|min_length[5]|regex_match[/^[\p{L}0-9\s\-\'\.\/,]+$/u]"],
        "app_address_line2" => ["label" => "Applicant address line 2", "rules" => "permit_empty|min_length[4]|regex_match[/^[\p{L}0-9\s\-\'\.\/,]+$/u]"],
        "app_address_line3" => ["label" => "Applicant address line 3", "rules" => "permit_empty|min_length[4]|regex_match[/^[\p{L}0-9\s\-\'\.\/,]+$/u]"],
        "app_address_town_city" => ["label" => "Applicant town/city", "rules" => "required|min_length[4]|regex_match[/^[\p{L}\-\'\. ]{4,}$/u]"],
        "app_address_postcode" => ["label" => "Applicant postcode", "rules" => "required|min_length[4]|regex_match[/^([A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]?\s+[0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}|[A-Za-z0-9\s\-]{4,})$/]"],
        "app_address_country" => ["label" => "Applicant country", "rules" => "required|integer"],
        "res_address_line1" => ["label" => "Respondent address line 1", "rules" => "required|min_length[5]|regex_match[/^[\p{L}0-9\s\-\'\.\/,]+$/u]"],
        "res_address_line2" => ["label" => "Respondent address line 2", "rules" => "permit_empty|min_length[4]|regex_match[/^[\p{L}0-9\s\-\'\.\/,]+$/u]"],
        "res_address_line3" => ["label" => "Respondent address line 3", "rules" => "permit_empty|min_length[4]|regex_match[/^[\p{L}0-9\s\-\'\.\/,]+$/u]"],
        "res_address_town_city" => ["label" => "Respondent town/city", "rules" => "required|min_length[4]|regex_match[/^[\p{L}\-\'\. ]{4,}$/u]"],
        "res_address_postcode" => ["label" => "Respondent postcode", "rules" => "required|min_length[4]|regex_match[/^([A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]?\s+[0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}|[A-Za-z0-9\s\-]{4,})$/]"],
        "res_address_country" => ["label" => "Respondent country", "rules" => "required|integer"],
        "app_owned_type" => ["label" => "Applicant owned type", "rules" => "required|in_list[Mortgaged,Mortgaged shared with new partner,Owned Outright,Rented/Tenant,Council Owned,Living with Friends,Living with Parents,living with new partner - NOT paying mortgage]"],
        "res_surname" => ["label" => "Respondent surname", "rules" => "required|regex_match[/^[\p{L}\-\' ]{2,}$/u]"],
        "res_forename" => ["label" => "Respondent forename", "rules" => "required|regex_match[/^[\p{L}\-\' ]{2,}$/u]"],
        "res_other_names" => ["label" => "Respondent middle name", "rules" => "permit_empty|regex_match[/^[\p{L}\-\' ]{2,}$/u]"],
        "res_gender" => ["label" => "Respondent gender", "rules" => "required|in_list[male,female]"],
        "res_number" => ["label" => "Respondent number", "rules" => "required|regex_match[/^[0-9\-+ ]{11,}$/i]"],
        "res_owned_type" => ["label" => "Respondent owned type", "rules" => "required|in_list[Mortgaged,Mortgaged shared with new partner,Owned Outright,Rented/Tenant,Council Owned,Living with Friends,Living with Parents,living with new partner - NOT paying mortgage]"],
        "app_dob" => ["label" => "Applicant dob", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|checkDateInFuture|checkOver18"],
        "res_dob" => ["label" => "Respondent dob", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|checkDateInFuture|checkOver18"]
    ];

    protected $page2Rules_errors = [
        'name_of_court' => ['required' => 'Name of court is a required field.', 'min_length' => 'Name of court is a required field.', 'regex_match' => 'Name of court is a required field.'],
        'case_number' => ['required' => 'Case number is a required field.'],
        'marr_or_civ' => ['required' => 'This field is required.', 'in_list' => 'This field is required.'],
        'app_surname' => ['required' => 'Applicant surname is required.', 'regex_match' => 'Applicant surname is required.'],
        'app_forename' => ['required' => 'Applicant forename is required.', 'regex_match' => 'Applicant forename is required.'],
        'app_other_names' => ['regex_match' => 'Applicant middle name is required.'],
        'app_gender' => ['required' => 'This field is required.', 'in_list' => 'This field is required.'],
        'app_number' => ['required' => 'Contact number is a required field.', 'regex_match' => 'Please enter a valid number'],
        'app_address_line1' => ['required' => 'Address Line 1 is a required field.', 'min_length' => 'Address Line 1 is a required field.', 'regex_match' => 'Address Line 1 is a required field.'],
        'app_address_line2' => ['min_length' => 'Address Line 2 is a required field.', 'regex_match' => 'Address Line 2 is a required field.'],
        'app_address_line3' => ['min_length' => 'Address Line 3 is a required field.', 'regex_match' => 'Address Line 3 is a required field.'],
        'app_address_town_city' => ['required' => 'Town/City is a required field.', 'min_length' => 'Town/City is a required field.', 'regex_match' => 'Town/City is a required field.'],
        'app_address_postcode' => ['required' => 'Postcode is a required field.', 'min_length' => 'Postcode is a required field.', 'regex_match' => 'Postcode is a required field.'],
        'app_address_country' => ['required' => 'Country is a required field.', 'integer' => 'Country is a required field.'],
        'res_address_line1' => ['required' => 'Address Line 1 is a required field.', 'min_length' => 'Address Line 1 is a required field.', 'regex_match' => 'Address Line 1 is a required field.'],
        'res_address_line2' => ['min_length' => 'Address Line 2 is a required field.', 'regex_match' => 'Address Line 2 is a required field.'],
        'res_address_line3' => ['min_length' => 'Address Line 3 is a required field.', 'regex_match' => 'Address Line 3 is a required field.'],
        'res_address_town_city' => ['required' => 'Town/City is a required field.', 'min_length' => 'Town/City is a required field.', 'regex_match' => 'Town/City is a required field.'],
        'res_address_postcode' => ['required' => 'Postcode is a required field.', 'min_length' => 'Postcode is a required field.', 'regex_match' => 'Postcode is a required field.'],
        'res_address_country' => ['required' => 'Country is a required field.', 'integer' => 'Country is a required field.'],
        'app_owned_type' => ['required' => 'Basis of occupation is required.', 'in_list' => 'Basis of occupation is required.'],
        'res_surname' => ['required' => 'Respondent surname is required.', 'regex_match' => 'Respondent surname is required.'],
        'res_forename' => ['required' => 'Respondent forename is required.', 'regex_match' => 'Respondent forename is required.'],
        'res_other_names' => ['regex_match' => 'Respondent middle name is required.'],
        'res_gender' => ['required' => 'This field is required.', 'in_list' => 'This field is required.'],
        'res_number' => ['required' => 'Contact number is a required field.', 'regex_match' => 'Please enter a valid number'],
        'res_owned_type' => ['required' => 'Basis of occupation is required.', 'in_list' => 'Basis of occupation is required.'],
        'app_dob' => ['required' => 'This field is required.', 'regex_match' => 'Invalid format the date must be DD-MM-YYYY'],
        'res_dob' => ['required' => 'This field is required.', 'regex_match' => 'Invalid format the date must be DD-MM-YYYY']
    ];


    protected $page3Rules = [
        "legal_app_add" => ["label" => "Applicant legal advice", "rules" => "required|in_list[0,1]"],
        "legal_res_add" => ["label" => "Respondent legal advice", "rules" => "required|in_list[0,1]"],
        "less_than_250" => ["label" => "Total combined net assets", "rules" => "required|in_list[0,1]"]
    ];

    protected $page3Rules_errors = [
        'legal_app_add' => ['required' => 'This field is required', 'in_list' => 'This field is required'],
        'legal_res_add' => ['required' => 'This field is required', 'in_list' => 'This field is required'],
        'less_than_250' => ['required' => 'The total combined net assets field is required', 'in_list' => 'The total combined net assets field is required']
    ];

    protected $page6Rules = [
        "currently_actioned" => ["label" => "Currently actioned", "rules" => "required|min_length[35]|max_length[1500]"],
        "tobe_actioned" => ["label" => "To be actioned", "rules" => "required|min_length[35]|max_length[1500]"]
    ];

    protected $page6Rules_errors = [
        'currently_actioned' => ['required' => 'This information is required', 'min_length' => 'You must enter at least 35 characters', 'max_length' => 'You have exceeded the maximum character limit of 1500'],
        'tobe_actioned' => ['required' => 'This information is required', 'min_length' => 'You must enter at least 35 characters', 'max_length' => 'You have exceeded the maximum character limit of 1500']
    ];

    protected $respondentLoginRules = [
        "email" => ["label" => "Email", "rules" => "required|valid_email"],
        "access_code" => ["label" => "Access code", "rules" => "required|integer"]
    ];

    protected $respondentLoginRules_errors = [
        'email' => ['required' => 'Email address is required', 'min_length' => 'Please enter a valid email address'],
        "access_code" => ['required' => 'Access code is required', 'integer' => 'Access code must only contain numbers',],
    ];

    protected $answerQuestion = [
        "client_response" => ["label" => "Client Responde", "rules" => "required|min_length[5]"],
    ];

    protected $answerQuestion_errors = [
        'client_response' => ['required' => 'This information is required', 'min_length' => 'This information is required'],
    ];



    protected $imageURL;

    public function __construct()
    {
        $this->dtrackDB = db_connect('dt');
        $this->adminDB = db_connect("am");
        $this->imageURL = site_url() . "images/";
    }

    public function returnCasesIdFromUserId($user_id)
    {
        $row = $this->select('ID')->where('UserId', $user_id)->get()->getRow();
        return ($row && $row->ID) ? $row->ID : null;
    }

    public function getAllQuestions($case_id)
    {
        $builder = $this->getConsentOrderQuestionNotesTable();
        return $builder->select('note,id')->where('case_id', $case_id)->where('completed', 0)->get()->getResultArray();
    }


    public function getSupportingDocuments($case_id)
    {
        $builder = $this->getCustomDocsDB();
        return $builder->select('uuid,document_name,document_path, document_added')
            ->where('case_id', $case_id)
            ->where('document_status', 1)
            ->where('supporting_document', 1)
            ->get()
            ->getResultArray();
    }


    public function updateBridgeTable($steps_bridge_table, $case_id, $consent_type)
    {
        $ins = array('step_id' => ($consent_type == "standalone") ? 1 : 20, 'case_id' => $case_id, 'status' => 1, 'completed_by' => NULL, 'date_completed' => date("Y-m-d H:i:s"));

        $builder = ($steps_bridge_table == "step_manager_bridge") ? $this->getStepManagerBridgeTable() : $this->getCCStandAloneTable();
        $builder->insert($ins);
    }


    public function checkForUpgrade($case_id)
    {

        $check_for_upgrade = $this->select('Upgraded')->where('ID', $case_id)->get()->getRow();
        if ($check_for_upgrade->Upgraded == 1) {
            // Get the latest package code and consent type
            $upgradeDetails = $this->query("SELECT product_to FROM upgrades WHERE case_id = " . $case_id . " AND upgrade_status = 1 ORDER BY id DESC LIMIT 1")->getRow();

            // Check if upgrade details exist
            if ($upgradeDetails === null) {
                return [false, null, null];
            }

            $builder = $this->getPackageTable();
            $get_consent_type = $builder->select('consent_order_type')->where('ID', $upgradeDetails->product_to)->get()->getRow();

            // Check if consent type exists
            if ($get_consent_type === null) {
                return [false, null, null];
            }

            return [true, $get_consent_type->consent_order_type, $upgradeDetails->product_to];
        }

        // Not upgraded
        return [false, null, null];
    }



    public function sendInProcessEmail($UserEmail, $Forename, $is_ft, $steps_bridge_table, $consent_type, $case_id, $future_date, $user_id)
    {
        //This means we can update the steps and send our emails as the FF has been fully completed
        //we also need to update different steps based on the consent type
        $ins = array(
            'step_id' => ($consent_type == "standalone") ? 2 : 27,
            'case_id' => $case_id,
            'status' => 1,
            'completed_by' => NULL,
            'date_completed' => date("Y-m-d H:i:s")
        );

        $builder = ($steps_bridge_table == "step_manager_bridge") ? $this->getStepManagerBridgeTable() : $this->getCCStandAloneTable();
        $builder->insert($ins);

        //get the email wrapper template
        $main_email_template = $this->getEmailWrapper($case_id);

        // We now need to fetch the email template from the correct table and send the email
        $db_step_id = ($consent_type == "standalone") ? 2 : 27;
        $db_step_table = ($consent_type == "standalone") ? "step_manager_consent_standalone" : "step_manager";
        $in_process_email = $this->getNotificationWordingWithWhere($db_step_table, $db_step_id);


        // get order placed date
        $orderPlacedDate = $this->db->table("cases")->select("OrderPlaced")->where("ID", $case_id)->get()->getRow()->OrderPlaced;

        // Its not a fast track and the service was ordered before 20th May 2025 so we need to change the dates to 28 days and not the current env settings
        $overWriteDaysToIncrease = false;
        $customDaysToIncrease = 0;

        if ($orderPlacedDate && $orderPlacedDate < "2025-05-20 00:00:00" && $is_ft == false) {
            $days_to_increase = 28;
            $overWriteDaysToIncrease = true;
            $customDaysToIncrease = $days_to_increase;
        }

        // We now need to see if the package was purchased between the 20th of may 2025 and the 30th of June 2025, we want to set this to 14 days as that was their purchase terms
        if ($orderPlacedDate && $orderPlacedDate >= "2025-05-20 00:00:00" && $orderPlacedDate <= "2025-06-30 23:59:59") {
            $days_to_increase = 14;
            $overWriteDaysToIncrease = true;
            $customDaysToIncrease = $days_to_increase;
        }

        $main_body = $in_process_email->notification_message;
        $msg = str_replace('{FIRST_NAME}', ucwords($Forename), $main_body);

        if ($overWriteDaysToIncrease == true) {
            $msg = str_replace('{NUMBER_OF_WORKING_DAYS}', $customDaysToIncrease, $msg);
        } else {
            $msg = str_replace('{NUMBER_OF_WORKING_DAYS}', ($is_ft == TRUE) ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : getenv("CONSENT_ORDER_DUE_DAYS_STANDARD"), $msg);
        }

        // We want to let the client know that if they fast track, the due date will be the future date
        $if_client_added_ft_date = date('dS F Y', strtotime($this->addBusinessDays(date("Y-m-d"), getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK"))));

        $ff_message = ($is_ft == false) ? "However, should you wish to FAST TRACK this service to have the Consent Order drafted within 7 working days for £200.00, 
        then please make contact on 01793 384029 and press option 2 (whilst this is the option for a new enquiry, you will be put through to the correct team to take this payment). <br /><br /> 
        If you fast tracked your application today, your new due date would be the <b>" . $if_client_added_ft_date . "</b>.<br /><br /> " : "";

        $msg = str_replace('{FAST_TRACK_MSG}', $ff_message, $msg);
        $msg = str_replace('{CAL_DATE}', $future_date, $msg);

        //str replace the details into placeholders
        $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
        $body = str_replace('{MAIN_SUBJECT}', "Case Update", $body);
        $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

        $this->sendEmail($UserEmail, "Case Update", $body);

        // Update to say the email has been send
        $builder = $this->getConsentFFTable();
        $builder->where('user_id', $user_id)->set(['ff_complete_email_sent' => 1])->update();
    }

    public function updateDueDate($user_id, $dueDate)
    {
        $builder = $this->getConsentFFTable();
        $builder->where('user_id', $user_id)->set(['due_date' => $dueDate])->update();
    }


    public function getNotificationWordingWithWhere($db_step_table, $db_step_id)
    {
        $builder = ($db_step_table == "step_manager") ? $this->getStepManagerTable() : $this->getStepManagerStandAloneTable();
        return $builder->select('notification_message')->where('id', $db_step_id)->get()->getRow();
    }

    public function getNotificationWording($ref)
    {
        $builder = $this->getStepManagerTable();
        return $builder->select('notification_message')->where('ref', $ref)->get()->getRow();
    }


    public function sendEmail($userEmail, $subject, $body)
    {
        $graph = new MicrosoftGraphMail();

        return $graph->send(
            $userEmail,
            $subject,
            $body
        );
    }

    public function returnEmailTemplate($template)
    {
        $db = db_connect();
        $builder = $db->table("email_templates");
        return $builder->select('subject,body, ')->where('email_ref', $template)->get()->getRow();
    }


    public function getEmailWrapper($case_id)
    {

        //find out if we need the dol wrapper or qed
        $sale_source = $this->select('SaleSource')->where('ID', $case_id)->get()->getRow();
        switch ($sale_source->SaleSource) {
            case 1:
                //DOL
                $template = $this->returnEmailTemplate("dol_main_wrapper");
                return $template->body;
                break;

            case 2:
                //QED
                $template = $this->returnEmailTemplate("qed_main_wrapper");
                return $template->body;
                break;
        }
    }


    public function getUserPackageDetails($user_id)
    {
        return $this->select('PackageId')->where('UserId', $user_id)->get()->getRow();
    }

    public function hasJointFees($packageID)
    {
        $builder = $this->getPackageTable();
        return $builder->where('ID', $packageID)->where('has_debt', 2)->countAllResults();
    }


    public function fetchCCProperties($user_id)
    {
        $builder = $this->getConsentPropertiesTable();
        $results = $builder->where('user_id', $user_id)->get()->getResultArray();
        return (is_array($results) && count($results) > 0) ? $results : array();
    }

    public function fetchCCPensions($user_id)
    {
        $builder = $this->getConsentPensionsTable();
        $results = $builder->where('user_id', $user_id)->get()->getResultArray();
        return (is_array($results) && count($results) > 0) ? $results : array();
    }

    public function missingConsentInfo($user_id)
    {
        // We want to look in the pensions table for this user and see if any of the "i dont know fields are 1"
        $has_missing_data = False;
        $builder = $this->getConsentPensionsTable();
        $user_pensions = $builder->where('user_id', $user_id)->get()->getResultArray();

        if (is_array($user_pensions) &&  count($user_pensions) > 0) {
            // Check if the user has any no info fields, if they dont just return false
            $logBuilder = $this->getNoInfoLogTable();
            $check_for_row = $logBuilder->where('user_id', $user_id)->countAllResults();
            if ($check_for_row == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            // Nothing missing as we dont have any pension info
            return false;
        }
    }

    public function checkIfComplete($user_id)
    {
        $builder = $this->getConsentFFTable();
        return ($builder->where('complete', 1)->where('user_id', $user_id)->countAllResults() > 0) ? true : false;
    }

    public function getQuestionnaireData($user_id, $asRow = false)
    {
        $builder = $this->getConsentFFTable();
        return ($asRow === false) ? $builder->where('user_id', $user_id)->get()->getResultArray() : $builder->where('user_id', $user_id)->get()->getRow();
    }

    public function getCourtDetails($user_id)
    {
        // Make sure we have a user id, we are going to be returning two values, the court name and the case number
        if (!$user_id) {
            return ['court_name' => '', 'case_number' => ''];
        }

        // We now need to check that there is a row in the consent_ff_questionnaire table matching the user_id and is complete =1
        $builder = $this->getConsentFFTable();
        $row = $builder->where('user_id', $user_id)->where('complete', 1)->get()->getRow();

        if (!$row) {
            return ['court_name' => '', 'case_number' => ''];
        }

        // We now want to fetch name_of_court and case_number from the consent_ff_questionnaire table
        $court_name = $row->name_of_court ?? '';
        $case_number = $row->case_number ?? '';

        return ['court_name' => $court_name, 'case_number' => $case_number];
    }

    public function updateQuestionnaire($user_id, $update)
    {
        $builder = $this->getConsentFFTable();
        $builder->where('user_id', $user_id)->set($update)->update();
    }

    public function isFastTrack($case_id, $isFee, $hasBeenFastTracked = false)
    {

        // Has the case been upgraded and has the fast track option been used?
        // first we will check if the case has been upgraded
        $builder = $this->getUpgradeTable();
        $upgrade = $builder->where('case_id', $case_id)->get()->getRow();
        if ($upgrade) {
            // We now need to get all the upgrades and join them to the packages table and see if the package has_fasttrack is 1
            $builder = $this->getUpgradeTable();
            $upgrades = $builder->join('packages', 'upgrades.product_to = packages.ID')
                ->where('upgrades.case_id', $case_id)
                ->where('upgrades.upgrade_status', 1)
                ->where('packages.has_fasttrack', 1)
                ->get()
                ->getResultArray();

            if (is_array($upgrades) && count($upgrades) > 0) {
                foreach ($upgrades as $upgrade) {
                    if ($upgrade['has_fasttrack'] == 1) {
                        return true;
                    }
                }
            }
        }


        if ($isFee === true) {
            $builder = $this->getFeesTable();
            return ($builder->where('case_id', $case_id)->where('fee_id', 74)->countAllResults() > 0) ? true : false;
        } else {
            $builder = $this->getServicesTable();
            return ($builder->where('case_id', $case_id)->where('fee_id', 74)->where('deleted', 0)->countAllResults() > 0) ? true : false;
        }
    }


    public function getUserDetails($user_id)
    {
        $builder = $this->getUserTable();
        return $builder->select('UserEmail,Forename, Surname')->where('ID', $user_id)->get()->getRow();
    }


    public function getAssignedCMData($case_id)
    {
        $getAssignedTo = $this->select('TakenBy')->where('ID', $case_id)->get()->getRow();

        $adminBuilder = $this->getAdminUserTable();
        $getCMDetails = $adminBuilder->select('first_name,email')->where('id', $getAssignedTo->TakenBy)->get()->getRow();

        return [$getAssignedTo, $getCMDetails];
    }


    public function addBusinessDays($date, $numDays = 1, $holidays = '')
    {
        if ($holidays === '') $holidays = 'https://www.gov.uk/bank-holidays/england-and-wales.ics';

        if (!is_array($holidays)) {
            $ch = curl_init($holidays);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ics = curl_exec($ch);
            curl_close($ch);
            $ics = explode("\n", $ics);
            $ics = preg_grep('/^DTSTART;/', $ics);
            $holidays = preg_replace('/^DTSTART;VALUE=DATE:(\\d{4})(\\d{2})(\\d{2}).*/s', '$1-$2-$3', $ics);
        }

        $addDay = 0;
        $counter = 1;
        while ($numDays--) {

            while (true) {
                $addDay++;
                $newDate = date('Y-m-d', strtotime("$date +$addDay Days"));
                $newDayOfWeek = date('w', strtotime($newDate));
                if ($newDayOfWeek > 0 && $newDayOfWeek < 6 && !in_array($newDate, $holidays)) break;
            }
            $counter++;
        }

        return $newDate;
    }


    public function returnCOType($case_id)
    {
        return $this->select('packages.consent_order_type, cases.OrderPlaced')
            ->join('packages', 'cases.PackageId=packages.ID')
            ->where('cases.ID', $case_id)
            ->get()
            ->getRow();
    }


    public function checkIfHasP1Data($case_id, $pension_id)
    {
        $builder = $this->getP1DocumentTable();
        $res = $builder->where('case_id', $case_id)->where('pension_id', $pension_id)->countAllResults();

        if ($res <= 0) {
            return [];
        }

        // Get the data
        $res = $builder->where('case_id', $case_id)->where('pension_id', $pension_id)->get()->getResult();
        return (count($res) > 0) ? $res : [];
    }



    public function getPensionData($user_id, $getRow = false, $pension_id = null)
    {
        $builder = $this->getConsentPensionsTable();

        if ($getRow === true && $pension_id != null) {
            return $builder->where('id', (int)$pension_id)
                ->where('user_id', $user_id)
                ->get()
                ->getResultArray();
        } else {
            $res = $builder->where('user_id', $user_id)->get()->getResultArray();
            return (count($res) > 0) ? $res : [];
        }
    }



    public function addStage1FF($separate_form, $data, $user_id)
    {

        $builder = $this->getConsentFFTable();
        if ($separate_form == 0) {
            //clear the database field
            if ($this->checkForFfRow($user_id) == "1") {
                $update = ['respondent_separate_email' => NULL, 'has_contact' => 1];
                $builder->where('user_id', $user_id)->set($update)->update();
            } else {

                $insert = ['has_contact' => 1, 'user_id' => $user_id];
                $builder->insert($insert);
            }
        } else {

            //set database fields
            if ($this->checkForFfRow($user_id) == "1") {
                $update = ['respondent_separate_email' => trim($data['respondent_separate_email']), 'has_contact' => 0];
                $builder->where('user_id', $user_id)->set($update)->update();
            } else {

                $insert = ['respondent_separate_email' => trim($data['respondent_separate_email']), 'has_contact' => 0, 'user_id' => $user_id];
                $builder->insert($insert);
            }
        }
    }


    public function addStage2FF($user_id, $data, $res_logged_in)
    {
        //unset the submit button
        unset($data['page2_submit'], $data['court_other']);
        $builder = $this->getConsentFFTable();

        //we need to check if this user has opted for seperate applications
        $check_split_application = $builder->select('has_contact')->where('user_id', $user_id)->get()->getRow();

        if ($check_split_application->has_contact == 0 && $res_logged_in == FALSE) {
            $format_dates = array("app_dob");
        } elseif ($check_split_application->has_contact == 0 && $res_logged_in == TRUE) {
            $format_dates = array("res_dob");
        } else {
            $format_dates = array("app_dob", "res_dob");
        }

        if ($res_logged_in === false) {
            //create applicant address
            $app_address = $data['app_address_line1'];
            $app_address .= ($data['app_address_line2'] != "") ? "\n" . $data['app_address_line2']  : "";
            $app_address .= ($data['app_address_line3'] != "") ? "\n" . $data['app_address_line3']  : "";
            $app_address .= "\n" . $data['app_address_town_city'];
            $app_address .= ($data['app_address_county'] != "") ? "\n" . $data['app_address_county']  : "";
            $app_address .= "\n" . $data['app_address_postcode'];

            //get the country name
            $country = $this->getCountryName((int)$data['app_address_country']);
            $app_address .= "\n" . $country;

            //add the fully formed address to the array for insert
            $data['app_faddress'] = $app_address;
        }


        //we only include the respondant address if they are doing the application together
        if ($check_split_application->has_contact == 0 && $res_logged_in === true || $check_split_application->has_contact == 1) {
            //create respondent address
            $res_address = $data['res_address_line1'];
            $res_address .= ($data['res_address_line2'] != "") ? "\n" . $data['res_address_line2']  : "";
            $res_address .= ($data['res_address_line3'] != "") ? "\n" . $data['res_address_line3']  : "";
            $res_address .= "\n" . $data['res_address_town_city'];
            $res_address .= ($data['res_address_county'] != "") ? "\n" . $data['res_address_county']  : "";
            $res_address .= "\n" . $data['res_address_postcode'];

            //get the country name
            $country = $this->getCountryName((int)$data['res_address_country']);
            $res_address .= "\n" . $country;

            //add the fully formed address to the array for insert
            $data['res_faddress'] = $res_address;
        }


        //create respondent date
        foreach ($data as $key => $val) {
            if (in_array($key, $format_dates)) {
                if ($val != "") {
                    $data[$key] = date("Y-m-d", strtotime($val));
                } else {
                    $data[$key] = NULL;
                }
            } else {
                $data[$key] = htmlentities($val);
            }
        }
        $data['user_id'] = $user_id;

        //if there is a record with the matching user ID we will update
        if ($this->checkForFfRow($user_id) == "1") {
            return ($builder->where('user_id', $user_id)->set($data)->update()) ? TRUE : FALSE;
        } else {
            return ($builder->insert($data)) ? TRUE : FALSE;
        }
    }


    public function addStage3FF($user_id, $data)
    {
        //unset the submit button
        unset($data['page3_submit'], $data["has_child_select"]);
        $builder = $this->getConsentFFTable();

        $format_dates = array('date_of_marr_civ', 'date_of_sep', 'nisi_date', 'absolute_date', 'child_name1_dob', 'child_name2_dob', 'child_name3_dob', 'child_name4_dob', 'child_name5_dob', 'child_name6_dob', 'child_name7_dob', 'child_name8_dob');

        foreach ($data as $key => $val) {
            if (in_array($key, $format_dates)) {

                if ($val != "") {
                    $data[$key] = date("Y-m-d", strtotime($val));
                } else {
                    $data[$key] = NULL;
                }
            } else {
                $data[$key] = htmlentities($val);
            }
        }
        $data['user_id'] = $user_id;

        //if there is a record with the matching user ID we will update
        if ($this->checkForFfRow($user_id) == "1") {
            return ($builder->where('user_id', $user_id)->set($data)->update()) ? TRUE : FALSE;
        } else {
            return ($builder->insert($data)) ? TRUE : FALSE;
        }
    }


    public function addStage5FF($user_id, $data)
    {
        //unset the submit button
        unset($data['page6_submit']);

        foreach ($data as $key => $val) {
            $data[$key] = htmlentities($val);
        }
        $data['user_id'] = $user_id;

        //if there is a record with the matching user ID we will update
        $questionnaireBuilder = $this->getConsentFFTable();
        if ($this->checkForFfRow($user_id) == "1") {
            return ($questionnaireBuilder->where('user_id', $user_id)->set($data)->update()) ? TRUE : FALSE;
        } else {
            return ($questionnaireBuilder->insert($data)) ? TRUE : FALSE;
        }
    }


    public function checkForFfRow($user_id)
    {
        $builder = $this->getConsentFFTable();
        return $builder->where('user_id', $user_id)->countAllResults();
    }


    public function getMuliUploadError($file)
    {
        $size = $file->getSizeByUnit('mb');
        if ($size > 4) {
            return ['error', 'File to large (max 4MB)', null];
        }

        $type = $file->getMimeType();
        if (!in_array($type, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return ['error', 'Invalid file format (PDF, DOC, DOCX, XLSX ONLY)', null];
        }
    }


    public function getQuestionUploadError($file)
    {
        $size = $file->getSizeByUnit('mb');
        if ($size > 4) {
            return ['error', 'File to large (max 4MB)', null];
        }

        $type = $file->getMimeType();
        if (!in_array($type, ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'text/csv'])) {
            return ['error', 'Invalid file format (PDF, DOCX, DOC and CSV only)', null];
        }
    }




    public function addSupportingFile($savePath, $fileName, $case_id)
    {

        $db = db_connect();
        $builder = $db->table("custom_documents");

        // before the insert we want to make the older rows with the same file name not show
        $builder->where('document_name', $fileName)->where('case_id', $case_id)->set(['document_status' => 0, 'show_document' => 0])->update();

        $insert = [
            'case_id' => $case_id,
            'document_added_by' => 37,
            'document_status' => 1,
            'document_name' => $fileName,
            'document_path' => $savePath,
            'show_document' => 0,
            'supporting_document' => 1,
            'sys_document' => 1
        ];


        $builder->set('uuid', 'UUID()', false);
        $builder->insert($insert);
    }


    public function removeSupportingDoc($uuid, $user_id)
    {
        // Check file belongs to the user
        $case_id = $this->returnCasesIdFromUserId($user_id);

        $builder = $this->getCustomDocsDB();
        $check_access = $builder->where('case_id', $case_id)->where("uuid", $uuid)->countAllResults();

        // Not their file to delete
        if ($check_access <= 0) {
            return "PERMERROR";
        }

        // Get the document path
        $document_data = $builder->select('document_name, document_path')->where('case_id', $case_id)->where("uuid", $uuid)->get()->getRow();

        // We can now delete, we need to delete the file from the system, then update the database to say its been deleted then redirect
        $file = ROOTPATH . $document_data->document_path . "/" . $document_data->document_name;


        if (file_exists($file)) {
            // try and delete the file
            try {

                unlink($file);

                // Update the database
                $update = ["document_status" => 0, "document_deleted_date" => date("Y-m-d H:i:s")];
                $builder->where('case_id', $case_id)->where('uuid', $uuid)->set($update)->update();

                session()->setFlashdata("SUCC", "File has been successfully deleted.");
                return "SUCCESS";
            } catch (\Exception $e) {
                session()->setFlashdata("user_msg_error", "Sorry, we weren't able to delete this file, please try again.");
                return "FAILED";
            }
        } else {
            // Redirct with error
            session()->setFlashdata("user_msg_error", "Sorry, we cant delete this file.");
            return "FILENOTFOUND";
        }
    }


    public function getSupportingDoc($uuid, $user_id)
    {
        // Check file belongs to the user
        $case_id = $this->returnCasesIdFromUserId($user_id);

        $builder = $this->getCustomDocsDB();
        $check_access = $builder->where('case_id', $case_id)->where("uuid", $uuid)->countAllResults();

        if ($check_access <= 0) {
            return "PERMERROR";
        }

        // Get the document path
        $document_data = $builder->select('document_name, document_path')->where('case_id', $case_id)->where("uuid", $uuid)->get()->getRow();

        return ($document_data !== null) ? site_url() . $document_data->document_path . "/" . $document_data->document_name : null;
    }


    public function getD81UploadErrors($file)
    {
        $size = $file->getSizeByUnit('mb');
        if ($size > 4) {
            return ['error', 'File to large (max 4MB)', null];
        }

        $type = $file->getMimeType();
        if (!in_array($type, ['application/pdf'])) {
            return ['error', 'Invalid file format (PDF only)', null];
        }
    }

    public function addPropertyCo($data, $userid)
    {

        if (is_array($data) && count($data) > 0) {

            // Remove any data that is linked to this followung questions as they have selected NO and may have forgot to remove the data from the form fields
            if ($data['is_subjected_mortage'] == 0) {
                $data['mortage_provider'] = "";
                $data["mortage_approx_val"] = "";
            }

            if ($data['is_transfering_prop_one_name'] == 0) {
                $data['one_name_transfer_details'] = "";
            }

            if ($data['selling_matrimonial_home'] == 0) {
                $data['selling_matrimonial_home_details'] = "";
            }

            if ($data['already_transfered'] == 0) {
                $data['already_transfered_details'] = "";
                $data['already_transfered_equity_split_app'] = "";
                $data['already_transfered_equity_split_res'] = "";
            }

            // Add the user ID into the array
            $data['user_id'] = $userid;

            if ($userid == "") {
                // Mising data, we cant save this data
                return "MISSING DATA";
            }


            $builder = $this->getConsentPropertiesTable();
            if ($data['is_update'] == 1) {
                $rowid = (int)$data['rowid'];

                // Update the record if it belongs to this user
                $check_row = $builder->where('user_id', $userid)
                    ->where('id', $rowid)
                    ->countAllResults();

                // Dont go any further the data has been changed
                if ($check_row <= 0) {
                    return "SUCERROR - " . $data['is_update'];
                } else {

                    // Remove the property_number and user_id array element as its not needed as its an update
                    unset($data['property_number'], $data['user_id'], $data['is_update'], $data['rowid']);
                    $update = $builder->where('user_id', $userid)->where('id', $rowid)->set($data)->update();

                    if ($update) {
                        return "SUCCESSUP";
                    } else {
                        return "DB ERROR";
                    }
                }
            } else {

                /* INSERT ROW */
                # Is this a split application or not?
                $questionnaireBuilder = $this->getConsentFFTable();
                $check_if_split = $questionnaireBuilder->where('user_id', $userid)
                    ->where('respondent_separate_email IS NOT NULL')
                    ->countAllResults();

                // An email address has been found in the respondent_separate_email field
                if ($check_if_split > 0) {
                    // We need to work out if this is the applicant or the respondent adding the record
                    $data['is_split_app'] = 1;
                    $data["respondent_row"] = (session()->has("respondent_only")) ? 1 : 0;
                    $data["petitioner_row"] = (!session()->has("respondent_only")) ? 1 : 0;


                    // We need to get the max row and plus one for the pension count
                    $propBuilder = $this->getConsentPropertiesTable();
                    $max_rows = $propBuilder->where('user_id', $userid)->countAllResults();

                    // Over write the property_number
                    $data["property_number"] = (int)$max_rows + 1;
                }


                // Try and save the data
                unset($data['is_update'], $data['rowid']);

                $db = db_connect();
                $propBuilder = $db->table("consent_order_properties");
                if ($propBuilder->insert($data)) {

                    //Get the insert ID so we can remove any other row that has the same property number
                    $insert_id = $db->insertID();

                    $this->query("DELETE 
									  FROM consent_order_properties
									  WHERE user_id = " . $userid . "
									  AND property_number = " . (int)$data['property_number'] . "
									  AND id NOT IN (" . $insert_id . ")");

                    session()->setFlashdata("SUCC", "Property successfully added");
                    return $insert_id;
                } else {
                    return "DB ERROR";
                }
            }
        } else {
            // We dont have any data, return false;
            return "NO DATA SENT";
        }
    }


    public function fetchPropertyData($data, $userid)
    {

        $rowid = (int)$data['id'];
        $builder = $this->getConsentPropertiesTable();

        // Does this record belong to the user
        if ($this->doesRecordfBelongToUser($rowid, $userid, true) === false) {
            return "FAILED";
        } else {

            // Fetch the data from the DB
            $row_data = $builder->where('user_id', $userid)->where('id', $rowid)->get()->getRow();

            if ($row_data) {
                $row_data = (array)$row_data;
                return json_encode($row_data);
            } else {
                return "DBERROR";
            }
        }
    }


    public function removeProperty($data, $userid)
    {

        $rowid = (int)$data['id'];
        $builder = $this->getConsentPropertiesTable();

        // Does this record belong to the user
        if ($this->doesRecordfBelongToUser($rowid, $userid, true) === false) {
            echo "FAILED";
        } else {

            // If the applicant is logged in AND they are trying to delete a record they dont have access to we need to kill the script
            if ($data["logged_in"] == 1) {
                // Check to see if the user is allowed to delete this row
                $check_row = $this->usersDB->where('user_id', $userid)
                    ->where('id', $rowid)
                    ->where('petitioner_row', 0)
                    ->count_all_results('consent_order_properties');


                // We can't delete
                if ($check_row <= 0) {
                    echo "SECERROR";
                    exit();
                }
            }

            // The record belongs to the user, now we try and delete
            $delete = $this->query("DELETE FROM consent_order_properties WHERE user_id = " . $userid . " AND id = " . $rowid . " ");
            if ($delete) {
                // if the delete was successfull we need to reorder the property numbers incase the users removed record 2 of 3 for example
                $records = $builder->where('user_id', $userid)
                    ->orderBy('property_number', 'ASC')
                    ->get()
                    ->getResultArray();

                if (is_array($records) && count($records) > 0) {
                    $counter = 1;
                    foreach ($records as $r) {
                        // Set a new property number
                        $builder->where("id", $r['id'])
                            ->where('user_id', $userid)
                            ->set(['property_number' => $counter])
                            ->update();
                        $counter++;
                    }
                }

                // Success + set flash data
                session()->setFlashdata("SUCC", "Property successfully removed");
                echo "SUCCESS";
            } else {
                echo "DATABASEERROR";
            }
        }
    }

    public function deletePropData($data, $userid)
    {
        if (is_array($data) && count($data) > 0) {
            if ($data['delete_data'] == true) {
                $builder = $this->getConsentPropertiesTable();

                $userid = (int)$userid;
                $delete = $this->query("DELETE FROM consent_order_properties WHERE user_id = " . $userid . "");
                if ($delete) {
                    return "SUCCESS";
                } else {
                    return "DATABASE ERROR";
                }
            } else {
                return "NO DATA";
            }
        } else {
            return "NO DATA";
        }
    }



    public function fetchPensionData($data, $userid)
    {

        $rowid = (int)$data['id'];
        $pensionBuilder = $this->getConsentPensionsTable();

        // Does this record belong to the user
        $check_row = $pensionBuilder->where('user_id', $userid)->where('id', $rowid)->countAllResults();

        if ($check_row <= 0) {
            return "FAILED";
        } else {

            // Fetch the data from the DB
            $row_data = $pensionBuilder->where('user_id', $userid)->where('id', $rowid)->get()->getRow();

            if ($row_data) {

                $row_data = (array)$row_data;
                // get the user checkbox data
                $noLogBuilder = $this->getNoInfoLogTable();
                $user_check_boxes = $noLogBuilder->where('user_id', $userid)->where('pension_id', $rowid)->get()->getResultArray();

                if (is_array($user_check_boxes) && count($user_check_boxes) > 0) {
                    foreach ($user_check_boxes as $key => $cb) {
                        for ($i = 1; $i <= 19; $i++) {
                            $k = "q" . $i . "_checked";
                            $row_data[$k] = $cb[$k];
                        }
                    }
                }

                // Reformat the date for the user
                if ($row_data['a_transferor_dob'] != "") {
                    $row_data['a_transferor_dob'] = date("d-m-Y", strtotime($row_data['a_transferor_dob']));
                }

                if ($row_data['b_transferor_dob'] != "") {
                    $row_data['b_transferor_dob'] = date("d-m-Y", strtotime($row_data['b_transferor_dob']));
                }

                return json_encode($row_data);
            } else {
                return "DBERROR";
            }
        }
    }


    public function addPensionCo($data, $userid)
    {
        $checkboxes = array();
        $found_checked = false;

        // Check if we have any no information checkboxes selected for the 19 options, if we do, add them to the array
        for ($i = 1; $i <= 19; $i++) {

            $key = "checkbox_" . $i;
            $save_key = "q" . $i . "_checked";
            if (array_key_exists($key, $data)) {
                $checkboxes[] = array("{$save_key}" => $data[$key]);
                if ($data[$key] == 1) {
                    $found_checked = True;
                }
            }
        }


        // Unset the data fields
        unset($data["checkbox_1"], $data["checkbox_2"], $data["checkbox_3"], $data["checkbox_4"], $data["checkbox_5"], $data["checkbox_6"], $data["checkbox_7"], $data["checkbox_8"], $data["checkbox_9"], $data["checkbox_10"], $data["checkbox_11"], $data["checkbox_12"], $data["checkbox_13"], $data["checkbox_14"], $data["checkbox_15"], $data["checkbox_16"], $data["checkbox_17"], $data["checkbox_18"], $data["checkbox_19"]);


        if (is_array($data) && count($data) > 0) {

            // Add the user ID into the array
            $data['user_id'] = $userid;

            // Format the DOB
            if (array_key_exists("a_transferor_dob", $data) && $data['a_transferor_dob'] != "") {
                $data['a_transferor_dob'] = date("Y-m-d", strtotime($data['a_transferor_dob']));
            }

            if (array_key_exists("b_transferor_dob", $data) && $data['b_transferor_dob'] != "") {
                $data['b_transferor_dob'] = date("Y-m-d", strtotime($data['b_transferor_dob']));
            }

            // If we dont have a user ID we dont want to proceed
            if ($userid == "") {
                // Mising data, we cant save this data
                return "MISSING DATA";
            } else {

                if ($data['is_update'] == 1) {
                    $rowid = (int)$data['rowid'];

                    // Update the record if it belongs to this user
                    $pensionBuilder = $this->getConsentPensionsTable();
                    $check_row = $pensionBuilder->where('user_id', $userid)->where('id', $rowid)->countAllResults();

                    // Dont go any further the data has been changed
                    if ($check_row <= 0) {
                        return "SUCERROR - " . $data['is_update'];
                    } else {

                        // Remove the property_number and user_id array element as its not needed as its an update
                        unset($data['pension_number'], $data['user_id'], $data['is_update'], $data['rowid']);
                        $update = $pensionBuilder->where('user_id', $userid)->where('id', $rowid)->set($data)->update();

                        if ($update) {
                            // if we have an array of data we want to them add these records to the database as we need to repopulate the check boxes in the form
                            if (is_array($checkboxes) && count($checkboxes) > 0) {

                                // Delete any no information rows in the database before saving new ones
                                $noLogBuilder = $this->getNoInfoLogTable();
                                $check_rows = $noLogBuilder->where('user_id', $userid)->where('pension_id', $rowid)->countAllResults();

                                if ($check_rows > 0) {

                                    $userid = (int)$userid;
                                    $delete = $this->query("DELETE FROM consent_ff_no_info_log WHERE user_id = " . $userid . " AND pension_id = " . $rowid . " ");
                                }

                                if ($found_checked === true) {
                                    // Create new row for us to update if it doesn't exist
                                    $check_row = $noLogBuilder->where('user_id', $userid)->where('pension_id', $rowid)->countAllResults();

                                    if ($check_row <= 0) {
                                        $noLogBuilder->insert(['user_id' => $userid, 'pension_id' => $rowid]);
                                    }

                                    foreach ($checkboxes as $key => $c) {
                                        $ins = array();
                                        foreach ($checkboxes[$key] as $k => $d) {
                                            $noLogBuilder->where('user_id', $userid)->where('pension_id', $rowid)->set([$k => (int)$d, 'user_id' => $userid])->update();
                                        }
                                    }
                                }
                            }

                            return "SUCCESSUP";
                        } else {
                            return "DB ERROR";
                        }
                    }
                } else {


                    /* INSERT ROW */
                    $pensionBuilder = $this->getConsentPensionsTable();

                    # Is this a split application or not?
                    $questionnaireBuilder = $this->getConsentFFTable();
                    $check_if_split = $questionnaireBuilder->where('user_id', $userid)->where('respondent_separate_email IS NOT NULL')->countAllResults();

                    // An email address has been found in the respondent_separate_email field
                    if ($check_if_split > 0) {
                        // We need to work out if this is the applicant or the respondent adding the record
                        $data['is_split_app'] = 1;
                        $data["respondent_row"] = (session()->has("respondent_only")) ? 1 : 0;
                        $data["petitioner_row"] = (!session()->has("respondent_only")) ? 1 : 0;

                        // We need to get the max row and plus one for the pension count
                        $max_rows =  $pensionBuilder->where('user_id', $userid)->countAllResults();

                        // Over write the property_number
                        $data["pension_number"] = (int)$max_rows + 1;
                    }

                    // Try and save the data
                    unset($data['is_update'], $data['rowid']);


                    $db = db_connect();
                    $pensionBuilder = $db->table("consent_pensions");

                    if ($pensionBuilder->insert($data)) {
                        // Get the insert ID so we can remove any other row that has the same pension number
                        $insert_id = $db->insertID();

                        $this->query("DELETE
									  FROM consent_pensions
									  WHERE user_id = " . $userid . "
									  AND pension_number = " . (int)$data['pension_number'] . "
									  AND id NOT IN (" . $insert_id . ")");

                        session()->setFlashdata("SUCC", "Pension successfully added");


                        // if we have an array of data we want to them add these records to the database as we need to repopulate the check boxes in the form
                        if (is_array($checkboxes) && count($checkboxes) > 0) {
                            if ($found_checked == TRUE) {

                                // Delete any no information rows in the database before saving new ones
                                $noLogBuilder = $this->getNoInfoLogTable();
                                $check_rows = $noLogBuilder->where('user_id', $userid)->where('pension_id', $insert_id)->countAllResults();

                                if ($check_rows > 0) {
                                    $noLogBuilder->where('user_id', $userid)
                                        ->where('pension_id', $insert_id)
                                        ->delete("consent_ff_no_info_log");
                                }

                                // Create new row for us to update
                                $this->query("DELETE FROM consent_ff_no_info_log WHERE user_id = " . $userid . " AND pension_id = " . $insert_id . " ");

                                foreach ($checkboxes as $key => $c) {
                                    $ins = array();
                                    foreach ($checkboxes[$key] as $k => $d) {
                                        $noLogBuilder->where('user_id', $userid)->set([$k => (int)$d, 'user_id' => $userid, 'pension_id' => $insert_id])->update();
                                    }
                                }
                            }
                        }


                        return $insert_id;
                    } else {
                        return "DB ERROR";
                    }
                }
            }
        } else {
            // We dont have any data, return false;
            return "NO DATA SENT";
        }
    }


    public function removePension($data, $userid)
    {

        $rowid = (int)$data['id'];
        $userid = (int)$userid;

        // Does this record belong to the user
        $builder = $this->getConsentFFTable();

        $check_row = $builder->where('user_id', $userid)->where('id', $rowid)->countAllResults();

        if ($this->doesRecordfBelongToUser($rowid, $userid, false) === false) {
            return "FAILED";
        } else {

            // If the applicant is logged in AND they are trying to delete a record they dont have access to we need to kill the script
            $pensionBuilder = $this->getConsentPensionsTable();
            if ($data["logged_in"] == 1) {
                // Check to see if the user is allowed to delete this row
                $check_row = $this->pensionBuilder->where('user_id', $userid)->where('id', $rowid)->where('petitioner_row', 0)->countAllResults();

                // We can't delete
                if ($check_row <= 0) {
                    return "SECERROR";
                }
            }

            // The record belongs to the user, now we try and delete
            $delete = $this->query("DELETE FROM consent_pensions WHERE user_id = " . $userid . " AND id = " . $rowid . " LIMIT 1");

            if ($delete) {
                $noLogBuilder = $this->getNoInfoLogTable();

                // We now need to remove any reference to the no info boxes
                $check_rows = $noLogBuilder->where('user_id', $userid)->where('pension_id', $rowid)->countAllResults();

                if ($check_rows > 0) {
                    $this->query("DELETE FROM consent_ff_no_info_log WHERE user_id = " . $userid . " AND pension_id = " . $rowid . " LIMIT 1");
                }


                // if the delete was successfull we need to reorder the property numbers incase the users removed record 2 of 3 for example
                $records = $pensionBuilder->where('user_id', $userid)->orderBy('pension_number', 'ASC')->get()->getResultArray();

                if (is_array($records) && count($records) > 0) {
                    $counter = 1;
                    foreach ($records as $r) {
                        // Set a new property number
                        $pensionBuilder->where("id", $r['id'])->where('user_id', $userid)->set(['pension_number' => $counter])->update();
                        $counter++;
                    }
                }

                // Success + set flash data
                session()->setFlashdata("SUCC", "Pension successfully removed");
                return "SUCCESS";
            } else {
                return "DATABASEERROR";
            }
        }
    }


    public function doesRecordfBelongToUser($rowid, $userid, $checkProperty)
    {
        if ($checkProperty === true) {
            $builder = $this->getConsentPropertiesTable();
            return ($builder->where('user_id', $userid)->where('id', $rowid)->countAllResults() > 0) ? true : false;
        } else {

            $builder = $this->getConsentPensionsTable();
            return ($builder->where('user_id', $userid)->where('id', $rowid)->countAllResults() > 0) ? true : false;
        }
    }


    public function doesQuestionBelongToUser($case_id, $questionID)
    {
        $builder = $this->getConsentOrderQuestionNotesTable();
        return ($builder->where('case_id', $case_id)->where('id', $questionID)->countAllResults() > 0) ? true : false;
    }


    public function getQuestionData($case_id, $questionID)
    {
        $builder = $this->getConsentOrderQuestionNotesTable();
        $res = $builder->select('id, note, user_completed_step')->where('case_id', $case_id)->where('id', $questionID)->get()->getRow();
        return [$res->note, ($res->user_completed_step === 1) ? true : false];
    }


    public function updateQuestionRow($questionID, $case_id, $data)
    {
        $builder = $this->getConsentOrderQuestionNotesTable();
        $builder->where('case_id', $case_id)->set($data)->where('id', $questionID)->update();
    }


    public function processCoFfAnswer($questionID, $data, $case_id)
    {

        // We now want to get the answer from the data array
        $answer = trim(htmlentities($data['client_response']));

        // We need to update the database with the response and mark the question as answered
        $up = [
            'client_response' => $answer,
            'completed' => 1,
            'user_completed_step' => 1,
            'user_completed_date' => date("Y-m-d H:i:s"),
            'completed_date' => date("Y-m-d H:i:s"),
            'completed_by' => 37
        ];

        $builder = $this->getConsentOrderQuestionNotesTable();
        $update = $builder->where("id", $questionID)->where('case_id', $case_id)->set($up)->update();
        if ($update) {
            // Send the required emails
            $this->markFfFNoteAsComplete($case_id, $questionID);
            return true;
        } else {
            return false;
        }
    }


    public function markFfFNoteAsComplete($case_id, $questionID)
    {
        $admin_id = 37; // system
        $user_id = session("user_id");

        $userBuilder = $this->getUserTable();
        $client_details = $userBuilder->select("UserEmail,Forename,CONCAT(Forename,' ',Surname) as full_name")->where('ID', $user_id)->get()->getRow();

        $questionnaireBuilder = $this->getConsentFFTable();
        $get_current_due_date = $questionnaireBuilder->select('due_date')->where('user_id', $user_id)->get()->getRow();

        $questionsBuilder = $this->getConsentOrderQuestionNotesTable();
        //get the note and date it was sent
        $note_details = $questionsBuilder->select('note,date_added,client_response')->where('id', $questionID)->get()->getRow();


        //get the email wrapper template
        $subject = "Case " . $case_id . " - Client has responded to your question.";

        $main_email_template = $this->getEmailWrapper($case_id);

        //str replace the details into placeholders
        $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);

        $msg = "
          This email is to inform you that the client has responded to your question that was sent on " . date('d-m-Y H:i:s', strtotime($note_details->date_added)) . ". <br /> <br />

          Client Case Number: <a href='" . base_url() . "cases/details/" . $case_id . "'>" . $case_id . "</a><br />
          Client Name: " . $client_details->full_name . "<br />
          Client Email: " . $client_details->UserEmail . "<br /><br />

          The question you asked the client is as follows:  <br /> <br />

          '" . $note_details->note . "'<br /> <br />

          The client has responded with the following information: <br /> <br />

          " . $note_details->client_response . "<br /> <br />

          Please login to case manager to review the changes.
          <br />
        ";

        $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
        $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

        $to = (ENVIRONMENT != "production") ? "ian.zammit@divorce-online.co.uk" :  "dolco@olssolicitors.co.uk";
        $this->sendEmail($to, $subject, $body);


        //add a case note
        $note = "Client has responded to a consent order question that raised on the " . date('d-m-Y H:i:s', strtotime($note_details->date_added)) . ". <br /><br /> The orginal question was: <b>" . $note_details->note . "</b>. <br /><br /> The client has responded with the following:<br /><br /> <b>" . $note_details->client_response . "</b> ";
        $this->addCaseNote($case_id, $note, null, 1);

        //we now need to see if the case can be removed from on hold
        $count_open_notes = $questionsBuilder->where('case_id', $case_id)->where('completed', 0)->countAllResults();
        if ($count_open_notes <= 0) {
            //take off hold
            $this->updateQuestionnaire($user_id, ['on_hold' => 0]);

            //if fast track update the cc_use_date field to use the current date
            $feesBuilder = $this->getFeesTable();
            $upd = $feesBuilder->where('fee_id', 74)
                ->where('case_id', $case_id)
                ->where('user_id', $user_id)
                ->set(['cc_use_date' => date('Y-m-d H:i:s')])
                ->update();

            //get the email wrapper template
            $subject = "Case " . $case_id . " - has answered all outstanding questions";
            $main_email_template = $this->getEmailWrapper($case_id);

            //str replace the details into placeholders
            $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);

            $msg = "This email is to inform you that case " . $case_id . " has now answered all outstanding questions and has now been moved from on hold to an active consent order.<br /><br />";

            $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
            $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

            $to = (ENVIRONMENT != "production") ? "ian.zammit@divorce-online.co.uk" :  "lucy.batstone@olssolicitors.co.uk";
            $this->sendEmail($to, $subject, $body);

            //add a case note
            $note = "Consent order case " . $case_id . " has been moved from on hold to an active consent order";
            $this->addCaseNote($case_id, $note, null, 1);

            //has this client fast tracked
            $has_fast_tracked = $this->isFastTrack($case_id, true);

            // We need to see if they purchased it as a service instead
            if ($has_fast_tracked === false) {
                $has_fast_tracked = $this->isFastTrack($case_id, false);
            }


            $due_date = $get_current_due_date->due_date;
            $question_created = $note_details->date_added;
            $do_date_increase = false; // This will be set to True if the client has taken over 48hrs

            // Workout if the client has respondent within 48hrs
            $current_date_time = date("Y-m-d H:i:s");
            $date1 = new \DateTime($question_created);
            $date2 = new \DateTime($current_date_time);

            $diff = $date2->diff($date1);

            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);
            $days_difference =  $diff->days;

            // the client has taken more than 48hrs to respond so we are going to increase there due_date by the amount of days extra they have taken
            if ($hours > 48) {
                $do_date_increase = true;
                // Workout how many days have passed since the last question was answered
                $due_date = $this->addBusinessDays($due_date, $days_difference);
                $this->sendOffHoldClientEmail($case_id, $due_date, $has_fast_tracked, $client_details, $do_date_increase, $user_id, $days_difference);
            } else {
                // We dont increase the due_date and send email
                $this->sendOffHoldClientEmail($case_id, $due_date, $has_fast_tracked, $client_details, $do_date_increase, $user_id);
            }
        }
    }


    public function sendOffHoldClientEmail($case_id, $due_date, $has_fast_tracked, $client_details, $do_date_increase, $user_id, $days_to_add = 0)
    {

        //Send email to the case manager that has been assigned the case
        $template_name = ($has_fast_tracked === false) ? "consent_set_back_standard" : "consent_set_back_ft";
        $email_details = $this->returnEmailTemplate($template_name);

        $subject = $email_details->subject;

        $main_body = $email_details->body;
        $msg = str_replace('{FIRST_NAME}', $client_details->Forename, $main_body);

        $due_date = date('l jS F Y', strtotime($due_date));
        $db_due_date = date("Y-m-d H:i:s", strtotime($due_date));
        $msg = str_replace('{DUE_DATE}', "<b>" . $due_date . "</b>", $msg);

        //get the email wrapper template
        $main_email_template = $this->getEmailWrapper($case_id);

        //str replace the details into placeholders
        $body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
        $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
        $body =  str_replace('{MAIN_CONTENT}', $msg, $body);

        $this->sendEmail($client_details->UserEmail, $subject, $body);
        if ($do_date_increase === true) {
            // We need to update the due date for this case as its changed becaused its been on hold
            $this->updateQuestionnaire($user_id, ['due_date' => $db_due_date]);
        }

        $note = "Consent order case " . $case_id . " has been reset for drafting. Email has been sent to client informing them of their new due date " . $due_date . ".";
        if (!empty($days_to_add) && $days_to_add > 0) {
            // The client has days added onto their consent order due date
            $note .= "<br />The system has added {$days_to_add} working days to the original due date to match the difference between when the question was asked and when the question was answered.<br />";
        }

        $this->addCaseNote($case_id, $note, null, 1);
    }


    public function addCaseNote($caseID, $note, $adminID = null, $addedBySystem = null)
    {
        $builder = $this->getCaseNotesTable();
        $builder->insert(['case_id' => $caseID, 'note' => $note, 'admin_id' => $adminID, 'added_by_system' => $addedBySystem]);
    }


    public function getUserIDFromCode($access_code, $email)
    {
        $builder = $this->getConsentFFTable();
        $getUserId = $builder->select('user_id')->where('respondent_separate_email', $email)->where('respondent_single_code', $access_code)->get()->getRow();
        return ($getUserId  !== null) ?  $getUserId->user_id : null;
    }


    public function tryRespondetLogin($data)
    {

        $email = trim($data['email']);
        $access_code = trim($data['access_code']);

        if ($email == "" || $access_code == "") {
            return "failed";
        }

        //get user ID based on the email
        $user_id = $this->getUserIDFromCode($access_code, $email);

        // User id not found
        if ($user_id === null) {
            return "failed";
        }

        //fetch email of the main client
        $userDetails = $this->getUserDetails($user_id);
        $main_client_email = $userDetails->UserEmail;

        //get the case ID
        $case_id = $this->returnCasesIdFromUserId($user_id);

        if ($case_id === null) {
            return "failed";
        }

        //check if the main account is active or not.
        $userEmail = $userDetails->UserEmail;

        $userBuilder = $this->getUserTable();
        $activeCheck = $userBuilder->where('UserEmail', $userEmail)->where('Validated', 1)->where('IsDeleted', 0)->countAllResults();

        if ($activeCheck == 1) {
            //see if the account is on hold or needs re activating
            $extra_status_checks = $this->query("
                                        SELECT c.case_on_hold,c.case_requires_reactivation
                                        FROM users AS u
                                        JOIN cases AS c ON u.ID=c.UserId
                                        WHERE u.UserEmail = '{$main_client_email}'
                                        AND u.IsDeleted = 0
                                        AND c.IsRefunded = 0
                                     ")->getResultArray();


            if ($extra_status_checks[0]['case_on_hold'] == "1") {
                return "on_hold";
            } elseif ($extra_status_checks[0]['case_requires_reactivation'] == "1") {
                return "requires_reactivation";
            }


            //the case is active so if $user_id is not null we know the details are correct
            if ($user_id === null) {
                return "login_failed";
            } else {
                //success
                session()->set('user_id', (int)$user_id);
                return "success";
            }
        } else {
            return "not_active";
        }
    }


    public function hasBeenInformed($access_code)
    {
        $builder = $this->getConsentFFTable();
        return ($builder->where('respondent_single_code', $access_code)->where('respondent_started', 1)->countAllResults() > 0) ? true : false;
    }


    public function getCaseDetails($user_id)
    {
        //get the case type (standard,non-standard,ddp/dpp)
        $get_case_details = $this->query("
            SELECT c.ID,c.TakenBy,c.PackageId,u.Forename,u.UserEmail
            FROM cases AS c
            JOIN users AS u ON c.UserID=u.ID
            WHERE c.UserId = " . (int)$user_id . "")->getRow();

        return [$get_case_details->ID, $get_case_details->UserEmail, $get_case_details->Forename];
    }

    public function returnRespondentDetails($user_id)
    {
        $builder = $this->getConsentFFTable();
        return $builder->select('res_forename')->where('user_id', $user_id)->get()->getRow();
    }



    public function isManaged($case_id)
    {

        //get the package id
        $package_id = $this->select('PackageId')->where('ID', $case_id)->get()->getRow();

        //we need to check to see if there is an active upgrade
        $upgradeBuilder = $this->getUpgradeTable();
        $is_upgraded = $upgradeBuilder->select('package_upgrade_code')
            ->where('case_id', $case_id)
            ->where('upgrade_status', 1)
            ->limit(1)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $packageBuilder = $this->getPackageTable();
        if (is_array($is_upgraded) && count($is_upgraded) > 0) {

            if ($is_upgraded && $is_upgraded[0]['package_upgrade_code'] == "DDPTODPPCO") {
                // Set the package code to DDP249TODPPCO
                $is_upgraded[0]['package_upgrade_code'] = "DDP249TODPPCO";
            }

            $package_id = $packageBuilder->select('ID')->where('PackageCode', $is_upgraded[0]['package_upgrade_code'])->get()->getRow();
            $is_managed = $packageBuilder->select('is_managed')->where('ID', $package_id->ID)->get()->getRow();
            return ($is_managed->is_managed == 1) ? true : false;
        } else {
            //check and see if the package is solicitor managed
            $is_managed = $packageBuilder->select('is_managed')->where('ID', $package_id->PackageId)->get()->getRow();
            return ($is_managed->is_managed == 1) ? true : false;
        }
    }


    public function isSolicitorManaged($case_id)
    {
        //get the package id
        $package_id = $this->select('PackageId')->where('ID', $case_id)->get()->getRow();

        if (!$package_id) {
            return false;
        }

        //we need to check to see if there is an active upgrade
        $upgradeBuilder = $this->getUpgradeTable();
        $is_upgraded = $upgradeBuilder->select('package_upgrade_code')
            ->where('case_id', $case_id)
            ->where('upgrade_status', 1)
            ->limit(1)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();


        $packageBuilder = $this->getPackageTable();
        if (is_array($is_upgraded) && count($is_upgraded) > 0) {

            if ($is_upgraded && $is_upgraded[0]['package_upgrade_code'] == "DDPTODPPCO") {
                // Set the package code to DDP249TODPPCO
                $is_upgraded[0]['package_upgrade_code'] = "DDP249TODPPCO";
            }

            $package_id = $packageBuilder->select('ID')->where('PackageCode', $is_upgraded[0]['package_upgrade_code'])->where('active', 1)->get()->getRow();

            // Check that we have a package ID, if we dont return false
            if ($package_id === null) {
                return false;
            }

            $is_managed = $packageBuilder->select('solicitor_managed')->where('ID', $package_id->ID)->get()->getRow();
            return ($is_managed && $is_managed->solicitor_managed == 1) ? true : false;
        } else {
            //check and see if the package is solicitor managed
            $is_managed = $packageBuilder->select('solicitor_managed')->where('ID', $package_id->PackageId)->where('active', 1)->get()->getRow();
            return ($is_managed && $is_managed->solicitor_managed == 1) ? true : false;
        }
    }



    public function checkForConsentQuestions($case_id)
    {
        $builder = $this->getConsentOrderQuestionNotesTable();
        return $builder->where('case_id', $case_id)->where('completed', 0)->countAllResults();
    }

    public function getOrderDate($case_id)
    {
        $orderDate = $this->select('OrderPlaced')->where('ID', $case_id)->get()->getRow();
        return ($orderDate != null) ? $orderDate->OrderPlaced : null;
    }


    public function returnAllNotifications($user_id, $case_id)
    {

        // Is this a consent, if so a standalone or managed
        $is_consent_order = false;
        $consent_type = "";
        $consent_check = $this->select('packages.consent_order_type')
            ->join('packages', 'cases.PackageId=packages.ID')
            ->where('cases.ID', $case_id)
            ->get()
            ->getRow();

        $is_consent_order = ($consent_check->consent_order_type == 1 || $consent_check->consent_order_type == 2) ? true : false;

        //Check if we are dealing with a portal client
        $builder = $this->dtrackDB->table("questionnaire");
        $check_is_portal = $builder->select('portal_case')->where('user_id', $user_id)->get()->getRow();
        $use_portal = false;

        if ($check_is_portal != NULL && $check_is_portal->portal_case == 1) {
            $use_portal = true;
        }

        if ($use_portal == true) {
            $query = $this->query("
                        SELECT sm.step,sm.notification_wording,smb.date_added,smb.id
                        FROM portal_step_manager_bridge AS smb
                        JOIN portal_step_manager AS sm ON smb.step_id=sm.id
                        WHERE smb.case_id = " . $case_id . "
                        AND smb.undone = 0
                        AND sm.id NOT IN(2,3)
                        AND sm.action = 'Notification Email Sent'
                        ORDER BY smb.id DESC
                        ")->getResultArray();
        }

        if ($is_consent_order == false && $use_portal == false) {
            // This is for the older cases that aren't portal cases
            $query = $this->query("
                    SELECT sm.step,sm.notification_wording,smb.date_added,smb.id
                    FROM step_manager_bridge AS smb
                    JOIN step_manager AS sm ON smb.step_id=sm.id
                    WHERE smb.case_id = " . $case_id . "
                    AND smb.undone = 0
                    AND sm.id NOT IN(2,3)
                    AND sm.action = 'Notification Email Sent'
                    ORDER BY smb.id DESC
                    ")->getResultArray();
        }

        if ($is_consent_order == true) {
            $consent_type = ($consent_check->consent_order_type == 1) ? "standalone" : "managed";
            $steps_db = ($consent_type == "standalone") ? "step_manager_consent_standalone" : "step_manager";
            $steps_bridge_db = ($consent_type == "standalone") ? "step_manager_cc_standalone_bridge" : "step_manager_bridge";
            $consent_ff_package_code = ($consent_type == "standalone") ? 74 : 264;
            $and_statement = ($consent_type == "standalone") ? "" : "AND sm.id NOT IN(2,3)";

            // This is for the older cases that aren't portal cases
            $query = $this->query("
                    SELECT sm.step,sm.notification_wording,smb.date_added,smb.id,smb.step_id, smb.date_completed
                    FROM " . $steps_bridge_db . " AS smb
                    JOIN " . $steps_db . " AS sm ON smb.step_id=sm.id
                    WHERE smb.case_id = " . $case_id . "
                    AND smb.undone = 0
                    " . $and_statement . "
                    AND sm.action = 'Notification Email Sent'
                    ORDER BY smb.id DESC
                    ")->getResultArray();

            // Loop the array and replace the place hodlers
            if (is_array($query) && $query > 0) {
                foreach ($query as $key => $q) {
                    $has_fast_track = $this->isFastTrack($case_id, true);
                    if ($has_fast_track === false) {
                        // try the add service
                        $has_fast_track = $this->isFastTrack($case_id, false);
                    }

                    // get the original OrderPlaced date from the cases table
                    $order_placed_date = $this->select('OrderPlaced')->where('ID', $case_id)->get()->getRow();
                    $order_placed_date = ($order_placed_date) ? $order_placed_date->OrderPlaced : "";

                    // Set a default 
                    $working_days = ($has_fast_track == true) ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : getenv("CONSENT_ORDER_DUE_DAYS_STANDARD");

                    // fetch the completed date
                    $builder = $this->getConsentFFTable();
                    $completed_date = $builder->select('date_completed')->where('user_id', $user_id)->get()->getRow();
                    $completed_date = ($completed_date) ? $completed_date->date_completed : "";

                    // If the date completed is before the 12'th Feb 2025 then we want to use the 30 days instead of the ENV value
                    $dymanicDateSwitch = "2025-02-12 00:00:00";
                    $secondChange = "2025-05-20 00:00:00";

                    // If the date is between $secondChange and $thirdChange then we want to use the 14 days instead of the ENV value
                    $thirdChange = "2025-06-30 23:59:59";

                    // if we have a date to check
                    if ($order_placed_date != "") {
                        if (($consent_type == "standalone" && $q['step_id'] == 2 && $order_placed_date <= $dymanicDateSwitch)
                            || ($consent_type == "managed" && $q['step_id'] == 27 && $order_placed_date <= $dymanicDateSwitch)
                        ) {
                            $working_days = $has_fast_track ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : 31;
                        } else if (($consent_type == "standalone" && $q['step_id'] == 2 && $order_placed_date > $dymanicDateSwitch && $order_placed_date <= $secondChange)
                            || ($consent_type == "managed" && $q['step_id'] == 27 && $order_placed_date > $dymanicDateSwitch && $order_placed_date <= $secondChange)
                        ) {
                            $working_days = $has_fast_track ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : 28;
                        } else if (($consent_type == "standalone" && $q['step_id'] == 2 && $order_placed_date > $secondChange && $order_placed_date <= $thirdChange)
                            || ($consent_type == "managed" && $q['step_id'] == 27 && $order_placed_date > $secondChange && $order_placed_date <= $thirdChange)
                        ) {
                            $working_days = $has_fast_track ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : 14;
                        } else {
                            $working_days = ($has_fast_track == true) ? getenv("CONSENT_ORDER_DUE_DAYS_FASTTRACK") : getenv("CONSENT_ORDER_DUE_DAYS_STANDARD");
                        }
                    }

                    if ($completed_date != "") {
                        $date_to_format = date("Y-m-d", strtotime($completed_date));
                        $date_to_format =  $this->addBusinessDays($date_to_format, $working_days);
                        $db_due_date = date('dS F Y', strtotime($date_to_format));
                        $calculated_date = $db_due_date;
                    } else {
                        $calculated_date = "";
                    }

                    $notification_wording = $q['notification_wording'];
                    $download_link = "<a href='" . base_url() . "consent-order/consent_order_docs'>click here</a>";
                    $replaced_string1 = str_replace(array("{NUMBER_OF_WORKING_DAYS}", "{CAL_DATE}", "{DOCUMENT_LINK}", "{CONSENT_DOCS_LINK}"), array($working_days, $calculated_date, $download_link, $download_link), $notification_wording);
                    $query[$key]["notification_wording"] = $replaced_string1;
                }
            }
        }
        return $query;
    }

    public function checkStepComplete($case_id, $step_id)
    {
        $builder = $this->getStepManagerBridgeTable();
        return ($builder->where('step_id', $step_id)->where('case_id', $case_id)->where('undone', 0)->countAllResults() > 0) ? true : false;
    }


    public function getCustomDocuments($case_id)
    {

        //get the documents
        $builder = $this->getCustomDocsDB();
        return $builder->where('case_id', $case_id)
            ->where('show_document', 1)
            ->where('sys_document', 0)
            ->where('document_status', 1)
            ->get()
            ->getResultArray();
    }


    public function checkForD81($case_id)
    {
        $builder = $this->getCustomDocsDB();
        $res = $builder->select('uuid')
            ->where('case_id', $case_id)
            ->where('show_document_d8', 1)
            ->where('document_status', 1)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get()
            ->getRow();

        return ($res && $res->uuid != "") ? $res->uuid : "";
    }


    public function checkForCO($case_id)
    {
        $builder = $this->getCustomDocsDB();
        $res = $builder->select('uuid')
            ->where('case_id', $case_id)
            ->where('show_document_consent_order', 1)
            ->where('document_status', 1)
            ->get()
            ->getRow();

        return ($res && $res->uuid != "") ? $res->uuid : "";
    }

    public function checkForChargeDoc($case_id)
    {
        $builder = $this->getCustomDocsDB();
        $res = $builder->select('uuid')
            ->where('case_id', $case_id)
            ->where('show_document_charge_order', 1)
            ->where('document_status', 1)
            ->get()
            ->getRow();

        return ($res && $res->uuid != "") ? $res->uuid : "";
    }


    public function getCountryName($countryID)
    {
        $builder = $this->getCountryTable();
        $row = $builder->select('country')->where('id', $countryID)->get()->getRow();
        return ($row) ? $row->country : "NOT SET";
    }


    public function deleteTempFileHash($user_id)
    {
        $builder = $this->getFileTempCheckTable();
        $builder->where('user_id', $user_id)->delete();
    }


    public function addTempFileHash($user_id, $random_hash, $uuid)
    {
        $insert = [
            'user_id' => $user_id,
            'hash' => $random_hash,
            'uuid' => $uuid
        ];

        $builder = $this->getFileTempCheckTable();
        $builder->insert($insert);
    }



    public function isConsentOrderFFComplete($user_id)
    {
        $builder = $this->getConsentFFTable();
        return ($builder->where('complete', 1)->where('user_id', $user_id)->countAllResults() > 0) ? true : false;
    }


    public function checkIfPackageContainsFastTrack($user_id)
    {
        // Get the package ID for the user
        $packageDetails = $this->getUserPackageDetails($user_id);
        if (!$packageDetails || !isset($packageDetails->PackageId)) {
            return false;
        }
        $packageID = $packageDetails->PackageId;

        // We now want to see IF this package contains the fast track service
        $builder = $this->getPackageTable();
        $res = $builder->select('has_fasttrack')
            ->where('ID', $packageID)
            ->where('active', 1)
            ->get()
            ->getRow();

        // If the we have a result and has_fasttrack is 0, then we want to check for upgrades true
        if ($res && $res->has_fasttrack == 0) {
            // Fetch the case ID
            $case_id = $this->db->table("cases")->select('ID')->where('UserId', $user_id)->get()->getRow()->ID;

            // We now want to look for upgrades that match the case_id and the upgrade status = 1
            // We need to join to the packages table and see if the package has_fasttrack is 1
            $builder = $this->getUpgradeTable();
            $upgrades = $builder->join('packages', 'upgrades.product_to = packages.ID')
                ->where('upgrades.case_id', $case_id)
                ->where('upgrades.upgrade_status', 1)
                ->where('packages.has_fasttrack', 1)
                ->get()
                ->getResultArray();

            if (is_array($upgrades) && count($upgrades) > 0) {
                foreach ($upgrades as $upgrade) {
                    if ($upgrade['has_fasttrack'] == 1) {
                        return true;
                    }
                }
            }
        }

        // If we have a result AND has_fasttrack is 1, then we return true
        if ($res && $res->has_fasttrack == 1) {
            return true;
        }

        // Safety net
        return false;
    }

    public function checkPreviousPage($user_id, $page, $res_logged_in, $package_code = "")
    {
        $builder = $this->getConsentFFTable();
        // Is the a joint application i.e. the applicant and respondent will fill out separately
        $hasContact = ($builder->select('has_contact')->where('user_id', $user_id)->get()->getRow()->has_contact == 1) ? true : false;

        if ($res_logged_in == 1) {
            switch ($page) {
                case 3:
                    $redirPage = 2;
                    // Only check the applicant because they can only see THEIR data
                    $res = $this->checkHasFieldBeenCompleted($user_id, "res_owned_type");
                    return [$res, $redirPage];
                    break;

                case 4:
                case 7:
                    $redirPage = 3;
                    $fieldChecks = [
                        $this->checkHasFieldBeenCompleted($user_id, "legal_res_add")
                    ];

                    // Check if D81 file exists
                    $d81File = ROOTPATH . "questionnaire_files/consent_order/D81_" . $user_id . "_respondent.pdf";
                    $d81FileExists = file_exists($d81File);
                    $fieldChecks[] = $d81FileExists;

                    // If ANY check returns false, $res will be false
                    $res = !in_array(false, $fieldChecks);
                    return [$res, $redirPage];
                    break;
            }
        } else {

            // If $hasContact is false and the respondent ISN't logged in then we need to check just for the applicant fields
            // If $hasContact is true and the respondent ISN't logged in then we check the last field of the page
            switch ($page) {
                case 3:
                    $redirPage = 2;

                    // We can ignore both app_owned_type and app_owned_type if CBCS package
                    if (! empty($package_code) && in_array($package_code, $this->codesToCheck)) {
                        return [true, $redirPage];
                    }

                    if ($hasContact == false) {
                        // Only check the applicant because they can only see THEIR data
                        $res = $this->checkHasFieldBeenCompleted($user_id, "app_owned_type");
                    } else {
                        $fieldChecks = [
                            $this->checkHasFieldBeenCompleted($user_id, "app_owned_type"),
                            $this->checkHasFieldBeenCompleted($user_id, "res_owned_type")
                        ];

                        // If ANY check returns false, $res will be false
                        $res = !in_array(false, $fieldChecks);
                    }

                    return [$res, $redirPage];
                    break;

                case 4:
                case 7:

                    $redirPage = 3;

                    // legal_app_add and legal_res_add are not required for CBCS package
                    if (! empty($package_code) && in_array($package_code, $this->codesToCheck)) {
                        // return true and the redirect page
                        return [true, $redirPage];
                    }


                    if ($hasContact == false) {
                        $fieldChecks = [
                            $this->checkHasFieldBeenCompleted($user_id, "legal_app_add")
                        ];

                        // Check if D81 file exists
                        $d81File = ROOTPATH . "questionnaire_files/consent_order/D81_" . $user_id . ".pdf";
                        $d81FileExists = file_exists($d81File);
                        $fieldChecks[] = $d81FileExists;

                        // If ANY check returns false, $res will be false
                        $res = !in_array(false, $fieldChecks);
                    } else {

                        $fieldChecks = [
                            $this->checkHasFieldBeenCompleted($user_id, "legal_res_add"),
                            $this->checkHasFieldBeenCompleted($user_id, "legal_app_add")
                        ];

                        // Check if D81 file exists
                        $d81File = ROOTPATH . "questionnaire_files/consent_order/D81_" . $user_id . ".pdf";
                        $d81FileExists = file_exists($d81File);
                        $fieldChecks[] = $d81FileExists;

                        // If ANY check returns false, $res will be false
                        $res = !in_array(false, $fieldChecks);
                    }

                    return [$res, $redirPage];
                    break;
            }
        }
    }

    public function checkHasFieldBeenCompleted($user_id, $field)
    {
        $builder = $this->getConsentFFTable();
        $result = $builder->select($field)->where('user_id', $user_id)->get()->getRow();
        return ($result && $result->$field !== null) ? true : false;
    }


    public function logDueDate($db_due_date, $future_date, $case_id)
    {
        // We need to format future date the same as db_due date Y-m-d 00:00:00
        $future_date = date('Y-m-d 00:00:00', strtotime($future_date));

        // Check we have a valid date for both, if not return false
        if (empty($db_due_date) || empty($future_date)) {
            return false;
        }

        // If the dates are different an email needs to be sent to stuart.may@divorce-online.co.uk and ian.zammit@divorce-online.co.uk
        if ($future_date != $db_due_date) {
            $to = (getenv("CI_ENVIRONMENT") == "production") ? "stuart.may@divorce-online.co.uk, ian.zammit@divorce-online.co.uk" : "ian.zammit@divorce-online.co.uk";
            $this->sendEmail(
                $to,
                "URGENT: Consent Order Due Date Mismatch",
                "Hi, <br /><br />The due date in the consent order email that has been sent to the client is
             " . date('d-m-Y 00:00:00', strtotime($future_date)) . " but the database has " . date('d-m-Y 00:00:00', strtotime($db_due_date)) . ".<br /><br />Case ID: " . $case_id .
                    "<br /><br />Thanks"
            );
        }
    }

    // fetch data for the CBCS consent order package
    public function fetchCBCSData($user_id)
    {
        // If no user id return false
        if (empty($user_id) || $user_id != session()->get('user_id')) {
            return false;
        }

        // from the consent_ff_questionnaire table get the following data
        // app_forename, app_surname, app_other_names, case_number, res_forename, res_surname, res_other_names
        $builder = $this->getConsentFFTable();
        $data = $builder->select('app_forename, app_surname, app_other_names, case_number, res_forename, res_surname, res_other_names, app_gender, res_gender')
            ->where('user_id', $user_id)
            ->get()
            ->getRow();

        return ($data) ? $data : false;
    }


    ####################################
    ########### DATABASES ##############
    ####################################

    public function getCaseNotesTable()
    {
        $db = db_connect();
        return $db->table("case_notes");
    }

    public function getConsentOrderQuestionNotesTable()
    {
        $db = db_connect();
        return $db->table("consent_ff_questionnaire_notes");
    }


    public function getStepManagerStandAloneTable()
    {
        $db = db_connect();
        return $db->table("step_manager_consent_standalone");
    }

    public function getStepManagerBridgeTable()
    {
        $db = db_connect();
        return $db->table("step_manager_bridge");
    }

    public function getStepManagerTable()
    {
        $db = db_connect();
        return $db->table("step_manager");
    }


    public function getCCStandAloneTable()
    {
        $db = db_connect();
        return $db->table("step_manager_cc_standalone_bridge");
    }


    public function getUserTable()
    {
        $db = db_connect();
        return $db->table("users");
    }

    public function getServicesTable()
    {
        $db = db_connect();
        return $db->table("additional_service_paid");
    }

    public function getFeesTable()
    {
        $db = db_connect();
        return $db->table("fees_paid");
    }


    public function getConsentPropertiesTable()
    {
        $db = db_connect();
        return $db->table("consent_order_properties");
    }

    public function getCountryTable()
    {
        $db = db_connect();
        return $db->table("country");
    }

    public function getConsentFFTable()
    {
        $db = db_connect();
        return $db->table("consent_ff_questionnaire");
    }

    public function getNoInfoLogTable()
    {
        $db = db_connect();
        return $db->table("consent_ff_no_info_log");
    }

    public function getConsentPensionsTable()
    {
        $db = db_connect();
        return $db->table("consent_pensions");
    }

    public function getPackageTable()
    {
        $db = db_connect();
        return $db->table("packages");
    }

    public function getCustomDocsDB()
    {
        $db = db_connect();
        return $db->table("custom_documents");
    }

    public function getAdminUserTable()
    {
        $db = $this->adminDB;
        return $db->table("users");
    }

    public function getUpgradeTable()
    {
        $db = db_connect();
        return $db->table("upgrades");
    }

    public function getP1DocumentTable()
    {
        $db = db_connect();
        return $db->table("document_p1");
    }

    public function getFileTempCheckTable()
    {
        $db = db_connect();
        return $db->table("tmp_file_check");
    }
}
