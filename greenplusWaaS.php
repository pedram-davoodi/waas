<?php

use WHMCS\Module\Server\greenplusWaaS\GreenPlusProvider;
use WHMCS\Module\Server\greenplusWaaS\Models\CustomField;
use WHMCS\Module\Server\greenplusWaaS\Models\CustomFieldValue;

/**
 * Define Language Constants.
 *
 * @var array An array containing language codes as keys and their corresponding values as values.
 */
const LANG = [
    'English' => 'en_US',
    'Persian' => 'fa_IR',
    'German' => 'de_DE',
    'Chinese' => 'zh_CN',
    'Arabic' => 'ar',
];

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Provides metadata for the Greenplus WaaS module.
 *
 * @return array An array containing metadata for the module:
 *               - 'DisplayName' (string) The display name of the module.
 *               - 'APIVersion' (string) The API version used by the module.
 *               - 'RequiresServer' (bool) Set to true if the module requires a server to work.
 */
function greenplusWaaSMetaData()
{
    return [
        'DisplayName' => 'Greenplus WaaS',
        'APIVersion' => '1.1',
        'RequiresServer' => false,
    ];
}

/**
 * Provides configuration options for the Greenplus WaaS module.
 *
 * @return array An array containing the configuration options for the module:
 *               - 'package' (array) An array containing the package configuration option:
 *                 - 'FriendlyName' (string) The friendly name of the configuration option.
 *                 - 'Type' (string) The type of the configuration option (e.g., "dropdown", "text", etc.).
 *                 - 'Options' (mixed) The available options for the configuration (e.g., dropdown values).
 *                 - 'Description' (string) The description of the configuration option.
 */
function greenplusWaaS_ConfigOptions()
{
    try {
        $response = (new GreenPlusProvider())->getSpecs();
        if (!$response['success']) {
            throw new Exception($response['message']);
        }

        $plans = [];
        foreach ($response['data'] as $spec) {
            foreach ($spec->plans as $plan) {
                $plans[] = $spec->name . " ($spec->id) - " . $plan->name . " ($plan->id)";
            }
        }
        $plans = implode(',', $plans);

        return [
            "package" => [
                "FriendlyName" => "Plans",
                "Type" => "dropdown",
                "Options" => $plans,
                "Description" => "Available plans",
            ],
        ];
    } catch (Exception $exception) {
        return [
            "package" => [
                "FriendlyName" => "Plans",
                "Type" => "dropdown",
                "Options" => '',
                "Description" => "Available plans",
            ],
        ];
    }
}

/**
 * Creates a new account using the Greenplus WaaS module.
 *
 * @param array $params An array containing the module parameters and configuration.
 *
 * @return string The result of the account creation process, either "success" or an error message.
 */
function greenplusWaaS_CreateAccount(array $params)
{
    try {
        $pattern = '/\((\d+)\)/';

        preg_match_all($pattern, $params['configoption1'], $config);

        $platform = $config[1][0];
        $plan = $config[1][1];

        $response = (new GreenPlusProvider())->create(
            $params['customfields']['Wordpress Name'],
            $params['customfields']['URL'],
            $params['configoptions']['PHP versions'],
            strtolower($params['configoptions']['Web server']),
            LANG[$params['configoptions']['Language']] ?? "",
            $params['customfields']['Wordpress user'],
            $params['customfields']['Wordpress password'],
            $params['customfields']['Wordpress email'],
            $plan,
            $platform
        );

        if (!$response['success']) {
            throw new Exception($response['message']);
        }

        CustomFieldValue::create([
            'fieldid' => CustomField::whereType('product')->whereRelid($params['pid'])->whereFieldname('UUID')->first()->id,
            'relid' => $params['serviceid'],
            'value' => $response['data']['uuid']
        ]);

        return 'success';
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
}

/**
 * Terminates an account using the Greenplus WaaS module.
 *
 * @param array $params An array containing the module parameters and configuration.
 *
 * @return string The result of the account termination process, either "success" or an error message.
 */
function greenplusWaaS_TerminateAccount(array $params)
{
    try {
        $response = (new GreenPlusProvider())->delete($params['customfields']['UUID']);
        if (!$response['success']) {
            throw new Exception($response['message']);
        }
        return 'success';
    } catch (Exception $e) {
        return $e->getMessage();
    }
}