<?php
use WHMCS\Database\Capsule;

add_hook('ClientAreaPage', 1, function($vars) {
    $nopaymentGateway = 'nopayment'; // The system name of the "No Payment Required" gateway

    // Check if we are on the upgrade page
    if ($vars['filename'] === 'upgrade') {
        // Log the current filename for debugging
        logActivity('Upgrade page accessed.');

        // Ensure gateways array exists and is an array to avoid errors
        if (isset($vars['gateways']) && is_array($vars['gateways'])) {
            logActivity('Gateways before filtering: ' . implode(', ', array_column($vars['gateways'], 'sysname')));
            $vars['gateways'] = array_filter($vars['gateways'], function($gateway) use ($nopaymentGateway) {
                return $gateway['sysname'] !== $nopaymentGateway;
            });
            logActivity('Gateways after filtering: ' . implode(', ', array_column($vars['gateways'], 'sysname')));
        } else {
            logActivity('No gateways found to filter or gateways is not an array.');
        }
    }

    return $vars;
});
