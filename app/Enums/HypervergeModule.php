<?php

namespace App\Enums;

enum HypervergeModule: string
{
    case ID_VERIFICATION = 'module_id';
    case SELFIE_VERIFICATION = 'module_selfie';
    case SELFIE_ID_MATCH = 'module_facematch';
}
