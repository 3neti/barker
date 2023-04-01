<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, MorphTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Arr;
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

    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class);
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

    public function getWorkflowIdAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.workflow_id'));
    }

    public function getApplicationStatusAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.application_status'));
    }

    public function getCountryAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.country'));
    }

    public function getIdImageUrlAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_image_url'));
    }

    public function getIdTypeAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_type'));
    }

    public function getIdNumberAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_number'));
    }

    public function getIdExpiryAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_expiry'));
    }

    public function getIdFullNameAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_full_name'));
    }

    public function getIdBirthdateAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_birthdate'));
    }

    public function getIdAddressAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_address'));
    }

    public function getIdGenderAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_gender'));
    }

    public function getIdNationalityAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.id_nationality'));
    }

    public function getFaceImageUrlAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.face_image_url'));
    }

    public function getFaceCheckStatusAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.face_check_status'));
    }

    public function getFaceCheckDetailsAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.face_check_details'));
    }

    public function getFaceIdMatchStatusAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.face_id_match_status'));
    }

    public function getFaceIdMatchDetailsAttribute()
    {
        return Arr::get($this->getAttribute('data'), config('domain.hyperverge.mapping.face_id_match_details'));
    }
}
