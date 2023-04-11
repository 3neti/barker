<?php

namespace App\Models;

use App\Enums\HypervergeModule;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, MorphTo};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Database\Eloquent\Model;
use App\Data\Hyperverge\KYCData;
use Illuminate\Support\{Arr, Str};
use App\Enums\HypervergeIDCard;
use Spatie\LaravelData\Data;

class Checkin extends Model
{
    use HasFactory, HasUuids, HasSpatial;

    protected $primaryKey = 'uuid';

    protected $fillable = ['url', 'uri', 'data', 'location'];

    protected $casts = [
        'data' => 'array', //don't change this, it has repercussions in data access
        'location' => Point::class,
        'data_retrieved_at' => 'datetime',
    ];

    protected $appends = ['QRCodeURI'];

    protected $visible = ['uuid', 'url', 'location', 'QRCodeURI', 'person'];

//    protected $appends = ['QRCodeURI', 'IdType', 'IdNumber', 'IdFullName', 'IdImageUrl', 'IdBirthdate'];

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

    public function getKYC(): ?KYCData
    {
        return empty($this->data)
            ? null
            : KYCData::from($this->data);
    }

    public function getFieldsExtracted(): ?array
    {
        $details = $this->getKYC()->application->modules[HypervergeModule::ID_VERIFICATION->value]->apiResponse->result->details;
        //sort values
        $data = array_merge([
            'type' => null,
            'idNumber' => null,
            'dateOfIssue' => null,
            'dateOfExpiry' => null,
            'countryCode' => null,
            'mrzString' => null,
        ], $details->fieldsExtracted->toArray());
        //remove null values
        $data = array_filter($data, static function ($var) {
            return $var !== null;
        });

        //prettify keys
        return array_flip(Arr::map(array_flip($data), function (string $value, string $key) {
            return Str::of($value)
                ->snake()
                ->replace('_', ' ')
                ->title()
                ->value;
        }));
    }

    /** attributes */

    public function getQRCodeURIAttribute(): ?string
    {
        $url = $this->getAttribute('url');

        return generateQRCodeURI($url);
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
