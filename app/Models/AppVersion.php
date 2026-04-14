<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppVersion extends Model
{
    use HasFactory;

    protected $table = 'versions';

    protected $fillable = ['product_id', 'version', 'description', 'file'];

    protected $hidden = ['product_id'];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute(): ?string
    {
        return $this->file ? Storage::disk('public')->url($this->file) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
