<?php

namespace App\Models;

use Config\Encryption;
use Config\Services;
use App\Libraries\MicrosoftGraphMail;

class QuestionnaireModel extends \CodeIgniter\Model
{
	protected $table = 'questionnaire';
	protected $DBGroup = 'dt';

	protected $cmDB = "";
	protected $adminDB = "";
	protected $allowedFields = [
		'questionnaire_section_status',
		'user_id',
		'date_created',
		'portal_case',
		'is_civilpartnership',
		'petitoner_forename',
		'petitoner_middle_name',
		'petitoner_last_name',
		'petitoner_occupation',
		'petitoner_dob',
		'petitoner_gender',
		'petitoner_address_line1',
		'petitoner_address_line2',
		'petitoner_address_line3',
		'petitoner_town',
		'petitoner_postcode',
		'petitoner_county',
		'petitoner_country',
		'respondent_forename',
		'respondent_middle_name',
		'respondent_last_name',
		'respondent_occupation',
		'respondent_dob',
		'respondent_gender',
		'respondent_email',
		'respondent_address_line1',
		'respondent_address_line2',
		'respondent_address_line3',
		'respondent_town',
		'respondent_postcode',
		'respondent_county',
		'respondent_country',
		'marriage_took_place_outside_uk',
		'marriage_place_of_marriage',
		'marriage_date_of_marriage',
		'marriage_date_of_separation',
		'marriage_petitioner_full_name',
		'marriage_respondent_full_name',
		'marriage_last_address_line1',
		'marriage_last_address_line2',
		'marriage_last_address_line3',
		'marriage_last_town',
		'marriage_last_postcode',
		'marriage_last_county',
		'marriage_last_country',
		'certificate_url',
		'file_name',
		'mime_type',
		'children_by_spouse',
		'num_of_children_by_spouse',
		'num_of_children_by_other',
		'spouse_child_forename',
		'spouse_child_middlename',
		'spouse_child_surname',
		'other_child_name_known',
		'child_gender',
		'child_relationship',
		'in_educ_train_fwork',
		'other_child_forename',
		'other_child_middlename',
		'other_child_surname',
		'other_child_name_known',
		'child_dob',
		'child_gender',
		'born_or_adopted_by',
		'child_of_other',
		'on_grounds',
		'behaviour_reason',
		'adultery_lived_with',
		'adultery_gender',
		'adultery_where',
		'adultery_when_discovered_date',
		'date_of_desertion',
		'date_completed',
		'questionnaire_complete',
		'court_proceedings',
		'court_proceedings_information',
		'letter_signed',
		'letter_signed_date',
		'pay_portal_cc',
		'portal_fee_ref',
		'portal_yes_no',
		'portal_case',
		'petitoner_nationality',
		'respondent_nationality'
	];

	protected $useTimestamps = false;

	protected $validationRules = [];
	protected $validationMessages = [];

	protected $beforeInsert = [];
	protected $beforeUpdate = [];

	protected $jointAppProducts;

