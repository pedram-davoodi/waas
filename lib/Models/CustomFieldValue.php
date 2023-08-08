<?php

namespace WHMCS\Module\Server\greenplusWaaS\Models;


use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $table = 'tblcustomfieldsvalues';
    protected $guarded = [];
    public $timestamps = false;
}