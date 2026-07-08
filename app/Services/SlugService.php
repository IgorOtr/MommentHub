<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a unique slug for the given model based on a source string.
     */
    public static function unique(string $source, string $modelClass, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $suffix = 1;

        while (self::exists($modelClass, $slug, $ignoreId)) {
            $suffix++;
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }

    protected static function exists(string $modelClass, string $slug, ?int $ignoreId): bool
    {
        $query = $modelClass::query()->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
