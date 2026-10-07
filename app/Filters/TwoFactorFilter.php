<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TwoFactorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip if user isn't logged in
        if (!session()->has("user_id")) {
            return;
        }

        // Is this an admin login? If it is, skip 2FA
        if (session()->has('admin_login')) {
            return;
        }

        // if the respondent is logged in, skip 2FA
        if (session()->has('respondent_only')) {
            return;
        }

        // Check if user needs 2FA
        $userModel = model("UserModel");
        $requires2FA = $userModel->checkTwoFactorRequired(session("user_id"));

        if ($requires2FA === true) {
            helper('cookie');
            return redirect()->to("account/verify")->withCookies();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}