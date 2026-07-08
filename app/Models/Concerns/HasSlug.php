<?php

namespace App\Models\Concerns;

use App\Services\SlugService;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = SlugService::unique($model->{$model->slugSource()}, static::class);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = SlugService::unique($model->{$model->slugSource()}, static::class, $model->id);
            }
        });
    }

    /**
     * The attribute used as the source string for the slug.
     */
    protected function slugSource(): string
    {
        return 'name';
    }
}
