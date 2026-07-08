<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'customer_id',
        'store_id',
        'title',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'logo',
        'cover_image',
        'event_date',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    public function logoUrl(): ?string
    {
        return $this->logo
            ? asset('storage/'.$this->logo)
            : $this->store->logoUrl();
    }
}
