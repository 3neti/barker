<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Database\Eloquent\Model;
//use App\Traits\HasData;

class Checkin extends Model
{
    use HasFactory, HasUuids, HasSpatial;

    protected $primaryKey = 'uuid';

    protected $fillable = ['url', 'uri', 'data', 'location'];

    protected $casts = [
        'data' => 'array',
        'location' => Point::class,
    ];

//    protected $appends = ['QRCodeURI', 'IdType', 'IdNumber', 'IdFullName', 'IdImageUrl', 'IdBirthdate'];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function person(): MorphTo
    {
        return $this->morphTo();
    }

    public function setAgent(User $agent): self
    {
        $this->agent()->associate($agent);

        return $this;
    }

    public function setCampaign(Campaign $campaign): self
    {
        $this->campaign()->associate($campaign);

        return $this;
    }

    public function setPerson(Model $person): self
    {
        $this->person()->associate($person);

        return $this;
    }

    public function setLocation(float $latitude, float $longitude): self
    {
        $location = new Point($latitude, $longitude);
        $this->setAttribute('location', $location);

        return $this;
    }

    public function setURL(string $url): self
    {
        $this->setAttribute('url', $url);

        return $this;
    }

    public function getQRCodeURIAttribute(): ?string
    {
        $url = $this->getAttribute('url');

        return generateQRCodeURI($url);;;
    }
}
