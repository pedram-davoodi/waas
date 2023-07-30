<?php

use WHMCS\Module\Server\greenplusWaaS\GreenPlusProvider;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function greenplusWaaSMetaData()
{
    return array(
        'DisplayName' => 'Greenplus WaaS',
        'APIVersion' => '1.1', // Use API Version 1.1
        'RequiresServer' => false, // Set true if module requires a server to work
    );
}


function greenplusWaaS_ConfigOptions()
{
    $specs = (new GreenPlusProvider())->getSpecs();
    $plans = [];
    foreach ($specs as $spec){
        foreach ($spec->plans as $plan)
            $plans[] = $spec->name . " - " . $plan->name;
    }
    $plans = implode(',' , $plans);

    return [
        "package" => [
            "FriendlyName" => "Plans",
            "Type" => "dropdown",
            "Options" => $plans,
            "Description" => "Available plans",
        ],
    ];
}


function greenplusWaaS_CreateAccount(array $params){
}

function greenplusWaaS_TerminateAccount(array $params)
{
    try {

    } catch (Exception $e) {
    }

    return 'success';
}

function greenplusWaaS_planList($params){
}
