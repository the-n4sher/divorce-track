<?php

namespace App\Controllers;

use \CodeIgniter\Entity\User;

class Cron extends BaseController
{

    private $model;
    private $questionnaireModel;


    public function __construct()
    {
        $this->model = model("AccountModel");
        $this->questionnaireModel = model("QuestionnaireModel");
    }


    public function remove_client_file()
    {
        $dir_path = ROOTPATH . "temp_udocs" . DIRECTORY_SEPARATOR;

        //get 10 files
        $files_to_delete = $this->model->getTempFilesToDelete();

        if (is_array($files_to_delete) && count($files_to_delete) > 0) {

            foreach ($files_to_delete as $fd) {
                $file_path = $dir_path . $fd['case_id'] . DIRECTORY_SEPARATOR . $fd['file_name'];

                //check the file exists, it should!
                if (file_exists($file_path)) {
                    $tryDelete = unlink($file_path);
                    if ($tryDelete) {
                        //update the database
                        $this->model->setTempFileAsDeleted($fd['case_id'], $fd['id'], $fd['file_name']);
                    } else {
                        //failed to remove file, write to file
                        $myfile = fopen(getcwd() . DIRECTORY_SEPARATOR . "cron_logs" . DIRECTORY_SEPARATOR . "failed_file_delete.txt", "a");
                        $txt = "[+ " . date('d-m-Y H:i:s') . " ] Failed to delete file [ {$fd['file_name']} ] for case [ {$fd['case_id']} ] \n";
                        fwrite($myfile, $txt);
                        fclose($myfile);
                        continue;
                    }
                } else {
                    //cant fiind the file, move on
                    continue;
                }
            }
        } else {
            //nothing to do, kill the script
            die();
        }
    }

    public function getTodayErrorLogEntries()
    {
        // Check if there is any POST data
        $postData = $this->request->getPost();

        if (empty($postData) || !isset($postData['hash'])) {
            // Return a JSON response with an empty array
            return $this->response->setJSON([]);
        }

        // Retrieve the hash from the POST data
        $receivedHash = $postData['hash'];

        // Define your expected hash (Replace 'your-secret-key' with your actual key or logic)
        $secretKey = getenv('cron.secretKey');
        $expectedHash = hash('sha256', $secretKey);

        // Verify the hash
        if ($receivedHash !== $expectedHash) {
            // Return a JSON response with an empty array
            return $this->response->setJSON([]);
        }

        // Define the path to the logs directory and get today's date
        $logPath = WRITEPATH . 'logs/';
        $todayDate = (array_key_exists("date", $postData) && $postData['date'] != null) ? $postData['date']  : date('Y-m-d');
        $logFile = $logPath . 'log-' . $todayDate . '.log';

        // Check if today's log file exists
        if (!file_exists($logFile)) {
            // Return a JSON response with an empty array
            return $this->response->setJSON([]);
        }

        // Read the contents of the log file
        $logContent = file_get_contents($logFile);

        // Remove the PHP opening tag if present (CodeIgniter adds `<?php` at the start of log files)
        $logContent = preg_replace('/^<\?php\s/', '', $logContent);

        // Sanitize UTF-8 content - remove invalid/malformed UTF-8 sequences (e.g. from attack attempts)
        $logContent = mb_convert_encoding($logContent, 'UTF-8', 'UTF-8');

        // Split entries by "CRITICAL" keyword using a regex to retain the "CRITICAL" marker in each entry
        $logEntries = preg_split('/(?=CRITICAL)/', $logContent);

        // Clean up each entry by trimming whitespace and removing empty entries
        $logEntries = array_filter(array_map('trim', $logEntries));

        // Return the log entries as JSON
        return $this->response->setJSON($logEntries);
    }

