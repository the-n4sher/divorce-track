<?php 
namespace Config;

class CustomValidation
{

    public function checkEmail(string $str, &$error = null): bool
    {
        $model = model("AccountModel");
        $doEmailCheck = $model->checkEmail($str);

        if($doEmailCheck === false)
        {
            $error = "This email is already registered in our system, please use a different email address.";
            return false;
        }else{
            return true;
        }
    }


    public function checkOver16(string $str, &$error = null): bool
    {
        $date_suppiled = date("Y-m-d",strtotime($str));
        if (time() < strtotime('+16 years', strtotime($date_suppiled))) {
            $error = "You must be over the age of 16 to use this service.";
            return false;
        }else{
            return TRUE;
        }
    }

    public function checkDateInFuture(string $str, &$error = null): bool
    {
        $todays_date = date("Y-m-d");
        $date_suppiled = date("Y-m-d",strtotime($str));

        if($date_suppiled > $todays_date){
            $error = 'Please select a date that is not in the future';
            return FALSE;
        }else{
            return TRUE;
        }
    }


    public function checkGender(string $str, &$error = null): bool
    {
        $model = model("QuestionnaireModel");
        $qdata = $model->returnRow(session("user_id"));

        if($qdata->is_civilpartnership == 0 && trim($str) == trim($qdata->petitoner_gender))
        {
            $error = "Please check that you have selected the correct gender for both the petitioner and respondent, the system has detected an error, both genders have been entered as '".$str."' which is invalid for the type of marriage selected.";
            return FALSE;
        }else{
            return TRUE;
        }
    }


    public function checkDates(string $str, string $fields, array $data, string &$error = null): bool
    {
 
        $split = explode(",", $fields);
        $marriage_date_of_separation = trim($split[0]);
        $marriage_date_of_marriage = trim($split[1]);

        if($marriage_date_of_separation == "" || $marriage_date_of_marriage == ""){
           $error =  "Date of marriage and date of seperation are required fields.";
           return FALSE;
        }

        $date_to_check = date("Y-m-d",strtotime($str));
        $today = date("Y-m-d");
        if($date_to_check > $today){
           $error =  "You can't select a date in the future, please check and try again.";
           return FALSE;
        }
    
        if(date("Y-m-d",strtotime($marriage_date_of_separation)) < date("Y-m-d",strtotime($marriage_date_of_marriage)) ){
            $error = "Date of separation cannot be before date of marriage";
            return FALSE;
        }else{
            return TRUE;
        }

    }

    public function checkIfDivorceDateIsAllowed(string $str, &$error = null): bool
    {
        $date_of_marriage = date("Y-m-d",strtotime($str));
        $minus_366_days = date("Y-m-d",strtotime("-366 days"));
        $plus_366_days = date("Y-m-d",strtotime("+366 days"));

        if($date_of_marriage < $minus_366_days){
            return TRUE;
        }else{
           $error = "You haven't been married for over 12 months so you cannot proceed, please contact your case manager.";
           return FALSE;
        }
    }

    public function livedWithSelected(string $str, &$error = null): bool
    {
        $err_msg = "Unfortunately you would be unable to divorce on the grounds of adultery if you have been living together for a period of 6 months after the other party has admitted the adultery. This is because the court would have deemed you to have accepted the adultery if you remain living together, please consider unreasonable behaviour.";

        if($str == "1"){
            $error = $err_msg;
            return FALSE;
        }else{
            return TRUE;
        }
    }


    public function checkCourtNumber(string $str, &$error = null): bool
    {
        if(preg_match("/^[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}$/i", $str) || preg_match("/^[Z]{2}[0-9]{2}[D]{1}[0-9]{5}$/i", $str)){
            return TRUE;
        }else{
            $error = 'Case number has been suppiled in an invalid format. The case number must be 16 characters in length. e.g. 1234-2212-5432-4543';
            return FALSE;
        }
    }

    public function checkOver18(string $str, &$error = null): bool
    {   
        $date_suppiled = date("Y-m-d",strtotime($str));
        if (time() < strtotime('+18 years', strtotime($date_suppiled))) {
            $error = 'You must be over the age of 18 to use this service.';
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function doPasswordCheck(string $str, &$error = null): bool 
    {

        // First lets check the password meets the requirments
        if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/', $str)) 
        {
            // Password is valid but we want to check that it doesn't contain certain words like password or letmein
            if($this->containsWeakPassword($str) === true)
            {
                $error = "Password must not contain weak phrases like `password`.";
                return false;
            }else{
                return true;
            }
             
        } else {
            // Password is invalid
            $error = "Password must be at least 6 characters long, contain at least one uppercase letter, one number, and one special character.";
            return false;
        }
        

    }

    public function containsWeakPassword($str) 
    {
        // Array of weak passwords
        $weakPasswords = ['password', 'letmein'];

        // Convert the input string to lowercase
        $lowerStr = strtolower($str);

        // Loop through the weak passwords
        foreach ($weakPasswords as $weakPassword) {
            // Check if the lowercase string contains the weak password
            if (strpos($lowerStr, $weakPassword) !== false) {
                // Return true if a weak password is found
                return true;
            }
        }

        // Return false if no weak password is found
        return false;
    }




}