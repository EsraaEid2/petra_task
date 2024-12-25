<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory ,SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_method',
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    // Relationship with order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}