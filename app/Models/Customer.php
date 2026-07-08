<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'logo',
    ];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function logoUrl(): ?string
    {
        return $this->logo ? asset('storage/'.$this->logo) : null;
    }
}
