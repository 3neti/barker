<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, MorphTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Casts\Attribute;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Data\Hyperverge\KYCData;
use Illuminate\Support\Arr;


class Checkin extends Model
{
    use HasFactory, HasUuids, HasSpatial;

    protected $primaryKey = 'uuid';

    protected $fillable = ['url', 'uri', 'data', 'location'];

    protected $appends = ['QRCodeURI', 'data'];

    protected $casts = [
        'location' => Point::class,
        'data_retrieved_at' => 'datetime',
    ];

//    protected $visible = ['uuid', 'url', 'location', 'QRCodeURI', 'person', 'agent', 'data'];

    public static function makeFromAgent(User $agent): self
    {
        return self::make([
            'agent_id' => $agent->id,
            'campaign_id' => $agent->currentCampaign->id,
        ]);
    }

    /** relations */

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

    /** relation setters */

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

    /** attribute setters */

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

    public function setData($data): self
    {
        $this->setAttribute('data', $data);
        $this->forceFill(['data_retrieved_at' => now()]);

        return $this;
    }

    /** computed */

    public function dataRetrieved(): bool
    {
        $dataRetrievedAt = $this->getAttribute('data_retrieved_at');

        return $dataRetrievedAt && $dataRetrievedAt <= now();
    }

    /** attributes */

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value
                ? KYCData::from((array) json_decode($value, true))
                : null,
            set: fn ($value) => json_encode($value),
        );
    }

    public function getQRCodeURIAttribute(): ?string
    {
        $long_url = $this->getAttribute('url');
        if (config('domain.shorten_url')) {
            if (Cache::has($key = 'short_url-' . $long_url)) {
                $short_url = Cache::get($key);
            }
            else {
                Cache::forever($key, $short_url = app('bitly')->getUrl($long_url));
            }
            $url = $short_url;
        }
        else {
            $url = $long_url;
        }


        if (Cache::has($key = 'qrcode_uri-' . $url)) {
            $qrCodeURI = Cache::get($key);
        }
        else {
            Cache::forever($key, $qrCodeURI = generateQRCodeURI($url));
        }

        return $qrCodeURI;
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

    //TODO: deprecate
    protected function idType(): Attribute
    {
        return Attribute::make(
            get: fn () => Arr::get($this->data, config('domain.hyperverge.mapping.id_type'))
        );
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
