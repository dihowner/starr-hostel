<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    
    public function hotelmeta() {
        return $this->hasMany(HotelMeta::class, 'hotel_id', 'id');
    }
    
    public function cart() {
        return $this->hasMany(ShoppingCart::class, "hotel_id", "id");
    }
}