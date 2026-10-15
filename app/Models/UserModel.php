<?php

namespace App\Models;

use Config\Services;
use Config\Encryption;

use App\Libraries\Token;
use App\Libraries\MicrosoftGraphMail;

class UserModel extends \CodeIgniter\Model
{
    protected $table = 'users';
    protected $allowedFields = ['password', 'Password', 'LastLogin', 'password_attempt_count', 'password_attempt_reached', 'locked_out_till', 'successful_reset'];

    protected $useTimestamps = false;
    protected $returnType = "App\Entities\User";

    protected $validationRules = [
        'email' => 'required|valid_email',
        'password' => 'required'
    ];
    protected $validationMessages = [
        'email' => [
            'required' => 'Email is a required field',
            'valid_email' => 'You must enter a valid email address',
        ],
        'password' => [
            'required' => 'Password is a required field'
        ]
    ];

    protected $forgotPasswordRule = [
        "email" => ["label" => "Email", "rules" => "required|valid_email"]
    ];
    protected $forgotPasswordRule_errors = [
        'email' => ['required' => 'Email address is a required field', 'valid_email' => 'Please enter a valid email address']
    ];


    protected $resetPasswordRule = [
        "password" => ["label" => "Password", "rules" => "required|doPasswordCheck"],
        "conf_password" => ["label" => "Confirmation Password", "rules" => "required|doPasswordCheck|matches[password]"]
    ];
    protected $resetPasswordRule_errors = [
        'password' => ['required' => 'Please enter a password'],
        'conf_password' => ['required' => 'Please confirm new password', 'matches' => "Password's don't match, please check both passwords and try again"]
    ];


    protected $beforeInsert = [];
    protected $beforeUpdate = [];


    protected function hashPassword(array $data)
    {
        if (isset($data['data']['Password'])) {
            $options = ['cost' => 10];
            $data['data']['password_hash'] = password_hash(trim($data['data']['Password']), PASSWORD_BCRYPT, $options);
            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }
        return $data;
    }

    public function returnHashedPassword($password)
    {
        $options = ['cost' => 10];
        return password_hash(trim($password), PASSWORD_BCRYPT, $options);
    }


    public function findByEmail($email)
    {
        return $this->where('UserEmail', $email)
            ->where('UserEmail <> ""')
            ->where('IsDeleted', 0)
            ->where('Validated', 1)
            ->first();
    }

    public function disablePasswordValidation()
    {
        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirmation']);
    }

    public function activateByToken($token)
    {

        $token = new Token($token);
        $token_hash = $token->getHash();
        $user = $this->where('activation_hash', $token_hash)->first();

        if ($user !== null) {
            $user->activate();
            $this->protect(false)->save($user);
        } else {
            // not found
            return false;
        }
    }

    function getUserForPasswordReset($token)
    {
        $token = new Token($token);
        $token_hash = $token->getHash();

        $user = $this->where('reset_hash', $token_hash)
            ->first();

        if ($user) {
            if ($user->reset_expires_at < date("Y-m-d H:i:s")) {
                $user = null;
            }
        }
        return $user;
    }

    public function updateLastLogin($user_id)
    {
        $upd = ["LastLogin" => date("Y-m-d H:i:s")];
        $this->where('ID', $user_id)->set($upd)->update();
    }


    public function sendPasswordReset($data)
    {
        $userEmail = trim($data["email"]);

        // //lookup the email address
        $builder = $this->getUserTable();
        $check_email = $builder->where('UserEmail', $userEmail)->where('Validated', 1)->countAllResults();

        // //for security we dont want to tell the user if wer have that email on record so we will say we have any email address they enter
        if ($check_email == 0) {
            return FALSE;
        } else {

            //Get the case id so we can log the password reset request to case notes
            $get_user_id = $builder->select('ID')
                ->where('UserEmail', htmlentities($userEmail))
                ->where('IsDeleted', 0)
                ->where('Validated', 1)
                ->get()->getRow();

            $user_id = $get_user_id->ID;

            $caseBuilder = $this->getCasesTable();
            $get_case_id = $caseBuilder->select('ID')->where('UserId', $user_id)->get()->getRow();
            $case_id = $get_case_id->ID;


            //get the users first name from the users table
            $user_details = $builder->select('Forename')
                ->where('UserEmail', $userEmail)
                ->where('Validated', 1)
                ->where('IsDeleted', 0)
                ->get()->getRow();

            // Get the first name
            $first_name = $user_details->Forename;

            //create the reset code && expiry date time
            $code = sha1(time() . microtime());
            $expiry = date("Y-m-d H:i:s", strtotime('+15 minutes'));


            //insert record into the password_reset table
            $insert = array(
                'email_address' => $userEmail,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'code' => $code,
                'expiry_date' => $expiry
            );
            $passwordBuilder = $this->getPasswordResetTable();
            $passwordBuilder->insert($insert);


            //log that the user has requested a password reset
            $note = "Password reset requested from divorce track login page.";
            $this->addCaseNote($case_id, $note, null, 1);

            //create the password reset link
            // Codeigniter 4.6.3 doesnt allow = in url so we need to replace the = with ~
            $reset_link = site_url() . "reset-password/" . $code . "/" . str_replace("=", "~", base64_encode($userEmail)) . "";

            //send the password reset email
            $email_details = $this->returnEmailTemplate('password_reset_frontend');
            $subject = $email_details->subject;
            $template = $email_details->body;

            //get the email wrapper template
            $main_email_template = $this->returnEmailTemplate('dol_main_wrapper');
            $main_email_template = $main_email_template->body;

            //we now need to do some string replacemeents for the email
            $body = str_replace('{FIRST_NAME}', ucfirst(strtolower($first_name)), $template);
            $body = str_replace('{EMAIL}', $userEmail, $body);
            $body = str_replace('{LINK}', $reset_link, $body);


            //str replace the details into placeholders
            $email_body = str_replace('{IMG_PATH}', $this->returnEmailImgPath(), $main_email_template);
            $email_body = str_replace('{MAIN_SUBJECT}', $subject, $email_body);
            $email_body =  str_replace('{MAIN_CONTENT}', $body, $email_body);

            if ($this->sendEmail($userEmail, $subject, $email_body)) {
                //log that the email was sent successfully
                $note = 'Password reset email sent ' . date("d-m-Y H:i:s") . '';
                $this->addCaseNote($case_id, $note, null, 1);
                return TRUE;
            } else {
                //log that the email was sent successfully
                $note = 'Password reset email failed to send ' . date("d-m-Y H:i:s") . '';
                $this->addCaseNote($case_id, $note, null, 1);
                return FALSE;
            }
        }
    }


