<?php

namespace App\Controllers;

use \CodeIgniter\Entity\User;

class Login extends BaseController
{
    private $model;
    private $auth;
    protected $validation;

    public function __construct()
    {
        $this->model = model("UserModel");
        $this->auth = service("auth");
        $this->validation = service("validation");
    }

    public function new()
    {

        if (session()->has('user_id')) {
            return redirect()->to("/account")
                ->with('info', 'Login Successful')
                ->withCookies();
        }
        return view("Login/new", ['title' => 'Login']);
    }

    public function create()
    {

        // the user is still blocked from making requests
        if (session()->has("locked_out")) {
            return redirect()->to("to-many-attempts");
        }


        $throttler = service("throttler");

        // The user can attempy 10 logins in a 1 minute period
        $allowed = $throttler->check('create', 10, MINUTE);

        $email = trim($this->request->getPost("email"));
        $password = trim($this->request->getPost("password"));


        if (!$allowed) {
            session()->setTempdata('locked_out', true, 300);
            return redirect()->to("to-many-attempts");
        } else {

            if ($this->auth->login($email, $password)) {

                // DOES THE PASSWORD CONTAIN TEMP? If it does, this is an admin so we want to skip 2FA
                if (strpos($password, 'TMPDOL') !== false) {
                    session()->set('admin_login', true);
                }

                // Is the user on hold?
                $onHold = $this->model->checkIfOnHold(session("user_id"));

                if ($onHold == true) {
                    return redirect()->to("on-hold");
                }

                // Does the user need to reactivate their account?
                $reActivate = $this->model->checkForReactivation(session("user_id"));
                if ($reActivate == true) {
                    return redirect()->to("reactivate");
                }

                // Does the user need to update their details on first login?
                $firstLogin = $this->model->isFirstLogin(session("user_id"));
                if ($firstLogin === true) {
                    return redirect()->to("account/update-details");
                }

                // Has this user past the 2FA? Dont check if this is an admin login
                if (!session()->has('admin_login')) {
                    $check2FA = $this->model->check2FA(session("user_id"));
                    if ($check2FA === false) {
                        // Set a DB flag two_factor_required = 1 to show that the user needs to complete 2FA
                        // The user cant use the application until they have completed 2FA
                        $this->model->setTwoFactorRequired(session("user_id"));
                        return redirect()->to("account/verify");
                    }
                }

                $redirect_url = session()->get('redirect_url') ?? "/account";

                if (strpos($redirect_url, site_url()) === false) {
                    $redirect_url = "/account"; // Set the fallback URL
                }

                session()->remove('redirect_url');

                // Clear browser cache
                $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $this->response->setHeader('Cache-Control', 'post-check=0, pre-check=0', false);
                $this->response->setHeader('Pragma', 'no-cache');
                $this->response->setHeader('Expires', 'Wed, 11 Jan 1984 05:00:00 GMT');

                return redirect()->to($redirect_url)
                    ->with('info', 'Login Successful')
                    ->withCookies();
            } else {

                return redirect()->back()
                    ->withInput()
                    ->with('warning', 'Email/Password is incorrect. Please try again');
            }
        }
    }

    public function toManyAttempts()
    {
        return view("Login/tomanyattempts", [
            'title' => "Process Blocked"
        ]);
    }


    public function onHold()
    {
        session()->destroy();
        return view("Login/onhold", [
            'title' => "Account On Hold"
        ]);
    }

    public function reactivate()
    {
        session()->destroy();
        return view("Login/reactivate", [
            'title' => "Reactivate Your Account"
        ]);
    }


    public function delete()
    {
        $this->auth->logout();
        return redirect()->to('/login/showLogoutMessage')
            ->withCookies();
    }

    public function showLogoutMessage()
    {
        return redirect()->to('/login')
            ->with('info', 'Logout Successful');
    }


    public function forgotPassword()
    {
        // the user is still blocked from making requests
        if (session()->has("locked_out")) {
            return redirect()->to("to-many-attempts");
        }


        if ($this->request->getPost("password_reset")) {

            $throttler = service("throttler");

            // The user can attempt 4 resets in a 1 minute period
            $allowed = $throttler->check('forgotPassword', 10, MINUTE);

            if (!$allowed) {
                session()->setTempdata('locked_out', true, 300);
                return redirect()->to("to-many-attempts");
            } else {

                $rule = $this->model->forgotPasswordRule;
                $this->validation->setRules($rule);

                if ($this->validation->withRequest($this->request)->run()) {
                    //send a password reset request if the email address is in the system
                    $this->model->sendPasswordReset($this->request->getPost());

                    //we are going to send the send message back even if the email account doesn't exist so people cant enumerate email addresses
                    session()->setFlashdata("SUCC", "We have sent an email to '" . trim($this->request->getPost("email")) . "' which contains a password reset link, please follow the instructions listed in the email.");
                    return redirect()->to("forgot-password")->withInput()->withCookies();
                } else {
                    session()->setFlashdata("errors", $this->validation->getErrors());
                    return redirect()->back()->withInput()->withCookies();
                }
            }
        }


        return view("Login/forgotpassword", [
            'title' => "Forgot Password"
        ]);
    }

    public function resetPassword($code, $email)
    {

        // the user is still blocked from making requests
        if (session()->has("locked_out")) {
            return redirect()->to("to-many-attempts");
        }


        $throttler = service("throttler");

        // The user can attempt 4 resets in a 1 minute period
        $allowed = $throttler->check('resetPassword', 10, MINUTE);

        if (!$allowed) {
            session()->setTempdata('locked_out', true, 300);
            return redirect()->to("to-many-attempts");
        } else {

            //get the code and email from the url
            $code = preg_replace('/[^a-z0-9]/i', "", $code);
            $email = trim(base64_decode(str_replace("~", "=", $email)));
            $resetErrors = false;
            $redirectUrl = site_url("reset-password/" . $code . "/" . str_replace("=", "~", base64_encode($email)));
            $credErrors = "";

            // try the reset data
            $tryResetData = $this->model->checkPasswordResetDetails($code, $email);

            if ($tryResetData !== true && $tryResetData != "" && !session()->has("ERR") && !session()->has("errors")) {
                // set error message
                $credErrors = $tryResetData;
                $resetErrors = true;
            }

            if ($this->request->getPost("password_reset_final")) {
                //get the password reset rules
                $rules = $this->model->resetPasswordRule;
                $this->validation->setRules($rules);

                if ($this->validation->withRequest($this->request)->run()) {
                    //update the password..
                    if ($this->model->updatePasswordFromReset($this->request->getpost("password"), $email)) {

                        if (session()->has("ERR")) {
                            session()->remove("ERR");
                        }

                        if (session()->has("errors")) {
                            session()->remove("errors");
                        }

                        // Send the user and email that their password has been changed.
                        $this->model->sendPasswordSuccessResetEmail($email);

                        session()->setFlashdata("SUCC", "Your password has been reset, please <a href='" . site_url('login') . "'>login</a>");
                        return redirect()->to($redirectUrl)->withCookies();
                    } else {
                        //error
                        session()->setFlashdata("ERR", "We have encountered an error, please try again.");
                        return redirect()->to($redirectUrl)->withCookies();
                    }
                } else {
                    session()->setFlashdata("errors", $this->validation->getErrors());
                    return redirect()->to($redirectUrl)->withInput()->withCookies();
                }
            }
        }

        return view("Login/resetpassword", [
            'title' => "Reset Your Password",
            'cred_errors' => $credErrors,
            'reset_errors' => $resetErrors
        ]);
    }
}
