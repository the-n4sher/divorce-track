<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;

$stripePath = str_replace("public/", "", getcwd() . '/vendor/stripe/stripe-php/init.php');
require_once($stripePath);

class Account extends BaseController
{
    protected $user;
    protected $model;
    protected $consentModel;
    protected $questionnaireModel;
    protected $userModel;
    protected $authService;
    protected $validation;
    protected $response;
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
    protected $use_portal;
    protected $hide_for_portal;
    protected $date_for_condition;
    protected $consent_questions_count;
    protected $orderPlaced;
    protected $consentOrderFFComplete;
    protected $vipPackages;
    protected $current_case_number;


    public function __construct()
    {
        $this->authService = service("auth");
        $this->user = $this->authService->getCurrentuser();
        // make sure the user is logged in
        if ($this->user === null) {
            session()->set('redirect_url', current_url(true));

            // Redirect to login page
            return redirect()->to(site_url('login'));
        }
        $this->model = model("AccountModel");
        $this->consentModel = model("ConsentOrderModel");
        $this->questionnaireModel = model("QuestionnaireModel");
        $this->userModel = model("UserModel");
        $this->validation = service("validation");
        $this->response = service("response");

        $this->res_logged_in = (session()->has("respondent_only")) ? true : false;
        $this->date_for_condition = "2022-03-31 08:45:00";
        $this->vipPackages = ["DDPV"];
        $this->current_case_number = "";

        // See if this is the users first login
        $this->checkFirstLogin();
        $this->checkRespondentLoggedIn();
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
        $this->setCurrentCaseNumber();
    }

