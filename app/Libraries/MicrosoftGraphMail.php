<?php

namespace App\Libraries;

class MicrosoftGraphMail
{
    private string $tenantId;
    private string $clientId;
    private string $clientSecret;
    private string $accessToken;
    private string $baseUrl;
    private string $fromAddress;

    public function __construct()
    {
        $this->tenantId = getenv('msgraph.tenantID');
        $this->clientId = getenv('msgraph.clientID');
        $this->clientSecret = getenv('msgraph.clientSecret');
        $this->baseUrl = getenv('msgraph.baseURL');
        $this->fromAddress = getenv('msgraph.fromAddress');

        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Send an email using Microsoft Graph.
     */
    public function send(
        string $to,
        string $subject,
        string $body,
        string $bcc = '',
        string $replyTo = '',
        string $file = ''
    ): bool {
        $message = [
            'subject' => $subject,
            'body' => [
                'contentType' => 'HTML',
                'content' => $body
            ],
            'toRecipients' => $this->getRecipients($to)
        ];

        // BCC
        if ($bcc !== '') {
            $message['bccRecipients'] = $this->getRecipients($bcc);
        }

        // Reply-To
        if ($replyTo !== '') {
            $message['replyTo'] = $this->getRecipients($replyTo);
        }

        // Attachment
        if ($file !== '') {
            if (!file_exists($file)) {
                throw new \RuntimeException(
                    'Email attachment does not exist: ' . $file
                );
            }

            if (!is_readable($file)) {
                throw new \RuntimeException(
                    'Email attachment is not readable: ' . $file
                );
            }

            $message['attachments'] = [
                [
                    '@odata.type' => '#microsoft.graph.fileAttachment',
                    'name' => basename($file),
                    'contentType' => $this->getMimeType($file),
                    'contentBytes' => base64_encode(
                        file_get_contents($file)
                    )
                ]
            ];
        }

        $response = $this->post(
            $this->baseUrl .
            'users/' .
            rawurlencode($this->fromAddress) .
            '/sendMail',
            [
                'message' => $message,
                'saveToSentItems' => true
            ],
            [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        );

        if ($response['code'] === 202) {
            return true;
        }

        throw new \RuntimeException(
            'Microsoft Graph sendMail failed. HTTP ' .
            $response['code'] .
            ': ' .
            $response['data']
        );
    }

    /**
     * Get an application access token from Microsoft Entra ID.
     */
    private function getAccessToken(): string
    {
        $url = 'https://login.microsoftonline.com/' .
            $this->tenantId .
            '/oauth2/v2.0/token';

        $fields = http_build_query([
            'client_id' => $this->clientId,
            'scope' => 'https://graph.microsoft.com/.default',
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials'
        ]);

        $response = $this->post(
            $url,
            $fields,
            [
                'Content-Type: application/x-www-form-urlencoded'
            ],
            false
        );

        $data = json_decode($response['data'], true);

        if (!empty($data['error'])) {
            throw new \RuntimeException(
                'Microsoft authentication failed: ' .
                ($data['error_description'] ?? $data['error'])
            );
        }

        if (empty($data['access_token'])) {
            throw new \RuntimeException(
                'Microsoft authentication failed: no access token returned.'
            );
        }

        return $data['access_token'];
    }

    /**
     * Make a POST request.
     */
    private function post(
        string $url,
        string|array $fields,
        array $headers = [],
        bool $authenticate = true
    ): array {
        $ch = curl_init($url);

        if (is_array($fields)) {
            $fields = json_encode(
                $fields,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );

            if ($fields === false) {
                throw new \RuntimeException(
                    'Failed to encode request as JSON: ' . json_last_error_msg()
                );
            }
        }

        if ($authenticate) {
            $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30
        ]);

        $data = curl_exec($ch);

        if ($data === false) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new \RuntimeException(
                'Microsoft Graph cURL error: ' . $error
            );
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'code' => $code,
            'data' => $data
        ];
    }

    /**
     * Check against supported mime types
     */
    private function getMimeType(string $file): string
    {
        $extension = strtolower(
            pathinfo($file, PATHINFO_EXTENSION)
        );

        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'txt'  => 'text/plain',
            'csv'  => 'text/csv',
            'html' => 'text/html',
            'htm'  => 'text/html',
            'xml'  => 'application/xml',
            'json' => 'application/json',

            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

            'zip'  => 'application/zip',

            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',

            'rtf'  => 'application/rtf',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Handle comma-separated email strings
     */
    private function getRecipients(string $addresses): array {
        $recipients = [];

        foreach (explode(',', $addresses) as $address) {
            $address = trim($address);

            if ($address === '') {
                continue;
            }

            if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException(
                    'Invalid email address: ' . $address
                );
            }

            $recipients[] = [
                'emailAddress' => [
                    'address' => $address
                ]
            ];
        }

        return $recipients;
    }
}