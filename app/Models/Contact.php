<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\{CanCheckin, HasData, HasMobile};
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Contact extends Model
{
    use HasFactory;
    use HasMobile;
    use HasData;
    use Notifiable;
    use CanCheckin;

    protected $fillable = ['mobile', 'handle'];

    protected $appends = ['name'];

    protected $hidden = ['id', 'updated_at', 'created_at'];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::title($this->getAttribute('handle'))
        );
    }
}
