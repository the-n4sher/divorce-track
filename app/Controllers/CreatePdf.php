<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;

require FCPATH . 'vendor/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

ini_set('memory_limit', '128M');


class CreatePdf extends BaseController
{
    protected $user;
    protected $consentModel;
    protected $questionnaireModel;
    protected $accountModel;
    protected $userModel;
    protected $authService;
    protected $res_logged_in;


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

        $this->consentModel = model("ConsentOrderModel");
        $this->questionnaireModel = model("QuestionnaireModel");
        $this->userModel = model("UserModel");
        $this->accountModel = model("AccountModel");
        $this->response = service("response");

        $this->res_logged_in = (session()->has("respondent_only")) ? true : false;

        // See if this is the users first login
        $this->checkFirstLogin();
        $this->checkRespondentLoggedIn();
    }


    public function checkFirstLogin()
    {
        $request = service('request');
        $controller = $request->getUri()->getSegment(1);
        $method = $request->getUri()->getSegment(2);

        if ($method != "update-details" && $controller != "logout") {
            $firstLogin = $this->userModel->isFirstLogin(session("user_id"));
            if ($firstLogin === true) {
                return $this->response->redirect(site_url('logout'));
                exit();
            }
        }
    }


    // The respondent shouldn't be able to do anything apart from the consent order questionnaire
    public function checkRespondentLoggedIn()
    {
        if ($this->res_logged_in === true) {
            return $this->response->redirect(site_url('logout'));
            exit();
        }
    }

    public function checkCorrectUserAndCase($user_id, $suppliedCaseId)
    {
        if ($user_id != session("user_id")) {
            die("***WARNING *** Process cancelled due to a mismatch in user information (UID). Incident logged and sent to our security team (IP: " . $_SERVER['REMOTE_ADDR'] . " )");
        }

        $rowCaseId = $this->consentModel->returnCasesIdFromUserId(session("user_id"));
        if ($suppliedCaseId != $rowCaseId) {
            die("***WARNING *** Process cancelled  due to a mismatch in user information(CID). Incident has been logged and sent to our security team (IP: " . $_SERVER['REMOTE_ADDR'] . " )");
        }
    }


    public function generateCOPdfFile($page, $user_id, $case_id, $pension_id = "")
    {
        $getPackageCode = $this->accountModel->getPackagedata($user_id);
        $package_code = ($getPackageCode !== null) ?  $getPackageCode->PackageCode : null;

        $data = array('doc_path' => 0);
        $case_id = (int)$case_id;
        $document_name = $page;

        #check the userid is that of the logged in client
        $this->checkCorrectUserAndCase($user_id, $case_id);

        $questionnaire_data = $this->consentModel->getQuestionnaireData($user_id);

        // get the country name for this record and add to the questionnaire data array so it doesn't need to be fetched on the page
        $questionnaire_data[0]['country_name_app'] = $this->consentModel->getCountryName($questionnaire_data[0]["app_address_country"]);
        $questionnaire_data[0]['country_name_res'] = $this->consentModel->getCountryName($questionnaire_data[0]["res_address_country"]);

        $data['document_name'] = $document_name;
        $data['style_sheet'] =  strtolower($document_name);
        $data['qdata'] = $questionnaire_data;

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // If pension id isn't null then we need to fetch the data for the pension
        if ($pension_id != "") {
            // fetch the data
            $pension_data = $this->consentModel->getPensionData($user_id, true, $pension_id);

            // Case pension data as array
            $pension_data[0]["doc_path"] = 0;

            // Check to see if we have edit data
            $edit_data = $this->consentModel->checkIfHasP1Data($case_id, $pension_id);

            // We now want to merge the data so we can use inside the document
            $merged_pension_data = array_merge($pension_data[0], $questionnaire_data[0]);

            // Send the merged pension data through
            $html = view('ConsentOrderDocs/' . $page . '', array('qdata' => $merged_pension_data, 'doc_path' => 0, 'edit_data' => $edit_data, 'package_code' => $package_code));
        } else {
            $html = view('ConsentOrderDocs/' . $page . '', array('qdata' => $questionnaire_data, 'package_code' => $package_code));
        }

        $html = str_replace("\n", "", $html);
        $html = str_replace("\r", "", $html);

        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream(str_replace('.php', "", $page) . '.pdf', array("Attachment" => false));
        exit();
    }


    public function createLegalAdviseWordDoc($document_name, $user_id, $case_id)
    {

        #check the userid is that of the logged in client
        $this->checkCorrectUserAndCase($user_id, $case_id);

        //get questinnaire data
        $questionnaire_data = $this->consentModel->getQuestionnaireData($user_id);
        $qdata = $questionnaire_data[0];

        $legal_name1 = false;
        $legal_name2  = false;

        //create the applicant name
        $app_name = $qdata['app_forename'] . " ";
        $app_name .= ($qdata['app_other_names'] != "") ? $qdata['app_other_names'] . " " : "";
        $app_name .= $qdata['app_surname'];
        $app_name = ucwords($app_name);

        //create the respondent name
        $res_name = $qdata['res_forename'] . " ";
        $res_name .= ($qdata['res_other_names'] != "") ? $qdata['res_other_names'] . " " : "";
        $res_name .= $qdata['res_surname'];
        $res_name = ucwords($res_name);

        if ($document_name == "Legal_Advice_Statement_Single") {
            //  We need to find out who has the legal advise and who doesn't
            if ($qdata['legal_app_add'] == 1) {
                $legal_name1 = $app_name;
            } else {
                $legal_name2 = $app_name;
            }

            if ($qdata['legal_res_add'] == 1) {
                $legal_name1 = $res_name;
            } else {
                $legal_name2 = $res_name;
            }
        }


        if ($document_name == "Legal_Advice_Statement_None" || $document_name == "Legal_Advice_Statement_Both") {
            // We need to find out who has the legal advise and who doesn't
            $legal_name1 = $app_name;
            $legal_name2 = $res_name;
        }

        include_once(getcwd() . '/tbs_class.php');
        include_once(getcwd() . '/tbs_plugin_opentbs.php');

        $TBS = new \clsTinyButStrong; // new instance of TBS
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
        $TBS->SetOption(array('noerr' => true));

        $template = getcwd() . DIRECTORY_SEPARATOR . "documents" . DIRECTORY_SEPARATOR . "CONSENT_ORDER" . DIRECTORY_SEPARATOR . $document_name . ".docx";

        ##replace tplaceholders
        $TBS->VarRef["case_number"] =  $qdata['case_number'];
        $TBS->VarRef["court_name"] =  ($qdata['name_of_court'] == "CTSC Government Online Portal") ? "CTSC" : $qdata['name_of_court'];
        $TBS->VarRef["app_name"] =  $app_name;
        $TBS->VarRef["res_name"] =  $res_name;

        $TBS->VarRef['legal_name1'] = $legal_name1;
        $TBS->VarRef['legal_name2'] = $legal_name2;

        $file_name = $document_name;
        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        //$TBS->Plugin(OPENTBS_DEBUG_XML_SHOW);
        $output_file_name = $file_name;

        // Output the result as a downloadable file (only streaming, no data saved in the server)
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name . ".docx");
        exit();
    }



    public function createCoWordDoc($document_name)
    {
        $user_id = session("user_id");
        include_once(getcwd() . '/tbs_class.php');
        include_once(getcwd() . '/tbs_plugin_opentbs.php');

        $TBS = new \clsTinyButStrong; // new instance of TBS
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
        $TBS->SetOption(array('noerr' => true));


        // Get the clients details
        $client_details = $this->consentModel->getQuestionnaireData($user_id, true);

        if (isset($client_details)) {
            $app_name = $client_details->app_forename . " ";
            $app_name .= ($client_details->app_other_names != "") ? $client_details->app_other_names . " " : "";
            $app_name .= $client_details->app_surname;
            $app_name = ucwords(strtolower($app_name));

            $res_name = $client_details->res_forename . " ";
            $res_name .= ($client_details->res_other_names != "") ? $client_details->res_other_names . " " : "";
            $res_name .= $client_details->res_surname;
            $res_name = ucwords(strtolower($res_name));

            switch ($document_name) {
                case 'Cover_Letter.docx':
                    $TBS->VarRef["date"] = date("d-m-Y", strtotime(date("Y-m-d")));
                    $TBS->VarRef["case_number"] = $client_details->case_number;
                    $TBS->VarRef["applicant_name"] = $app_name;
                    $TBS->VarRef["respondent_name"] = $res_name;
                    break;
            }



            $file_name = $document_name;
            $full_path = getcwd() . DIRECTORY_SEPARATOR . "documents" . DIRECTORY_SEPARATOR . "CONSENT_ORDER" . DIRECTORY_SEPARATOR . $file_name;
            $template = $full_path;

            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
            //$TBS->Plugin(OPENTBS_DEBUG_XML_SHOW);
            $output_file_name = $file_name;

            // Output the result as a downloadable file (only streaming, no data saved in the server)
            $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
            // Be sure that no more output is done, otherwise the download file is corrupted with extra data.
            exit();
        }
    }
}
