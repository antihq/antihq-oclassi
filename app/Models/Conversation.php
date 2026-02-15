<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'buyer_id',
        'seller_id',
        'subject',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->oldest();
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function otherUser(User $user): User
    {
        return $this->buyer_id === $user->id ? $this->seller : $this->buyer;
    }

    public function unreadMessagesFor(User $user): HasMany
    {
        return $this->messages()->where('sender_id', '!=', $user->id)->whereNull('read_at');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('buyer_id', $userId)->orWhere('seller_id', $userId);
    }

    public function touchLastMessageAt(): void
    {
        $this->update(['last_message_at' => now()]);
    }

    public static function findOrCreateFor(User $buyer, Listing $listing): self
    {
        return static::firstOrCreate(
            [
                'buyer_id' => $buyer->id,
                'seller_id' => $listing->user_id,
                'listing_id' => $listing->id,
            ],
            ['subject' => "Inquiry about {$listing->title}"]
        );
    }
}