    public function deleteOldLogFiles()
    {
        // Path to the logs directory
        $logDirectory = WRITEPATH . 'logs/';

        // Get all log files in the directory
        $logFiles = glob($logDirectory . 'log-*.log'); // Adjust pattern as needed

        // Get the current time
        $currentTime = time();

        // Loop through each log file
        if ($logFiles !== null && is_array($logFiles)) {
            foreach ($logFiles as $file) {
                // Check the last modified time of the file
                $fileModificationTime = filemtime($file);

                // Calculate the file's age (in seconds)
                $fileAge = $currentTime - $fileModificationTime;

                // If the file is older than 10 days (86400 seconds * 10)
                if ($fileAge > 864000) { // 864000 seconds = 10 days
                    // Delete the file
                    unlink($file);
                }
            }
        }
    }


    /* Any case that is over 2 years old and is case completed we need to remove the documents from the system*/
    public function archiveCases()
    {
        $limit = 20;
        // Get total case count separately
        $totalQuery = $this->questionnaireModel->cmDB->table('archive_log')
            ->select('COUNT(*) AS total_cases')
            ->where('case_manager_archived', 1)
            ->where('dt_archived', 0)
            ->get()
            ->getRow();

        $totalCases = $totalQuery ? (int)$totalQuery->total_cases : 0;

        //echo "Results Found To Archive: " . $totalCases . " new<br/>";
        //die();

        // If we have totaCases we then need to do another query to fetch the ID as case_id from cases and join the users table on cases.UserId=users.ID and we also need to select users.ID as user_id from users and we also need to select users.email as email from users
        if ($totalCases > 0) {
            //die("FOUND CASES");
            $casesQuery = $this->questionnaireModel->cmDB->table('archive_log')
                ->select('case_id, user_id')
                ->where('case_manager_archived', 1)
                ->where('dt_archived', 0)
                ->limit($limit)
                ->get()
                ->getResultArray();

            // Loop through the cases and archive the documents
            foreach ($casesQuery as $case) {

                // we need to look for any files in /questionnaire_files that have a pattern of user_id.*
                // Make sure $case['user_id'] is an integer 
                $cron_user_id = (int) ($case['user_id'] ?? 0);

                //echo "USERID: {$cron_user_id}<br />";

                // If we have a user id then we want to remove the files
                if ($cron_user_id > 0) {
                    // Get the files in the questionnaire_files directory that match the user_id
                    $files = glob(ROOTPATH . 'questionnaire_files/' . $cron_user_id . '.*');

                    // Loop through the files and delete them
                    if ($files !== false && !empty($files)) {
                        foreach ($files as $file) {
                            if (file_exists($file)) {
                                unlink($file);
                                //echo $file . " removed<br/>";
                            }
                        }
                    } else {
                        //echo "No Files found for userID {$cron_user_id}<br />";
                    }
                }

                // Update the archive_log table to set dt_archived to the current date and time
                // Check if row exists first
                $exists = $this->questionnaireModel->cmDB->table('archive_log')
                    ->where('case_id', $case['case_id'])
                    ->countAllResults();

                if ($exists > 0) {
                    $this->questionnaireModel->cmDB->table('archive_log')
                        ->where('case_id', $case['case_id'])
                        ->update(['dt_archived' => 1]);

                    //echo "Update the cron log for case {$case['case_id']}<br />";
                }

                // Make sure $case['case_id'] is an integer 
                $cron_case_id = (int) ($case['case_id'] ?? 0);

                //echo "Case ID is: {$cron_case_id}<br />";

                // If we have a case id then we want to remove the files
                if ($cron_case_id > 0) {
                    // Check if there is a consent order dir /questionnaire_files/consent_order/CASE_ID
                    $consentOrderDir = ROOTPATH . 'questionnaire_files/consent_order/' . $cron_case_id;

                    //echo "Consent dir is {$consentOrderDir}<br />";

                    $consentOrderRemoved = false;
                    if (is_dir($consentOrderDir)) {
                        // remove any files in the consent order dir
                        $files = glob($consentOrderDir . '/*');
                        if ($files !== false && !empty($files)) {
                            foreach ($files as $file) {
                                if (file_exists($file)) {
                                    unlink($file);
                                    //echo $file . " removed<br />";
                                }
                            }
                        }

                        $consentOrderRemoved = true;
                        if ($consentOrderRemoved) {
                            $this->questionnaireModel->cmDB->table('archive_log')
                                ->where('case_id', $case['case_id'])
                                ->update(['dt_co_archived' => 1]);

                            //echo "Consent Order Removed for case: " . $case['case_id'] . "<br/>";
                        }
                    } else {
                        //echo "No consent files found for case {$cron_case_id}<br />";
                    }
                }

                // Make sure we have a user id
                if ($cron_user_id > 0) {

                    // is there a D81 or a d81_respondent file that is names D81_USER_ID.pdf or D81_USER_ID_respondent.pdf
                    $consentBaseDir = ROOTPATH . 'questionnaire_files/consent_order';
                    $d81File = glob($consentBaseDir . '/D81_' . $cron_user_id . '.pdf');
                    //echo "Looking for file D81_".$cron_user_id.'.pdf' . "<br />";

                    if ($d81File !== false && is_array($d81File) && count($d81File) > 0 && file_exists($d81File[0])) {
                        unlink($d81File[0]);
                        //echo "D81 File Removed for case: " .  $cron_case_id . "<br/>";
                    }

                    $d81File = glob($consentBaseDir . '/D81_' . $cron_user_id . '_respondent.pdf');
                    //echo "Looking for file D81_".$cron_user_id.'_respondent.pdf' . "<br />";

                    if ($d81File !== false && is_array($d81File) && count($d81File) > 0 && file_exists($d81File[0])) {
                        unlink($d81File[0]);
                        //echo "D81 (respondent) File Removed for case: " . $cron_case_id . "<br/>";
                    }

                    // need to check for signatures in /questionnaire_files/signatures/USER_ID
                    $signaturesDir = ROOTPATH . 'questionnaire_files/signatures/' . $cron_user_id;

                    if (is_dir($signaturesDir)) {
                        // remove any files in the signatures dir
                        $files = glob($signaturesDir . '/*');
                        if ($files !== false && !empty($files)) {
                            foreach ($files as $file) {
                                if (file_exists($file)) {
                                    unlink($file);
                                    //echo $file . " removed<br />";
                                }
                            }
                        }
                        //echo "Signatures Removed for case: " . $cron_case_id . "<br/>";
                    }

                    // We need to check for any files in /questionnaire_files/supporting_files/USER_ID
                    $supportingFilesDir = ROOTPATH . 'questionnaire_files/supporting_files/' . $cron_user_id;

                    if (is_dir($supportingFilesDir)) {
                        // remove any files in the supporting files dir
                        $files = glob($supportingFilesDir . '/*');
                        if ($files !== false && !empty($files)) {
                            foreach ($files as $file) {
                                if (file_exists($file)) {
                                    unlink($file);
                                    //echo $file . " removed<br />";
                                }
                            }
                        }
                        //echo "Supporting Files Removed for case: " . $cron_case_id . "<br/>";
                    }
                }


                //echo "<br/><br/>";
            }
        } else {
            //echo "No records";
        }
    }

    public function getAdditionalFiles()
    {
        // this request is coming in from curl, need to check that the hash is correct
        $postData = $this->request->getPost();
        if (empty($postData) || !isset($postData['hash'])) {
            return $this->response->setJSON([]);
        }

        // check the hash is correct
        $hash = $postData['hash'];
        $secretKey = getenv('cron.secretKey');
        $expectedHash = hash('sha256', $secretKey);

        if ($hash !== $expectedHash || array_key_exists("user_id", $postData) == false) {
            return $this->response->setJSON([]);
        }

        // get the user_id from the post data
        $user_id = (int)$postData['user_id'];

        // If we dont have a user_id, return an empty array
        if ($user_id <= 0) {
            return $this->response->setJSON([]);
        }

        // get the additional files for the user_id
        $additional_files = $this->questionnaireModel->getExtraDivorceCertFiles($user_id);
        return $this->response->setJSON($additional_files);
    }


    // make a simple function that writes a text file to /temp_udocs 
    public function writeTextFile()
    {
        $text = "from remove";
        $file = fopen(ROOTPATH . 'temp_udocs/test.txt', 'w');
        fwrite($file, $text);
        fclose($file);
    }
}
