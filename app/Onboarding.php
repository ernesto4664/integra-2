<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Onboarding extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232);

        $this->addMediaConversion('image_onboarding')
            ->width(640)
            ->height(610)
            ->performOnCollections('image_onboarding');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image_onboarding')->singleFile();
    }

    public function mediaRelation()
    {
        return $this->hasMany(Media::class, 'model_id');
    }

}