    public function setCurrentCaseNumber()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->current_case_number = $this->questionnaireModel->getCurrentCaseNumber($case_id);
    }


    public function returnCaseID($user_id)
    {
        return $this->consentModel->returnCasesIdFromUserId($user_id);
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
            'consent_ff_complete' => $this->consentOrderFFComplete,
            'date_for_condition' => $this->date_for_condition,
            'current_case_number' => $this->current_case_number
        ];
    }

    public function setConsentOrderCompleteStatus()
    {
        $user_id = session("user_id");
        $this->consentOrderFFComplete = $this->consentModel->isConsentOrderFFComplete($user_id);
    }


    public function setOrderPlaced()
    {
        $case_id = $this->returnCaseID(session("user_id"));
        $this->orderPlaced = $this->model->getOrderPlaced($case_id);
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
        $this->requires_q = $this->model->checkPackage($case_id);
    }


    public function setMenuPackageData()
    {
        $this->allUserPackageData = $this->model->getPackagedata(session("user_id"));
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

    public function checkFirstLogin()
    {
        $request = service('request');
        $controller = $request->getUri()->getSegment(1);
        $method = $request->getUri()->getSegment(2);

        if ($method != "update-details" && $controller != "logout") {
            $firstLogin = $this->userModel->isFirstLogin(session("user_id"));
            if ($firstLogin === true) {
                return redirect()->to(site_url('account/update-details'));
            } else {
                // Mark setup complete in session so nav shows
                session()->set('account_setup', true);
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


    public function index()
    {

        if (($this->package_consent_type == 1 && $this->package_code && $this->package_code != "DDPCBCS") || $this->isNonPortalConsentPackage()) {
            return redirect()->to('consent-order/consent-order-progress');
        }

        $conditionalOrderDate = null;
        // Check if we have a conditional order date - but only if they are completing a questionnaire and its a portal case and is issued
        if ($this->requires_q == true && $this->use_portal == true && $this->is_issued == true) {
            // we need to get the case id from the user id
            $case_id = $this->returnCaseID(session("user_id"));
            $conditionalOrderDate =  $this->model->checkForConditionalOrderDate($case_id);
        }

        $case_id = $this->returnCaseID(session("user_id"));
        $res = $this->questionnaireModel->getCurrentStepWording($case_id);
        $currentStepWording = ($res === NULL) ? "" :  $res;
        $isVip = (in_array($this->package_code, $this->vipPackages)) ? true : false;

        // has this user completed uploaded their marriage cert
        $check_for_cert = $this->model->dtrackDB->table("questionnaire")->where('portal_yes_no', 1)->where('user_id', session("user_id"))->countAllResults();

        $view_data = [
            'title' => "Account Home",
            'current_step_wording' => $currentStepWording,
            'is_vip' => $isVip,
            'cert_found' => ($check_for_cert && $check_for_cert > 0) ? true : false,
            'conditional_order_date' => $conditionalOrderDate
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/dashboard", $view_data);
    }


    public function firstLogin()
    {
        if ($this->request->getPost("update_details")) {
            $rules = $this->model->updateDetailsFirstTime;
            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run()) {
                //update the details
                $data = $this->request->getPost();
                if ($this->model->firstTimeUpdateDetails($data, session("user_id")) === true) {
                    session()->setFlashdata("SUCC", "Account details successfully updated");
                    session()->set('account_setup', true);
                    return redirect()->to(site_url("account"))->withCookies();
                } else {
                    session()->setFlashdata("ERR", "We have encountered an error, please try again.");
                    return redirect()->back()->withInput()->withCookies();
                }
            } else {
                session()->setFlashdata("errors", $this->validation->getErrors());
                return redirect()->back()->withInput()->withCookies();
            }
        }

        $view_data = [
            'title' => "Update Details",
            'secert_questions' => $this->model->getSecretQuestions()
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/firstlogin", $view_data);
    }


    public function payments()
    {
        // Get the package details for this user
        $packageDetails = $this->model->getOriginalPackageData(session("user_id"));
        $upgrades = $this->model->getAllPaidUpgrades(session("user_id"));
        $fees = $this->model->getAllPaidFees(session("user_id"));
        $services = $this->model->getAllPaidServices(session("user_id"));
        $addStepMsg = $this->model->checkPaymentStep(session("user_id"));

        $view_data = [
            'title' => "Your Payments",
            'package_details' => $packageDetails,
            'upgrades' => $upgrades,
            'fees' => $fees,
            'services' => $services,
            'add_step_msg' => $addStepMsg
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/payments", $view_data);
    }


    public function myAccount()
    {
        $userRow = $this->model->getUserRow(session("user_id"));
        $countries = $this->model->getCountries();
        $secretQuestions = $this->model->getSecretQuestions();

        $view_data = [
            'title' => 'My Account',
            'user_data' => $userRow,
            'countries' => $countries,
            'secret_questions' => $secretQuestions
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/myaccount", $view_data);
    }

    public function updateMyAccount()
    {

        $data = $this->request->getPost();
        $rules = $this->model->myAccountRules;

        // Is the user trying to change their password?
        if ($data['password'] == "" && $data['conf_password'] == "") {
            unset($rules["password"], $rules['conf_password'], $data['password'], $data['conf_password']);
        }

        // Remove the respondent email rule if its not a split application
        if (array_key_exists("split_application", $data) && $data['split_application'] != "1") {
            unset($rules['RespondentEmail']);
        }

        // Set the rules
        $this->validation->setRules($rules);
        if (!$this->validation->withRequest($this->request)->run()) {
            session()->setFlashdata("errors", $this->validation->getErrors());
            return redirect()->to("account/my-account")->withInput()->withCookies();
        } else {

            // Before we update, we will check the input for common sql and xss attacks
            helper('securityscan');
            if (isset($data) && contains_attack_strings($data)) {

                // Log to writable/attacks robustly using file_put_contents for repeated writes.
                $log_file_path = WRITEPATH . "attacks/attacks.txt";

                // Remove sensitive data and mark if the attack used those fields
                $data_to_log = $data;
                if (isset($data_to_log['password'])) {
                    if (contains_attack_strings(['password' => $data_to_log['password']])) {
                        $data_to_log['password'] = "PASSWORD_ATTACKED";
                    } else {
                        $data_to_log['password'] = "PASSWORD_REMOVED_FOR_SECURITY_REASONS";
                    }
                }
                // Account for possible variation in key (case)
                if (isset($data_to_log['SecertAnswer'])) {
                    if (contains_attack_strings(['SecertAnswer' => $data_to_log['SecertAnswer']])) {
                        $data_to_log['SecertAnswer'] = "SECRET_ANSWER_ATTACKED";
                    } else {
                        $data_to_log['SecertAnswer'] = "SECRET_ANSWER_REMOVED_FOR_SECURITY_REASONS";
                    }
                }

                // We also need to do this for conf_password
                if (isset($data_to_log['conf_password'])) {
                    if (contains_attack_strings(['conf_password' => $data_to_log['conf_password']])) {
                        $data_to_log['conf_password'] = "CONFIRM_PASSWORD_ATTACKED";
                    } else {
                        $data_to_log['conf_password'] = "CONFIRM_PASSWORD_REMOVED_FOR_SECURITY_REASONS";
                    }
                }

                $log_entry = date("Y-m-d H:i:s") . " - " . session("user_id") . " - " . print_r($data_to_log, true) . "\n";

                // Use file_put_contents with FILE_APPEND and LOCK_EX for reliability
                file_put_contents($log_file_path, $log_entry, FILE_APPEND | LOCK_EX);

                session()->setFlashdata("ERR", "We have encountered an error, please try again.");
                return redirect()->to("account/my-account");
            }

            $upd = $this->model->updateAccountDetails($data);
            if ($upd  === TRUE) {
                session()->setFlashdata("SUCC", "Account details successfully updated");
                return redirect()->to("account/my-account");
            } elseif ($upd == "NOUP") {
                session()->setFlashdata("SUCC", "Nothing to update.");
                return redirect()->to("account/my-account");
            } else {
                //failed, show error
                session()->setFlashdata("ERR", "We have encountered an error, please try again.");
                return redirect()->to("account/my-account");
            }
        }
    }


    public function notifications()
    {
        $user_id = session("user_id");
        $case_id = $this->model->returnCaseIDFromUserId($user_id);

        // Set all notifications to read status
        $this->model->setAllToRead($user_id, $this->use_portal);

        $allNotifications = $this->model->returnAllNotifications($user_id, $case_id, false, $this->use_portal);

        $view_data = [
            'title' => 'Notifications',
            'all_notifications' => $allNotifications,
            'current_n_count' => count($allNotifications)
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/notifications", $view_data);
    }

    public function payFees()
    {
        $user_id = session("user_id");

        //fetch users email
        $user_email = $this->model->returnUserData($user_id, "UserEmail");

        //if the form has been submmited
        if ($this->request->getPost("submit_payment")) {
            $data = $this->request->getPost();

            if ($data['fee_id_check'] == 0 || $data['fee_id_check'] == "") {
                $error = $this->validation->getErrors();
                session()->setFlashdata("user_msg_error", "You must select an option from the fees dropdown.");
                return redirect()->back();
            }

            $data['user_id'] = $user_id;
            $data['user_email'] = $user_email;
            $this->stripe_checkout($data, "fee");
        }

        // Do we have a  vlalid orderPlaced date
        $orderPlaced = ($this->orderPlaced) ? $this->orderPlaced : null;
        $view_data = [
            'title' => 'Payments',
            'fees_paid' => $this->model->getFeesPaid($user_id),
            'fee_options' => $this->model->getFeeOptions($user_id, $orderPlaced),
            'is_complete' => $this->model->isCaseComplete($user_id)
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/pay_fees", $view_data);
    }


    public function upgrade()
    {
        $user_id = session("user_id");
        $user_email = $this->model->returnUserData($user_id, "UserEmail");
        $case_id = $this->model->returnCaseIDFromUserId($user_id);

        if ($this->request->getPost("upgrade_package_payment")) {
            $rules = $this->model->UpgradePaymentRules;
            $this->validation->setRules($rules);


            if ($this->validation->withRequest($this->request)->run()) {

                $data = $this->request->getPost();
                //we need to get the price of the package from the database
                $builder = $this->model->getPackageTable();
                $get_package_details = $builder->select('DigitalPrice,ID,PackageCode')->where('ID', (int)$data['upgrade_package'])->get()->getRow();
                $package_price = $get_package_details->DigitalPrice;
                $package_id = $get_package_details->ID;

                //Set the total
                $data['total'] = $package_price;
                $data['user_id'] = $user_id;
                $data['user_email'] = $user_email;
                $data['case_id'] = $case_id;

                //make payment attempt
                $this->stripe_checkout($data, "upgrade");
            } else {
                $error = $this->validation->getErrors();
                session()->setFlashdata("user_msg_error", $error['upgrade_package']);
                return redirect()->back();
            }
        }

        $upgradeOptions = $this->model->getUpgradeOptions($user_id);

        $view_data = [
            'title' => 'Upgrade',
            'upgrade_options' => $upgradeOptions,
            'has_upgrades' => (count($upgradeOptions) > 0) ? true : false,
            'case_id' => $case_id
        ];
        $view_data = array_merge($view_data, $this->fetchExtraViewData());
        return view("Account/upgrade", $view_data);
    }


    public function stripe_checkout($data, $checkout_type)
    {

        // We need to set an expire date/time
        $checkout_expiry = strtotime(date('Y-m-d H:i:s', strtotime("+30 min")));

        $stripe_key = (env("CI_ENVIRONMENT") == "development") ? env("STRIPE_PUBLISHABLE_KEY_SANDBOX") : env("STRIPE_PUBLISHABLE_KEY_LIVE");
        $stripe = new \Stripe\StripeClient($stripe_key);

        if ($checkout_type == "fee") {
            $fee_id = $data['fee_id_check'];
            if ($fee_id != "") {
                // Get package name
                $builder = $this->model->getPackageTable();
                $package_details = $builder->select('PackageName, DigitalPrice, PackageCode, Disbursement')->where('ID', $fee_id)->get()->getRow();
                if ($package_details != NULL && $package_details->PackageName != "" && $package_details->DigitalPrice != "" && $data['user_id'] != "" && $data['user_email'] != "") {

                    $total = preg_replace('/[^0-9]/', "", $package_details->DigitalPrice);
                    $logPrice = $package_details->DigitalPrice;

                    if ($total != "" && $total != "0.00") {

                        $order_id = $package_details->PackageCode . "-" . time() . rand(1000, 9999);
                        $create_data = array(
                            'customer_email' => $data['user_email'],
                            'billing_address_collection' => 'required',
                            'after_expiration' => [
                                'recovery' => [
                                    'enabled' => True,
                                ],
                            ],
                            'expires_at' => $checkout_expiry,
                            'payment_method_types' => ['card', 'paypal', 'klarna'],
                            'line_items' => [[
                                'price_data' => [
                                    'currency' => 'gbp',
                                    'product_data' => [
                                        'name' => $package_details->PackageName,
                                    ],
                                    'unit_amount' => preg_replace('/[^0-9]/', "", $total),
                                ],
                                'quantity' => 1,
                            ]],
                            'mode' => 'payment',
                            'success_url' => site_url() . 'account/payments/fees?s=1',
                            'cancel_url' => site_url() . 'account/payments/fees',
                            'customer_creation' => 'always',
                            'metadata' => [
                                'voucher_id' => "",
                                'package_code' => '',
                                'type_of_payment' => "DT-FEE",
                                'fee_id' => $fee_id,
                                'user_id' => $data['user_id'],
                                'order_id' => $order_id,
                                'dist' => $package_details->Disbursement,
                                'price' => $logPrice
                            ],
                            'payment_intent_data' => [
                                'description' => $order_id,
                                'metadata' => [
                                    'voucher_id' => "",
                                    'package_code' => '',
                                    'type_of_payment' => "DT-FEE",
                                    'fee_id' => $fee_id,
                                    'user_id' => $data['user_id'],
                                    'order_id' => $order_id,
                                    'dist' => $package_details->Disbursement,
                                    'price' => $logPrice
                                ],
                            ]
                        );

                        $checkout = $stripe->checkout->sessions->create($create_data);
                        if ($checkout != null) {
                            // Redirect to the checkout page
                            return $this->response->redirect($checkout->url);
                        } else {
                            die("Failed to generate payment link. Contact Support");
                        }
                    } else {
                        session()->setFlashdata("user_msg_error", "Payment failed due to incorrect price supplied, please try again.");
                        return redirect()->to(site_url() . "account/payments/fees");
                    }
                }
            }
        }


        if ($checkout_type == "upgrade") {
            $fee_id = $data['upgrade_package'];
            if ($fee_id != "") {
                // Get package name
                $builder = $this->model->getPackageTable();
                $package_details = $builder->select('PackageName, DigitalPrice, PackageCode')->where('ID', $fee_id)->get()->getRow();
                if ($package_details != NULL && $package_details->PackageName != "" && $package_details->DigitalPrice != "" && $data['user_id'] != "" && $data['user_email'] != "" && $data['case_id'] != "") {

                    $total = preg_replace('/[^0-9]/', "", $package_details->DigitalPrice);
                    if ($total != "" && $total != "0.00") {
                        header('Content-Type: application/json');
                        $domain = base_url();
                        $order_id = $package_details->PackageCode . "-" . time() . rand(1000, 9999);

                        $create_data = [
                            'customer_email' => $data['user_email'],
                            'billing_address_collection' => 'required',
                            'after_expiration' => [
                                'recovery' => [
                                    'enabled' => True,
                                ],
                            ],
                            'expires_at' => $checkout_expiry,
                            'payment_method_types' => ['card', 'paypal', 'klarna'],
                            'line_items' => [[
                                'price_data' => [
                                    'currency' => 'gbp',
                                    'product_data' => [
                                        'name' => $package_details->PackageName,
                                    ],
                                    'unit_amount' => preg_replace('/[^0-9]/', "", $total),
                                ],
                                'quantity' => 1,
                            ]],
                            'mode' => 'payment',
                            'success_url' => site_url() . 'account/upgrade?s=1',
                            'cancel_url' => site_url() . 'account/upgrade',
                            'customer_creation' => 'always',
                            'metadata' => [
                                'voucher_id' => "",
                                'package_code' => '',
                                'type_of_payment' => "DT-UPGRADE",
                                'upgrade_id' => $fee_id,
                                'user_id' => $data['user_id'],
                                'case_id' => $data['case_id'],
                                'order_id' => $order_id,
                            ],
                            'payment_intent_data' => [
                                'description' => $order_id,
                                'metadata' => [
                                    'voucher_id' => "",
                                    'package_code' => '',
                                    'type_of_payment' => "DT-UPGRADE",
                                    'upgrade_id' => $fee_id,
                                    'user_id' => $data['user_id'],
                                    'case_id' => $data['case_id'],
                                    'order_id' => $order_id,
                                ],
                            ]
                        ];

                        $checkout = $stripe->checkout->sessions->create($create_data);
                        if ($checkout != null) {
                            // Redirect to the checkout page
                            return $this->response->redirect($checkout->url);
                        } else {
                            die("Failed to generate payment link. Contact Support (UP)");
                        }
                    } else {
                        session()->setFlashdata("user_msg_error", "Payment failed due to incorrect price supplied, please try again.");
                        return redirect()->to(site_url("account/upgrade"));
                    }
                }
            }
        }
    }


    public function verify()
    {
        $user_id = session("user_id");

        // handle the form submission
        if ($this->request->getPost("verify_submit")) {
            $data = $this->request->getPost();

            // Check if the code is valid or not, based on the response we will redirect.
            // if its a success we need to check if the session redirect_url is set, if it is then we redirect to the url.
            // flash data set in the model
            $doCheck = $this->model->verifyVerificationCode($user_id, $data['verification_code']);

            // If false, then we redirect to the verify page
            if ($doCheck === false) {
                return redirect()->to("account/verify")->withInput()->withCookies();
            } else {
                // is true, then we need to check if the session redirect_url is set, if it is then we redirect to the url.
                if (session()->get("redirect_url")) {
                    return redirect()->to(session()->get("redirect_url"));
                } else {
                    return redirect()->to("account")->withCookies();
                }
            }
        }

        // before any further checks we want to see if the user has a temp_bypass set, if they do then we can bypass the 2FA check.
        $tempBypass = $this->model->checkTempBypass($user_id);
        if ($tempBypass) {
            return redirect()->to("account")->withCookies();
        }

        // we only send once, this is managed in the model
        $this->model->resendVerificationCode($user_id, false);

        return view("Account/verify", ['title' => 'Verify Account']);
    }

    public function resendVerificationCode()
    {
        $user_id = session("user_id");
        $this->model->resendVerificationCode($user_id, true);

        session()->setFlashdata("SUCC", "Verification code resent to your email");
        return redirect()->to("account/verify");
    }
}
