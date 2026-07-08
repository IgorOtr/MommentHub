<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'customer_id',
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'logo',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function logoUrl(): ?string
    {
        return $this->logo
            ? asset('storage/'.$this->logo)
            : $this->customer->logoUrl();
    }
}
