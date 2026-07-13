<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $table = 'company_settings';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'state',
        'city',
        'country',
        'pincode',
        'gst_no',
        'pan',
        'bank_name',
        'ac_number',
        'ifsc_code',
        'branch',
        'logo',
    ];
}
