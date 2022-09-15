<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'added_by',
        'client_name',
        'client_email',
        'client_contact',
        'client_kra',
        'client_address',
        'client_balance',
        'tax_exempt',
    ];
}
