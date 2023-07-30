<?php

use Illuminate\Support\Collection;
use WHMCS\Module\Server\greenplusWaaS\Models\CustomField;
use WHMCS\Module\Server\greenplusWaaS\Models\Pricing;
use WHMCS\Module\Server\greenplusWaaS\Models\ProductConfigLinks;
use WHMCS\Module\Server\greenplusWaaS\Models\ProductConfigOptions;
use WHMCS\Module\Server\greenplusWaaS\Models\ProductConfigGroups;
use WHMCS\Module\Server\greenplusWaaS\Models\ProductConfigOptionsSub;

/*
|--------------------------------------------------------------------------
| This hook insert custom fields and configuration options
|--------------------------------------------------------------------------
*/
add_hook('ProductEdit', 1, function ($vars) {
    if ($vars['servertype'] != 'greenplusWaaS')
        return;

    $productConfigGroup = ProductConfigGroups::firstOrCreate(
        ['name' => 'GreenPlusWaaS'],
        ['description' => 'word press as a service fields']
    );

    $productConfigGroupOptions = [
        ['optionname' => 'Php versions', 'gid' => $productConfigGroup->id],
        ['optionname' => 'Web server', 'gid' => $productConfigGroup->id],
        ['optionname' => 'Language', 'gid' => $productConfigGroup->id],
    ];
    $ProductConfigGroupOptionsLists = new Collection();
    foreach ($productConfigGroupOptions as $productConfigGroupOption) {
        $ProductConfigGroupOptionsLists->add(ProductConfigOptions::firstOrCreate(
            $productConfigGroupOption,
            ['optiontype' => '1', 'qtyminimum' => '0', 'qtymaximum' => '0', 'order' => '0', 'hidden' => '0']
        ));
    }
    $ProductConfigGroupOptionsLists = $ProductConfigGroupOptionsLists->pluck('id' , 'optionname');

    $productConfigGroupOptionsSubs = [
        ['configid' => $ProductConfigGroupOptionsLists['Php versions'], 'optionname' => '8.0'],
        ['configid' => $ProductConfigGroupOptionsLists['Php versions'], 'optionname' => '7.4'],
        ['configid' => $ProductConfigGroupOptionsLists['Web server'], 'optionname' => 'Nginx'],
        ['configid' => $ProductConfigGroupOptionsLists['Web server'], 'optionname' => 'Apache'],
        ['configid' => $ProductConfigGroupOptionsLists['Language'], 'optionname' => 'English'],
        ['configid' => $ProductConfigGroupOptionsLists['Language'], 'optionname' => 'Persian'],
        ['configid' => $ProductConfigGroupOptionsLists['Language'], 'optionname' => 'Germany'],
        ['configid' => $ProductConfigGroupOptionsLists['Language'], 'optionname' => 'Chinese'],
        ['configid' => $ProductConfigGroupOptionsLists['Language'], 'optionname' => 'Arabic'],
    ];

    $productConfigGroupOptionsSubLists = new Collection();
    foreach ($productConfigGroupOptionsSubs as $productConfigGroupOptionsSub) {
        $productConfigGroupOptionsSubLists->add(ProductConfigOptionsSub::firstOrCreate(
            $productConfigGroupOptionsSub ,
            ['sortorder' => '0' , 'hidden' => 0]
        ));
    }

    foreach ($productConfigGroupOptionsSubLists as $item) {
        Pricing::firstOrCreate([
            'type' => 'configoptions',
            'currency' => 1,
            'relid' => $item->id
        ],[]);
    }

    ProductConfigLinks::firstOrCreate([
        'pid' => $vars['pid'] ,
        'gid' => $productConfigGroup->id
    ] , []);


    $customFields = [
        [
            'type' => 'product',
            'relid' => $vars['pid'],
            'fieldname' => 'Wordpress Name',
            'fieldtype' => 'text',
            'description' => 'Name of wordpress',
            'required' => 'on',
            'showorder' => 'on',
        ],
        [
            'type' => 'product',
            'relid' => $vars['pid'],
            'fieldname' => 'URL',
            'fieldtype' => 'link',
            'description' => 'Domain of the wordpress - It should start with http or https.',
            'required' => 'on',
            'showorder' => 'on',
        ],
        [
            'type' => 'product',
            'relid' => $vars['pid'],
            'fieldname' => 'Wordpress user',
            'fieldtype' => 'text',
            'description' => 'Admin Username for admin panel wordpress',
            'required' => 'on',
            'showorder' => 'on',
        ],
        [
            'type' => 'product',
            'relid' => $vars['pid'],
            'fieldname' => 'Wordpress password',
            'fieldtype' => 'password',
            'description' => 'admin Password for admin panel wordpress',
            'required' => 'on',
            'showorder' => 'on',
        ],
        [
            'type' => 'product',
            'relid' => $vars['pid'],
            'fieldname' => 'Wordpress email',
            'regexpr' => '/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/',
            'fieldtype' => 'text',
            'description' => 'Admin Email for admin panel wordpress',
            'required' => 'on',
            'showorder' => 'on',
        ],
        [
            'type' => 'product',
            'relid' => $vars['pid'],
            'fieldname' => 'UUID',
            'fieldtype' => 'text',
            'description' => 'UUID of greenplus product',
            'adminonly' => 'on',
        ]
    ];

    foreach ($customFields as $customField) {
        CustomField::firstOrCreate($customField ,[]);
    }
});