    public function sendPasswordSuccessResetEmail($emailTo)
    {

        $main_email_template = $this->returnEmailTemplate('dol_main_wrapper')->body;
        $email_body = "
 			Hi, <br /><br />

 			Your password has been successfully reset, if this wasn't you, please conatct support immediately.<br /><br />

 			This email can be ignored if it was you who reset the account password.<br /><br />

 			Thanks<br />
 		";

        $subject = "Your password has been reset!";
        $body = str_replace('{IMG_PATH}', $this->returnEmailImgPath(), $main_email_template);
        $body = str_replace('{MAIN_SUBJECT}', $subject, $body);
        $body =  str_replace('{MAIN_CONTENT}', $email_body, $body);

        $this->sendEmail($emailTo, $subject, $body);
    }

    public function sendEmail(
        $userEmail,
        $subject,
        $body,
        $bcc = "",
        $replyTo = "",
        $file = ""
    ) {
        try {
            $graph = new MicrosoftGraphMail();

            return $graph->send(
                $userEmail,
                $subject,
                $body,
                $bcc,
                $replyTo,
                $file
            );
        } catch (\Throwable $e) {
            log_message(
                'error',
                'Microsoft Graph email failed: ' . $e->getMessage()
            );

            return false;
        }
    }


    public function checkPasswordResetDetails($code, $email, $firstStage = true)
    {

        //we need to check that the email and the code are in the database then check that it hasn't expired
        $res = $this->checkRestDetails($code, $email);

        $errorMsg = "";
        switch ($res) {
            case 1:
                #
                break;
            case 'BAD_CREDS':
                $errorMsg = "We have failed to find your password reset details, please contact support for further assistance."; // code or email dont match the database
                break;

            case 'LINKUSED':
                $errorMsg = "This password reset link has all ready been used to update your password and can't be used again, please use the <a href='" . site_url() . "forgot-password'>password reset facility</a> to send another password reset link. "; // code or email dont match the database
                break;

            case 'EXPIRED':
                $errorMsg = "This password reset link has expired, please use the <a href='" . site_url() . "forgot-password'>password reset facility</a> to send another password reset link. ";
                break;
        }

        return $errorMsg;
    }

    public function checkRestDetails($code, $email)
    {
        //first we want to check that the code and email match the database
        $builder = $this->getPasswordResetTable();
        $valid_reset = $builder->where('email_address', $email)
            ->where('code', $code)
            ->countAllResults();

        if ($valid_reset == 0) {
            return "BAD_CREDS";
        } else {
            //code and email are correct lets make sure it hasn't expired
            $curDate = date("Y-m-d H:i:s");
            $check_expired = $builder->select('expiry_date,successful_reset')->where('email_address', $email)->where('code', $code)->get()->getRow();

            //The reset link has expired
            if ($curDate > $check_expired->expiry_date) {
                $builder->where('email_address', $email)->where('code', $code)->update(['link_expired' => 1]);
                return "EXPIRED";
            } elseif ($check_expired->successful_reset == 1) {
                return "LINKUSED";
            } else {
                return TRUE;
            }
        }
    }

