<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ["hotel_id", "user_id"];
    
    public function hotel() {
        return $this->belongsTo(Hotel::class, "hotel_id", "id");
    }
    
}