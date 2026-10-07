<?php

namespace App\Models;

use Config\Encryption;
use Config\Services;
use App\Libraries\MicrosoftGraphMail;

class AccountModel extends \CodeIgniter\Model
{
    protected $table = 'cases';
    protected $dtrackDB = "";
    protected $adminDB = "";
    protected $allowedFields = [''];

    protected $useTimestamps = true;

    protected $validationRules = [];
    protected $validationMessages = [];

    protected $beforeInsert = [""];
    protected $beforeUpdate = [""];
    protected $jointAppProducts;
    protected $imageURL;

    protected $myAccountRules = [
        "UserEmail" => ["label" => "Email address", "rules" => "required|valid_email|checkEmail"],
        "RespondentEmail" => ["label" => "Email address", "rules" => "required|valid_email"],
        "Forename" => ["label" => "Forename", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
        "Surname" => ["label" => "Surname", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
        'Address1' => ["label" => "Address Line 1", "rules" => "required|min_length[5]|trim"],
        'Town' => ["label" => "Town/City", "rules" => "required|min_length[2]|trim"],
        'Postcode' => ["label" => "Postcode", "rules" => "required|min_length[4]|trim"],
        'CountryId' => ["label" => "Country", "rules" => "required"],
        'Dob' => ["label" => "Date Of Birth", "rules" => "required|regex_match[/^[0-9]{2}-{1}[0-9]{2}-{1}[0-9]{4}$/]"],
        'SecertQuestion' => ["label" => "Secret Question", "rules" => "required"],
        'SecertAnswer' => ["label" => "Secret Answer", "rules" => "required"],
        'password' => ["label" => "Password", "rules" => "required|doPasswordCheck"],
        'conf_password' => ["label" => "Password Confirmation", "rules" => "required|matches[password]|doPasswordCheck"]
    ];

    protected $myAccountRules_errors = [
        'UserEmail' => ['required' => 'Email address is required', 'valid_email' => 'Please enter a valid email'],
        'RespondentEmail' => ['required' => 'Respondent Email address is required', 'valid_email' => 'Please enter a valid email'],
        'Forename' => ['required' => 'Forename is required', 'regex_match' => 'Please enter a valid forename'],
        "Surname" => ['required' => 'Surname is required', 'regex_match' => 'Please enter a surname'],
        'Address1' => ['required' => 'Address line 1 is required', 'min_length' => 'Please enter the first line of your address'],
        'Town' => ['required' => 'Town/City is required', 'min_length' => 'Please enter the town/city that you live in'],
        'Postcode' => ['required' => 'Postcode is required', 'min_length' => 'Postcode is required and must be valid'],
        'CountryId' => ['required' => 'Please select the country you live in'],
        'Dob' => ['required' => 'Date of birth is required', 'regex_match' => 'Please enter a valid date of birth'],
        'SecertQuestion' => ['required' => 'Secret question is required'],
        'SecertAnswer' => ['required' => 'Secret answer is required'],
        'password' => ['required' => 'Please enter a password'],
        'conf_password' => ['required' => 'Please confirm your password', 'matches' => "Password's don't match, please check both passwords and try again"]
    ];


    protected $UpgradePaymentRules = [
        "upgrade_package" => ["label" => "Upgrade package", "rules" => "required|trim"]
    ];
    protected $UpgradePaymentRules_errors = [
        'upgrade_package' => ['required' => 'You must select a package']
    ];


    protected $updateDetailsFirstTime = [
        "password" => ["label" => "password", "rules" => "required|doPasswordCheck"],
        "conf_password" => ["label" => "Password Confirmation", "rules" => "required|doPasswordCheck|matches[password]"],
        "Dob" => ["label" => "Date of birth", "rules" => "required|checkOver16|regex_match[/^[0-9]{2}-{1}[0-9]{2}-{1}[0-9]{4}$/]|checkDateInFuture"],
        "SecertQuestion" => ["label" => "Secert Question", "rules" => "required"],
        "SecertAnswer" => ["label" => "Secert Answer", "rules" => "required"]
    ];

    protected $updateDetailsFirstTime_errors = [
        'password' => ['required' => 'Please enter a password'],
        'conf_password' => ['required' => 'Please confirm your password', 'matches' => "Password's don't match, please check both passwords and try again"],
        'Dob' => ['required' => 'Please enter your date of birth', 'regex_match' => 'Please enter a valid date of birth'],
        "SecertQuestion" => ["required" => "Please select a secret question from the suppiled list"],
        "SecertAnswer" => ["required" => "Please enter the answer to your secret question"]
    ];


    public function __construct()
    {
        $this->dtrackDB = db_connect('dt');
        $this->adminDB = db_connect("am");
        $this->jointAppProducts = ["DDPJ"];
        $this->imageURL = site_url() . "images/";
    }


    public function getOrderPlaced($case_id)
    {
        $row = $this->select('OrderPlaced')->where('ID', $case_id)->get()->getRow();
        return $row->OrderPlaced;
    }

    public function checkEmail($str)
    {

        $userid = session("user_id");

        //we need to get the current email address, if it matches the email we have now then we dont need to run the check and we just return TRUE as it hasn't changed
        $builder = $this->getUserTable();
        $current_email = $builder->select('UserEmail')->where('ID', $userid)->get()->getRow();

        // The email hasn't changed so we can ignore...
        if ($current_email->UserEmail == trim($str)) {
            return TRUE;
        } else {
            //email address has changed so we need to check this..
            $does_email_exist = $builder->where('Validated', 1)
                ->where('IsDeleted', 0)
                ->where('UserEmail', trim($str))
                ->countAllResults();

            if ($does_email_exist == 1) {
                return FALSE;
            } else {
                return TRUE; //email not found;
            }
        }
    }


    public function getOriginalPackageData($user_id)
    {
        $row = $this->select('cases.ID,cases.orderPlaced,packages.packageName,packages.DigitalPrice')
            ->join('packages', 'cases.PackageId=packages.ID')
            ->where('cases.UserId', $user_id)
            ->get()
            ->getRow();

        return ($row) ? $row : [];
    }


    public function getAllPaidUpgrades($user_id)
    {

        // Get the case ID
        $case_id = $this->returnCaseIDFromUserId($user_id);

        $builder = $this->getUpgradesTable();
        $res = $builder->select('packages.DigitalPrice,packages.PackageName,upgrades.date_added, upgrades.price_paid')
            ->join('packages', 'upgrades.product_to=packages.ID')
            ->where('upgrades.case_id', $case_id)
            ->orderBy('upgrades.date_added')
            ->get()
            ->getResultArray();

        return (is_array($res) && count($res) > 0) ? $res : [];
    }

    public function getAllPaidFees($user_id)
    {
        $builder = $this->getFeesTable();
        $res = $builder->select('fees_paid.fee_id,fees_paid.date_added,fees_paid.merchant_id, fees_paid.price_paid, packages.PackageCode,packages.PackageName,packages.DigitalPrice')
            ->join('packages', 'fees_paid.fee_id=packages.ID')
            ->where('fees_paid.user_id', $user_id)
            ->orderBy('fees_paid.date_added')
            ->get()
            ->getResultArray();

        return (is_array($res) && count($res) > 0) ? $res : [];
    }


    public function getAllPaidServices($user_id)
    {
        $builder = $this->getServicesTable();
        $res = $builder->select('additional_service_paid.date_added,additional_service_paid.merchant_id, additional_service_paid.price_paid, packages.PackageCode,packages.PackageName,packages.DigitalPrice')
            ->join('packages', 'additional_service_paid.fee_id=packages.ID')
            ->where('additional_service_paid.user_id', $user_id)
            ->where('additional_service_paid.deleted', 0)
            ->orderBy('additional_service_paid.date_added')
            ->get()
            ->getResultArray();

        return (is_array($res) && count($res) > 0) ? $res : [];
    }



    public function checkPaymentStep($user_id)
    {
        $case_id = $this->returnCaseIDFromUserId($user_id);
        $builder = $this->getPortalStepsManagerBridgeTable();
        $check_completed_step = $builder->where('case_id', $case_id)
            ->where('step_id', 7)
            ->where('undone', 0)
            ->where('status', 1)
            ->countAllResults();

        if ($check_completed_step > 0) {

            // We now want to get the completed date
            $completed_date = $builder->select('date_added')
                ->where('case_id', $case_id)
                ->where('step_id', 7)
                ->where('undone', 0)
                ->where('status', 1)
                ->get()
                ->getRow();

            return date("d-m-Y H:i", strtotime($completed_date->date_added));
        } else {
            return false;
        }
    }



    public function updateAccountDetails($data)
    {
        $userid = session("user_id");
        $config = $this->getEncryptionConfig();
        $encrypter = Services::encrypter($config, false);

        //get the secret question from the database based on the post value
        $builder = $this->getSecretQuestionTable();
        $posted_squestion = $builder->select('question')->where('id', trim(htmlentities($data['SecertQuestion'])))->get()->getRow();

        //get the case id from the database
        $case_id = $this->returnCaseIDFromUserId($userid);


        // Set the clients contact preferences
        $data['email_optin'] = (array_key_exists("email_optin", $data)) ? $data['email_optin']  : 0;
        $data['sms_optin'] = (array_key_exists("sms_optin", $data)) ? $data['sms_optin']  : 0;
        $data['phone_optin'] = (array_key_exists("phone_optin", $data)) ? $data['phone_optin']  : 0;

        $data['Dob'] = date("Y-m-d", strtotime($data['Dob']));

        //update marketing preferences
        $builder = $this->getUserTable();
        $marketing_update = $builder->where('ID', $userid)->update(
            [
                'email_optin' => $data['email_optin'],
                'sms_optin' => $data['sms_optin'],
                'phone_optin' => $data['phone_optin']
            ]
        );

        $current_details = $this->query("
            SELECT u.*,sq.question
            FROM users AS u
            LEFT JOIN security_questions AS sq ON u.SecertQuestion=sq.id
            WHERE u.ID = " . $userid . "")
            ->getRow();

        // We need to get the package ID
        $packageData = $this->getPackageData($userid);
        $isSplitApp = ($packageData && in_array($packageData->PackageCode, $this->jointAppProducts)) ? true : false;

        $status = array( // 0 meaning unchanged
            'UserEmail' => array('current_value' => $current_details->UserEmail, 'changed' => 0, 'new_value' => ''),
            'TelNum' => array('current_value' => $current_details->TelNum, 'changed' => 0, 'new_value' => ''),
            'Forename' => array('current_value' => $current_details->Forename, 'changed' => 0, 'new_value' => ''),
            'Othernames' => array('current_value' => $current_details->Othernames, 'changed' => 0, 'new_value' => ''),
            'Surname' => array('current_value' => $current_details->Surname, 'changed' => 0, 'new_value' => ''),
            'Address1' => array('current_value' => $current_details->Address1, 'changed' => 0, 'new_value' => ''),
            'Address2' => array('current_value' => $current_details->Address2, 'changed' => 0, 'new_value' => ''),
            'Address3' => array('current_value' => $current_details->Address3, 'changed' => 0, 'new_value' => ''),
            'Town' => array('current_value' => $current_details->Town, 'changed' => 0, 'new_value' => ''),
            'Postcode' => array('current_value' => $current_details->Postcode, 'changed' => 0, 'new_value' => ''),
            'County' => array('current_value' => $current_details->County, 'changed' => 0, 'new_value' => ''),
            'CountryId' => array('current_value' => $current_details->CountryId, 'changed' => 0, 'new_value' => ''),
            'Dob' => array('current_value' => $current_details->Dob, 'changed' => 0, 'new_value' => ''),
            'SecertQuestion' => array('current_value' => $current_details->question, 'changed' => 0, 'new_value' => ''),
            'SecertAnswer' => array('current_value' => $current_details->SecertAnswer, 'changed' => 0, 'new_value' => '--encryted--'),
        );

        // if split application we want to include the new respondent email
        if ($isSplitApp === true) {
            $status['RespondentEmail'] = array('current_value' => $current_details->RespondentEmail, 'changed' => 0, 'new_value' => '');
        }


        // has the data changed?
        $data_changed = FALSE;

        //we now want to see if the values match
        // if a split application the user email address needs to be rebuild to match the pattern like example@hotmail.com, example2@hotmail.com
        if ($isSplitApp === true) {
            $data['UserEmail'] = trim($data['UserEmail']) . ", " . trim($data['RespondentEmail']);
        }


        if ($status['UserEmail']['current_value'] != trim($data['UserEmail'])) {
            $status['UserEmail']['changed'] = 1;
            $status['UserEmail']['new_value'] = trim($data['UserEmail']);
            $data_changed = TRUE;
        }

        if ($isSplitApp === true) {
            if ($status['RespondentEmail']['current_value'] != trim($data['RespondentEmail'])) {
                $status['RespondentEmail']['changed'] = 1;
                $status['RespondentEmail']['new_value'] = trim($data['RespondentEmail']);
                $data_changed = TRUE;
            }
        }

        if ($status['TelNum']['current_value'] != trim($data['TelNum'])) {
            $status['TelNum']['changed'] = 1;
            $status['TelNum']['new_value'] = trim($data['TelNum']);
            $data_changed = TRUE;
        }

        if ($status['Forename']['current_value'] != trim($data['Forename'])) {
            $status['Forename']['changed'] = 1;
            $status['Forename']['new_value'] = trim($data['Forename']);
            $data_changed = TRUE;
        }

        if ($status['Othernames']['current_value'] != trim($data['Othernames'])) {
            $status['Othernames']['changed'] = 1;
            $status['Othernames']['new_value'] = trim($data['Othernames']);
            $data_changed = TRUE;
        }

        if ($status['Surname']['current_value'] != trim($data['Surname'])) {
            $status['Surname']['changed'] = 1;
            $status['Surname']['new_value'] = trim($data['Surname']);
            $data_changed = TRUE;
        }

        if ($status['Address1']['current_value'] != trim($data['Address1'])) {
            $status['Address1']['changed'] = 1;
            $status['Address1']['new_value'] = trim($data['Address1']);
            $data_changed = TRUE;
        }

        if ($status['Address2']['current_value'] != trim($data['Address2'])) {
            $status['Address2']['changed'] = 1;
            $status['Address2']['new_value'] = trim($data['Address2']);
            $data_changed = TRUE;
        }

        if ($status['Address3']['current_value'] != trim($data['Address3'])) {
            $status['Address3']['changed'] = 1;
            $status['Address3']['new_value'] = trim($data['Address3']);
            $data_changed = TRUE;
        }

        if ($status['Town']['current_value'] != trim($data['Town'])) {
            $status['Town']['changed'] = 1;
            $status['Town']['new_value'] = trim($data['Town']);
            $data_changed = TRUE;
        }

        if ($status['Postcode']['current_value'] != trim($data['Postcode'])) {
            $status['Postcode']['changed'] = 1;
            $status['Postcode']['new_value'] = trim($data['Postcode']);
            $data_changed = TRUE;
        }

        if ($status['County']['current_value'] != trim($data['County'])) {
            $status['County']['changed'] = 1;
            $status['County']['new_value'] = trim($data['County']);
            $data_changed = TRUE;
        }

        if ($status['CountryId']['current_value'] != trim($data['CountryId'])) {
            $status['CountryId']['changed'] = 1;
            $status['CountryId']['new_value'] = trim($data['CountryId']);
            $data_changed = TRUE;
        }

        if (date("d-m-Y", strtotime($status['Dob']['current_value'])) != trim(date("d-m-Y", strtotime($data['Dob'])))) {
            $status['Dob']['changed'] = 1;
            $status['Dob']['new_value'] = trim($data['Dob']);
            $data_changed = TRUE;
        }

        if ($status['SecertQuestion']['current_value'] != $posted_squestion->question) {
            $status['SecertQuestion']['changed'] = 1;
            $status['SecertQuestion']['new_value'] = trim($data['SecertQuestion']);
            $data_changed = TRUE;
        }

        if ($encrypter->decrypt($status['SecertAnswer']['current_value']) != trim($data['SecertAnswer'])) {
            $status['SecertAnswer']['changed'] = 1;
            $data_changed = TRUE;
        }

        if (array_key_exists("password", $data) && $data['password'] != "") {
            $status['password']['current_value'] = "IGNORE";
            $data_changed = TRUE;
        }


        // Check to see if we need to let the case manager know that some information has been updated
        $userBuilder = $this->getUserTable();

        if ($data_changed == TRUE) {
            //we now want to update the user's details
            $update = array();
            //we want to loop the $status array and make an update array
            if (is_array($status) && count($status) > 0) {

                foreach ($status as $key => $s) {
                    if ($s['current_value'] != "IGNORE" && $s['changed'] == 1 && $key != "SecertAnswer") {
                        $update[$key] = $s['new_value'];
                    } elseif ($s['current_value'] != "IGNORE" && $s['changed'] == 1 && $key == "SecertAnswer") {
                        //we need to encrtyed the new secret answer
                        $update['SecertAnswer'] = $encrypter->encrypt(trim($data['SecertAnswer']));
                    }
                }

                //we now want to check if we need to update the password
                if (array_key_exists("password", $data) && $data['password'] != "") {
                    $options = ['cost' => 10];
                    $password = htmlentities(trim($data['password']));
                    $hashedPass = password_hash($password, PASSWORD_BCRYPT, $options);
                    $update['Password'] = $hashedPass; //add the password to the update array so we can update the password
                }

                //we now want to update the user table, with only the data that has changed
                $do_update = $userBuilder->where('ID', $userid)->set($update)->update();

                //check to see if the case attached has a case manager assigned
                $check_for_case_manager = $this->select('TakenBy')
                    ->where('active', 1)
                    ->where('ResultCode', 'A')
                    ->where('UserId', $userid)
                    ->get()
                    ->getRow();


                //we now need to update the system documents if they have been drafted already.
                $this->updateCMDocs($case_id, $data);

                // Check if the case has been taken
                if ($check_for_case_manager->TakenBy != 0) {
                    if ($do_update) {
                        //the case has a case manger, we need to send an email letting them know that details have changed.
                        //create out Table output of changes
                        $output = "<table border='1' cellspacing='5'>";

                        $output .= "<thead>";
                        $output .= "<tr>";
                        $output .= "<th>Field</th>";
                        $output .= "<th>Original</th>";
                        $output .= "<th>Updated</th>";
                        $output .= "</tr>";
                        $output .= "</thead>";

                        $output .= "<tbody>";
                        $field_count = 0;
                        foreach ($status as $key => $s) {

                            if (@$s['changed'] == 1 && $s['current_value'] != "IGNORE") {
                                $output .= "<tr>";
                                $output .= "<td>" . ucwords(strtolower($key)) . "</td>";
                                $current_val_wording = ($s['current_value'] == '') ? "--empty--" : $s['current_value'];
                                $output .= "<td>" . $current_val_wording . "</td>";
                                $output .= "<td>" . $s['new_value'] . "</td>";
                                $output .= "</tr>";
                                $field_count++;
                            }
                        }
                        $output .= "</tbody>";
                        $output .= "</table>";


                        //get the case managers first name and email address
                        $db = $this->adminDB;
                        $adminBuilder = $db->table("users");
                        $admin_details = $adminBuilder->select('first_name,email')
                            ->where('id', $check_for_case_manager->TakenBy)
                            ->get()->getRow();



                        if ($field_count > 0) {

                            //send email
                            $email_details = $this->returnEmailTemplate("account_update_details_divtrak");
                            $subject = $email_details->subject;
                            $template = $email_details->body;

                            //replace caseid in the email subject line
                            $subject = str_replace("{CASE}", $case_id, $subject);

                            //we now need to do some string replacemeents for the email
                            $body = str_replace('{FIRST_NAME}', ucfirst(strtolower($admin_details->first_name)), $template);
                            $body = str_replace('{CASE}', $case_id, $body);
                            $body = str_replace('{TABLE}', $output, $body);


                            try {
                                $graph = new MicrosoftGraphMail();

                                $graph->send(
                                    $admin_details->email,
                                    $subject,
                                    $body
                                );
                            } catch (\Throwable $e) {
                                log_message(
                                    'error',
                                    'Microsoft Graph email failed: ' . $e->getMessage()
                                );
                            }


                            //add the table to case notes
                            $note = "Details Updated By Client<br /><br />" . $output;
                            $this->addCaseNote($case_id, $note, null, 1);

                            //we now want to update the audit table so we have a record of whats changed.
                            foreach ($status as $key => $s) {
                                if (@$s['changed'] == 1 && $s['current_value'] != "IGNORE") {
                                    $insert = array();
                                    $insert['user_id'] = $userid;
                                    $insert['case_id'] = $case_id;
                                    $insert['action'] = "user updated " . $key . " to " . $s['new_value'] . "";
                                    $this->addAduitLog($insert);
                                }
                            }

                            //we want to log a password reset, but dont log the new password!
                            if (array_key_exists("password", $data) && $data['password'] != "") {
                                $insert = array();
                                $insert['user_id'] = $userid;
                                $insert['case_id'] = $case_id;
                                $insert['action'] = "user updated their password";
                                $this->addAduitLog($insert);
                            }

                            // All done
                            return TRUE;
                        } else {
                            // Just password reset 
                            return true;
                        }
                    }
                } else {

                    //The case isn't assigned at this stage so we dont send an email but we do log this to the user audits table
                    //we now want to update the audit table so we have a record of whats changed.
                    foreach ($status as $key => $s) {
                        if ($s['current_value'] != "IGNORE" && $s['changed'] == 1) {
                            $insert = array();
                            $insert['user_id'] = $userid;
                            $insert['case_id'] = $case_id;
                            $insert['action'] = "user updated " . $key . " to " . $s['new_value'] . "";
                            $this->addAduitLog($insert);
                        }
                    }

                    //we want to log a password reset, but dont log the new password!
                    if (array_key_exists("password", $data) && $data['password'] != "") {
                        $insert = array();
                        $insert['user_id'] = $userid;
                        $insert['case_id'] = $case_id;
                        $insert['action'] = "user updated their password";
                        $this->addAduitLog($insert);
                    }
                    return TRUE;
                }
            } else {
                return "NOUP"; // NOTHING TO UPDATE
            }
        } else {
            return "NOUP"; // NOTHING TO UPDATE
        }
    }


    public function updateCMDocs($case_id, $data)
    {
        //set some vars we can use in the data replacement
        $first_name = strtoupper(trim($data['Forename']));
        $last_name = strtoupper(trim($data['Surname']));

        $full_name = $first_name . " ";
        $full_name .= (trim($data['Othernames']) != "") ? trim($data['Othernames']) . " " : "";
        $full_name .= $last_name;
        $full_name = strtoupper($full_name);

        $address = ucfirst(trim($data['Address1'])) . "\r\n";
        $address .= ($data['Address2'] != "") ? ucfirst(trim($data['Address2'])) . "\r\n"  : "";
        $address .= ($data['Address3'] != "") ? ucfirst(trim($data['Address3'])) . "\r\n"  : "";
        $address .= ucfirst(trim($data['Town'])) . "\r\n";
        $address .= ($data['County'] != "") ? ucfirst(trim($data['County'])) . "\r\n"  : "";
        $address .= strtoupper(trim($data['Postcode']));

        $address_npcode = ucfirst(trim($data['Address1'])) . "\r\n";
        $address_npcode .= ($data['Address2'] != "") ? ucfirst(trim($data['Address2'])) . "\r\n"  : "";
        $address_npcode .= ($data['Address3'] != "") ? ucfirst(trim($data['Address3'])) . "\r\n"  : "";
        $address_npcode .= ucfirst(trim($data['Town'])) . "\r\n";
        $address_npcode .= ($data['County'] != "") ? ucfirst(trim($data['County'])) : "";

        //Update questionnaire data
        $get_user_id = $this->select('UserId')->where('ID', $case_id)->get()->getRow();
        $user_id = $get_user_id->UserId;

        //we now need to update the questionnaire data if it exists
        $db = $this->dtrackDB;
        $builder = $db->table("questionnaire");
        $questionnaire_started = $builder->where('user_id', $user_id)->where('questionnaire_section_status >= 1')->countAllResults();


        if ($questionnaire_started == 1) {
            //update the petitioner details
            $update = array(
                'petitoner_forename' => $first_name,
                'petitoner_middle_name' => (trim($data['Othernames']) != "") ? trim($data['Othernames']) . " " : "",
                'petitoner_last_name' => $last_name,
                'petitoner_address_line1' => ucfirst(trim($data['Address1'])),
                'petitoner_address_line2' => ($data['Address2'] != "") ? ucfirst(trim($data['Address2']))  : "",
                'petitoner_address_line3' => ($data['Address3'] != "") ? ucfirst(trim($data['Address3']))  : "",
                'petitoner_postcode' => strtoupper(trim($data['Postcode'])),
                'petitoner_town' => ucfirst(trim($data['Town'])),
                'petitoner_county' => ($data['County'] != "") ? ucfirst(trim($data['County'])) : "",
                'petitoner_country' => $data['CountryId'],
            );
            $builder->where('user_id', $user_id)
                ->update($update);
        }


        // document_letter1
        $letter1 = array('full_name' => $full_name, 'petitioner_fullname' => $full_name, 'signed_petitioner_fullname' => $full_name, 'address' => $address);
        $this->documentTableConnectCheckAndUpdate("document_letter1", $letter1, $case_id);

        // document_letter2
        $letter2 = array('full_name' => $full_name, 'signed_petitioner_fullname' => $full_name, 'address' => $address);
        $this->documentTableConnectCheckAndUpdate("document_letter2", $letter2, $case_id);

        // document_d36
        $d36 = array('name_of_petitioner' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d36", $d36, $case_id);

        // document_d84
        $d84 = array('name_of_petitioner' => $full_name, 'the_petitioner' => $full_name, 'the_petitioner2' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d84", $d84, $case_id);

        // document_d80A
        $d80a = array('name_of_petitioner' => $full_name, 'printed_fullname' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d80A", $d80a, $case_id);

        // document_d80B
        $d80b = array('name_of_petitioner' => $full_name, 'fullname' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d80B", $d80b, $case_id);

        // document_d80C
        $d80c = array('name_of_petitioner' => $full_name, 'fullname' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d80C", $d80c, $case_id);

        // document_d80D
        $d80d = array('name_of_petitioner' => $full_name, 'fullname' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d80D", $d80d, $case_id);

        // document_d80E
        $d80e = array('name_of_petitioner' => $full_name, 'fullname' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d80E", $d80e, $case_id);

        // document_d8
        $d8 = array('page2_pet_first_name' => $first_name, 'page2_pet_last_name' => $last_name, 'paddr' => $address_npcode, 'print_full_name' => $full_name);
        $this->documentTableConnectCheckAndUpdate("document_d8", $d8, $case_id);
    }


    public function returnAllNotifications($user_id, $case_id, $returnCount = false)
    {

        // Check if we are dealing with a portal client
        $builder = $this->getQuestionnaireTable();
        $check_is_portal = $builder->where('user_id', $user_id)->get()->getRow();
        $use_portal = ($check_is_portal != NULL && $check_is_portal->portal_case == 1) ? true : false;

        $results = [];
        if ($use_portal == TRUE) {
            $results = $this->query("
				 		SELECT sm.step,sm.notification_wording,smb.date_added,smb.id
						FROM portal_step_manager_bridge AS smb
						JOIN portal_step_manager AS sm ON smb.step_id=sm.id
						WHERE smb.case_id = " . $case_id . "
						AND smb.undone = 0
						AND sm.id NOT IN(2,3)
						AND sm.action = 'Notification Email Sent'
						ORDER BY smb.id DESC
					")
                ->getResultArray();
        } else {
            $results = $this->query("
				 		SELECT sm.step,sm.notification_wording,smb.date_added,smb.id
						FROM step_manager_bridge AS smb
						JOIN step_manager AS sm ON smb.step_id=sm.id
						WHERE smb.case_id = " . $case_id . "
						AND smb.undone = 0
						AND sm.id NOT IN(2,3)
						AND sm.action = 'Notification Email Sent'
						ORDER BY smb.id DESC
					")
                ->getResultArray();
        }

        return ($returnCount === false) ? $results : count($results);
    }

    public function getFeesPaid($user_id)
    {
        $builder = $this->getFeesTable();
        return  $builder->select('fees_paid.fee_id,fees_paid.date_added,fees_paid.merchant_id,packages.PackageCode,packages.PackageName,packages.DigitalPrice')
            ->join('packages', 'fees_paid.fee_id=packages.ID')
            ->where('fees_paid.user_id', $user_id)
            ->orderBy('fees_paid.date_added')
            ->get()
            ->getResultArray();
    }


    public function getFeeOptions($user_id, $orderPlaced = null)
    {
        //remove any fees all ready brought    
        $not_in = "";
        $fees_allowed = "";

        if ($user_id != NULL) {
            //we want to get a list of all the fees paid from the client and remove them from the list of "fees" they can pay.
            $builder = $this->getFeesTable();
            $fees_paid = $builder->select('fee_id')->where('user_id', $user_id)->get()->getResultArray();

            //get the packid,package code linked to the case
            $get_package_details = $this->select('cases.packageId, packages.PackageCode')
                ->join('packages', 'cases.PackageId=packages.ID')
                ->where('cases.UserId', $user_id)
                ->get()
                ->getRow();


            switch ($get_package_details->PackageCode) {
                case 'CBO':
                case 'DCP':
                case 'DCO':
                case 'QEC':
                case 'CBC':
                    $fees_allowed = "PackageCode IN('SCO','COF','1LA', 'COF24', 'COF25','COF26')";
                    break;

                case 'PNP':
                case 'DSP':
                case 'SPA':
                case 'CHP':
                case 'AVM':
                    $fees_allowed = "PackageCode IN('SCO','PO10','PO20','1LA')";
                    break;
            }


            // we want to get all the upgrade options that are packages
            $builder = $this->getPackageTable();           
            $builder->select('ID,PackageName,PackageCode,DigitalPrice');
            $builder->where('active', 1)->where('is_fee', 1);
            
            if ($not_in != "") {
                $builder->where($not_in);
            }
                
            if ($fees_allowed != "") {
                $builder->where($fees_allowed);
            }
                    
            $exCludeDate = "02-03-2026"; // ChANGE TO  (2nd March 2026)
            $excludePackageCodes = array();

            // If  we have a valid orderPlaced date format it to dd-mm-YYYY - ensure its vlaid to avoid errors
            // based on the order placed date we need to exclude certain packages.
            if ($orderPlaced != null && $orderPlaced != "")
            {
                $orderDt = \DateTime::createFromFormat('Y-m-d H:i:s', $orderPlaced);
                $excludeDt = \DateTime::createFromFormat('d-m-Y', $exCludeDate);
                if ($orderDt && $excludeDt) 
                {
                    if ($orderDt->getTimestamp() < $excludeDt->getTimestamp()) {
                        $excludePackageCodes = array("AFE26", "COF26");
                    } else {
                        $excludePackageCodes = array("AFE24", "COF25", "COF", "AFE");
                    }
                }
            }

            if (!empty($excludePackageCodes)) {
                $builder->whereNotIn('PackageCode', $excludePackageCodes);
            }

            $builder->orderBy("packageName");
            $res = $builder->get()->getResultArray();

            return $res;
        } else {
            return null;
        }
    }

    public function getUpgradeOptions($user_id)
    {
        $case_id = $this->returnCaseIDFromUserId($user_id);

        //get the current packge id for this case..
        $get_package_id = $this->select('PackageId, OrderPlaced')->where('ID', $case_id)->get()->getRow();
        $packageID = $get_package_id->PackageId;

        //we need to check if package has been upgraded
        $has_been_upgraded = $this->checkIfupgraded($case_id);

        if ($has_been_upgraded != FALSE) {
            $packageID = $has_been_upgraded['ID'];
        }

        $builder = $this->getPackageTable();
        $get_package_code = $builder->select('PackageCode')->where('ID', $packageID)->get()->getRow();
        $package_code = $get_package_code->PackageCode;

        // we want to split the upgrade code and get the LAST 3-5 characters
        if (strpos($package_code, 'TO') !== false) {
            $parts = explode("TO", $package_code);
            if ($parts[1] != "") {
                //get the package ID for the last set of characters for the next set of upgrades.
                $get_new_packageID = $builder->select("ID")->where('PackageCode', $parts[1])->get()->getRow();

                if ($get_new_packageID) {
                    $packageID =  $get_new_packageID->ID;
                    $get_package_code = $builder->select('PackageCode')->where('ID', $packageID)->get()->getRow();
                    $package_code = $get_package_code->PackageCode;
                }
            }
        }

        //get the website the package was brought from
        $sale_source = $this->select('SaleSource')->where('ID', $case_id)->get()->getRow();
        $sale_source = ($sale_source->SaleSource == 1) ? "DOL" : "QED";

        //we want to get all the upgrade options that are packages
        $upgradeBuilder = $this->getUpgradeOptionsTable();
        $upgradeBuilder->distinct('packages.ID');
        $upgradeBuilder->select("packages.PackageCode,packages.PackageName,packages.DigitalPrice,packages.upgrade_reason,upgrade_options.upgradeable_to_package_id");
        $upgradeBuilder->join('packages', 'upgrade_options.upgradeable_to_package_id=packages.ID');
        $upgradeBuilder->where('upgrade_options.package_id', $packageID);
        $upgradeBuilder->where('packages.Website', $sale_source);
        $upgradeBuilder->where('packages.active', 1);
        $upgradeBuilder->where('upgrade_options.deleted', 0);
        $upgradeBuilder->orderBy("packages.packageCode");
        $res = $upgradeBuilder->get()->getResultArray();

        // If package code is DDP - if a DDP has been purchased before 2025-03-10 08:59:59 then we need to remove  DDP199TODPPCO  from the $options array
        $cutoffDate = "2025-03-10 08:59:59";
        if ($package_code && $package_code == "DDP") {
            // if the purchase date is before 2025-03-10 08:59:59 then we need to remove  DDP199TODPPCO  from the $options array	
            if ($get_package_id && $get_package_id->OrderPlaced < $cutoffDate) {
                $options = array_filter($res, function ($res) {
                    return $res['PackageCode'] != "DDP199TODPPCO";
                });
            }

            // If the DDP was purchased after then we need to remove DDP249TODPPCO  from the options array
            if ($get_package_id && $get_package_id->OrderPlaced > $cutoffDate) {
                $res = array_filter($res, function ($res) {
                    return $res['PackageCode'] != "DDP249TODPPCO";
                });
            }
        }

        return $res;
    }

    public function firstTimeUpdateDetails($data, $user_id)
    {
        // Set the contact prefences
        $data['email_optin'] = (array_key_exists("email_optin", $data)) ? $data['email_optin']  : 0;
        $data['sms_optin'] = (array_key_exists("sms_optin", $data)) ? $data['sms_optin']  : 0;
        $data['phone_optin'] = (array_key_exists("phone_optin", $data)) ? $data['phone_optin']  : 0;


        //remove the show hiden details
        unset($data['update_details'], $data['conf_password'], $data['hiddenAnswer'], $data['ShowPassword'], $data['showPassword']); //unset the submit button and confirm password
        $data['Dob'] = date("Y-m-d", strtotime($data['Dob'])); //reformat the dob so its mysql format yyyy-mm-dd

        //hash the password
        $options = ['cost' => 10];
        $password = htmlentities(trim($data["password"]));
        $hashedPass = password_hash($password, PASSWORD_BCRYPT, $options);

        //add the hashed password into the data array
        $data['Password'] = $hashedPass;

        //encryted the secert answer
        $config = $this->getEncryptionConfig();
        $encrypter = Services::encrypter($config, false);
        $data['SecertAnswer'] = $encrypter->encrypt(trim($data['SecertAnswer']));

        //update the "force update details" database fields to 0
        $data['ForceUpdate'] = 0;
        $data['ForceSecurityQuestions'] = 0;
        $data['LastUpdated'] = date("Y-m-d H:i:s");

        // We want to create a secure cookie that expires in 30 days
        // get the cookie ID and encrypt it using the env encryption key
        // We then need to save a serialised array with the encrypted cookie ID
        $twofactorCookie = $this->createTwoFactorCookie($user_id, $config);

        $builder = $this->getUserTable();
        if ($builder->where('ID', $user_id)->update($data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkTempBypass($user_id)
    {
        // Do we have a user id?
        if( !isset($user_id) || empty($user_id) ) {
            return false;
        }

        // We now need to get the temp_bypass field from the users table
        $builder = $this->getUserTable();
        $user = $builder->select('temp_bypass')->where('ID', $user_id)->get()->getRow();
       
        // has the temp bypass expired?
        if($user && $user->temp_bypass < date("Y-m-d H:i:s")) {
            return false;
        }

        // We now want to set the cookie but only valid for 1 hour
        $config = $this->getEncryptionConfig();
        $this->createTwoFactorCookie($user_id, $config, true);

        // Set the flags in the dataabase to reflect that the user doesn't need to complete 2FA (clear the temp_bypass flag)
        $upd = [
            'two_factor_required' => 0,
            'two_factor_code' => null,
            'code_expiry' => null,
            'temp_bypass' => null,
        ];
        $builderUpdate = $this->getUserTable();
        $builderUpdate->where('ID', $user_id)->update($upd);
        return true;
    }

    // Create a cookie for the 2FA process
    public function createTwoFactorCookie($user_id, $config, $shortenExpiry = false)
    {
        // Generate a random ID for the cookie
        $cookieId = bin2hex(random_bytes(16));
        $encrypterCookie = \Config\Services::encrypter($config, false);
        $encryptedCookieId = $encrypterCookie->encrypt($cookieId);

        // Determine expiry time (in seconds)
        $expirySeconds = $shortenExpiry ? 60 * 60 : 60 * 60 * 24 * 30; // 1 hour or 30 days

        // Get the Response service
        $response = service('response');

        // Set the cookie using the Response object
        $response->setCookie(
            name: 'token_access',
            value: $encryptedCookieId,
            expire: $expirySeconds,  // absolute timestamp
            path: '/',
            domain: '',
            secure: true,              // HTTPS only
            httponly: true,
            samesite: 'Lax'
        );

        // Optionally, send immediately if you’re not returning a response object
        $response->send();

        // Update the two_factor db
        $this->updateTwoFactor($encryptedCookieId, $user_id);

        return true;
    }

    
    // This creates the first 2FA record for the user
    public function updateTwoFactor($token, $user_id)
    {
        $builder = $this->getTwoFactorTable();
        $builder->insert(['user_id' => $user_id, 'token' => $token]);

        // There can only be a max of 3 records for the the user
        // If there are now 4, remove the oldest record
        $builderRemove = $this->getTwoFactorTable();
        $records = $builderRemove->where('user_id', $user_id)->get()->getResultArray();
        if (count($records) >= 4) {
            $builderRemove->where('user_id', $user_id)->orderBy('created_at', 'ASC')->limit(1)->delete();
        }
    }

    public function resendVerificationCode($user_id, $isResend = false)
    {
        // If this isn't a resend then we want to check if a code is set, if we have a code then we dont send again
        if ($isResend == false) {
            $builder = $this->getUserTable();
            $code = $builder->select('two_factor_code')->where('ID', $user_id)->get()->getRow();

            // if $code is NOT null then we can return true
            if ($code && $code->two_factor_code != "" && $code->two_factor_code != null) {
                return true;
            }
        }

        // Generate a random 6 digit code
        $code = rand(100000, 999999);
        // update the users table field two_factor_code to the code
        $builder = $this->getUserTable();

        // Set an expiry date time - 15 minutes from now
        $expiryTime = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        $builder->where('ID', $user_id)->set(['two_factor_code' => $code, 'code_expiry' => $expiryTime])->update();

        // Send an email to the client with the code, first we need to get the Forename, UserEmail from users table
        $user = $builder->select('Forename, UserEmail, RespondentEmail')->where('ID', $user_id)->get()->getRow();
        $email = $user->UserEmail;

        // if we have a respondent email, we will add the , to the email address
        $respondentEmail = ( $user->RespondentEmail != "" && $user->RespondentEmail != null) ? ", ".$user->RespondentEmail : "";
        $forename = $user->Forename;
        
        // Send the verification code to the client's email
        $this->sendVerificationCode($email, $respondentEmail, $forename, $code);
       
        return true;
    }


    public function sendVerificationCode($emailAddress, $respondentEmail, $forename, $code)
    {
        // Get the DOL email wrapper
        $main_email_template = $this->returnEmailTemplate("dol_main_wrapper");

        //str replace the details into placeholders
        $email_body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template->body);
       
        // get the 2FA verification email template
        $email_details = $this->returnEmailTemplate("dt_two_factor_verification");
        $subject = $email_details->subject;
        $template = $email_details->body;

        // Set the subject
        $email_body = str_replace('{MAIN_SUBJECT}', $subject, $email_body);
       
        //we now need to do some string replacemeents for the email
        $body = str_replace('{FORENAME}', ucfirst(strtolower($forename)), $template);
        $body = str_replace('{CODE}', "<b>$code</b>", $body);

        // Remove the {JOINT_ACCOUNT} placeholder from the email body if we dont have a respondent email
        if($respondentEmail == "" || $respondentEmail == null) {
            $body = str_replace('{JOINT_ACCOUNT}', "", $body);
        }
        else {
            $message = "Your current package with us is a joint account, which means both account holders receive all account-related emails.<br /><br />
If you did not initiate this request, it may have been made by the other account holder, and you can safely disregard this message.<br /><br />";
            $body = str_replace('{JOINT_ACCOUNT}', $message, $body);
        }

        $email_body =  str_replace('{MAIN_CONTENT}', $body, $email_body);

        // Respondent email already has a comma, so we don't need to add it again
        $toEmail = $emailAddress . $respondentEmail;

        try {
            $graph = new MicrosoftGraphMail();

            $graph->send(
                $toEmail,
                $subject,
                $email_body
            );
        } catch (\Throwable $e) {
            log_message(
                'error',
                'Microsoft Graph email failed: ' . $e->getMessage()
            );
        }
    }

    public function verifyVerificationCode($user_id, $code)
    {
        $code  = trim(esc($code));

        // if the code is not 6 digits, return false
        if (strlen($code) != 6) {
            session()->setFlashdata("ERR", "Invalid verification code");
            return false;
        }

        // Unsure the user id matches the session user id, if it does then we can verify the code
        if ($user_id == session("user_id")) 
        {
            // We now need to get two_factor_code, code_expiry from the users table
            $builder = $this->getUserTable();
            $user = $builder->select('two_factor_code, code_expiry')->where('ID', $user_id)->get()->getRow();;

            // Is the code correct?
            if ($user && $user->two_factor_code !== $code) { 
                session()->setFlashdata("ERR", "Invalid verification code");
                return false;
            }

            // first check if this has expired, if it has, then we need to send a new code and return false
            if ($user && $user->code_expiry < date("Y-m-d H:i:s")) {
                session()->setFlashdata("ERR", "Verification code has expired, we have sent a new code to your email.");
                $this->resendVerificationCode($user_id, true);
                return false;
            }

            // We know that the code is valid and not expired, so we need to set the cookie
            // Update the user table two_factor_required = 0
            $config = $this->getEncryptionConfig();
            $this->createTwoFactorCookie($user_id, $config);

            $builderSetFlag = $this->getUserTable();
            $builderSetFlag->where('ID', $user_id)->set(['two_factor_required' => 0])->update();
            return true;   
        }

        // No user ID OR the user id has been changed, return false
        session()->setFlashdata("ERR", "Invalid verification code");
        return false;
    }


    public function getPackageData($user_id)
    {
        $builder = $this->getCasesTable();
        return $builder->select('packages.PackageCode')
            ->join('packages', 'cases.PackageId=packages.ID')
            ->where('cases.UserId', $user_id)
            ->get()
            ->getRow();
    }



    public function checkPackage($case_id)
    {
        //does this package require a divorce questionnaire
        $q_required = $this->query(
            "SELECT requires_questionnaire
               FROM cases AS c
               JOIN packages AS p ON c.PackageID=p.ID
               WHERE c.ID = {$case_id}"
        )->getRow();

        //we need to check for upgrades
        $upgradeBuilder = $this->getUpgradesTable();
        $check_for_upgrades = $upgradeBuilder->select('product_to')
            ->where('upgrade_status', 1)
            ->where('case_id', $case_id)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getResultArray();

        if (is_array($check_for_upgrades) && count($check_for_upgrades) > 0) {
            //get the questionnaire status for the upgrade
            $builder = $this->getPackageTable();
            $q_required = $builder->select('requires_questionnaire')->where('ID', $check_for_upgrades[0]['product_to'])->get()->getRow();
            return ($q_required->requires_questionnaire == 0) ? FALSE : TRUE;
        } else {
            return ($q_required->requires_questionnaire == 0) ? FALSE : TRUE;
        }
    }



    public function getTempFilesToDelete()
    {

        $db = db_connect();
        $builder = $db->table("dt_temp_files");
        $res = $builder->select('id,case_id,file_name')
            ->where('delete_stagged', 1)
            ->where('deleted', 0)
            ->limit(10)
            ->get()
            ->getResultArray();
        return $res;
    }


    public function setTempFileAsDeleted($case_id, $row_id, $file_name)
    {
        $db = db_connect();
        $builder = $db->table("dt_temp_files");

        $insert = [
            'deleted' => 1,
            'deleted_by' => 37,
            'deleted_date' => date("Y-m-d H:i:s")
        ];
        $builder->where('case_id', $case_id)
            ->where('id', $row_id)
            ->where('file_name', $file_name)
            ->set($insert)
            ->update();
    }

    public function checkForConditionalOrderDate($case_id)
    {
        // If we dont have a case id, return false
        if( !isset($case_id) || empty($case_id) ) {
            return null;
        }
        
        // we need to now check in the portal_step_manager_bridge table for step 9 based on case_id and status = 1 and undone = 0
        $builder = $this->getPortalStepsManagerBridgeTable();
        $conditionalOrderDate = $builder->where('case_id', $case_id)
                                        ->where('step_id', 9)
                                        ->where('status', 1)
                                        ->where('undone', 0)
                                        ->get()
                                        ->getRow();
                                        
        if (!$conditionalOrderDate) {
            return null;
        }

        // Check for a valid row in the court_details table, else return null
        $courtDetailsBuilder = $this->getCourtDetailsTable();
        $courtDetails = $courtDetailsBuilder->select('date_issued')
                                            ->where('case_id', $case_id)
                                            ->get()
                                            ->getRow();
        if (!$courtDetails) {
            return null;
        }
        
        // We now need to check that we have a value and its not null
        if ($courtDetails && $courtDetails->date_issued != null) 
        {
            // We need to make sure the value is a valid date, stored as dd-mm-YYYY
            $timestamp = strtotime($courtDetails->date_issued);
            if ($timestamp === false) {
                // Invalid date string, treat as no date
                return null;
            }

            // Normalise to Y-m-d for internal use
            $date = date("Y-m-d", $timestamp);

            // We now need to add 141 days (20 weeks) to the date
            $plus_20_weeks = date("d-m-Y", strtotime("+141 days", strtotime($date)));
            return ($plus_20_weeks) ? $plus_20_weeks : null;
        }

        // As a last resort, return null
        return null;
    }


    #########################################################################################
    ############################### DATABASE TABLES #########################################
    #########################################################################################

    public function getUpgradeOptionsTable()
    {
        $db = db_connect();
        return $db->table("upgrade_options");
    }

    public function getCasesTable()
    {
        $db = db_connect();
        return $db->table("cases");
    }


    public function getQuestionnaireTable()
    {
        $db = $this->dtrackDB;
        return $db->table("questionnaire");
    }

    public function getPackageTable()
    {
        $db = db_connect();
        return $db->table("packages");
    }

    public function getUserTable()
    {
        $db = db_connect();
        return $db->table("users");
    }

    public function getUpgradesTable()
    {
        $db = db_connect();
        return $db->table("upgrades");
    }

    public function getFeesTable()
    {
        $db = db_connect();
        return $db->table("fees_paid");
    }

    public function getServicesTable()
    {
        $db = db_connect();
        return $db->table("additional_service_paid");
    }

    public function getPortalStepsManagerBridgeTable()
    {
        $db = db_connect();
        return $db->table("portal_step_manager_bridge");
    }

    public function getStepManagerBridgeTable()
    {
        $db = db_connect();
        return $db->table("step_manager_bridge");
    }

    public function getCountryTable()
    {
        $db = db_connect();
        return $db->table("country");
    }


    public function getSecretQuestionTable()
    {
        $db = db_connect();
        return $db->table("security_questions");
    }

    public function getNotesTable()
    {
        $db = db_connect();
        return $db->table("case_notes");
    }

    public function getTwoFactorTable()
    {
        $db = db_connect();
        return $db->table("two_factor");
    }

    public function getCourtDetailsTable()
    {
        $db = db_connect();
        return $db->table("court_details");
    }   


    #########################################################################################
    ############################### DATABASE TABLES END  ####################################
    #########################################################################################



    #########################################################################################
    ############################### HELPER FUNCTIONS ########################################
    #########################################################################################

    public function checkIfupgraded($case_id)
    {
        //we need to see if this case has been upgraded
        $builder = $this->getUpgradesTable();
        $has_upgrade = $builder->where('case_id', $case_id)->where('upgrade_status', 1)->countAllResults();

        if ($has_upgrade >= 1) {
            $get_upgrade_details = $this->query("
                SELECT p.ID,p.PackageName,p.PackageCode,p.DigitalPrice
                FROM upgrades AS u
                JOIN packages AS p ON u.product_to=p.ID
                WHERE u.case_id = $case_id
                AND u.upgrade_status = 1
                ORDER BY u.id DESC
                LIMIT 1
            ")->getResultArray();

            $rtn = array();
            if (is_array($get_upgrade_details) && count($get_upgrade_details) > 0) {

                $rtn['PackageName'] = $get_upgrade_details[0]["PackageName"];
                $rtn['PackageCode'] = $get_upgrade_details[0]["PackageCode"];
                $rtn['DigitalPrice'] = $get_upgrade_details[0]["DigitalPrice"];
                $rtn['FinalPrice'] = $get_upgrade_details[0]["DigitalPrice"];
                $rtn['ID']  = $get_upgrade_details[0]["ID"];
            }

            return $rtn;
        } else {
            return FALSE;
        }
    }

    public function isCaseComplete($user_id)
    {
        return ($this->where('UserId', $user_id)->where('case_complete', 0)->countAllResults()) ? false : true;
    }

    public function returnUserData($user_id, $field = null)
    {
        $builder = $this->getUserTable();

        if ($field !== null) {
            // Send a single field back
            $res = $builder->select($field)->where('ID', $user_id)->get()->getRow();
            return $res->$field;
        } else {
            // Send the whole row back
            return $builder->where('ID', $user_id)->get()->getRow();
        }
    }

    public function setAllToRead($user_id)
    {
        // Check if we are dealing with a portal client
        $builder = $this->getQuestionnaireTable();
        $check_is_portal = $builder->select('portal_case')->where('user_id', $user_id)->get()->getRow();
        $use_portal = ($check_is_portal != NULL && $check_is_portal->portal_case == 1) ? true : false;
        $table_name = ($use_portal == TRUE) ? "portal_step_manager_bridge" : "step_manager_bridge";

        //set all notfications to read
        if ($table_name == "portal_step_manager_bridge") {
            $builder = $this->getPortalStepsManagerBridgeTable();
        }

        if ($table_name == "step_manager_bridge") {
            $builder = $this->getStepManagerBridgeTable();
        }

        $case_id = $this->returnCaseIDFromUserId($user_id);
        $builder->where('case_id', $case_id)->update(["user_seen_notification" => 1]);
    }

    public function getUserRow($user_id)
    {

        $builder = $this->getUserTable();
        $data = $builder->select('users.*, security_questions.question')
            ->join('security_questions', 'users.SecertQuestion=security_questions.id')
            ->where('users.ID', $user_id)
            ->get()
            ->getResultArray();

        if ($data[0]['SecertAnswer'] != "") {
            //decryted the secret answer
            $config = $this->getEncryptionConfig();
            $encrypter = Services::encrypter($config, false);
            $data[0]['SecertAnswer'] = $encrypter->decrypt($data[0]['SecertAnswer']);
        }
        return $data[0];
    }

    public function getCountries()
    {
        $builder = $this->getCountryTable();
        return $builder->select('id,country')->get()->getResultArray();
    }

    public function getSecretQuestions()
    {
        $builder = $this->getSecretQuestionTable();
        return $builder->select('id,question')->orderBy('question', 'ASC')->get()->getResultArray();
    }


    public function addAduitLog($insert)
    {
        $db = db_connect();
        $builder = $db->table("user_audit");
        $builder->insert($insert);
    }

    public function returnEmailTemplate($template)
    {
        $db = db_connect();
        $builder = $db->table("email_templates");
        return $builder->select('subject,body')->where('email_ref', $template)->get()->getRow();
    }

    public function documentTableConnectCheckAndUpdate($table, $update, $case_id)
    {
        $db = db_connect();
        $builder = $db->table($table);
        $builder->where('case_id', $case_id)->update($update);
    }



    public function returnCaseIDFromUserId($user_id)
    {
        $id = $this->select('ID')->where('UserId', $user_id)->get()->getRow();
        return ($id) ? $id->ID : null;
    }


    public function getEncryptionConfig()
    {
        $config   = new Encryption();
        $config->driver = 'OpenSSL';

        // Your CI3's 'encryption_key'
        $key = bin2hex(getenv("encryption.key"));
        $config->key = hex2bin($key);
        // Your CI3's 'cipher' and 'mode'
        $config->cipher = 'AES-128-CBC';

        $config->rawData        = false;
        $config->encryptKeyInfo = 'encryption';
        $config->authKeyInfo    = 'authentication';

        return $config;
    }

    public function addCaseNote($caseID, $note, $adminID = null, $addedBySystem = null)
    {
        $builder = $this->getNotesTable();
        $builder->insert(['case_id' => $caseID, 'note' => $note, 'admin_id' => $adminID, 'added_by_system' => $addedBySystem]);
    }

    public function logPciRun($status)
    {
        $db = db_connect();
        $builder = $db->table("pci");
        $builder->insert(['success' => $status, 'date_time_ran' => date('Y-m-d H:i:s'), 'site_id' => 2]);
    }

    #########################################################################################
    ############################### HELPER FUNCTIONS END  ###################################
    #########################################################################################


}