    public function updatePasswordFromReset($password, $email)
    {

        $options = ['cost' => 10];
        $password = trim($password);
        $hashedPass = password_hash($password, PASSWORD_BCRYPT, $options);

        //Get the case id so we can log the password reset request to case notes
        $get_user_id = $this->select('ID')
            ->where('UserEmail', htmlentities($email))
            ->where('IsDeleted', 0)
            ->where('Validated', 1)
            ->get()->getRow();

        $user_id = $get_user_id->ID;
        $case_id = $this->returnCaseIDFromUserId($user_id);


        //we now want to update the users password
        $upd = ['Password' => $hashedPass, 'password_attempt_count' => 0, 'password_attempt_reached' => 0, 'locked_out_till' => NULL];
        if ($this->where('UserEmail', $email)->set($upd)->update()) {
            $note = 'Password reset successfully updated ' . date("d-m-Y H:i:s") . '';
            $this->addCaseNote($case_id, $note, $adminID = null, $addedBySystem = null);

            $builder = $this->getPasswordResetTable();
            $builder->where('email_address', $email)->set(['successful_reset' => 1])->update();
            return TRUE;
        } else {

            $note = 'Password update failed ' . date("d-m-Y H:i:s") . '';
            $this->addCaseNote($case_id, $note, $adminID = null, $addedBySystem = null);
            return FALSE;
        }
    }




    ##################################################
    ################## DATABASES #####################
    ##################################################

    public function getCaseNotesTable()
    {
        $db = db_connect();
        return $db->table("case_notes");
    }

    public function getPasswordResetTable()
    {
        $db = db_connect();
        return $db->table("password_reset");
    }

    public function getCasesTable()
    {
        $db = db_connect();
        return $db->table("cases");
    }

    public function getUserTable()
    {
        $db = db_connect();
        return $db->table("users");
    }

    public function getTwoFactorTable()
    {
        $db = db_connect();
        return $db->table("two_factor");
    }

    public function checkForSplitEmail($email)
    {
        if ($email == "") {
            return false;
        }

        $row = $this->select('UserEmail')->where('JointEmail', trim($email))->get()->getRow();
        return ($row && $row->UserEmail != "") ? $row->UserEmail : false;
    }


    ####################################################
    ################## HELPER FUNCTIONS ################
    ####################################################

    public function checkIfOnHold($user_id)
    {
        $builder = $this->getCasesTable();
        return ($builder->where('UserId', $user_id)->where('case_on_hold', 1)->countAllResults() > 0) ? true : false;
    }

    public function checkForReactivation($user_id)
    {
        $builder = $this->getCasesTable();
        return ($builder->where('UserId', $user_id)->where('case_requires_reactivation', 1)->countAllResults() > 0) ? true : false;
    }

    public function isFirstLogin($user_id)
    {
        return ($this->where('ForceUpdate', 1)->where('ID', $user_id)->countAllResults() > 0) ? true : false;
    }

    public function returnCaseIDFromUserId($user_id)
    {
        $builder = $this->getCasesTable();
        $id = $builder->select('ID')->where('UserId', $user_id)->get()->getRow();
        return ($id) ? $id->ID : null;
    }

    public function returnEmailTemplate($template)
    {
        $db = db_connect();
        $builder = $db->table("email_templates");
        return $builder->select('subject,body')->where('email_ref', $template)->get()->getRow();
    }

    public function returnEmailImgPath()
    {
        return base_url() . "images/";
    }

    public function addCaseNote($caseID, $note, $adminID = null, $addedBySystem = null)
    {
        $builder = $this->getCaseNotesTable();
        $builder->insert(['case_id' => $caseID, 'note' => $note, 'admin_id' => $adminID, 'added_by_system' => $addedBySystem]);
    }


    public function check2FA($user_id)
    {
        helper('cookie');
        $config = $this->getEncryptionConfig();
        $encrypter = Services::encrypter($config, false);

        $builder = $this->getTwoFactorTable();
        $results = $builder->where('user_id', $user_id)->get()->getResultArray();

        // If we dont have any results, return false
        if (empty($results)) {
            return false;
        }

        // if we have results then we need to get the cookie ID and decrypt it
        $token = get_cookie('token_access');
        if ($token !== null) {
            // Decrypt the token for checking against the database
            $cookieId = $encrypter->decrypt($token);

            foreach ($results as $result) {
                // check that token exists and its not empty, if empty continue
                if (empty($result['token'])) {
                    continue;
                }

                // decrypt the token
                $dbToken = $encrypter->decrypt($result['token']);

                if ($dbToken === $cookieId) {
                    return true;
                }
            }

            // Fail safe to catch if there are no tokens and to cause a 2FA email to be sent
            return false;
        } else {
            // this will force a 2FA email to be sent
            return false;
        }
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

    public function setTwoFactorRequired($user_id)
    {
        // To ensure a 2FA email is sent, we need to set the two_factor_required flag to 1 and clear the two_factor_code and code_expiry fields
        $builder = $this->getUserTable();
        $builder->where('ID', $user_id)->set(['two_factor_required' => 1, 'two_factor_code' => null, 'code_expiry' => null])->update();
    }

    public function checkTwoFactorRequired($user_id)
    {
        // Check we have a user_id set
        if (!$user_id) {
            return false;
        }

        $builder = $this->getUserTable();
        $user = $builder->select('two_factor_required')->where('ID', $user_id)->get()->getRow();
        return ($user && $user->two_factor_required == 1) ? true : false;
    }
}
