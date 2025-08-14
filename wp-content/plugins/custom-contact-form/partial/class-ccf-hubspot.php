<?php

if (!defined('ABSPATH')) exit;

class CCF_HubSpot
{
    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @return bool
     */
    public static function create_contact(string $email, string $firstname = '', string $lastname = ''): bool
    {
        $opts = get_option('ccf_options', []);
        $token = isset($opts['hubspot_token']) ? trim($opts['hubspot_token']) : '';

        if (!$token || !is_email($email)) return false;

        $body_array = [
            'properties' => [
                'email'     => $email,
                'firstname' => $firstname,
                'lastname'  => $lastname,
            ],
        ];

        $body = wp_json_encode($body_array);

        $response = wp_remote_post('https://api.hubapi.com/crm/v3/objects/contacts', [
            'method'  => 'POST',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            'body' => $body,
            'timeout' => 20,
            'sslverify' => true,
        ]);

        $dir = CCF_PATH . 'logs';
        if (!file_exists($dir)) wp_mkdir_p($dir);
        $logfile = trailingslashit($dir) . 'hubspot.log';

        if (is_wp_error($response))
        {
            $err = $response->get_error_message();
            $line = sprintf("[%s] ERROR: HubSpot request failed: %s | Request: %s\n", current_time('mysql'), $err, $body);
            file_put_contents($logfile, $line, FILE_APPEND);
            return false;
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        $resp_body = wp_remote_retrieve_body($response);

        $line = sprintf("[%s] RESPONSE: code=%d body=%s | Request: %s\n", current_time('mysql'), $code, $resp_body, $body);
        file_put_contents($logfile, $line, FILE_APPEND);

        if (in_array($code, [200, 201, 202, 204, 409], true)) return true;

        return false;
    }
}
