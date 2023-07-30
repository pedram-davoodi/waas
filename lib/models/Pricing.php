<?php

namespace WHMCS\Module\Server\greenplusWaaS\Models;


use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'tblpricing';
    protected $guarded = [];
    public $timestamps = false;
}