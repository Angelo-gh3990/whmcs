<?php

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly.');
}

/**
 * Define module related meta data.
 *
 * @return array
 */
function datadropprovisioning_MetaData() {
    return [
        'DisplayName' => 'DataDrop Provisioning Module',
        'APIVersion' => '1.0', // Use API versioning
    ];
}

/**
 * Provisioning module configuration options.
 *
 * @return array
 */
function datadropprovisioning_ConfigOptions() {
    return [];
}

/**
 * Log messages to a file for debugging.
 *
 * @param string $message
 */
function datadropprovisioning_Log($message) {
    $logFile = __DIR__ . '/datadropprovisioning.log';
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

/**
 * Retrieve the value of a custom product field by name.
 *
 * @param array $params
 * @param string $fieldName
 * @return string|null
 */
function logAllCustomFields(array $params) {
    $customFieldsData = [];
    foreach ($params['customfields'] as $customfield) {
        $customFieldsData[] = json_encode($customfield);
        datadropprovisioning_Log('Google or Github Account DataDrop custom field data: ' . json_encode($customfield));
    }
    return $customFieldsData;
}

/**
 * Create a new service.
 *
 * @param array $params
 * @return string "success" or error message
 */
function datadropprovisioning_CreateAccount(array $params) {
//    logAllCustomFields($params);
    $customFieldsData = logAllCustomFields($params);

    $customEmail = $customFieldsData[0];
    $customEmail = trim($customEmail, '"'); // Remove surrounding double quotes

    datadropprovisioning_Log('CreateAccount: Custom field value retrieved: ' . $customEmail);

    $email = $customEmail;
//    $email = $params['clientsdetails']['email'];
    $apiUrl = 'https://' . $params['serverhostname'] . '/v1/api/create-datadrop-user-4539';
    $username = $params['serverusername'];
    $password = $params['serverpassword'];

    $data = json_encode(['args' => [$email]]);

    datadropprovisioning_Log('CreateAccount: URL: ' . $apiUrl);
    datadropprovisioning_Log('CreateAccount: Data: ' . $data);
    datadropprovisioning_Log('CreateAccount: Username: ' . $username);
    datadropprovisioning_Log('CreateAccount: Password: ' . $password); // Log the password for debugging (remove in production)

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Skip SSL verification (for testing only)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification (for testing only)

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    datadropprovisioning_Log('CreateAccount: Response Code: ' . $httpCode);
    datadropprovisioning_Log('CreateAccount: Response: ' . $response);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        datadropprovisioning_Log('CreateAccount: Error: ' . $error);
        return 'Could not create account: ' . $error;
    }

    curl_close($ch);

    if ($httpCode == 200) {
        return 'success';
    } else {
        return 'Could not create account: ' . $response;
    }
}

/**
 * Terminate a service.
 *
 * @param array $params
 * @return string "success" or error message
 */
function datadropprovisioning_TerminateAccount(array $params) {
    $customFieldsData = logAllCustomFields($params);

    $customEmail = $customFieldsData[0];
    $customEmail = trim($customEmail, '"'); // Remove surrounding double quotes

    datadropprovisioning_Log('TerminateAccount: Custom field value retrieved: ' . $customEmail);

    $email = $customEmail;
    $apiUrl = 'https://' . $params['serverhostname'] . '/v1/api/delete-datadrop-user-6539';
    $username = $params['serverusername'];
    $password = $params['serverpassword'];

    $data = json_encode(['args' => [$email]]);

    datadropprovisioning_Log('TerminateAccount: URL: ' . $apiUrl);
    datadropprovisioning_Log('TerminateAccount: Data: ' . $data);
    datadropprovisioning_Log('TerminateAccount: Username: ' . $username);
    datadropprovisioning_Log('TerminateAccount: Password: ' . $password); // Log the password for debugging (remove in production)

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Skip SSL verification (for testing only)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification (for testing only)

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    datadropprovisioning_Log('TerminateAccount: Response Code: ' . $httpCode);
    datadropprovisioning_Log('TerminateAccount: Response: ' . $response);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        datadropprovisioning_Log('TerminateAccount: Error: ' . $error);
        return 'Could not terminate account: ' . $error;
    }

    curl_close($ch);

    if ($httpCode == 200) {
        return 'success';
    } else {
        return 'Could not terminate account: ' . $response;
    }
}

/**
 * Suspend a service.
 *
 * @param array $params
 * @return string "success" or error message
 */
function datadropprovisioning_SuspendAccount(array $params) {
    // Implement the suspend functionality if your API supports it
    return 'Suspend functionality not implemented.';
}

/**
 * Unsuspend a service.
 *
 * @param array $params
 * @return string "success" or error message
 */
function datadropprovisioning_UnsuspendAccount(array $params) {
    // Implement the unsuspend functionality if your API supports it
    return 'Unsuspend functionality not implemented.';
}

/**
 * Add custom buttons to the client area.
 *
 * @param array $params
 * @return array
 *
 * function datadropprovisioning_ClientAreaCustomButtonArray(array $params) {
 *     return [
 *         "Login to DataDrop" => "https://sync.decentrally.cloud"
 *     ];
 * }
**/

/**
 * Client area output.
 *
 * @param array $params
 * @return string HTML output to display to the client
 */
function datadropprovisioning_ClientArea(array $params) {
    // Provide output for the client area
    return '<p><a href="https://sync.decentrally.cloud" target="_blank">Login to DataDrop</a></p>';
}

?>
