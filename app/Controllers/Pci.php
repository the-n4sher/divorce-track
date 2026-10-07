<?php

namespace App\Controllers;

use App\Libraries\MicrosoftGraphMail;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class Pci extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = model('AccountModel');
    }

    public function hashFile()
    {

        // Get the pass key from the get value in the url c=????/, if its not set dont render
        $passKey = $this->request->getGet('c');

        if (!$passKey || $passKey !== getenv('PCI_HASH_KEY')) {
            // Throw a 403 error not in json format
            return $this->response->setStatusCode(403, 'Forbidden')
                ->setBody('Access denied');
        }

        // Get the consents of /app/Controllers/Account.php file (stripe_checkout function)
        $contents = $this->fetchOrderCode();
        $pattern = '/public function stripe_checkout[^{]*\{([\s\S]*?)(?=public function|\Z)/';

        if (preg_match($pattern, $contents, $matches)) {
            // Trim to clean up whitespace
            $functionBody = trim($matches[1]);

            // I now want to remove all white space and new lines and hash the string
            $functionBody = hash('sha256', preg_replace('/\s+/', '', $functionBody));

            // lets try and write the string to a file, we want to overwrite the file
            // We are going to write this to WRITABLE/pci
            $path =  WRITEPATH . 'pci/hash.txt';
            $writeToFile = file_put_contents($path, $functionBody);
            if ($writeToFile) {
                echo "File written successfully";
            } else {
                echo "File not written";
            }
        } else {
            echo "Function not found.";
        }
    }

    public function compareHash()
    {

        // Get the pass key from the get value in the url c=????/, if its not set dont render
        $passKey = $this->request->getGet('c');

        if (!$passKey || $passKey !== getenv('PCI_HASH_KEY')) {
            // Throw a 403 error not in json format
            return $this->response->setStatusCode(403, 'Forbidden')
                ->setBody('Access denied');
        }

        // Get the hash from the file
        $file = WRITEPATH . 'pci/hash.txt';
        $hash = file_get_contents($file);

        $to = (getenv('CI_ENVIRONMENT') == 'production') ? 'paul.nash@divorce-online.co.uk, mark.keenan@divorce-online.co.uk' : 'paul.nash@divorce-online.co.uk';

        // Check we have a hash, if we dont we need to email someone and let them know!
        if (!$hash) {
            // Email someone and let them know
            $subject = 'PCI Hash File Not Found For DT Checkout - Cant Compare For Compliance';
            $body = '(DT) The PCI hash file has not been found, please check the file and try again.';
            $this->sendEmail($to, $subject, $body);
            echo "Missing Hash File Email Sent.";
            exit();
        }

        // Now we have a hash, we need to get the code and hash it and see if they match
        // We then need to compare the hash to the hash in the file
        $contents = $this->fetchOrderCode();
        $pattern = '/public function stripe_checkout[^{]*\{([\s\S]*?)(?=public function|\Z)/';
        if (preg_match($pattern, $contents, $matches)) {
            $functionBody = trim($matches[1]);
            $functionBodyHashed = hash('sha256', preg_replace('/\s+/', '', $functionBody));

            $storedHash = trim((string)$hash);
            if (hash_equals($functionBodyHashed, $storedHash)) {
                $subject = 'DT SUCCESSFULLY COMPLIANT - Check Completed';
                $body = '(DT) The checkout hash matches the hash in the file, and the checkout code is compliant.';
                $this->sendEmail($to, $subject, $body);
                echo "Successfully Compliant Email Sent.";
                $this->logCompareRun(1);
                exit();
            } else {
                $subject = 'URGENT - DT Checkout Hash Does Not Match - Code Has Been Changed!';
                $body = '(DT) The function hash does not match the hash in the file, please check the changes!';
                $this->sendEmail($to, $subject, $body);
                echo "Hash Does Not Match Email Sent.";
                $this->logCompareRun(0);
                exit();
            }
        } else {
            $subject = 'Could Not Find Function In DT Checkout Code - Cant File For Compliance';
            $body = '(DT) The function can not be found inside the Controllers/Account.php file, please check the file and try again.';
            $this->sendEmail($to, $subject, $body);
            echo "Missing Function Email Sent.";
            $this->logCompareRun(0);
            exit();
        }
    }

    public function fetchOrderCode()
    {
        // Get the consents of /app/models/OrderModel.php file
        return file_get_contents(getcwd() . '/app/Controllers/Account.php');
    }

    public function logCompareRun($status)
    {
        // Update the database
        $this->model->logPciRun($status);
    }

    public function sendEmail($userEmail, $subject, $body, $bcc = "", $replyTo = "", $file = "") {
        $graph = new MicrosoftGraphMail();

        return $graph->send(
            $userEmail,
            $subject,
            $body,
            $bcc,
            $replyTo,
            $file
        );
    }
}
