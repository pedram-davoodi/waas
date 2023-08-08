<?php

namespace WHMCS\Module\Server\greenplusWaaS\Models;


use Illuminate\Database\Eloquent\Model;

class ProductConfigOptions extends Model
{
    protected $table = 'tblproductconfigoptions';
    protected $guarded = [];
    public $timestamps = false;
}