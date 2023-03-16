<?php

namespace App\Models;

use Junges\InviteCodes\Http\Models\Invite as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasData;

class Invite extends BaseModel
{
    use HasData;
}
