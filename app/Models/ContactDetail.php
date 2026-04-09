<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'email', 'phone', 'info'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
