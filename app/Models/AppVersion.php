<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    protected $table = 'versions';

    protected $fillable = ['product_id', 'version', 'description', 'file'];

    protected $hidden = ['product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
