<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['mobile', 'handle'];

    public function getNameAttribute(): string
    {
        return $this->handle;
    }

    public function getBirthdateAttribute(): string
    {
        return '21 April 9170';
    }

    public function getAddressAttribute(): string
    {
        return 'Quezon City';
    }
}