	protected $stage1Rules = [
		"is_civilpartnership" => ["label" => "Divorce Type", "rules" => "required"],
		"petitoner_forename" => ["label" => "Forename", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
		"petitoner_last_name" => ["label" => "Last name", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
		"petitoner_occupation" => ["label" => "Occupation", "rules" => "required|min_length[2]|trim"],
		"petitoner_dob" => ["label" => "Date of birth", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|trim|checkDateInFuture"],
		"petitoner_gender" => ["label" => "Pet gender", "rules" => "required"],
		"petitoner_address_line1" => ["label" => "Address line 1", "rules" => "required|min_length[5]|trim"],
		"petitoner_town" => ["label" => "Town/City", "rules" => "required|min_length[2]|trim"],
		"petitoner_postcode" => ["label" => "Postcode", "rules" => "required|min_length[4]|trim"],
		"petitoner_country" => ["label" => "Country", "rules" => "required"]
	];

	protected $stage1Rules_errors = [
		'is_civilpartnership' => ['required' => 'Please select the type of divorce that you are entering into'],
		'petitoner_forename' => ['required' => 'Please enter a valid forename', 'regex_match' => "Please enter a valid forename"],
		'petitoner_last_name' => ['required' => 'Please enter a valid surname', 'regex_match' => 'Please enter a valid surname'],
		'petitoner_occupation' => ['required' => 'Please enter your occupation', 'min_length' => 'Please enter your occupation'],
		'petitoner_dob' => ['required' => 'Please enter a valid date of birth', 'regex_match' => 'Please enter a valid date of birth'],
		'petitoner_gender' => ['required' => 'Please select your gender'],
		'petitoner_address_line1' => ['required' => 'Please enter the first line of your address', 'min_length' => 'Please enter the first line of your address'],
		'petitoner_town' => ['required' => 'Please enter the town you live in', 'min_length' => 'Please enter the town you live in'],
		'petitoner_postcode' => ['required' => 'Please enter your postcode', 'min_length' => 'Please enter your postcode'],
		'petitoner_country' => ['required' => 'Please select the country you live in']
	];


	protected $stage2Rules  = [
		"respondent_forename" => ["label" => "Forename", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
		"respondent_last_name" => ["label" => "Last name", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
		"respondent_occupation" => ["label" => "Occupation", "rules" => "required|min_length[2]|trim"],
		"respondent_dob" => ["label" => "Date of birth", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|trim|checkDateInFuture"],
		"respondent_gender" => ["label" => "Pet gender", "rules" => "required|checkGender"],
		"respondent_address_line1" => ["label" => "Address line 1", "rules" => "required|min_length[5]|trim"],
		"respondent_town" => ["label" => "Town/City", "rules" => "required|min_length[2]|trim"],
		"respondent_postcode" => ["label" => "Postcode", "rules" => "required|min_length[4]|trim"],
		"respondent_country" => ["label" => "Country", "rules" => "required"]
	];

	protected $stage2Rules_errors = [
		'respondent_forename' => ['required' => 'Please enter a valid forename', 'regex_match' => "Please enter a valid forename"],
		'respondent_last_name' => ['required' => 'Please enter a valid surname', 'regex_match' => 'Please enter a valid surname'],
		'respondent_occupation' => ['required' => 'Please enter your occupation', 'min_length' => 'Please enter your occupation'],
		'respondent_dob' => ['required' => 'Please enter a valid date of birth', 'regex_match' => 'Please enter a valid date of birth'],
		'respondent_gender' => ['required' => 'Please select your gender'],
		'respondent_address_line1' => ['required' => 'Please enter the first line of your address', 'min_length' => 'Please enter the first line of your address'],
		'respondent_town' => ['required' => 'Please enter the town you live in', 'min_length' => 'Please enter the town you live in'],
		'respondent_postcode' => ['required' => 'Please enter your postcode', 'min_length' => 'Please enter your postcode'],
		'respondent_country' => ['required' => 'Please select the country you live in']
	];


	protected $formData = "marriage_date_of_separation, marriage_date_of_marriage";

	protected $stage3Rules = [
		"marriage_place_of_marriage" => ["label" => "Place of marriage", "rules" => "required|min_length[2]|trim"],
		"marriage_date_of_marriage" => ["label" => "date of marriage", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|checkIfDivorceDateIsAllowed|trim"],
		"marriage_date_of_separation" => ["label" => "Date of seperation", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|trim"],
		"marriage_petitioner_full_name" => ["label" => "Petitioner full name", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
		"marriage_respondent_full_name" => ["label" => "Respondent full name", "rules" => "required|regex_match[/^[a-z\-' ]{2,}$/i]|trim"],
		"marriage_last_address_line1" => ["label" => "Address line 1", "rules" => "required|min_length[5]|trim"],
		"marriage_last_town" => ["label" => "Town/City", "rules" => "required|min_length[2]|trim"],
		"marriage_last_postcode" => ["label" => "Postcode", "rules" => "required|min_length[4]|trim"],
		"marriage_last_country" => ["label" => "Country", "rules" => "required"],
		"marriage_took_place_outside_uk" => ["label" => "Marriage outside the uk", "rules" => "required"]
	];

	protected $stage3Rules_errors = [
		'marriage_place_of_marriage' => ['required' => 'Please enter place of marriage', 'min_length' => 'Please enter place of marriage'],
		'marriage_date_of_marriage' => ['required' => 'Please enter date of your marraige', 'regex_match' => 'Please enter date of your marriage'],
		'marriage_date_of_separation' => ['Please enter date of your seperation', 'regex_match' => 'Please enter date of your seperation'],
		'marriage_petitioner_full_name' => ['required' => 'Please enter petitioners full name', 'regex_match' => 'Please enter petitioners full name'],
		'marriage_respondent_full_name' => ['required' => 'Please enter respondents full name', 'regex_match' => 'Please enter respondents full name'],
		'marriage_last_address_line1' => ['required' => 'Please enter the first line of your address', 'min_length' => 'Please enter the first line of your address'],
		'marriage_last_town' => ['required' => 'Please enter the town you live in', 'min_length' => 'Please enter the town you live in'],
		'marriage_last_postcode' => ['required' => 'Please enter your postcode', 'min_length' => 'Please enter your postcode'],
		'marriage_last_country' => ['required' => 'Please select the country you live in'],
		'marriage_took_place_outside_uk' => ['required' => 'Please tell us if your marriage took place outside of the UK']
	];

	protected $stage5Rules = ["divorce_grounds" => ["label" => "Divorce grounds", "rules" => "required|in_list[1,2,3,4,5]"]];
	protected $stage5Rules_errors = [
		'divorce_grounds' => ['required' => 'Please specify under which ground you wish to file for divorce', 'in_list' => 'Please specify under which ground you wish to file for divorce']
	];


	protected $stage5Step1Rules = [
		"adultery_when_discovered_date" => ["label" => "Adultery discovered", "rules" => "required|regex_match[/^[0-9]{2}\-{1}[0-9]{2}\-[0-9]{4}$/i]|trim"],
		"adultery_where" => ["label" => "Where adultery took place", "rules" => "required|min_length[2]"],
		"adultery_gender" => ["label" => "respondent's sex", "rules" => "required"],
		"adultery_lived_with" => ["label" => "Lived with", "rules" => "livedWithSelected"],
	];
	protected $stage5Step1Rules_errors = [
		'adultery_when_discovered_date' => ['required' => 'Please enter the date the adultery was discovered', 'regex_match' => 'Please enter the date the adultery was discovered'],
		'adultery_where' => ['required' => 'Please enter where the adultery took place', 'min_length' => 'Please enter where the adultery took place'],
		'adultery_gender' => ['required' => 'Please select the respondent\'s lovers sex']
	];


	protected $stage5Step1BehaviourRules = [
		"behaviour_reason" => ["label" => "Behaviour", "rules" => "required|min_length[10]|max_length[800]"]
	];

	protected $stage5Step1BehaviourRules_errors = [
		'behaviour_reason' => ['required' => 'Please provide behaviour details', 'min_length' => 'Please provide behaviour details', 'max_length' => 'You have exceeded the character limit of 800']
	];

	protected $stage5CourtRules = [
		"court_proceedings_information" => ["label" => "Court proceedings", "rules" => "required|min_length[5]|max_length[200]"]
	];

	protected $stage5CourtRules_errors = [
		'court_proceedings_information' => ['required' => 'Please provide details of current court proceedings', 'min_length' => 'Please provide details of current court proceedings', 'max_length' => 'Please enter no more than 200 characters']
	];

	protected $imageURL;


	public function __construct()
	{
		$this->cmDB = db_connect('default');
		$this->adminDB = db_connect('am');
		$this->imageURL = site_url() . "images/";
		$this->jointAppProducts = ["DDPJ"];
	}


	public function saveStage($stage, $data, $upload_path = NULL, $file_name = NULL, $mime_type = NULL, $step1Stage4 = false, $childUpdate = false, $step5Stage = 0)
	{
		$user_id = session("user_id");

		if (!is_array($data)) {
			return false;
		}

		if ($stage === 1) {
			//unset any array elements we dont need
			unset($data['stage1_submit']);
			$upd = $data;

			if ($this->where('user_id', $user_id)->set($data)->update()) {
				//query successfully, update the stage
				if ($this->hasCompletedStandardQuestionnaire($user_id) === false) {
					$this->where('user_id', $user_id)->set(['questionnaire_section_status' => 2])->update();
				}
				return true;
			} else {
				return false;
			}
		}


		if ($stage === 2) {
			unset($data['stage2_submit'], $data['res_unknown']);
			$upd = $data;
			if ($this->where('user_id', $user_id)->set($data)->update()) {
				//query successfully, update the stage
				if ($this->hasCompletedStandardQuestionnaire($user_id) === false) {
					$this->where('user_id', $user_id)->set(['questionnaire_section_status' => 4])->update();
				}
				return TRUE;
			} else {
				///query failed
				return FALSE;
			}
		}


		if ($stage === 3) {
			unset($data['stage3_submit']);
			if (array_key_exists("delete_cert", $data)) {
				unset($data['delete_cert']);
			}

			//see if we have a file uploaded
			if ($file_name != NULL) {
				$data['certificate_url'] = $upload_path . $file_name;
				$data['file_name'] = htmlentities(trim($file_name));
				$data['mime_type'] = htmlentities(trim($mime_type));
			}

			//update the record
			if ($this->where('user_id', $user_id)->set($data)->update()) {
				//query successfully, update the stage
				if ($this->hasCompletedStandardQuestionnaire($user_id) === false) {
					$this->where('user_id', $user_id)->set(['questionnaire_section_status' => 6])->update();
				}
				return TRUE;
			} else {
				///query failed
				return FALSE;
			}
		}


		if ($stage === 4) {
			//unset any array elements we dont need
			unset($data['stage4_submit']);
			$data['children_by_spouse'] =  (array_key_exists("children_by_spouse", $data)) ? 1 : 0;
			$data['num_of_children_by_spouse'] =  (array_key_exists("num_of_children_by_spouse", $data)) ? $data['num_of_children_by_spouse'] : 0;
			$data['children_by_other'] =  (array_key_exists("children_by_other", $data)) ? 1 : 0;
			$data['num_of_children_by_other'] =  (array_key_exists("num_of_children_by_other", $data)) ? $data['num_of_children_by_other'] : 0;


			if ($step1Stage4 === true) {
				// Do the first part of the update, before insert we want to some vlaues to 0
				$upd = array();
				$upd['children_by_spouse'] = 0;
				$upd['num_of_children_by_spouse'] = 0;
				$upd['children_by_other'] = 0;
				$upd['children_by_other_name_unknow'] = 0;
				$upd['num_of_children_by_other'] = 0;
				$this->where('user_id', $user_id)->set($upd)->update();

				if ($this->where('user_id', $user_id)->set($data)->update()) {
					if (!isset($data['children_by_spouse']) && isset($data['children_by_other']) && isset($data['children_by_other_name_unknow']) && $data['children_by_other_name_unknow'] == 1) {
						return redirect()->to('questionnaire/stage1/5');
					}
					return TRUE;
				} else {
					return FALSE;
				}
			}

			if ($step1Stage4 === false && $childUpdate === false) {
				$upd = [
					'questionnaire_section_status' => 8,
					'children_by_spouse' => $data['children_by_spouse'],
					'num_of_children_by_spouse' => $data['num_of_children_by_spouse'],
					'children_by_other' => $data['children_by_other'],
					'num_of_children_by_other' => $data['num_of_children_by_other']
				];

				return ($this->where('user_id', $user_id)->set($upd)->update()) ? true : false;
			}


			if ($childUpdate === true) {
				$qid = $this->getQuestionnaireId($user_id);

				//un needed posted element								  
				unset($data['children_submit_stage_4_submit']);

				if (is_array($data) && count($data) > 0) {
					//we want to remove all rows from the child bridge table where user_id and ref match as we may be adding new children..
					$this->query("DELETE FROM divorce_child_bridge WHERE user_id = {$user_id} AND qid = {$qid}");
					if (array_key_exists("spouse_child_details", $data)) {
						$sdata = $data['spouse_child_details'];
						foreach ($sdata as $key => $value) {

							$ins = array();
							$ins['user_id'] = $user_id;
							$ins['qid'] = $qid;
							$ins['child_dob'] = $value['dob'];
							$ins['spouse_child_forename'] = $value['spouse_child_forename'];
							$ins['spouse_child_middlename'] = $value['spouse_child_middlename'];
							$ins['spouse_child_surname'] = $value['spouse_child_surname'];
							$ins['other_child_name_known'] = 0;
							$ins['child_gender'] = $value['gender'];
							$ins['child_relationship'] = 1; // $value['relationship'] only here is stu changes his mind, this would get the value from the dropdown
							$ins['in_educ_train_fwork'] = $value['in_edc_train_ftwok'];
							$ins['child_of_spouse'] = 1;
							$this->db->table("divorce_child_bridge")->insert($ins);
						}
					}

					if (array_key_exists("other_child_details", $data)) {
						$sdata = $data['other_child_details'];
						foreach ($sdata as $key => $value) {
							$ins = array();
							$ins['user_id'] = $user_id;
							$ins['qid'] = $qid;
							$ins['other_child_forename'] = $value['other_child_forename'];
							$ins['other_child_middlename'] = $value['other_child_middlename'];
							$ins['other_child_surname'] = $value['other_child_surname'];
							$ins['other_child_name_known'] = $value['other_child_name_known'];
							$ins['child_dob'] = $value['dob'];
							$ins['child_gender'] = $value['gender'];
							$ins['born_or_adopted_by'] = $value['born_or_adopted_by'];
							$ins['child_of_other'] = 1;
							$this->db->table("divorce_child_bridge")->insert($ins);
						}
					}
					return TRUE;
				}
				return FALSE;
			}
		}


		if ($stage === 5) {
			if ($step5Stage === 1) {
				$this->resetStep5Details($user_id, 1);
				$this->resetStep5Details($user_id, 5);
				unset($data['stage5_submit_adultery_step1']); //un needed posted element

				if ($this->where('user_id', $user_id)->set($data)->update()) {
					return TRUE;
				} else {
					return FALSE;
				}
			}


			if ($step5Stage === 2) {
				$this->resetStep5Details($user_id, 2);
				$this->resetStep5Details($user_id, 5);

				unset($data['stage5_submit_behaviour_step1']); //un needed posted element
				if ($this->where('user_id', $user_id)->set($data)->update()) {
					return TRUE;
				} else {
					return FALSE;
				}
			}


			if ($step5Stage === 3) {
				$res = $this->saveStage5Court($user_id, false, $data);
				return $res;
			}

			if ($step5Stage === 4) {
				$this->saveStage5Court($user_id, true, $data);

				$upd = [
					'questionnaire_section_status' => 10,
					'date_completed' => date("Y-m-d H:i:s"),
					'questionnaire_complete' => 1
				];
				$this->where('user_id', $user_id)->set($upd)->update();
				return true;
			}
		}
	}


	public function saveStage5Court($user_id, $ignore_user_update = null, $data, $is_portal = false)
	{
		//send upgrade email
		$allowed = array(201, 195, 207);

		//we only want to send the auto email once so the code is inside a check
		//get the case type (standard,non-standard,ddp/dpp)
		$builder = $this->getCasesTable();

		$builder->select('cases.ID,cases.TakenBy,cases.PackageId,users.Forename,users.UserEmail');
		$builder->join('users', 'cases.UserId=users.ID');
		$builder->where('cases.UserId', (int)$user_id);
		$get_case_details = $builder->get()->getRow();

		$package_id = $get_case_details->PackageId;
		$case_id = $get_case_details->ID;
		$user_email = $get_case_details->UserEmail;
		$forename = $get_case_details->Forename;


		//If we have a package id we want to get the package code
		$isJointApp = false;
		if ($package_id) {
			$cmDB = $this->cmDB;
			$cmBuilder = $cmDB->table("packages");
			$getPackageCode = $cmBuilder->select('PackageCode')->where('ID', $package_id)->get()->getRow();
			$isJointApp = ($getPackageCode && in_array($getPackageCode->PackageCode, $this->jointAppProducts)) ? true : false;
		}

		//only send the email if the package is inside the allowed array above
		if (in_array($package_id, $allowed)) {

			//send the user a confirmation email
			$email_template_details = $this->returnEmailTemplate("financial_advisor");
			$subject = $email_template_details->subject;

			$main_body = $email_template_details->body;
			$msg = str_replace('{CLIENT_NAME}', $forename, $main_body);

			//get the email wrapper template
			$main_email_template = $this->getEmailWrapper($case_id);

			//str replace the details into placeholders
			$body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
			$body = str_replace('{MAIN_SUBJECT}', $subject, $body);
			$body =  str_replace('{MAIN_CONTENT}', $msg, $body);

			$this->sendEmail($user_email, $subject, $body);
		}

		unset($data['stage5_submit_court_proceddings']);
		$data['questionnaire_section_status'] = 10; // 100%
		$data['questionnaire_complete'] = 1;

		//send email to client and to case manager if theres one assigned.
		$hasCompleted = $this->isQuestionnaireComplete($user_id);

		// we need to check if this has previously been completed as the steps may have been undone to allow access to the fact finder.
		$allReadyCompletedButUndone = false;
		$previous_completed = $this->where('user_id', $user_id)->where('questionnaire_complete', 1)->countAllResults();
		if ($previous_completed && $previous_completed > 0) {
			$allReadyCompletedButUndone = true;
		}

		// If the questionnaire has not been completed or steps have been undone to allow access to the fact finder then we want to send the email out and reset the steps.
		if ($hasCompleted == FALSE || $allReadyCompletedButUndone == true) {

			//we only want to send the auto email once so the code is inside a check	             
			$assigned_to = 25;

			//get the notification wording from the database
			$db = $this->cmDB;

			$table = ($is_portal === false) ? "step_manager" : "portal_step_manager";
			$builder = $db->table($table);

			$client_msg = $builder->select('notification_message')->where('id', 2)->get()->getRow();
			$client_msg = $client_msg->notification_message;

			$table = ($is_portal === false) ? "step_manager_bridge" : "portal_step_manager_bridge";
			$bridgeBuilder = $db->table($table);
			$ins = [
				'case_id' => $case_id,
				'step_id' => 1,
				'status' => 1,
				'date_completed' => date("Y-m-d H:i:s"),
				'completed_by' => 37
			];
			$bridgeBuilder->insert($ins);

			//get the email wrapper template
			$main_email_template = $this->getEmailWrapper($case_id);

			//Client Notification, update the steps manager, again we will update for all three types incase a case type gets changed
			//we want to send an email out to the user their order details.

			if ($isJointApp === true) {
				$body = str_replace('Dear {FIRST_NAME},', "", $client_msg);
			} else {
				$body = str_replace('{FIRST_NAME}', ucfirst($get_case_details->Forename), $client_msg);
			}


			//str replace the details into placeholders
			$subject = "Fact Finder Questionnaire Complete.";
			$email_body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
			$email_body = str_replace('{MAIN_SUBJECT}', $subject, $email_body);
			$email_body =  str_replace('{MAIN_CONTENT}', $body, $email_body);

			//only add to the steps manager if the email was sent
			$checkEmail = $this->sendEmail($user_email, $subject, $email_body);
			if ($checkEmail === false) {
				// Add to failed_emails table
				$failedEmailBuilder = $this->getFailedEmailTable();
				$ins = [
					'case_id' => $case_id,
					'message' => $email_body,
					'subject' => $subject . " (Client)"
				];
				$failedEmailBuilder->insert($ins);
			}


			//add a case note to say that the questionnaire has been completed
			$note_ins = array(
				'note' => 'Questionnaire completed and email notification sent to client.',
				'added_by_system' => 1,
				'case_id' => $case_id
			);

			$note = 'Questionnaire completed and email notification sent to client.';
			$this->addCaseNote($case_id, $note, null, 1);

			//add this action to the steps bridge table
			$ins  = [
				'case_id' => $case_id,
				'step_id' => 2,
				'status' => 1,
				'date_completed' => date("Y-m-d H:i:s"),
				'completed_by' => 37
			];
			$bridgeBuilder->insert($ins);


			//CM Notification - Get the name and email address
			$cm_msg = $builder->select('notification_message')->where('id', 3)->get()->getRow();
			$cm_msg = $cm_msg->notification_message;

			$db = $this->adminDB;
			$builder = $db->table("users");

			$get_cm_details = $builder->select('first_name,email')->where('id', $assigned_to)->get()->getRow();
			$cm_email = $get_cm_details->email;
			$cm_name = $get_cm_details->first_name;


			//Send CM notification email
			$subject = "Fact finder questionnaire complete.";
			$to = (getenv("CI_ENVIRONMENT") == "production") ? $cm_email . ", nicki.matthews@divorce-online.co.uk" : "ian.zammit@divorce-online.co.uk";

			//get package details
			$db = $this->cmDB;
			$builder = $db->table("packages");

			$package_details = $builder->select('PackageCode,PackageName')->where('id', $package_id)->get()->getRow();
			$package_name = $package_details->PackageName;
			$package_code = $package_details->PackageCode;

			$body = str_replace('{FIRST_NAME}', ucfirst($cm_name), $cm_msg);
			$body = str_replace('{CASE_ID}', $case_id, $body);

			if ($package_code == "NSD") {
				//update the case so we know its a non standard divorce
				$caseBuilder = $db->table("cases");
				$caseBuilder->where('ID', $case_id)->set(['is_non_standard' => 1])->update();
				$body = str_replace('{NSD}', "<b>** THIS IS A NON STANDARD DIVORCE **</b><br /><br />", $body);
			} else {
				$body = str_replace('{NSD}', "", $body);
			}

			$body = str_replace('{PACKAGE_NAME}', $package_name, $body);
			$body = str_replace('{PACKAGE_CODE}', $package_code, $body);

			//str replace the details into placeholders
			$email_body = str_replace('{IMG_PATH}', $this->imageURL, $main_email_template);
			$email_body = str_replace('{MAIN_SUBJECT}', $subject, $email_body);
			$email_body =  str_replace('{MAIN_CONTENT}', $body, $email_body);

			$checkEmail = $this->sendEmail($to, $subject, $email_body);
			if ($checkEmail === false) {
				// Add to failed_emails table
				$failedEmailBuilder = $this->getFailedEmailTable();
				$ins = [
					'case_id' => $case_id,
					'message' => $email_body,
					'subject' => $subject . " (CM)"
				];
				$failedEmailBuilder->insert($ins);
			}

			//update the steps_bridge table
			$ins = [
				'case_id' => $case_id,
				'step_id' => 3,
				'status' => 1,
				'date_completed' => date("Y-m-d H:i:s"),
				'completed_by' => 37
			];
			$bridgeBuilder->insert($ins);


			// the court will always be SOUTHAMPTON RDC, if the case is a managed case then we want to create a record in the court details table so this is populated across all forms
			$packageBuilder = $db->table("packages");
			$check_for_managed = $packageBuilder->select('is_managed')->where('ID', $package_id)->get()->getRow();

			if ($check_for_managed->is_managed == 1) {
				//create a row for the court details
				$courtBuilder = $db->table("court_details");
				$ins = array(
					'court_name' => 'BURY ST EDMUNDS', //from the 12-01-2021 this was changed from southampton
					'case_id' =>  $case_id
				);
				$courtBuilder->insert($ins);
			}
		}


		if ($ignore_user_update === false) {
			if (!array_key_exists("date_completed", $data)) {
				$data['date_completed'] = date("Y-m-d H:i:s");
			}

			if ($this->where('user_id', $user_id)->set($data)->update()) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}



	public function updateStandardFFStatus($user_id, $status)
	{
		if ($this->hasCompletedStandardQuestionnaire($user_id) === false) {
			$this->where('user_id', $user_id)->set(['questionnaire_section_status' => (int)$status])->update();
		}
	}

	public function checkForAccountLock($user_id)
	{
		//get the case id
		$case_id = $this->returnCaseIDFromUserId($user_id);

		//see if this questionnaire has been unlocked
		$builder = $this->getCasesTable();
		$unlock_check = $builder->where('ID', $case_id)->where('case_unlocked', 1)->countAllResults();
		if ($unlock_check == 1) {
			return FALSE;
		} else {
			$stepBuilder = $this->getStepManagerBridgeTable();
			return ($stepBuilder->where('case_id', $case_id)->where('step_id', 4)->where('status', 1)->where('undone', 0)->countAllResults() == 1) ? TRUE : FALSE;
		}
	}


	public function checkQuestionnaireRow($user_id)
	{
		$check_for_row = $this->where('user_id', $user_id)->countAllResults();

		//no record so create one..
		if ($check_for_row == 0) {
			$ins = array();
			$ins['user_id'] = $user_id;
			$ins['date_created'] = date("Y-m-d H:i:s");
			$this->insert($ins);
			return $this->insertID();
		} elseif ($check_for_row == 1) {

			//return the id
			$rowid = $this->select('id')->where('user_id', $user_id)->get()->getRow();
			return $rowid->id;
		}
	}


	public function hasCompletedStandardQuestionnaire($user_id)
	{
		// Get case ID
		$case_id = $this->returnCaseIDFromUserId($user_id);

		// Check if the step 1 from the steps manager has been completed and use that for is_complete
		$builder = $this->getStepManagerBridgeTable();
		$check_for_complete = $builder->where('case_id', $case_id)
			->where('status', 1)
			->where('step_id', 1)
			->where('undone', 0)
			->countAllResults();

		return ($check_for_complete <= 0) ? false : true;
	}

	public function isQuestionnaireComplete($user_id)
	{
		$user_id = (int)$user_id;
		$is_complete = $this->where('user_id', $user_id)->where('questionnaire_complete', 1)->countAllResults();
		return ($is_complete == 1) ? TRUE : FALSE;
	}

	public function getChildrenBySpouse($user_id)
	{
		//get the questionnaire id
		$qid = $this->getQuestionnaireId($user_id);
		return $this->query("SELECT * FROM divorce_child_bridge WHERE user_id = {$user_id} AND qid = {$qid} AND child_of_spouse = 1 AND row_status = 1")->getResultArray();
	}

	public function getChildrenByOther($user_id)
	{
		//get the questionnaire id
		$qid = $this->getQuestionnaireId($user_id);
		return $this->query("SELECT * FROM divorce_child_bridge WHERE user_id = {$user_id} AND qid = {$qid} AND child_of_other = 1 AND row_status = 1")->getResultArray();
	}


	public function saveMarriageCertPortal($upload_path = NULL, $file_name = NULL, $mime_type = NULL, $data)
	{

		if (!is_array($data)) {
			return redirect()->to('account/portal');
		}

		$user_id = session("user_id");

		//unset any array elements we dont need
		unset($data['save_portal']);

		if (array_key_exists("delete_cert", $data)) {
			unset($data['delete_cert']);
		}

		//see if we have a file uploaded
		if ($file_name != NULL) {
			$data['certificate_url'] = $upload_path . $file_name;
			$data['file_name'] = htmlentities(trim($file_name));
			$data['mime_type'] = htmlentities(trim($mime_type));
		}

		//update the record
		if ($this->where('user_id', $user_id)->set($data)->update()) {
			return TRUE;
		} else {
			///query failed
			return FALSE;
		}
	}


	public function savePortalData($user_id, $data)
	{
		unset($data['save_portal']);
		$update = ["pay_portal_cc" => $data['pay_portal_cc'], "portal_fee_ref" => $data['portal_fee_ref'], "portal_yes_no" => 1];
		if ($this->where('user_id', $user_id)->set($update)->update()) {
			return true;
		} else {
			return false;
		}
	}

	public function checkIfUserHasMarriageCertificate($user_id)
	{
		$row = $this->select('certificate_url')
			->where('user_id', $user_id)
			->get()
			->getRow();

		return ($row && !empty($row->certificate_url)) ? true : false;
	}

	public function returnCertAsBase64($user_id)
	{
		$row = $this->select('certificate_url')
			->where('user_id', $user_id)
			->get()
			->getRow();

		if ($row && $row->certificate_url) {
			$path = ROOTPATH . $row->certificate_url;
			if (file_exists($path) && $row->certificate_url != "") {
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);

				// if the url contains .pdf, then we need to return a string that says "Unable to display PDF"
				if ($type == "pdf") {
					return "Unable to display PDF";
				}
				return base64_encode($data);
			} else {
				return null;
			}
		}

		return null;
	}

	################################################
	################# HELPER  ######################
	################################################

	public function sendEmail($userEmail, $subject, $body) {
		$graph = new MicrosoftGraphMail();

		return $graph->send(
			$userEmail,
			$subject,
			$body
		);
	}

	public function addCaseNote($caseID, $note, $adminID = null, $addedBySystem = null)
	{
		$db = $this->cmDB;
		$builder = $db->table("case_notes");
		$builder->insert(['case_id' => $caseID, 'note' => $note, 'admin_id' => $adminID, 'added_by_system' => $addedBySystem]);
	}

	public function getNotificationWording($ref)
	{
		$builder = $this->getStepManagerTable();
		return $builder->select('notification_message')->where('ref', $ref)->get()->getRow();
	}


	public function getEmailWrapper($case_id)
	{

		//find out if we need the dol wrapper or qed
		$builder = $this->getCasesTable();
		$sale_source = $builder->select('SaleSource')->where('ID', $case_id)->get()->getRow();
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

	public function returnEmailTemplate($template)
	{
		$db = $this->cmDB;
		$builder = $db->table("email_templates");
		return $builder->select('subject,body, ')->where('email_ref', $template)->get()->getRow();
	}

	public function fetchMarriageCert($user_id)
	{
		//show the image, get the image path and mime type from the database
		$image_data = $this->select('certificate_url,mime_type')->where('user_id', $user_id)->get()->getRow();

		if ($image_data) {
			$path = ROOTPATH . $image_data->certificate_url;
			if (file_exists($path) && $image_data->certificate_url != "") {
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				return $base64;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	public function getDivorceGrounds()
	{
		$builder = $this->getDivorceGroundsTable();
		return $builder->select('id,description')->get()->getResultArray();
	}

	public function dateDifference($user_id)
	{
		$qdata = $this->select('marriage_date_of_marriage,marriage_date_of_separation')
			->where('user_id', $user_id)
			->get()
			->getResultArray();

		$today = date("Y-m-d");
		$datetime1 = new \DateTime(date("Y-m-d", strtotime('-1 day', strtotime($today)))); // remove one day from the current date
		$datetime2 = new \DateTime(date("Y-m-d", strtotime($qdata[0]['marriage_date_of_separation'])));

		$interval = $datetime1->diff($datetime2);
		return $interval->format('%a days');
	}

	public function checkIsCivilPartnership($user_id)
	{
		$data = $this->select('is_civilpartnership')
			->where('user_id', $user_id)
			->get()
			->getRow();

		return ($data->is_civilpartnership == 1) ? 1 : 0;
	}

	public function checkIsSameSex($user_id)
	{
		$data = $this->select('is_civilpartnership')
			->where('user_id', $user_id)
			->get()
			->getRow();

		return ($data->is_civilpartnership == 3) ? 1 : 0;
	}

	public function getSectionWording($currentStage)
	{
		switch ($currentStage) {
			case 2:
				return " - Section 2";
				break;
			case 3:
				return  " - Section 3";
				break;
			case 4:
				return  " - Section 4";
				break;
			case 5:
				return "- Section 5";
				break;
			case "stage6":
				return " - Section 6";
				break;
			case 6:
				return  " - Complete";
				break;
		}
	}

	public function returnCountries()
	{
		$builder = $this->getCountriesTable();
		return $builder->get()->getResultArray();
	}

	public function getQuestionnaireStatus($questionnaire_id)
	{
		$res = $this->select('questionnaire_section_status AS status')->where('id', $questionnaire_id)->get()->getRow();
		return ($res && $res->status != "") ? $res->status : 1;
	}

	public function getQuestionnaireId($user_id)
	{
		$row = $this->select('id')->where('user_id', $user_id)->get()->getRow();
		return (int)$row->id;
	}

	public function returnCaseIDFromUserId($user_id)
	{
		$builder = $this->getCasesTable();
		$id = $builder->select('ID')->where('UserId', $user_id)->get()->getRow();
		return ($id) ? $id->ID : null;
	}

	public function returnRow($user_id)
	{
		return $this->where('user_id', $user_id)->get()->getRow();
	}

	public function hasSigned($user_id, $sendRow = false)
	{
		$row = $this->select('portal_yes_no')->where('user_id', $user_id)->get()->getRow();

		if ($sendRow === true) {
			return $row;
		}
		return ($row && $row->portal_yes_no == 0) ? false : true;
	}

	public function setAsPortalCase($user_id)
	{
		return ($this->where('user_id', $user_id)->set(['portal_case' => 1])->update());
	}

	public function checkIfPortal($user_id)
	{
		$row = $this->select('portal_case')->where('user_id', $user_id)->get()->getRow();
		return ($row->portal_case == 1) ? true : false;
	}


	public function getMarriageCert($user_id)
	{
		return $this->select('certificate_url,file_name')->where('user_id', $user_id)->get()->getRow();
	}

	public function deleteCert($user_id, $current_image_details)
	{
		if ($current_image_details && $current_image_details->certificate_url != null) {
			$certUrl = ltrim($current_image_details->certificate_url, "/");
			if (file_exists(ROOTPATH . $certUrl)) {
				if (unlink(ROOTPATH . $certUrl)) {
					$update = array('certificate_url' => NULL, 'mime_type' => NULL, 'file_name' => NULL);
					$this->where('user_id', $user_id)->set($update)->update();
				}
			}
		}
	}

	public function unlinkOldCert($current_image_details, $fileName)
	{
		if ($current_image_details && $current_image_details->certificate_url != "" && $fileName != $current_image_details->file_name) {
			if (file_exists(ROOTPATH . $current_image_details->certificate_url)) {
				unlink(ROOTPATH . $current_image_details->certificate_url);
			}
		}
	}

	public function getMarriageUploadErrors($user_id, $file)
	{
		$size = $file->getSizeByUnit('mb');
		if ($size > 4) {
			return ['error', 'File to large (max 4MB)', null];
		}

		$type = $file->getMimeType();
		if (!in_array($type, ['image/png', 'image/jpeg', 'image/JPEG', 'application/pdf'])) {
			return ['error', 'Invalid file format (PNG, PDF and JPEG only)', null];
		}
	}


	public function resetStep5Details($user_id, $stage = NULL)
	{

		switch ($stage) {
			case 1: //adultary		
				$upd = array();
				$upd['on_grounds'] = 1;
				$upd['behaviour_reason'] = "";
				return ($this->where('user_id', $user_id)->set($upd)->update()) ? TRUE : FALSE;
				break;
			case 2: //adultary              
				$upd = array();
				$upd['on_grounds'] = 2;
				$upd['adultery_lived_with'] = 0;
				$upd['adultery_gender'] = "";
				$upd['adultery_where'] = "";
				$upd['adultery_when_discovered_date'] = "";
				return ($this->where('user_id', $user_id)->set($upd)->update()) ? TRUE : FALSE;
				break;
			case 3:
			case 4:
				$upd = array();
				$upd['adultery_lived_with'] = 0;
				$upd['adultery_gender'] = "";
				$upd['adultery_where'] = "";
				$upd['adultery_when_discovered_date'] = "";
				$upd['behaviour_reason'] = "";
				$this->where('user_id', $user_id)->set($upd)->update();
				break;

			case 5:
				$upd = array();
				$upd['date_of_desertion'] = NULL;
				$this->where('user_id', $user_id)->set($upd)->update();
				break;

			default:
				//do nothing
				break;
		}
	}


	public function getNotifications($case_id, $returnFirstRow = false, ?bool $usePortalOverride = null)
	{
		$user_id = session("user_id");
		$check_is_portal = $this->select('portal_case')->where('user_id', $user_id)->get()->getRow();
		$use_portal = false;

		if ($check_is_portal != NULL && $check_is_portal->portal_case == 1) {
			$use_portal = true;
		}
		if ($usePortalOverride !== null) {
			$use_portal = $usePortalOverride;
		}

		if ($use_portal == TRUE) {

			$query = $this->cmDB->query("
				 		SELECT sm.step,sm.notification_wording,smb.date_added,sm.id
						FROM portal_step_manager_bridge AS smb
						JOIN portal_step_manager AS sm ON smb.step_id=sm.id
						WHERE smb.case_id = " . $case_id . "
						AND smb.undone = 0
						AND sm.id NOT IN(2,3)
						AND smb.user_seen_notification = 0
						AND sm.action = 'Notification Email Sent'")->getResultArray();
		} else {
			$query = $this->cmDB->query("
				 		SELECT sm.step,sm.notification_wording,smb.date_added, sm.id
						FROM step_manager_bridge AS smb
						JOIN step_manager AS sm ON smb.step_id=sm.id
						WHERE smb.case_id = " . $case_id . "
						AND smb.undone = 0
						AND sm.id NOT IN(2,3)
						AND smb.user_seen_notification = 0
						AND sm.action = 'Notification Email Sent'")->getResultArray();
		}

		if ($returnFirstRow === true) {
			return (count($query) > 0) ? $query[0] : [];
		} else {
			return count($query);
		}
	}

	public function getNotificationReplacements($user_id)
	{

		$builder = $this->getCourtDetailsTable();
		$userBuilder = $this->getUserTable();
		$user = $userBuilder->select('Forename')->where('ID', $user_id)->get()->getRow();
		$res = $builder->select('case_number,date_issued,nisi_date')->where('ID', $user_id)->get()->getRow();

		return [
			"forename" => $user->Forename,
			"case_number" => ($res && $res->case_number !== null) ? $res->case_number : null,
			"date_issued" => ($res && $res->date_issued !== null) ? $res->date_issued : null,
			"nisi_date" => ($res && $res->nisi_date !== null) ? $res->nisi_date : null,
		];
	}


	public function hasDateIssued($case_id)
	{
		$builder = $this->getPortalStepsManagerBridgeTable();
		return ($builder->where('step_id', 8)->where('case_id', $case_id)->where('undone', 0)->countAllResults() > 0) ? true : false;
	}

	public function checkIfNisiDateDDPCBCS($package_code)
	{
		// If $package_code is not DDPCBCS then return false as we only want this for the one package
		if($package_code !== "DDPCBCS"){
			return false;
		}

        $case_id = $this->returnCaseIDFromUserId(session("user_id"));

		//if we dont have a valid case id return false
		if(!$case_id){
			return false;
		}

		// First check if has a date issued
		$res = $this->hasDateIssued($case_id);

		if($res === false){
			return false;
		}

		// We have a date issued so now we want to check if that date is over three months old
        $courtDetails = $this->getCourtDetailsTable();
		$res = $courtDetails->where('case_id', $case_id)->where('nisi_date IS NOT NULL AND nisi_date != ""')->get()->getRow();
		if($res && !empty($res->nisi_date))
		{
			$nisi_date_raw = $res->nisi_date; // stored as dd-mm-YYYY (e.g. 10-03-2026)
			$nisi_date = \DateTime::createFromFormat('!d-m-Y', $nisi_date_raw);
			if ($nisi_date === false) {
				return false;
			}
			$today = new \DateTime('today');
			return $nisi_date <= $today;
			
		}else{
			return false;
		}
	}


	public function checkStepComplete($step_id, $case_id)
	{
		$builder = $this->getStepManagerBridgeTable();
		$count = $builder->where('case_id', $case_id)
			->where('status', 1)
			->where('undone', 0)
			->where('step_id IN(' . $step_id . ')')
			->countAllResults();

		return ($count <= 0) ? false : true;
	}


	public function getCurrentStepWording($case_id)
	{
		$builder = $this->getPortalStepsManagerBridgeTable();
		$check_for_steps = $builder->where('case_id', $case_id)->where('step_id', 1)->countAllResults();
		if ($check_for_steps > 0) {
			// We have steps, get the latest step
			$wording = $builder->select('portal_step_manager_bridge.id,portal_step_manager_bridge.step_id, portal_step_manager.notification_wording')
				->join('portal_step_manager', 'portal_step_manager_bridge.step_id=portal_step_manager.id')
				->where('portal_step_manager_bridge.status', 1)
				->where('portal_step_manager_bridge.undone', 0)
				->where('portal_step_manager_bridge.case_id ', $case_id)
				->orderBy('portal_step_manager_bridge.id', 'DESC')
				->limit(1)
				->get()
				->getRow();

			return ($wording) ? $wording->notification_wording : null;
		} else {
			return NULL;
		}
	}


	public function sendVipEmail($package)
	{
		$case_id = $this->returnCaseIDFromUserId(session("user_id"));

		// Only send if we have a case ID
		$url = (getenv("CI_ENVIRONMENT") == "production") ? "https://www.olstools.co.uk/case-manager/" : "https://www.case-manager-dev.co.uk/";
		if ($case_id) {
			$wording = "
				Hi, <br /><br />
				This email is inform you that VIP case {$case_id} has uploaded their marriage certificate.<br /><br />

				Please make contact with the client asap.<br /><br />

				Login to view the case details - <a href='{$url}cases/details/{$case_id}'>View Case</a><br /><br />

				Thanks.<br />
			";

			$subject = "VIP Client has uploaded their marriage certificate!";
			$to = (getenv("CI_ENVIRONMENT") == "production") ? "support@divorce-online.co.uk" : "ian.zammit@divorce-online.co.uk";
			$this->sendEmail($to, $subject, $wording);
		}
	}


	public function uploadExtraFiles($user_id, $file)
	{
		$upload_path = 'questionnaire_files/supporting_files/' . $user_id . '/';
		// Check the upload path exists, if not create it
		if (!is_dir(ROOTPATH . $upload_path)) {
			mkdir(ROOTPATH . $upload_path, 0777, true);
		}

		$errors = [];
		$success = [];
		foreach ($file['extraFiles'] as $f) {
			if ($f->isValid() && ! $f->hasMoved()) {
				// Check if the file passes the validation using function getMarriageUploadErrors($user_id, $file)	
				list($status, $message) = $this->getMarriageUploadErrors($user_id, $f);
				if ($status == 'error') {
					// File is not valid, so we add the error message to our list
					$errors[] = "File {$f->getName()} - {$message}";
				} else {
					// Valid file, move it to the upload directory, if the file was successfully uploaded add that to an array
					// We need to check if the file was successfully uploaded
					// Overwrite the file if it already exists
					$uploadedFile = $f->move(ROOTPATH . $upload_path, $f->getName(), true);
					if ($uploadedFile) {
						$success[] = "File {$f->getName()} - uploaded successfully";
					} else {
						$errors[] = "File {$f->getName()} - upload failed";
					}
				}
			} else if (!$f->isValid()) {
				$errors[] = "File {$f->getName()} - upload failed";
			}
		}

		// We need to return the errors and success messages
		return ['errors' => $errors, 'success' => $success];
	}

	public function getExtraDivorceCertFiles($user_id)
	{
		$upload_path = '/questionnaire_files/supporting_files/' . $user_id . '/';
		$files = glob(ROOTPATH . $upload_path . '*');

		// Return just the filenames instead of full paths
		$filenames = [];
		foreach ($files as $file) {
			$filenames[] = basename($file);
		}
		return $filenames;
	}


	public function deleteFile($user_id, $file)
	{
		// Check the user_id matches the user_id in the session
		if ($user_id != session("user_id")) {
			return ['error' => 'User ID does not match'];
		}

		// Check if the file exists
		$upload_path = '/questionnaire_files/supporting_files/' . $user_id . '/';
		$file_path = ROOTPATH . $upload_path . $file;  

		if (file_exists($file_path)) {
			// if the file exists, delete it and return a success message
			if (unlink($file_path)) {
				return ['success' => 'File deleted successfully'];
			} else {
				return ['error' => 'File could not be deleted'];
			}
		} else {
			return ['error' => 'File does not exist'];
		}
	}
  
	public function getCurrentCaseNumber($case_id)
	{
		// if no case id return empty string
		if (!$case_id) {
			return "";
		}

		// get the case number from the court details table
		// first check we have a row that matches the case id and date_issued is not null or empty
		$builder = $this->getCourtDetailsTable();
		$row = $builder->where('case_id', $case_id)->where('date_issued IS NOT NULL AND date_issued != ""')->get()->getRow();
		if ($row) {
			return $row->case_number ?? "";
		} else {
			return "";
		}
	}




	################################################
	################# DATABASES ####################
	################################################

	public function getUserTable()
	{
		$db = db_connect();
		return $db->table("users");
	}

	public function getCountriesTable()
	{
		$db = db_connect();
		return $db->table("country");
	}

	public function getDivorceGroundsTable()
	{
		$db = db_connect();
		return $db->table("divorce_grounds");
	}

	public function getDivorceChildTable()
	{
		$db = db_connect();
		return $db->table("divorce_child_bridge");
	}

	public function getCourtDetailsTable()
	{
		$db = db_connect();
		return $db->table("court_details");
	}

	public function getCasesTable()
	{
		$db = $this->cmDB;
		return $db->table("cases");
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

	public function getStepManagerTable()
	{
		$db = db_connect();
		return $db->table("step_manager");
	}

	public function getFailedEmailTable()
	{
		$db = db_connect();
		return $db->table("failed_emails");
	}
}
