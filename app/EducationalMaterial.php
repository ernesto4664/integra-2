<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class EducationalMaterial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250)
            ->nonQueued();

        $this->addMediaConversion('image_educational_material')
            ->width(640)
            ->height(370)
            ->performOnCollections('image_educational_material');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image_educational_material');
    }

    public function mediaRelation()
    {
        return $this->hasMany(Media::class, 'model_id');
    }
}
