<?php

# No Payment Required Payment Gateway Module

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function nopayment_config() {

    $configarray = array(
     "FriendlyName" => array(
        "Type" => "System",
        "Value" => "No Payment Required"
        ),
     "instructions" => array(
        "FriendlyName" => "Instructions",
        "Type" => "textarea",
        "Rows" => "5",
        "Value" => "No payment is required for this order.",
        "Description" => "The instructions displayed to customers who choose this payment method.",
        ),
    );

    return $configarray;

}

function nopayment_link($params) {
    $code = '<p>'
        . nl2br($params['instructions'])
        . '<br />'
        . Lang::trans('invoicerefnum')
        . ': '
        . $params['invoicenum']
        . '</p>';

    return $code;

}
