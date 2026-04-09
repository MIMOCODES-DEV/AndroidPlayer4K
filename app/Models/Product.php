<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function domainUrl()
    {
        return $this->hasOne(DomainUrl::class);
    }

    public function contactDetail()
    {
        return $this->hasOne(ContactDetail::class);
    }

    public function version()
    {
        return $this->hasOne(AppVersion::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
