<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Benefit extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'benefits';

    protected $casts = [
        'datetime' => 'datetime',
    ];

    protected $attributes = [
        'is_private' => true,
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(130)
            ->height(130)
            ->nonQueued();

        $this->addMediaConversion('image_new')
            ->width(650)
            ->height(370)
            ->performOnCollections('image_new')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image_new');
    }

    public function mediaRelation()
    {
        return $this->hasMany(Media::class, 'model_id');
    }

    public function linkBenefit()
    {
        return $this->belongsToMany(Link::class, 'link_benefits', 'benefit_id', 'link_id');
    }

    public function district()
    {
        return $this->belongsToMany(District::class, 'benefit_districts', 'benefit_id', 'district_id');
    }

    public function region()
    {
        return $this->belongsToMany(Region::class, 'benefit_regions', 'benefit_id', 'region_id');
    }

    public function position()
    {
        return $this->belongsToMany(Position::class, 'benefit_positions', 'benefit_id', 'position_id');
    }
}
