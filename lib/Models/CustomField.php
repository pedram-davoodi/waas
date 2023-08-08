<?php

namespace WHMCS\Module\Server\greenplusWaaS\Models;


use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $table = 'tblcustomfields';
    protected $guarded = [];
    public $timestamps = false;
}