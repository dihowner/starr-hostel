<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id","hotel_id","reference",
        "checkin_date","checkout_date",
        "total_amount","vat","sub_total_amount",
    ];
}