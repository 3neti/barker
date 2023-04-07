<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Support\Arr;

class CampaignItem extends Model
{
    use HasFactory;

    //TODO: rename uri to uri_template
    //TODO: rename template to payload_template
    protected $fillable = ['channel', 'uri', 'template'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getURITemplate(): string
    {
        return $this->getAttribute('uri');
    }

    /** deprecate once template is renamed to payload template */
    public function getPayloadTemplateAttribute(): string
    {
        return $this->getAttribute('template');
    }

    #[ArrayShape(['scheme' => "string", 'path' => "string", 'query' => "string"])]
    public function getParsedURIAttribute(): array
    {
        return parse_url($this->getAttribute('uri'));
    }

    public function getSchemeAttribute(): string
    {
        return Arr::get($this->getAttribute('parsedURI'), 'scheme');
    }

    public function getRouteAttribute(): string
    {
        return Arr::get($this->getAttribute('parsedURI'), 'path');
    }

    public function getURITemplateAttribute(): string
    {
        return Arr::get($this->getAttribute('parsedURI'), 'query');
    }

}
